<?php
function parse_tm_amazing_func($atts, $content){
	global $post;
	$condition = get_post_meta(get_the_ID(),'header_home_condition',true)?get_post_meta(get_the_ID(),'header_home_condition',true):ot_get_option('header_home_condition','lastest');
	$ids = get_post_meta(get_the_ID(),'header_home_postids',true)?get_post_meta(get_the_ID(),'header_home_postids',true):ot_get_option('header_home_postids','');
	$categories = get_post_meta(get_the_ID(),'header_home_cat',true)?get_post_meta(get_the_ID(),'header_home_cat',true):ot_get_option('header_home_cat','');
	$tags = get_post_meta(get_the_ID(),'header_home_tag',true)?get_post_meta(get_the_ID(),'header_home_tag',true):ot_get_option('header_home_tag','');
	$sort_by = get_post_meta(get_the_ID(),'header_home_order',true)?get_post_meta(get_the_ID(),'header_home_order',true):ot_get_option('header_home_order','DESC');
	$count = get_post_meta(get_the_ID(),'header_home_number',true)?get_post_meta(get_the_ID(),'header_home_number',true):ot_get_option('header_home_number',12);
	$themes_pur='';
	if(function_exists('ot_get_option')){ $themes_pur= ot_get_option('theme_purpose');}
	if(class_exists('CT_ContentHelper')){
		$conditions = 'latest';
		$ids = '';
		$item_loop_video = new CT_ContentHelper;	
		$the_query = $item_loop_video->tm_get_popular_posts($condition, $tags, $count, $ids,$sort_by, $categories, $args = array(),$themes_pur);
		$num_item = count($the_query->posts);
		$show_title= 1;
		$show_meta= $show_exceprt=0;
		$item_video = new CT_ContentHtml; 
		if($the_query->have_posts()){
		$html = '
			<div id="slider" class="amazing" '.(!ot_get_option('header_home_auto')?'data-notauto=1':'').(ot_get_option('header_home_auto_timeout')?' data-auto_timeout='.ot_get_option('header_home_auto_timeout'):'').(ot_get_option('header_home_auto_duration')?' data-auto_duration='.ot_get_option('header_home_auto_duration'):'').'>
			<div class="overlay">
				<div class="container">
					<div class="inner-slides"> ';
					$arr_thumb = array();
					$arr_id = array();
					while($the_query->have_posts()){ $the_query->the_post();
						$thumb = get_post_meta(get_the_ID(),'ct_bg_image',true);
						if($thumb==''){
							$thumb = get_post_thumbnail_id(get_the_ID());
							$arr_thumb[] = $thumb;
						} else{
							$arr_thumb[] = $thumb;
						}
						$html .= '
							<div class="slide">	
								<div class="container ">
								<div class="row video-item">
									<div class="col-md-6">
										<div class="item-thumbnail element ">
											<a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_post_thumbnail(get_the_ID(), 'thumb_100x100', array('alt' => get_the_title())).'
											<div class="link-overlay fa fa-play "></div>
											</a>
										</div>
									</div>
									<div class="col-md-6">
										<div class="item-head element">
											<h3 class="item-heading"><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'';
												if(tm_post_rating(get_the_ID())){
												$html .= tm_post_rating(get_the_ID()).'<span class="rating-bar rate-ama bgcolor2">/10</span></a></h3>';
												}else{
													$html .= '</a></h3>';
												}
												$html .= '<div class="item-info">
												<span class="item-date">'.get_the_time(get_option('date_format'),get_the_ID()).'</span>
												<span class="item-author">by <a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'" title="'.get_the_author().'" rel="author">'.get_the_author().'</a></span>
											</div>
											<div class="item-content"><p>'.wp_trim_words(get_the_excerpt(),15,$more = '').'</p>
		</div>
																				<a class="ct-btn small " href="'.get_permalink().'">'.__('View Details','cactusthemes').'</a>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
								</div>
							</div>							
						';
					}
		$html .= '</div>
				</div>
			</div>
			<div class="bg-slides">';
			for($i=0; $i < $num_item; $i++){
				$background_image_post = wp_get_attachment_image_src( $arr_thumb[$i], 'full');
				$html .= '<div class="bg_slider" ><img src="'.$background_image_post[0].'" /></div>';
			}
			//while($the_query->have_posts()){ $the_query->the_post();
			//$html .= get_the_post_thumbnail(get_the_ID(), 'thumb_100x100', array('alt' => get_the_title()));
			//}
			$html .= '	
			</div>
			<div class="carousel-button">
				<a href="#" class="prev maincolor1 bordercolor1 bgcolor1hover" style="display: block;"><i class="fa fa-chevron-left"></i></a>
				<a href="#" class="next maincolor1 bordercolor1 bgcolor1hover" style="display: block;"><i class="fa fa-chevron-right"></i></a>
			</div>
			<div class="carousel-pagination">
				<a href="#">1</a><a href="#">2</a><a href="#">3</a>
			</div>
        </div><!--/slider-->

		<div class="clear"></div>
		';
		}
		wp_reset_query();
		return $html;
	}
}

add_shortcode( 'tm_amazing', 'parse_tm_amazing_func' );

