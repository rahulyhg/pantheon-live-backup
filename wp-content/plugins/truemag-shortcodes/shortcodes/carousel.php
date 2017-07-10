<?php
function parse_carousel($atts, $content, $id){
        wp_enqueue_style( 'ui-custom-theme' );
        wp_enqueue_script('jquery-ui-accordion');
		$id = rand();
        $output = '';
		if(class_exists('Mobile_Detect')){
			$detect = new Mobile_Detect;
			$_device_ = $detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'pc';
			if(isset($atts['animation'])){
			$animation_class = ($atts['animation']&&$_device_=='pc')?'wpb_'.$atts['animation'].' wpb_animate_when_almost_visible':'';
			}
		}else{
			if(isset($atts['animation'])){
			$animation_class = $atts['animation']?'wpb_'.$atts['animation'].' wpb_animate_when_almost_visible':'';
			}
		}
        str_replace("[Carousel_item","",$content,$i);
		$output .= "\n\t".'<div class="is-carousel simple-carousel testimonial car-style" id="post-gallery'.$id.'">';
		$output .= "\n\t\t".'<div class="simple-carousel-content carousel-content">';
		$output .= do_shortcode(str_replace('<br class="nc" />', '', $content));
		$output .= "\n\t\t".'</div>';
		$output .= "\n\t\t".'<div class="carousel-pagination"></div>';
		$output .= "\n\t".'</div>';
        return $output;
}

function parse_carousel_item($atts, $content, $id){
		wp_enqueue_script( 'jquery-isotope');
		$output = '';
		$output .= '<div class="item-testi">';
		$output .= '<div class="car-content">';
		$output .=  $content ;
		$output .= '<div class="tt-tooltip"><!----></div>';
		$output .= '</div>';
		$output .= '</div>';
        return $output;
}
add_shortcode( 'Carousel_item', 'parse_carousel_item' );
add_shortcode( 'Carousel', 'parse_carousel' );
//Visual Composer
add_action( 'after_setup_theme', 'reg_carousel' );
function reg_carousel(){
	if(function_exists('vc_map')){

		vc_map( array(
			"name"		=> __("Carousel", "cactusthemes"),
			"base"		=> "Carousel",
			"as_parent" => array('only' => 'Carousel_item'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
			"content_element" => true,
			"show_settings_on_create" => false,
			"icon" => "icon-carousel",
			"params"	=> array(
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
			),
			'js_view' => 'VcColumnView'
		) );
		
		vc_map( array(
			"name"		=> __("Carousel item", "cactusthemes"),
			"base"		=> "Carousel_item",
			"content_element" => true,
			"as_child" => array('only' => 'Carousel_item'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"icon" => "icon-carousel",
			"params"	=> array(
				array(
					"type" => "textarea_html",
					"holder" => "div",
					"class" => "",
					"heading" => __("Content", "cactusthemes"),
					"param_name" => "content",
					"value" => '',
					"description" => '',
				),
			),
		) );
	}
	if(class_exists('WPBakeryShortCode') && class_exists('WPBakeryShortCodesContainer')){
	class WPBakeryShortCode_carousel extends WPBakeryShortCodesContainer{}
	class WPBakeryShortCode_carousel_item extends WPBakeryShortCode{}
	}
}
