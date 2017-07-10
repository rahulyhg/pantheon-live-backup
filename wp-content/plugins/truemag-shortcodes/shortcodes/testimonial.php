<?php
function parse_testimonial($atts, $content, $id){
	global $testimonial_slides;
	$testimonial_id = rand(0,99999);
	$auto_play = isset($atts['auto_play']) ? $atts['auto_play'] : '1';
	wp_enqueue_style( 'ui-custom-theme' );
	wp_enqueue_script('jquery-ui-accordion');
	
	$data_auto = '';
	if($auto_play==0){$data_auto = 'data-notauto=1';}
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
	str_replace("[testimonial_item","",$content,$i);
		$output .= "\n\t".'<div class="is-carousel simple-carousel testimonial" '.$data_auto.' id="post-gallery'.$id.$testimonial_id.'">';
		$output .= "\n\t\t".'<div class="simple-carousel-content carousel-content">';
		$output .= do_shortcode(str_replace('<br class="nc" />', '', $content));
		$output .= "\n\t\t".'</div>';
		$output .= "\n\t\t".'<div class="carousel-pagination"></div>';
		$output .= "\n\t".'</div>';
        return $output;
}

function parse_testimonial_item($atts, $content, $id){
	$position = isset($atts['position']) ? $atts['position'] : '';
	$name = isset($atts['name']) ? $atts['name'] : '';
	$company = isset($atts['company']) ? $atts['company'] : '';
	$avatar = isset($atts['avatar']) ? $atts['avatar'] : '';
	$img = wp_get_attachment_image_src(preg_replace('/[^\d]/', '', $atts['avatar']),'thumbnail', true);
	$id = rand();
	$bg = '<img src="'.$img[0].'" width="60" height="60" alt="'.esc_attr($atts['name']).'" />';
	wp_enqueue_script( 'jquery-isotope');
	//$el_class = $this->getExtraClass($el_class);
	$output = '';
	$output .= '<div class="item-testi">';
	$output .= '<div class="tt-content icon-quote-right">';
	$output .= strip_tags($content);
	$output .= '<div class="tt-tooltip"><!----></div>';
	$output .= '</div>';
	$output .= '<div class="avata">'.$bg.'</div>';
	$output .= '<div class="name">'.$atts['name'].'</div>';
	$output .= '<div class="name pos">'.$atts['position'].'</div>';
	$output .= '<p class="end"> &nbsp;</p>';
	$output .= '</div>';
	return $output;

}
add_shortcode( 'testimonial_item', 'parse_testimonial_item' );
add_shortcode( 'testimonial', 'parse_testimonial' );

//Visual Composer

add_action( 'after_setup_theme', 'reg_testimonial' );
function reg_testimonial(){
	if(function_exists('vc_map')){
		vc_map( array(
			"name"		=> __("Testimonial", "cactusthemes"),
			"base"		=> "testimonial",
			"as_parent" => array('only' => 'testimonial_item'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
			"content_element" => true,
			"show_settings_on_create" => false,
			"icon" => "icon-testimonial",
			"params"	=> array(
				array(		
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __("Auto play", 'cactusthemes'),
					"param_name" => "auto_play",
					"value" => array(
						__("Yes", 'cactusthemes') => '1',
						__("No", 'cactusthemes') => '0',
					),
					"description" => ''
				),
			),
			'js_view' => 'VcColumnView'
		) );
		vc_map( array(
			"name"		=> __("Testimonial item", "cactusthemes"),
			"base"		=> "testimonial_item",
			"content_element" => true,
			"as_child" => array('only' => 'testimonial_item'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"icon" => "icon-testimonial",
			"params"	=> array(
				array(
					"type" => "textfield",
					"heading" => __("Name", "cactusthemes"),
					"param_name" => "name",
					"value" => "",
					"description" => '',
				),
				array(
					"type" => "textfield",
					"heading" => __("Position", "cactusthemes"),
					"param_name" => "position",
					"value" => "",
					"description" => '',
				),
		
		
				array(
					"type" => "textfield",
					"heading" => __("Company", "cactusthemes"),
					"param_name" => "company",
					"value" => "",
					"description" => '',
				),
				array(
					"type" => "attach_image",
					"heading" => __("Avatar"),
					"param_name" => "avatar",
					"value" => "",
					"description" => '',
				),
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
	class WPBakeryShortCode_testimonial extends WPBakeryShortCodesContainer{}
	class WPBakeryShortCode_testimonial_item extends WPBakeryShortCode{}
	}
}
