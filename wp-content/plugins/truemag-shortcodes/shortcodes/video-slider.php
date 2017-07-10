<?php
function parse_tm_video_silder_func($atts, $content){
	$title 			= isset($atts['title']) ? $atts['title'] : '';	
	$ids 			= isset($atts['ids']) ? $atts['ids'] : '';	
	$condition 					= isset($atts['condition']) ? $atts['condition'] : 'latest';
	$count					= isset($atts['count']) ? $atts['count'] : '-1';
	$categories 			= isset($atts['categories']) ? $atts['categories'] : '';
	$sort_by 					= isset($atts['order']) ? $atts['order'] : 'DESC';
	$tags 					= isset($atts['tags']) ? $atts['tags'] : '';

	$themes_pur='';
	if(function_exists('ot_get_option')){ $themes_pur= ot_get_option('theme_purpose');}
	if(class_exists('CT_ContentHelper')){
		wp_enqueue_script( 'jquery-isotope');
		if($condition==''){
			$condition = 'latest';
		}
		$item_loop_video = new CT_ContentHelper;	
		$the_query = $item_loop_video->tm_get_popular_posts($condition, $tags, $count, $ids,$sort_by, $categories, $args = array(),$themes_pur);
		$num_item = count($the_query->posts);
		$item_video = new CT_ContentHtml; 
		if($the_query->have_posts()){
		$id = rand();
		$data_auto = (!ot_get_option('header_home_auto')?' data-notauto=1':'').(ot_get_option('header_home_auto_timeout')?' data-auto_timeout='.ot_get_option('header_home_auto_timeout'):'').(ot_get_option('header_home_auto_duration')?' data-auto_duration='.ot_get_option('header_home_auto_duration'):'');	
		$page_layout = get_post_meta(get_the_ID(),'sidebar',true);
		if(function_exists('ot_get_option')){
			if($page_layout==''){
				$page_layout = ot_get_option('page_layout');
			}
			$sidebar_width = ot_get_option('sidebar_width');	
		}
		$css_fl = '';
		if($page_layout=='right'){
			$css_fl = 'float: right;';
		}
		if($sidebar_width ==1){
			$sidebar_width ='col-md-3';
		}else{
			$sidebar_width ='col-md-4';
		}
		$html = '
			<div class="container video_slider">';
			$html .='<div class="'.$sidebar_width.' video-with" style=" padding-bottom:30px; '.$css_fl.'">';
			$html .='
			<div class="is-carousel simple-carousel testimonial tm-video-slider" '.$data_auto.' id="post-gallery'.$id.'">
				<div class="simple-carousel-content carousel-content">';
					while($the_query->have_posts()){ $the_query->the_post();
						$html .= '
							<div class="item-testi">
								<div class="tt-content icon-quote-right">
								<a href="'.get_permalink().'" title="'.get_the_title().'">
								'.get_the_post_thumbnail(get_the_ID(), 'thumb_365x235', array('alt' => get_the_title())).'
								<div class="link-overlay fa fa-play "></div>
								</a>
								</div>
								<div class="name title"><h2>'.get_the_title().'</h2></div>
								<div class="name pos">'.date_i18n(get_option('date_format') ,get_the_time('U')).'</div>
								<div class="excerpt">'.wp_trim_words(get_the_excerpt(),15,$more = '').'</div>
								<a class="ct-btn small " href="'.get_permalink().'">'.__('View Details','cactusthemes').'</a>
								<p class="end"> &nbsp;</p>
							</div>';
					}
		$html .= '
			</div>
			<div class="carousel-pagination"></div>
		</div>';
		$html .='</div>';
		$html .='
		</div>
		<div class="clear"></div>
		';
		}
		wp_reset_query();
		return $html;
	}
}

add_shortcode( 'video_silder', 'parse_tm_video_silder_func' );
/* Register shortcode with Visual Composer */
add_action( 'after_setup_theme', 'reg_video_silder' );
function reg_video_silder(){
	if(function_exists('vc_map')){
	vc_map( array(
		"name"		=> __("Video Carousel", "cactusthemes"),
		"base"		=> "video_silder",
		"class"		=> "wpb_vc_posts_slider_widget",
		//"icon"		=> "icon-wpb-slideshow",
		"category"  => __('Content', 'js_composer'),
		"params"	=> array(
			array(
				"type" => "dropdown",
				"holder" => "div",
				"heading" => __("Condition", "cactusthemes"),
				"param_name" => "condition",
				"value" => array(
				__("Latest","cactusthemes")=>'latest', 
				__("Most viewed","cactusthemes")=>'most_viewed', 
				__("Most Liked","cactusthemes")=>'likes', 
				__("Most commented","cactusthemes")=>'most_comments',
				__("Title", "js_composer") => "title", 
				__("Modified", "js_composer") => "modified", 
				__("Random", "js_composer") => "random"), 
				"description" => __("Select condition", "cactusthemes")
			),
			array(
				"type" => "textfield",
				"heading" => __("IDs", "cactusthemes"),
				"param_name" => "ids",
				"value" => "",
				"description" => __('', "cactusthemes")
			),
	
			array(
				"type" => "textfield",
				"heading" => __("Number of Carousel", "cactusthemes"),
				"param_name" => "count",
				"value" => "",
				"description" => __('How many number to show? Enter number .', "cactusthemes")
			),
			array(
			  "type" => "textfield",
			  "heading" => __("Categories", "cactusthemes"),
			  "param_name" => "categories",
			  "description" => __("List of cat ID (or slug), separated by a comma", "cactusthemes")
			),
	
			array(
				"type" => "textfield",
				"heading" => __("Tags", "cactusthemes"),
				"param_name" => "tags",
				"value" => "",
				"description" => __('Tags', "cactusthemes")
			),
			array(
				"type" => "dropdown",
				"heading" => __("Order by", "js_composer"),
				"param_name" => "order",
				"value" => array( 
				__("Descending", "cactusthemes") => "DESC", 
				__("Ascending", "cactusthemes") => "ASC" ),
				"description" => __('Designates the ascending or descending order. More at <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>.', 'cactusthemes')
			),
		)
	) );
	}
}
