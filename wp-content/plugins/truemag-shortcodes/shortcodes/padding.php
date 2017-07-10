<?php
function ct_shortcode_padding_parse($atts, $content){
	$padding_top = isset($atts['top']) ? $atts['top'] : '0';
	$padding_bottom = isset($atts['bottom']) ? $atts['bottom'] : '0';
	
	$html = '<div style="margin-top:'.$padding_top.'px;margin-bottom:'.$padding_bottom.'px;height:1px;line-height:0px"><!-- padding element --></div>';

	return $html;
}

add_shortcode( 'margin', 'ct_shortcode_padding_parse' );


/* Register shortcode with Visual Composer */
add_action( 'after_setup_theme', 'reg_margin' );
function reg_margin(){
	if(function_exists('vc_map')){
	vc_map( array(
	   "name" => __("Margin", 'castusthemes'),
	   "base" => "margin",
	   "class" => "",
	   "controls" => "full",
	   "category" => __('Structure', 'castusthemes'),
	   "params" => array(
		  array(
			 "type" => "textfield",
			 "holder" => "div",
			 "class" => "",
			 "heading" => __("Margin Top", 'castusthemes'),
			 "param_name" => "top",
			 "value" => '',
			 "description" => 'Margin Top in pixels (enter value without "px")',
		  ),
		array(
			 "type" => "textfield",
			 "holder" => "div",
			 "class" => "",
			 "heading" => __("Margin Bottom", 'castusthemes'),
			 "param_name" => "bottom",
			 "value" => '',
			 "description" => 'Margin Bottom in pixels (enter value without "px")',
		  )
	   )
	)
	);
	}
}