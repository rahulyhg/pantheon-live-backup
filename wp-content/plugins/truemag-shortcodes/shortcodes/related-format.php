<?php


function parse_tm_related_post_func($atts, $content){
	$postformat 			= isset($atts['postformat']) ? $atts['postformat'] : 'video';	
	$posttypes 				= isset($atts['posttypes']) ? $atts['posttypes'] : 'post';
	$count 					= isset($atts['count']) ? $atts['count'] : -1;
	$tags 				= isset($atts['tag']) ? $atts['tag'] : '';
	$orderby 				= isset($atts['orderby']) ? $atts['orderby'] : 'date';
	global $post;
	if(is_single()){
		if($tags==''){
				$tags = '';
					$posttags = get_the_tags();
					if ($posttags) {
						foreach($posttags as $tag) {
							$tags .= ',' . $tag->slug; 
						}
						$tags = substr($tags, 1); 
					}
					
		}
	}

	$args = array(
		'taxonomy' => 'post_format',
		'post_type' => $posttypes,
		'posts_per_page' => $count,
		'post_status' => 'publish',
		'posts_not_in' =>  array(get_the_ID($post)),
		'terms' => array($postformat),
		'tag' => $tags,
	);
	$the_query = new WP_Query($args);
	if($the_query->have_posts()){		
		$html = '<div class="popular_video ">';
		while($the_query->have_posts()){ $the_query->the_post();
			$html .= '
				<div class="row-fluid">
				  <div class="rt-article span12">
					  <div class="popular_video_item">
						  <div class="video_thumb">
							<a href="#">'.get_the_post_thumbnail(get_the_ID(), array(150,150) ).'
							<span class="link-overlay fa fa-play"></span></a>
							'.tm_post_rating(get_the_ID()).'
						  </div> 	
						  <div class="pp_info">
							<h4 class="rt-article-title"> <a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></h4>
							<div class="pp_video_view"><span><i class="icon-eye-open"></i>  '.tm_get_post_views().''.__('  Views').'</span></div>
							<div class="pp_video_comment"><span><i class="icon-comment"></i>  '.get_comments_number().''.__(' Comments').'</span></div>
							<div class="pp_video_like"><span><i class="icon-heart"></i>   '.tm_get_post_likes(get_the_ID()).' '.__(' Likes').'</span></div>
						  </div>
						<!-- end post wrap -->
						<div class="clear"><!-- --></div>
					</div>
				  </div>
				  <!-- end span -->                              
				</div>
			';
				
		}
	}
	$html .= '
		</div>
		<div class="clear"></div>
	';
	wp_reset_query();
	return $html;
}

add_shortcode( 'tm_related_post', 'parse_tm_related_post_func' );


/* Register shortcode with Visual Composer */
add_action( 'after_setup_theme', 'reg_tm_related_post' );
function reg_tm_related_post(){
	if(function_exists('vc_map')){
	vc_map( array(
		"name"		=> __("TM Related post", "js_composer"),
		"base"		=> "tm_related_post",
		"class"		=> "wpb_vc_posts_slider_widget",
		//"icon"		=> "icon-wpb-slideshow",
		"category"  => __('Content', 'js_composer'),
		"params"	=> array(
			array(
				"type" => "dropdown",
				"heading" => __("Post format", "cactusthemes"),
				"param_name" => "postformat",
				"value" => array(__("Video")=> "video",__("News")=> "standard", __("Or Both")=> "both"),
				"description" => __("", "cactusthemes")
			),
			array(
				"type" => "textfield",
				"heading" => __("Posts count", "cactusthemes"),
				"param_name" => "count",
				"value" => "",
				"description" => __('How many count to show? Enter number .', "cactusthemes")
			),
			array(
				"type" => "textfield",
				"heading" => __("Show same tag", "cactusthemes"),
				"param_name" => "tag",
				"value" => "",
				"description" => __('Enter name tag', "cactusthemes")
			),
			array(
				"type" => "dropdown",
				"heading" => __("Order by", "js_composer"),
				"param_name" => "orderby",
				"value" => array(
					__("Date", "js_composer") => "date",
					__("Random", "js_composer") => "rand", 
				),
				"description" => __('', 'cactusthemes')
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






