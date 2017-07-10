<?php

function parse_dropcap_func($atts, $content){
	//$first = substr(trim(strip_tags($content)), 0, 1);
	$html = '<span class="dropcap">'.$content.'</span>';//.str_replace($first, ' ', strip_tags($content));
	return $html;
}


add_shortcode( 'dropcap', 'parse_dropcap_func' );




















