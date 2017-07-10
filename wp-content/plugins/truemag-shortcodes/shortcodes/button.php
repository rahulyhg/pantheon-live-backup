<?php


function parse_button($atts, $content){	
	$size = isset($atts['size']) ? $atts['size'] : '';	
	$id = isset($atts['id']) ? $atts['id'] : '';
	$links = isset($atts['link']) ? $atts['link'] : '';
	//$style = isset($atts['style']) ? $atts['style'] : 'default';
	$icon = isset($atts['icon']) ? $atts['icon'] : '';	
	$bg_color = isset($atts['bg_color']) ? $atts['bg_color'] : '';

	$html = '';
	
	$html .= '<a href="'.$links.'" title="' . $content .'" class="ct-btn '.(($size == 'small')?'small':'').'"' . ($bg_color != ''? 'style="background:'.$bg_color.'"':'') . ' >'. (($icon != '')?'<i class="fa '. $icon .'"></i>':'') .$content.'</a>';
		
	return $html;	
}
add_shortcode( 'ct_button', 'parse_button' );