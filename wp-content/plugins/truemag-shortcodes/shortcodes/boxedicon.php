<?php
function parse_boxed($atts, $content, $id){
		global $style,$border;
		$style = isset($atts['style']) ? $atts['style'] : 'style-1';
		$border = isset($atts['border']) ? $atts['border'] : '1';
		$output = '';
		$id = 'box-'.rand();
		str_replace("[boxed_item","",$content,$number_item);
        $output = '';
		$title = '';
		$id = 'box-'.rand();
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
		
		if($number_item==1){$w_col='item_1';}
		else if($number_item==2){$w_col='item_2';}
		else if($number_item==3){$w_col='item_3';}
		else if($number_item==4){$w_col='item_4';}
		else if($number_item==5){$w_col='item_5';}
		else if($number_item==6){$w_col='item_6';}

		$number_item=0;
        $width = '';//wpb_translateColumnWidthToSpan($width);
        $output .= "\n\t\t".'<div class="boxedicon">';
		if($border=='0'){
		$output_css ='#'.$id.'{ border:0; padding-bottom:0} ';
		$output .= "\n\t\t" .'<style type="text/css" scoped="scoped"> '.$output_css.' </style>';
		}
		$output .= "\n\t\t" . '<div id="'.$id.'" class="boxed-icon '.$style.' '.$w_col.' '.$animation_class.' ">';
        $output .= wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_accordion_heading'));
        $output .= do_shortcode(str_replace('<br class="nc" />', '', $content));
		$output .= "\n\t\t".'</div> ';
        $output .= "\n\t\t".'</div><div class="clear"><!-- --></div>';
		$number_item=0;
        return $output;
}

function parse_boxed_item($atts, $content, $id){
		$heading = isset($atts['heading']) ? $atts['heading'] : '';
		$icon = isset($atts['icon']) ? $atts['icon'] : '';
		$id = rand();
		global $style,$border;
		$res_css = '';
		if($border=='0'){
		$res_css ='notop-bt';
		}
        $output .= "\n\t\t\t" . '<div class="boxed-item" id="boxed-'.$id.'"><div class="re_box '.$res_css.'">';
		if($style=='style-1'){
			$output .= "\n\t\t\t\t" . '<h2 class="heading"><i class="fa '.$icon.' icon_ct" ></i>'.$heading.'</h2>';
		}else
		{
			$output .= "\n\t\t\t\t" . '<div class="ic_st2"><span class="icon_ct"><i class="fa '.$icon.'" ></i></span></div><h2 class="heading">'.$heading.'</h2>';
		}
		$output .= "\n\t\t\t\t" . '<div class="contain"><div class="boxed-item-bg"></div>';
		
		$output .= "\n\t\t\t\t" . '<div class="contain-content">'.$content.'</div>';

		$output .= "\n\t\t\t\t" . '</div>';
		$output .= "\n\t\t\t" . '</div></div>';
        return $output;
}
add_shortcode( 'boxed_item', 'parse_boxed_item' );
add_shortcode( 'boxed', 'parse_boxed' );

//Visual Composer

add_action( 'after_setup_theme', 'reg_boxed' );
function reg_boxed(){
	if(function_exists('vc_map')){

		vc_map( array(
			"name"		=> __("Boxed", "cactusthemes"),
			"base"		=> "boxed",
			"as_parent" => array('only' => 'boxed_item'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
			"content_element" => true,
			"show_settings_on_create" => false,
			"icon" => "icon-boxed",
			"params"	=> array(
					array(
						"type" => "dropdown",
						"heading" => __("Style", "cactusthemes"),
						"param_name" => "style",
						"value" => array(
						__("Icon in Heading", 'castusthemes') =>"style-1",
						__("Icon Centered", 'castusthemes') =>"style-2"),
						"description" => __("", "cactusthemes")
					),
					array(
						"type" => "dropdown",
						"heading" => __("Border", "cactusthemes"),
						"param_name" => "border",
						"value" => array(
						__("Yes", 'castusthemes') =>"1",
						__("No", 'castusthemes') =>"0"),
						"description" => __("", "cactusthemes")
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
					),
			),
			"js_view" => 'VcColumnView'
		) );
		vc_map( array(
			"name"		=> __("Boxed item", "cactusthemes"),
			"base"		=> "boxed_item",
			"content_element" => true,
			"as_child" => array('only' => 'boxed_item'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"icon" => "icon-boxed",
			"params"	=> array(
					array(
						"type" => "textfield",
						"heading" => __("Heading", "cactusthemes"),
						"param_name" => "heading",
						"value" => "",
						"description" => '',
					),
					array(
						"type" => "textfield",
						"heading" => __("Icon", "cactusthemes"),
						"param_name" => "icon",
						"value" => "",
						"description" => __("Name Font-Awesome . Ex:fa-random ")
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
	class WPBakeryShortCode_boxed extends WPBakeryShortCodesContainer{}
	class WPBakeryShortCode_boxed_item extends WPBakeryShortCode{}
	}
}
