<?php
function parse_smart_content_func($atts, $content){
	$title 			= isset($atts['title']) ? $atts['title'] : '';	
	$ids 			= isset($atts['ids']) ? $atts['ids'] : '';	
	$column 			= isset($atts['column']) ? $atts['column'] : '2';	
	$row 			= isset($atts['row']) ? $atts['row'] : '1';	
	$layout 			= isset($atts['layout']) ? $atts['layout'] : 'grid';	
	$condition 					= isset($atts['condition']) ? $atts['condition'] : 'latest';
	$timerange 					= isset($atts['timerange']) ? $atts['timerange'] : '';
	$number					= isset($atts['count']) ? $atts['count'] : '8';
	$url_viewall 				= isset($atts['url_viewall']) ? $atts['url_viewall'] : '';
	$categories 			= isset($atts['categories']) ? $atts['categories'] : '';
	$sort_by 					= isset($atts['order']) ? $atts['order'] : 'DESC';
	$tags 					= isset($atts['tags']) ? $atts['tags'] : '';
	$postformats 			= isset($atts['postformats']) ? $atts['postformats'] : '';
	$label 					= isset($atts['label']) ? $atts['label'] : '';
	$show_title 			= isset($atts['show_title']) ? $atts['show_title'] : '1';	
	$show_exceprt1 			= isset($atts['show_exceprt']) ? $atts['show_exceprt'] : '1';
	$show_exceprt 			= isset($atts['show_excerpt']) ? $atts['show_excerpt'] : '1';	
	$show_rate 			= isset($atts['show_rate']) ? $atts['show_rate'] : '1';	
	$show_dur 			= isset($atts['show_dur']) ? $atts['show_dur'] : '1';	
	$show_view 			= isset($atts['show_view']) ? $atts['show_view'] : '1';	
	$show_com 			= isset($atts['show_com']) ? $atts['show_com'] : '1';	
	$show_like 			= isset($atts['show_like']) ? $atts['show_like'] : '1';	
	$show_aut 			= isset($atts['show_aut']) ? $atts['show_aut'] : '1';
	$show_date 			= isset($atts['show_date']) ? $atts['show_date'] : '1';
	$number_excerpt 			= isset($atts['number_excerpt']) ? $atts['number_excerpt'] : 55;
	$quick_view 			= isset($atts['quick_view']) ? $atts['quick_view'] : 'def';
	
	if(isset($atts['css_animation'])){
		$animation_class = $atts['css_animation']?'wpb_'.$atts['css_animation'].' wpb_animate_when_almost_visible':'';
	}
	if(class_exists('Mobile_Detect')){
		$detect = new Mobile_Detect;
		$_device_ = $detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'pc';
		$animation_class = ($_device_=='pc' && isset($animation_class))?$animation_class:'';
	}
	
	if($number_excerpt==''){ $number_excerpt = apply_filters('excerpt_length',$number_excerpt,1);}
	$num_r='';
	
	if($show_exceprt==''){$show_exceprt = $show_exceprt1;}
	
	if(function_exists('ot_get_option')){ $themes_pur= ot_get_option('theme_purpose');}
	if($row==''){$row=1;}
	if($layout=='grid'){
		if($column==2){ $count=($number*1);
		} else if($column==4) { $count=(($number*5));
		} else if($column==6) { $count=$number*9; }
	}else
	if($layout=='small_carousel'){
		$num_r=$row * $column;
		if($column==2){$count=($number*2*$row);
		} else if($column==4) { $count=($number*4*$row);
		} else if($column==6) { $count=$number*6*$row; }
	}else
	if($layout=='medium_carousel' || $layout=='medium_carousel_2'){
		
		if($column==2){$count=($number*1*$row);
			$num_r=$row * 1;
		} else if($column==4) { $count=($number*2*$row);
			$num_r=$row * 2;
		} else if($column==6) { $count=$number*3*$row; 
			$num_r=$row * 3;
		}
	}else
	if($layout=='single'){
		$count=$number*$row;
	}
	//if(){ $count=$number;}
	
	if(class_exists('CT_ContentHelper')){
		$item_video1 = new CT_ContentHelper;	
		$the_query = $item_video1->tm_get_popular_posts($condition, $tags, $count, $ids,$sort_by, $categories, $args = array(),$themes_pur,$postformats,$timerange);
		$num_item = count($the_query->posts);
		$html = '
			<div class="smart-box '.$animation_class.' ';
		$id=rand();	
		if($layout=='grid'){$html .= 'smart-box-style-1 is-carousel" id="'.$id.'"'; }else
		if($layout=='small_carousel'){$html .= 'smart-box-style-2 is-carousel" id="'.$id.'"'; }else
		if($layout=='medium_carousel'){$html .= 'smart-box-style-3 is-carousel" id="'.$id.'"'; }else
		if($layout=='medium_carousel_2'){$html .= 'smart-box-style-3 smart-box-style-3-2 is-carousel" id="'.$id.'" data-pagi="pagi-'.$id.'"'; }else
		if($layout=='single'){$html .= 'smart-box-style-4 is-carousel" id="'.$id.'"'; }
		$html .='>';
		$border_h ='';
		$class_cssit='';
		if($layout!='grid'){$class_cssit='it-row';}
		if($the_query->have_posts()){	
		if($title=='' && $url_viewall=='' && $number =='1'){
			$border_h = 'style="border:0"';
		}
		if($layout!='medium_carousel_2'){
			$html .= '<div class="smart-box-head" '.$border_h.'>';
				if($number==1){
				$html .= '<h2 class="light-title title">'.$title.'</h2>';}
				else {
					if($url_viewall !=''){
					$html .= '<h2 class="light-title title"><a class="title-link" href="'.$url_viewall.'">'.$title.'</a></h2>';
					} else {$html .= '<h2 class="light-title title">'.$title.'</h2>';}
				}
				$html .= '<div class="smart-control pull-right">
					<a class="prev maincolor2 bordercolor2 bgcolor2hover" href="#" style="display: inline-block;"><i class="fa fa-angle-left"></i></a>
					<a class="next maincolor2 bordercolor2 bgcolor2hover" href="#" style="display: inline-block;"><i class="fa fa-angle-right"></i></a>';
					if(($url_viewall!='') && ($number==1) && ($label=='')){$html .= '	
					<a href="'.$url_viewall.'" class="bordercolor2 bgcolor2hover">'.__('View all','cactusthemes').'</a>';
					} else
					if(($url_viewall!='') && ($number==1) && ($label!='')){$html .= '	
					<a href="'.$url_viewall.'" class="bordercolor2 bgcolor2hover">'.$label.'</a>';
					}
				$html .= '</div>
			</div>';
		}
			$html .= '<div class="smart-box-content">';
			if($layout!='medium_carousel_2'){
				$html .= '<div class="smart-item '.$class_cssit.'">
					<div class="row">';
			}
			$item_video = new CT_ContentHtml; 
			$item=0;
				while($the_query->have_posts()){ $the_query->the_post();
					$item++;
					$html.= $item_video->get_all_item_video($item,$num_item,$column,$layout, $show_title, $show_exceprt, $show_rate,$show_dur,$show_view,$show_com,$show_like, $show_aut,$show_date, $themes_pur,$num_r,$number,$row,$class_cssit,$number_excerpt,$quick_view);
			
			}
			$html .= '</div>';
			if($layout=='medium_carousel_2'){
				$html .= '<div class="carousel-dotted" id="pagi-'.$id.'"></div>
				';
			}
			$html .= '</div>';
			$html .= '<div class="clear"></div>';
			
		}
		wp_reset_query();
		return $html;
	}
}

