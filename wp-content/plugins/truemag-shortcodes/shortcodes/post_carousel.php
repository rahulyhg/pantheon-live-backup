<?php


function parse_carousel_func($atts, $content){
	$showmetadata 			= isset($atts['showmetadata']) ? $atts['showmetadata'] : 1;	
	$count 					= isset($atts['count']) ? $atts['count'] : -1;
	$width					= isset($atts['width']) ? $atts['width'] : '';
	$posttypes 				= isset($atts['posttypes']) ? $atts['posttypes'] : 'post';
	$posts_in 				= isset($atts['posts_in']) ? $atts['posts_in'] : '';
	$categories 			= isset($atts['categories']) ? $atts['categories'] : '';
	$orderby 				= isset($atts['orderby']) ? $atts['orderby'] : 'date';
	$order 					= isset($atts['order']) ? $atts['order'] : 'DESC';
	$el_class 				= isset($atts['el_class']) ? $atts['el_class'] : '';
	if(class_exists('Mobile_Detect')){
		$detect = new Mobile_Detect;
		$_device_ = $detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'pc';
		$animation_class = ($atts['animation']&&$_device_=='pc')?'wpb_'.$atts['animation'].' wpb_animate_when_almost_visible':'';
	}else{
		$animation_class = $atts['animation']?'wpb_'.$atts['animation'].' wpb_animate_when_almost_visible':'';
	}
	
	$args = array(
		'post_type' => split(',',$posttypes),
		'posts_per_page' => $count,
		'post_status' => 'publish',
		'orderby' => $orderby,
		'order' => $order,
	);
	
	if($posts_in != ''){
		$posts_in = split(',',$posts_in);
		$args += array('post__in'=> $posts_in);
	}
	if(isset($categories) && $categories != '' && is_numeric ($categories)){
		$args['cat'] = $categories;
	}elseif(isset($categories) && $categories != '' && !is_numeric ($categories)){			 
		$args['category_name'] = $categories;
	}
	$the_query = new WP_Query( $args );
	$html = '
		<div class="posts-carousel responsive '.$animation_class.'">
	';
	$id = rand();
	if($the_query->have_posts()){		
		$html .= '<ul id="posts-carousel-'.$id.'">';
		while($the_query->have_posts()){ $the_query->the_post();
			$meta_html = ($showmetadata) ? '
				<div class="datetime"><span class="icon-calendar"></span>'.get_the_date().'</div>
				<div class="comment"><span class="icon-comment"></span>'.get_comments_number().' '.__('Comments','cactusthemes').'</div>' : '';
			$html .= '
			<li>
				<div class="postleft">
					<a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_post_thumbnail(get_the_ID(), 'thumb_100x100', array('alt' => get_the_title())).'</a>
				</div>
				<div class="postright">
					<h3><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></h3>
					'.$meta_html.'
				</div>
			</li>';
		}
		$html .= '
			</ul>
			<div class="clear"></div>
			<div class="slides-control">
				<div class="dotted"><!---->
				<div class="control-a">
					<a href="javascript:void(0)" id="posts-prev-'.$id.'" class="icon-sign-blank">
						<span class="icon-caret-left"><!----></span>
					</a>
					<a href="javascript:void(0)" id="posts-next-'.$id.'" class="icon-sign-blank">
						<span class="icon-caret-right"><!----></span>
					</a>
				</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
		';
	}
	global $post_carousel_js;
	$post_carousel_js[]= '
		<script>
			jQuery(document).ready(function() {
				jQuery("#posts-carousel-'.$id.'").carouFredSel({
					height: "auto",
					prev: "#posts-prev-'.$id.'",
					next: "#posts-next-'.$id.'",
					auto: false,
					width: "100%",
					align: "left",
				});
			});
		</script>
	';
	if($width != '' && $width > 100){
		$html .= '
			<style>
				#posts-carousel-'.$id.' li{width:'.$width.'px !important;}
				#posts-carousel-'.$id.' .postright{width:'.($width - 115).'px;}
			</style>
		';
	}
	wp_reset_query();
	return $html;
}

add_shortcode( 'posts_carousel', 'parse_carousel_func' );
function post_carousel_js(){
	global $post_carousel_js;
	if($post_carousel_js) foreach($post_carousel_js as $apost_carousel_js){echo $post_carousel_js;}
}
add_action('wp_footer', 'post_carousel_js', 100);

/* Register shortcode with Visual Composer */
add_action( 'after_setup_theme', 'reg_posts_carousel' );
function reg_posts_carousel(){
	if(function_exists('vc_map')){
	vc_map( array(
		"name"		=> __("Posts Carousel", "js_composer"),
		"base"		=> "posts_carousel",
		"class"		=> "wpb_vc_posts_slider_widget",
		//"icon"		=> "icon-wpb-slideshow",
		"category"  => __('Content', 'js_composer'),
		"params"	=> array(
			array(
				"type" => "dropdown",
				"heading" => __("Show metadata", "js_composer"),
				"param_name" => "showmetadata",
				"value" => array(__("Yes")=>1, __("No")=>0),
				"description" => __("Show title, comment, date", "js_composer")
			),
			array(
				"type" => "textfield",
				"heading" => __("Posts count", "js_composer"),
				"param_name" => "count",
				"value" => "",
				"description" => __('How many slides to show? Enter number or "All".', "js_composer")
			),
			array(
				"type" => "textfield",
				"heading" => __("Width per post", "js_composer"),
				"param_name" => "width",
				"value" => "",
				"description" => __('Width of post in slide (> 100). Ex: 260', "js_composer")
			),
			array(
				"type" => "posttypes",
				"heading" => __("Post types", "js_composer"),
				"param_name" => "posttypes",
				"description" => __("Select post types to populate posts from.", "js_composer")
			),
			array(
				"type" => "textfield",
				"heading" => __("Post/Page IDs", "js_composer"),
				"param_name" => "posts_in",
				"value" => "",
				"description" => __('Fill this field with page/posts IDs separated by commas (,), to retrieve only them. Use this in conjunction with "Post types" field.', "js_composer")
			),
			array(
				"type" => "exploded_textarea",
				"heading" => __("Categories", "js_composer"),
				"param_name" => "categories",
				"description" => __("If you want to narrow output, enter category names here. Note: Only listed categories will be included. Divide categories with linebreaks (Enter).", "js_composer")
			),
			array(
				"type" => "dropdown",
				"heading" => __("Order by", "js_composer"),
				"param_name" => "orderby",
				"value" => array(
					__("Date", "js_composer") => "date",
					__("ID", "js_composer") => "ID", 
					__("Author", "js_composer") => "author", 
					__("Title", "js_composer") => "title", 
					__("Modified", "js_composer") => "modified", 
					__("Random", "js_composer") => "rand", 
					__("Comment count", "js_composer") => "comment_count", 
					__("Menu order", "js_composer") => "menu_order" 
				),
				"description" => __('Select how to sort retrieved posts. More at <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>.', 'js_composer')
			),
			array(
				"type" => "dropdown",
				"heading" => __("Order by", "js_composer"),
				"param_name" => "order",
				"value" => array( __("Descending", "js_composer") => "DESC", __("Ascending", "js_composer") => "ASC" ),
				"description" => __('Designates the ascending or descending order. More at <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>.', 'js_composer')
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
		  ),
		)
	) );
	}
}













