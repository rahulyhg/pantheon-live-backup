<?php 
function shortcode_tooltip($atts, $content = null) {
	$title = isset($atts['title']) ? $atts['title'] : '';
	extract(shortcode_atts(array(
		'title' => 'This tooltip is on top!',
	), $atts));
	$html ='';
	//$html = '<span class="tooltip-shortcode">';
	//$html .= $content;
	$html .='
	<a href="#" data-toggle="tooltip" title="'.$title.'" class="gptooltip" data-animation="true">'.$content.'</a>';
	return $html;
}
add_shortcode('tooltip', 'shortcode_tooltip');
