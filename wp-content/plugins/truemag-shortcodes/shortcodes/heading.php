<?php

function heading_func($atts, $content){
	$style = isset($atts['style']) ? $atts['style'] : 'h-classic';
	$heading = isset($atts['heading']) ? $atts['heading'] : '';
	$fonsize = isset($atts['fonsize']) ? $atts['fonsize'] : '';
	$id=rand();
	$html='';
		if($fonsize != ''){
			$html .= '<style type="text/css" scoped="scoped">
				#heading-id'.$id.' .module-title h2{font-size: '.$fonsize.'px;}
			</style>';
		}
		$html.='
			<div class="heading-shortcode '.$style.'" id="heading-id'.$id.'">
				<div class="module-title"><h2>'.$heading.'</h2></div>';
				if($style=='h-modern'){$html .='<p class="border-style"></p>';}
			$html.='
			</div>
		';
	return $html;

}
add_shortcode( 'heading', 'heading_func' );
/* Register shortcode with Visual Composer */
add_action( 'after_setup_theme', 'reg_heading' );
function reg_heading(){
	if(function_exists('vc_map')){
	vc_map( array(
	   "name" => __("Heading"),
	   "base" => "heading",
	   "class" => "",
	   "controls" => "full",
	   "category" => __('Content'),
	   "params" => array(
		  array(
			 "type" => "dropdown",
			 "holder" => "div",
			 "class" => "",
			 "heading" => __("Style", 'cactusthemes'),
			 "param_name" => "style",
			 "value" => array(__('Classic', 'cactusthemes') => 'h-classic', __('Modern', 'cactusthemes') => 'h-modern'),
			 "description" => ''
		  ),
		  array(
			 "type" => "textarea",
			 "holder" => "div",
			 "class" => "",
			 "heading" => __("Heading", 'cactusthemes'),
			 "param_name" => "heading",
			 "value" => '',
			 "description" => '',
		  ),
		  array(
			"type" => "textfield",
			"heading" => __("Font size", "cactusthemes"),
			"param_name" => "fonsize",
			"value" => "",
			"description" => __("Ex. 10."),
		  ),
	   )
	) );
	}
}