<?php
function parse_tm_cat_videos_func($atts, $content){
	$condition 					= isset($atts['condition']) ? $atts['condition'] : 'latest';
	$count 					= isset($atts['count']) ? $atts['count'] : -1;
	$categories 			= isset($atts['categories']) ? $atts['categories'] : '';
	if($condition=='likes' && class_exists('CT_ContentHelper'))
	{
			$atts=array();
			global $wpdb;	
			$show_count = 1 ;
			$time_range = 'all';
			//$show_type = $instance['show_type'];
			$order_by = 'ORDER BY like_count DESC, post_title';
			if($count > 0) {
				$limit = "LIMIT " . $count;
			}
			$show_excluded_posts = get_option('wti_like_post_show_on_widget');
			$excluded_post_ids = explode(',', get_option('wti_like_post_excluded_posts'));
			
			if(!$show_excluded_posts && count($excluded_post_ids) > 0) {
				$where = "AND post_id NOT IN (" . get_option('wti_like_post_excluded_posts') . ")";
			}
			//getting the most liked posts
			$query = "SELECT post_id, SUM(value) AS like_count, post_title FROM `{$wpdb->prefix}wti_like_post` L, {$wpdb->prefix}posts P ";
			$query .= "WHERE L.post_id = P.ID AND post_status = 'publish' AND value > 0 $where GROUP BY post_id $order_by $limit";
			$posts = $wpdb->get_results($query);
			$item_loop_video = new CT_ContentHtml;
			$widget_data  = '
				<div id="top-carousel" class="cat-carousel">
					 <div class="container">
						<div class="is-carousel" id="top1">
							<div class="carousel-content">';

			if(count($posts) > 0) {
				foreach ($posts as $post) {
					$post_title = stripslashes($post->post_title);
					$permalink = get_permalink($post->post_id);
					$like_count = $post->like_count;
					$widget_data .= $item_loop_video->tm_likes_cat_html($post,$like_count);
				}
			}
			$widget_data .= '
						</div><!--/carousel-content-->
						<div class="carousel-button">
							<a href="#" class="prev maincolor1 bordercolor1 bgcolor1hover"><i class="fa fa-chevron-left"></i></a>
							<a href="#" class="next maincolor1 bordercolor1 bgcolor1hover"><i class="fa fa-chevron-right"></i></a>
						</div><!--/carousel-button-->
					</div><!--/is-carousel-->
					</div>
			</div>';
			wp_reset_query();
			return $widget_data;
		
		
	}else if(class_exists('CT_ContentHelper')) {
		$item_video1 = new CT_ContentHelper;	
		$the_query = $item_video1->tm_get_popular_posts($condition, $tags, $count, $ids,$sort_by, $categories, $args = array());
		$num_item = count($the_query->posts);
		$html = '
			<div id="top-carousel" class="cat-carousel">
				 <div class="container">
					<div class="is-carousel" id="top1">
						<div class="carousel-content">';
		if($the_query->have_posts()){
			while($the_query->have_posts()){ $the_query->the_post();
						$html .= '   
							<div class="video-item">
								<div class="item-thumbnail">
									<a href="'.get_permalink().'" title="'.get_the_title().'">';
									
									if(has_post_thumbnail()){
										$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(),'thumb_196x126', true);
									}else{
										$thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
									}
									$html .= '
									<img src="'.$thumbnail[0].'" alt="'.the_title_attribute('echo=0').'" title="'.the_title_attribute('echo=0').'">
										<div class="link-overlay fa fa-play"></div>
									</a>
									'.tm_post_rating(get_the_ID()).'
									<div class="item-head">
										<h3><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></h3>
									</div>
								</div>
							</div>';
			
			}
		}
		$html .= '
						</div><!--/carousel-content-->
						<div class="carousel-button">
							<a href="#" class="prev maincolor1 bordercolor1 bgcolor1hover"><i class="fa fa-chevron-left"></i></a>
							<a href="#" class="next maincolor1 bordercolor1 bgcolor1hover"><i class="fa fa-chevron-right"></i></a>
						</div><!--/carousel-button-->
					</div><!--/is-carousel-->
					</div>
			</div>';
		wp_reset_query();
		return $html;
	}
}

add_shortcode( 'tm_cat_videos', 'parse_tm_cat_videos_func' );


/* Register shortcode with Visual Composer */
add_action( 'after_setup_theme', 'reg_cat_videos' );
function reg_cat_videos(){
	if(function_exists('vc_map')){
	vc_map( array(
		"name"		=> __("TM Categories videos", "js_composer"),
		"base"		=> "tm_cat_videos",
		"class"		=> "wpb_vc_posts_slider_widget",
		//"icon"		=> "icon-wpb-slideshow",
		"category"  => __('Content', 'js_composer'),
		"params"	=> array(
			array(
				"type" => "dropdown",
				"heading" => __("Condition", "cactusthemes"),
				"param_name" => "condition",
				"value" => array(
				__("Latest","cactusthemes")=>'latest', 
				__("Most viewed","cactusthemes")=>'most_viewed',
				__("Most liked","cactusthemes")=>'likes', 
				__("Most commented","cactusthemes")=>'most_comments',
				__("Title", "js_composer") => "title", 
				__("Modified", "js_composer") => "modified", 
				__("Random", "js_composer") => "random"), 
				"description" => __("Select condition", "cactusthemes")
			),
			array(
				"type" => "textfield",
				"heading" => __("Posts count", "cactusthemes"),
				"param_name" => "count",
				"value" => "",
				"description" => __('How many count to show? Enter number .', "cactusthemes")
			),
			array(
			  "type" => "categories",
			  "heading" => __("Categories", "cactusthemes"),
			  "param_name" => "categories",
			  "description" => __("Select Categories.", "cactusthemes")
			),
			array(
				"type" => "textfield",
				"heading" => __("Extra class name", "js_composer"),
				"param_name" => "el_class",
				"value" => "",
				"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
			),
			array(		
			 "type" => "dropdown",
			 "holder" => "div",
			 "class" => "",
			 "heading" => __("CSS Animation", 'cactusthemes'),
			 "param_name" => "animation",
			 "value" => array(
				__("No", 'cactusthemes') => '',
				__("Top to bottom", 'cactusthemes') => 'top-to-bottom',
				__("Bottom to top", 'cactusthemes') => 'bottom-to-top',
				__("Left to right", 'cactusthemes') => 'left-to-right',
				__("Right to left", 'cactusthemes') => 'right-to-left',
				__("Appear from center", 'cactusthemes') => 'appear',
			 ),
			 "description" => ''
		  )
		)
	) );
	}
}





