<?php
function parse_featured_content_func($atts, $content){
	$title 			= isset($atts['title']) ? $atts['title'] : '';	
	$condition 					= isset($atts['condition']) ? $atts['condition'] : 'latest';
	$count					= isset($atts['count']) ? $atts['count'] : '6';
	$categories 			= isset($atts['categories']) ? $atts['categories'] : '';
	$sort_by 					= isset($atts['order']) ? $atts['order'] : 'DESC';

	$show_title 			= isset($atts['show_title']) ? $atts['show_title'] : '';	
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
	if($number_excerpt==''){ $number_excerpt = apply_filters('excerpt_length',$number_excerpt,1);}

	if(function_exists('ot_get_option')){ $themes_pur= ot_get_option('theme_purpose');}
	if($show_exceprt==''){$show_exceprt = $show_exceprt1;}
	if(class_exists('CT_ContentHelper')){
		ob_start();
		global $featured_content_id;
		$featured_content_id = $featured_content_id?$featured_content_id:0;
		$featured_content_id++;
		
		$contenthelper = new CT_ContentHelper;
		//$num_item = count($featured_query->posts);
		$contenthtml = new CT_ContentHtml; 		
		$categories_array = explode(',',$categories);
		?>
        <div class="featured-content-box" id="featured-content-box-<?php echo $featured_content_id ?>">
        	<div class="featured-content-box-inner">
            	<div class="featured-box-heading bgcolor2">
                	<h3 class="featured-box-title pull-left"><?php echo $title ?></h3>
                	<div class="featured-tabs pull-right hidden-xs">
                    	<ul class="list-inline">
                        <?php
						$tab_count=1;
						if($categories){
							foreach($categories_array as $category){
								// get category name
								$category_name = '';
								if(is_numeric($category)) {
									$category_name = get_cat_name($category);
								} else {
									$cat = get_category_by_slug($category);
									if($cat) $category_name = $cat->name;
								}
								echo '<li class="'.($tab_count==1?'active':'').'"><a class="featured-tab" href="#'.$category.$featured_content_id.'" data-toggle="tab">'.$category_name.'</a></li>';
								$tab_count++;
							}
							$categories_array[]='__allcat_hidden';
						}else{
							$categories_array = array('__allcat');
						}?>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div><!--/featured-box-heading-->
                <div class="tab-content featured-box-content">
                <?php foreach($categories_array as $category){
					$category_to_query = $category=='__allcat'?'':($category=='__allcat_hidden'?$categories:$category);
					$featured_query = $contenthelper->tm_get_popular_posts($condition, $tags, $count, $ids,$sort_by, $category_to_query, $args = array(),$themes_pur);
					$html_carousel = '';
				?>
                	<div class="tab-pane fade featured-box-tab <?php echo $category==$categories_array[0]?'in active':''; echo $category=='__allcat_hidden'?' visible-xs in active':($category=='__allcat'?'':' hidden-xs') ?>" id="<?php echo $category.$featured_content_id ?>">
                  		<div class="featured-box-tab-inner">
                            <div class="row">
                            	<div class="featured-control-col col-md-4 col-sm-4">
                                	<ul class="featured-control" data-id="<?php echo $category ?>" data-featured-id="<?php echo $featured_content_id ?>" >
                                        <?php
										$featured_count = 0;
										while($featured_query->have_posts()){ $featured_query->the_post();
											echo '<li><a class="item-cat-'.$category.' item-cat-'.$category.'-'.$featured_count.' '.($featured_count==0?'selected':'').'" data-pos='.$featured_count.' href="#">'.get_the_title().'</a></li>';
											$html_carousel.= $contenthtml->get_item_medium_video($thumb='thumb_520x293', $show_title, $show_exceprt, $show_rate,$show_dur,$show_view,$show_com,$show_like, $show_aut,$show_date,$themes_pur,$number_excerpt);
											$featured_count++;
										}
										wp_reset_postdata();
										?>
                                    </ul>
                                </div>
                                <div class="featured-carousel-col col-md-8 col-sm-8">
                                	<div class="featured-box-carousel is-carousel" data-pagi="pagi-<?php echo $category ?>">
                                    	<div data-id="<?php echo $category ?>" data-featured-id="<?php echo $featured_content_id ?>" class="featured-box-carousel-content">
                                    	<?php echo $html_carousel; ?>
                                        </div>
                                        <div id="pagi-<?php echo $category ?>" class="carousel-pagination"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                	</div><!--featured-box-tab-->
                    <?php }?>
                </div>
            </div>
        </div><!--/featured-content-box-->
        <?php
		$output_string=ob_get_contents();
		ob_end_clean();
		return $output_string;
	}
}

add_shortcode( 'featured-content', 'parse_featured_content_func' );

/* Register shortcode with Visual Composer */
add_action( 'after_setup_theme', 'reg_featured_content' );
function reg_featured_content(){
	if(function_exists('vc_map')){

	vc_map( array(
		"name"		=> __("Featured Content Box", "js_composer"),
		"base"		=> "featured-content",
		"class"		=> "",
		"icon"		=> "icon-wpb-slideshow",
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
			  "type" => "textfield",
			  "heading" => __("Categories", "cactusthemes"),
			  "param_name" => "categories",
			  "description" => __("Enter categories, separated by a comma (can be either ID or slug).", "cactusthemes")
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
				__("Modified", "js_composer") => "modified", 
				__("Random", "js_composer") => "random"), 
				"description" => __("Select condition", "cactusthemes")
			),
			array(
				"type" => "textfield",
				"heading" => __("Number of posts", "cactusthemes"),
				"param_name" => "count",
				"value" => "",
				"description" => __('Number of posts per category', "cactusthemes")
			),
			array(
				"type" => "dropdown",
				"heading" => __("Order", "js_composer"),
				"param_name" => "order",
				"value" => array( 
				__("Descending", "cactusthemes") => "DESC", 
				__("Ascending", "cactusthemes") => "ASC" ),
				"description" => __('Designates the ascending or descending order. More at <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>.', 'cactusthemes')
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
		)
	) );
	}
}













