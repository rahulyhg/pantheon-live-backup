<?php


function parse_alert($atts, $content){	
	$links = isset($atts['links']) ? $atts['links'] : '';
	$style = isset($atts['style']) ? $atts['style'] : 'alert-success';
	$dis_alerts = isset($atts['dis_alerts']) ? $atts['dis_alerts'] : '';

	$html = '';
	
	$html .= ' 
		<div class="alert '.$style.'">';
		if($dis_alerts=='1'){
			$html .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';	
		}
		if($links!=''){
			$html .= '<a href="'.$links.'" class="alert-link">';	
		}
		$html .= $content;
		if($links!=''){
			$html .= '</a>';	
		}
		$html .= '</div>
	';
	
	return $html;	
}




add_shortcode( 'alert', 'parse_alert' );
/* Register shortcode with Visual Composer */
add_action( 'after_setup_theme', 'reg_alert' );
function reg_alert(){
	if(function_exists('vc_map')){
	vc_map( array(
		"name"		=> __("Alert", "cactusthemes"),
		"base"		=> "alert",
		"class"		=> "wpb_vc_posts_slider_widget",
		//"icon"		=> "icon-wpb-slideshow",
		"category"  => __('Content', 'cactusthemes'),
		"params"	=> array(
			array(
				"type" => "dropdown",
				"heading" => __("Style", "cactusthemes"),
				"param_name" => "style",
				"value" => array(
				__("Alert-success","cactusthemes")=>'alert-success', 
				__("Alert-info","cactusthemes")=>'alert-info',
				__("Alert-warning","cactusthemes")=>'alert-warning', 
				__("Alert-danger","cactusthemes")=>'alert-danger',
				), 
				"description" => __("Select style", "cactusthemes")
			),
			array(
				"type" => "textfield",
				"heading" => __("Links", "cactusthemes"),
				"param_name" => "links",
				"value" => "",
				"description" => __('', "cactusthemes")
			),
			array(
				"type" => "textarea",
				"heading" => __("Content", "cactusthemes"),
				"param_name" => "content",
				"value" => "",
				"description" => __('', "cactusthemes")
			),
			array(
				"type" => "dropdown",
				"heading" => __("Dismissable alerts", "cactusthemes"),
				"param_name" => "dis_alerts",
				"value" => array(
				__("No","cactusthemes")=>'0', 
				__("Yes","cactusthemes")=>'1',
				), 
				"description" => __("Select condition", "cactusthemes")
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