add_shortcode( 'scb', 'parse_smart_content_func' );
function smart_content_js(){
	global $smart_content_js;
	if($smart_content_js) foreach($smart_content_js as $asmart_content_js){echo $smart_content_js;}
}
add_action('wp_footer', 'smart_content_js', 100);

/* Register shortcode with Visual Composer */
add_action( 'after_setup_theme', 'reg_scb', 100 );
function reg_scb(){
	global $theme_post_formats;
	$theme_post_formats = get_theme_support( 'post-formats' );
	$post_formats = array("Standard" => "post-format-standard");
	foreach ( $theme_post_formats[0] as $format) {
		$post_formats[ucfirst($format)] = "post-format-" . $format;
    }
	 
	if(function_exists('vc_map')){
	vc_map( array(
		"name"		=> __("Smart Content Box", "js_composer"),
		"base"		=> "scb",
		"class"		=> "wpb_vc_posts_slider_widget",
		//"icon"		=> "icon-wpb-slideshow",
		"category"  => __('Content', 'js_composer'),
		"params"	=> array(
			array(
				"type" => "textfield",
				"heading" => __("Title", "cactusthemes"),
				"param_name" => "title",
				"value" => "",
				"description" => __('', "cactusthemes")
			),
			array(
				"type" => "dropdown",
				"holder" => "div",
				"heading" => __("Layout", "cactusthemes"),
				"param_name" => "layout",
				"value" => array(
					__("Grid","cactusthemes")=>'grid',
					__("Small carousel","cactusthemes")=>'small_carousel',
					__("Medium carousel","cactusthemes")=>'medium_carousel',
					__("Medium carousel with Navigation","cactusthemes")=>'medium_carousel_2',
					__("Single","cactusthemes")=>'single'
				),
				"description" => __("Select layout", "cactusthemes")
			),
			array(
				"type" => "dropdown",
				"holder" => "div",
				"heading" => __("Column ", "cactusthemes"),
				"param_name" => "column",
				"value" => array( 
				__("2", "cactusthemes") => "2", 
				__("4", "cactusthemes") => "4" ,
				__("6", "cactusthemes") => "6" ),
				"description" => __('', 'cactusthemes')
			),	
			array(
				"type" => "textfield",
				"heading" => __("Row", "cactusthemes"),
				"param_name" => "row",
				"value" => "",
				"description" => __('', "cactusthemes")
			),	
			array(
				"type" => "dropdown",
				"holder" => "div",
				"heading" => __("Condition", "cactusthemes"),
				"param_name" => "condition",
				"value" => array(
				__("Latest","cactusthemes")=>'latest', 
				__("Most viewed","cactusthemes")=>'most_viewed', 
				__("Most Liked","cactusthemes")=>'likes', 
				__("Most commented","cactusthemes")=>'most_comments',
				__("Title", "js_composer") => "title", 
				__("Modified", "js_composer") => "modified", 
				__("Random", "js_composer") => "random"), 
				"description" => __("Select condition", "cactusthemes")
			),
			array(
				"type" => "dropdown",
				"holder" => "div",
				"heading" => __("Time range", "cactusthemes"),
				"param_name" => "timerange",
				"value" => array(
				__("All","cactusthemes")=>'', 
				__("Day","cactusthemes")=>'day', 
				__("Week","cactusthemes")=>'week', 
				__("Month","cactusthemes")=>'month',
				__("Year", "cactusthemes") => "year"), 
				"description" => __("Select Time range", "cactusthemes")
			),
			array(
				"type" => "textfield",
				"heading" => __("IDs", "cactusthemes"),
				"param_name" => "ids",
				"value" => "",
				"description" => __('', "cactusthemes")
			),
	
			array(
				"type" => "textfield",
				"heading" => __("Number of Slides", "cactusthemes"),
				"param_name" => "count",
				"value" => "",
				"description" => __('How many number to show? Enter number .', "cactusthemes")
			),
			array(
			  "type" => "textfield",
			  "heading" => __("Categories", "cactusthemes"),
			  "param_name" => "categories",
			  "description" => __("List of cat ID (or slug), separated by a comma", "cactusthemes")
			),
	
			array(
				"type" => "textfield",
				"heading" => __("Tags", "cactusthemes"),
				"param_name" => "tags",
				"value" => "",
				"description" => __('Tags', "cactusthemes")
			),
			array(
				"type" => "dropdown",
				"heading" => __("Order by", "js_composer"),
				"param_name" => "order",
				"value" => array( 
				__("Descending", "cactusthemes") => "DESC", 
				__("Ascending", "cactusthemes") => "ASC" ),
				"description" => __('Designates the ascending or descending order. More at <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>.', 'cactusthemes')
			),
			array(
				"type" => "checkbox",
				"heading" => __("Post Formats", "cactusthemes"),
				"param_name" => "postformats",
				"value" => $post_formats,
				"description" => __('Select Post Formats', 'cactusthemes')
       		),
			array(
				"type" => "textfield",
				"heading" => __("Url-viewall", "cactusthemes"),
				"param_name" => "url_viewall",
				"value" => "",
				"description" => __('Link', "cactusthemes")
			),
			array(
				"type" => "textfield",
				"heading" => __("Label For Url-viewall", "cactusthemes"),
				"param_name" => "label",
				"value" => "",
				"description" => __('', "cactusthemes")
			),
			array(
				"type" => "dropdown",
				"heading" => __("Show title", "cactusthemes"),
				"param_name" => "show_title",
				"value" => array( 
				__("Yes", "cactusthemes") => "1", 
				__("No", "cactusthemes") => "0" ),
				"description" => __('', 'cactusthemes')
			),
			array(
				"type" => "dropdown",
				"heading" => __("Show excerpt", "cactusthemes"),
				"param_name" => "show_excerpt",
				"value" => array( 
				__("Yes", "cactusthemes") => "1", 
				__("No", "cactusthemes") => "0" ),
				"description" => __('', 'cactusthemes')
			),
			array(
				"type" => "dropdown",
				"heading" => __("Rating Value", "cactusthemes"),
				"param_name" => "show_rate",
				"value" => array( 
				__("Yes", "cactusthemes") => "1", 
				__("No", "cactusthemes") => "0" ),
				"description" => __('', 'cactusthemes')
			),
			array(
				"type" => "dropdown",
				"heading" => __("Show Duration", "cactusthemes"),
				"param_name" => "show_dur",
				"value" => array( 
				__("Yes", "cactusthemes") => "1", 
				__("No", "cactusthemes") => "0" ),
				"description" => __('', 'cactusthemes')
			),
			array(
				"type" => "dropdown",
				"heading" => __("Show view Count", "cactusthemes"),
				"param_name" => "show_view",
				"value" => array( 
				__("Yes", "cactusthemes") => "1", 
				__("No", "cactusthemes") => "0" ),
				"description" => __('', 'cactusthemes')
			),
			array(
				"type" => "dropdown",
				"heading" => __("Show comment count", "cactusthemes"),
				"param_name" => "show_com",
				"value" => array( 
				__("Yes", "cactusthemes") => "1", 
				__("No", "cactusthemes") => "0" ),
				"description" => __('', 'cactusthemes')
			),
			array(
				"type" => "dropdown",
				"heading" => __("Show like count", "cactusthemes"),
				"param_name" => "show_like",
				"value" => array( 
				__("Yes", "cactusthemes") => "1", 
				__("No", "cactusthemes") => "0" ),
				"description" => __('', 'cactusthemes')
			),
			array(
				"type" => "dropdown",
				"heading" => __("Show author", "cactusthemes"),
				"param_name" => "show_aut",
				"value" => array( 
				__("Yes", "cactusthemes") => "1", 
				__("No", "cactusthemes") => "0" ),
				"description" => __('Only use for Medium carousel and Single style', 'cactusthemes')
			),
			array(
				"type" => "dropdown",
				"heading" => __("Show date", "cactusthemes"),
				"param_name" => "show_date",
				"value" => array( 
				__("Yes", "cactusthemes") => "1", 
				__("No", "cactusthemes") => "0" ),
				"description" => __('Only use for Medium carousel and Single style', 'cactusthemes')
			),
			array(
				"type" => "textfield",
				"heading" => __("Number of excerpt to show", "cactusthemes"),
				"param_name" => "number_excerpt",
				"value" => "",
				"description" => __('', "cactusthemes")
			),
			array(
				"type" => "dropdown",
				"heading" => __("Enable Quick View Info", "cactusthemes"),
				"param_name" => "quick_view",
				"value" => array(
				__("Default", "cactusthemes") => 'def',
				__("Yes", "cactusthemes") => 1, 
				__("No", "cactusthemes") => 0 ),
				"description" => __('Quick View Info / Quick View Video Hover Popup', 'cactusthemes')
			),
		)
	) );
	}
}













