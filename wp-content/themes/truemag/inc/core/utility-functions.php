<?php
/* Utility functions */

if(!function_exists('curPageURL')){
	/* Get current page URL */
	function curPageURL() {
		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
			$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
}

if(!function_exists('cactus_hex2rgb')){
	/* Convert Hexa to RGB */
	function cactus_hex2rgb($hex) {
	   $hex = str_replace("#", "", $hex);
	
	   if(strlen($hex) == 3) {
		  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
		  $r = hexdec(substr($hex,0,2));
		  $g = hexdec(substr($hex,2,2));
		  $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   //return implode(",", $rgb); // returns the rgb values separated by commas
	   return $rgb; // returns an array with the rgb values
	}
}

if(!function_exists('rgb2hexa')){
	/* Convert RGB to HEXA
	 *
	 * return hexa color without '#' at beginning
	 * $rgb: array of RGB values
	 */
	function rgb2hexa($rgb) {
	   if(count($rgb) == 3) {
			if($rgb[0] < 10) $hex1 = '0'.$rgb[0];
			else $hex1 = dechex($rgb[0]);
			if($rgb[1] < 10) $hex2 = '0'.$rgb[1];
			else $hex2 = dechex($rgb[1]);
			if($rgb[2] < 10) $hex3 = '0'.$rgb[2];
			else $hex3 = dechex($rgb[2]);
		
		    return $hex1 . $hex2 . $hex3;
		}
		 
		return '000';
	}
}

if(!function_exists('tm_color_gradient')){
	/* calculate gradient color of a basic color
	 *
	 * return gradient color in hexa, without '#'
	 * $basic_hexa: basic color, in hexa value
	 * $step_hexa: difference between 2 colors, in rgb values (array)
	 */
	function tm_color_gradient($basic_hexa,$step_rgb){
		$basic_rbg = cactus_hex2rgb($basic_hexa);
		$r = $basic_rbg[0] - $step_rgb[0];
		if($r < 0) $r = 0;
		$g = $basic_rbg[1] - $step_rgb[1];
		if($g < 0) $g = 0;
		$b = $basic_rbg[2] - $step_rgb[2];
		if($b < 0) $b = 0;
		
		return rgb2hexa(array($r,$g,$b));
	}
}

if(!function_exists('startsWith')){
	function startsWith($haystack, $needle)
	{
		return !strncmp($haystack, $needle, strlen($needle));
	}
}

if(!function_exists('cactus_hex2rgba')){
	/* Add opacity to a Hexa color */
	function cactus_hex2rgba($hex,$opacity) {
	   $hex = str_replace("#", "", $hex);

	   if(strlen($hex) == 3) {
		  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
		  $r = hexdec(substr($hex,0,2));
		  $g = hexdec(substr($hex,2,2));
		  $b = hexdec(substr($hex,4,2));
	   }
	   $opacity = $opacity/100;
	   $rgba = array($r, $g, $b, $opacity);
	   return implode(",", $rgba); // returns the rgb values separated by commas
	}
}
if(!function_exists('tm_number')) {
	function tm_number($n, $decimals = 2, $suffix = '') {
		if(!$suffix)
			$suffix = 'K,M,B';
		$suffix = explode(',', $suffix);
	
		if ($n < 1000) { // any number less than a Thousand
			$shorted = number_format($n);
		} elseif ($n < 1000000) { // any number less than a million
			$shorted = number_format($n/1000, $decimals).$suffix[0];
		} elseif ($n < 1000000000) { // any number less than a billion
			$shorted = number_format($n/1000000, $decimals).$suffix[1];
		} else { // at least a billion
			$shorted = number_format($n/1000000000, $decimals).$suffix[2];
		}
	
		return $shorted;
	}
}
