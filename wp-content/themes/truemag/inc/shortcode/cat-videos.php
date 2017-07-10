<?php
function parse_tm_cat_videos_func($atts, $content){
	$condition 					= isset($atts['condition']) ? $atts['condition'] : '';
	$count 					= isset($atts['count']) ? $atts['count'] : -1;
	$categories 			= isset($atts['categories']) ? $atts['categories'] : '';
	
	$idcategories = get_the_category();
	$category_id = $idcategories[0]->cat_ID;
	

	if($condition=='likes')
	{
				//$cat = get_query_var('cat');
				$args = array(
					'post_type' => 'post',
					'post_status' => 'publish',
					'ignore_sticky_posts' => 1,
					'posts_per_page' => '-1',
					'orderby' => 'latest',
				);
				if($categories){
					$args['cat'] = $categories;
				}
				$postlist = get_posts($args);
				$posts_id = array();
				foreach ( $postlist as $post ) {
				   $posts_id[] += $post->ID;
				}
				wp_reset_postdata();
				//print_r($posts_id);
				global $wpdb;
				$time_range = 'all';
				//$show_type = $instance['show_type'];
				$order_by = 'ORDER BY like_count DESC, post_title';
				$show_excluded_posts = get_option('wti_like_post_show_on_widget');
				$excluded_post_ids = explode(',', get_option('wti_like_post_excluded_posts'));
				if(!$show_excluded_posts && count($excluded_post_ids) > 0) {
					$where = "AND post_id NOT IN (" . get_option('wti_like_post_excluded_posts') . ")";
				}
				else{$where = '';}
				$query = "SELECT post_id, SUM(value) AS like_count, post_title FROM `{$wpdb->prefix}wti_like_post` L, {$wpdb->prefix}posts P ";
				$query .= "WHERE L.post_id = P.ID AND post_status = 'publish' AND value > -1 $where GROUP BY post_id $order_by";
				$posts = $wpdb->get_results($query);
				//$cates_ar = $cates;
				$p_data = array();
				//print_r($posts);
				if(count($posts) > 0) {
					foreach ($posts as $post) {
						$p_data[] = $post->post_id;
					}
				}
				$diff_result= array_diff($posts_id,$p_data);
				$common_result = array_intersect($p_data,$posts_id);
				$posts_id = array_merge($common_result, $diff_result);
				//print_r($posts_id);
				$args = null;
				$args = array(
					  'post_type' => 'post',
					  'posts_per_page' => $count,
					  'orderby'=> 'post__in',
					  'post__in' =>  $posts_id,
					  'ignore_sticky_posts' => 1,
				);
				$the_query = new WP_Query( $args );
				
				$html = '
					<div id="top-carousel" class="cat-carousel">
						 <div class="container">
							<div class="is-carousel" id="top1">
								<div class="carousel-content">';
								if($the_query->have_posts()){
									while($the_query->have_posts()){ $the_query->the_post();
									$format_c = get_post_format(get_the_ID());
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
															<img src="'.$thumbnail[0].'" alt="'.the_title_attribute('echo=0').'" title="'.the_title_attribute('echo=0').'">';
															if($format_c=='' || $format_c =='standard'  || $format_c =='gallery'){
																$html .= '<div class="link-overlay fa fa-search"></div>';
															}else {
																$html .= '<div class="link-overlay fa fa-play"></div>';
															}
															$html .= '</a>
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
		
		
	}else {
		$item_video1 = new CT_ContentHelper;
		$themes_pur=0;
		$tags = $ids = $sort_by = '';
		$the_query = $item_video1->tm_get_popular_posts($condition, $tags, $count, $ids,$sort_by, $categories, $args = array(),$themes_pur);
		$num_item = count($the_query->posts);
		$html = '
			<div id="top-carousel" class="cat-carousel">
				 <div class="container">
					<div class="is-carousel" id="top1">
						<div class="carousel-content">';
		if($the_query->have_posts()){
			while($the_query->have_posts()){ $the_query->the_post();
			$format_c = get_post_format(get_the_ID());
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
									<img src="'.$thumbnail[0].'" alt="'.the_title_attribute('echo=0').'" title="'.the_title_attribute('echo=0').'">';
									if($format_c=='' || $format_c =='standard'  || $format_c =='gallery'){
										$html .= '<div class="link-overlay fa fa-search"></div>';
									}else {
										$html .= '<div class="link-overlay fa fa-play"></div>';
									}
									$html .= '</a>
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

//wpb_map( array(
//    "name"		=> __("TM Categories videos", "js_composer"),
//    "base"		=> "tm_cat_videos",
//    "class"		=> "wpb_vc_posts_slider_widget",
//	//"icon"		=> "icon-wpb-slideshow",
//	"category"  => __('Content', 'js_composer'),
//    "params"	=> array(
//        array(
//            "type" => "dropdown",
//            "heading" => __("Condition", "cactusthemes"),
//            "param_name" => "condition",
//            "value" => array(
//			__("Latest","cactusthemes")=>'latest', 
//			__("Most viewed","cactusthemes")=>'most_viewed',
//			__("Most liked","cactusthemes")=>'likes', 
//			__("Most commented","cactusthemes")=>'most_comments',
//			__("Title", "js_composer") => "title", 
//			__("Modified", "js_composer") => "modified", 
//			__("Random", "js_composer") => "random"), 
//            "description" => __("Select condition", "cactusthemes")
//        ),
//        array(
//            "type" => "textfield",
//            "heading" => __("Posts count", "cactusthemes"),
//            "param_name" => "count",
//            "value" => "",
//            "description" => __('How many count to show? Enter number .', "cactusthemes")
//        ),
//		array(
//		  "type" => "categories",
//		  "heading" => __("Categories", "cactusthemes"),
//		  "param_name" => "categories",
//		  "description" => __("Select Categories.", "cactusthemes")
//		),
//        array(
//            "type" => "textfield",
//            "heading" => __("Extra class name", "js_composer"),
//            "param_name" => "el_class",
//            "value" => "",
//            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
//        ),
//		array(		
//		 "type" => "dropdown",
//		 "holder" => "div",
//		 "class" => "",
//		 "heading" => __("CSS Animation", 'cactusthemes'),
//		 "param_name" => "animation",
//		 "value" => array(
//			__("No", 'cactusthemes') => '',
//			__("Top to bottom", 'cactusthemes') => 'top-to-bottom',
//			__("Bottom to top", 'cactusthemes') => 'bottom-to-top',
//			__("Left to right", 'cactusthemes') => 'left-to-right',
//			__("Right to left", 'cactusthemes') => 'right-to-left',
//			__("Appear from center", 'cactusthemes') => 'appear',
//		 ),
//		 "description" => ''
//	  )
//    )
//) );






