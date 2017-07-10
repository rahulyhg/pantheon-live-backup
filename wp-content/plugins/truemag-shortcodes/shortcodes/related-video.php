<?php


function parse_tm_related_post_func($atts, $content){
	$title 			= isset($atts['title']) ? $atts['title'] : '';	
	$postformat 			= isset($atts['postformat']) ? $atts['postformat'] : 'video';	
	$posttypes 				= isset($atts['posttypes']) ? $atts['posttypes'] : 'post';
	$count 					= isset($atts['count']) ? $atts['count'] : '';
	$tags 				= isset($atts['tag']) ? $atts['tag'] : '';
	$orderby 				= isset($atts['orderby']) ? $atts['orderby'] : 'date';
	if(function_exists('ot_get_option')){ $themes_pur= ot_get_option('theme_purpose');}
	if(function_exists('ot_get_option')){	
		$layout_ct_video = ot_get_option('single_layout_ct_video');
		if($layout_ct_video!='full'){$count='4';}else {$count='6';}
	}
	if(class_exists('CT_ContentHelper')){
		$item_loop_video = new CT_ContentHelper;	
		$the_query = $item_loop_video->tm_get_related_posts($posttypes, $tags, $postformat, $count, $orderby, $args = array());
		$thumb='thumb_196x126';
		$show_title= 1;
		$show_meta= $show_exceprt=0;
		$item_video = new CT_ContentHtml; 
		if($the_query->have_posts()){
		$html = '
			<div class="smart-box smart-box-style-2 is-carousel" >';
		$html .= '<div class="smart-box-head">
			<h2 class="light-title">'.$title.'</h2>
			</div>
			<div class="smart-box-content">
				<div class="smart-item">
					<div class="row">
			';
			
			while($the_query->have_posts()){ $the_query->the_post();
				if($layout_ct_video!='full'){
				$html .= '
					<div class="col-md-3 col-sm-6 col-xs-6">';
				}else {
				$html .= '
					<div class="col-md-2 col-sm-6 col-xs-6">';
				}
					$html.= $item_video->get_item_medium_video($thumb,$show_title, $show_exceprt, $show_meta,$themes_pur);
				$html .= '</div>';		
			}
		
		$html .= '</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
		';
		}
		wp_reset_query();
		return $html;
	}
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
				"type" => "textfield",
				"heading" => __("Title", "cactusthemes"),
				"param_name" => "title",
				"value" => "",
				"description" => __('', "cactusthemes")
			),
			array(
				"type" => "dropdown",
				"heading" => __("Post format", "cactusthemes"),
				"param_name" => "postformat",
				"value" => array(__("Video")=> "video",__("News")=> "standard", __("Or Both")=> "both"),
				"description" => __("", "cactusthemes")
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






