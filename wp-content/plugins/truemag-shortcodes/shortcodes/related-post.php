<?php


function parse_related_post_func($atts, $content){
	$showmetadata 			= isset($atts['showmetadata']) ? $atts['showmetadata'] : 1;	
	$posttypes 				= isset($atts['posttypes']) ? $atts['posttypes'] : 'post';
	$count 					= isset($atts['count']) ? $atts['count'] : -1;
	$condition 				= isset($atts['condition']) ? $atts['condition'] : '';
	$posts_in 				= isset($atts['posts_in']) ? $atts['posts_in'] : '';
	$categories 			= isset($atts['categories']) ? $atts['categories'] : '';	
	$args = array(
		'post_type' => $posttypes,
		'posts_per_page' => $count,
		'post_status' => 'publish',
		'tag' => $condition,
	);
	
	if($posts_in != ''){
		$posts_in = split(',',$posts_in);
		$args += array('post__in'=> $posts_in);
	}else if($categories!=''){
		if(isset($categories) && $categories != '' && is_numeric ($categories)){
			$args['cat'] = $categories;
		}
	}
	$the_query = new WP_Query($args);
	$id = rand();
	$rtl = function_exists('ot_get_option')?ot_get_option('righttoleft'):0;	
	if($rtl == 0){
	$html = '
	<div id="related-posts">
		<div class="dotted-title" id="related-dot-'.$id.'">
			<h3>'.__('Related Posts','cactusthemes').'</h3>
			<div class="dotted"></div>
		</div>

		<div class="posts-carousel responsive">
	';
	} else
	{
	$html = '
	<div id="related-posts">
		<div class="dotted-title" id="related-dot-'.$id.'">
			<div class="dotted"></div>
			<h3>'.__('Related Posts','cactusthemes').'</h3>
		</div>

		<div class="posts-carousel responsive">
	';
		
	}
	if($the_query->have_posts()){		
		$html .= '<ul id="posts-carousel-'.$id.'">';
		$dem=0;
		while($the_query->have_posts()){ $the_query->the_post();
		$dem++;
			$meta_html = ($showmetadata) ? '
				<div class="datetime"><span class="icon-calendar"></span>'.get_the_date().'</div>
				<div class="comment"><span class="icon-comment"></span>'.get_comments_number().' '.__('Comments','cactusthemes').'</div>' : '';
			$html .= '
			<li>
				<div class="postleft">
					<a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_post_thumbnail(get_the_ID(), 'thumb_100x100', array('alt' => get_the_title())).'</a>
				</div>
				<div class="postright">
					<h3><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></h3>
					'.$meta_html.'
				</div>
			</li>';
		}
		$html .= '
			</ul>
			<div class="clear"></div>
			<div class="slides-control">
				<div class="dotted" id="posts-prev-'.$id.'"><!---->
				<div class="control-a">
					<a href="javascript:void(0)" id="posts-prev-'.$id.'" class="icon-sign-blank">
						<span class="icon-caret-left"><!----></span>
					</a>
					<a href="javascript:void(0)" id="posts-next-'.$id.'" class="icon-sign-blank">
						<span class="icon-caret-right"><!----></span>
					</a>
				</div>	
				</div>
			</div>
		';
	}
	$html .= '
			</div>
		</div>
		<div class="clear"></div>
	';
	global $related_post_js;
	$related_post_js[] = '
		<script>
			jQuery(document).ready(function() {
				jQuery("#posts-carousel-'.$id.'").carouFredSel({
					height: "auto",
					prev: "#posts-prev-'.$id.' ",
					next: "#posts-next-'.$id.'",
					auto: false,
					width: "100%",
					align: "left",
				});
			});
		</script>
	';
	if($width != '' && $width > 100){
		$html .= '
			<style>
				#posts-carousel-'.$id.' li{width:'.$width.'px !important;}
				#posts-carousel-'.$id.' .postright{width:'.($width - 115).'px;}
			</style>
		';
	}
	if($dem==0){
		$html .= '
			<style>
				#related-dot-'.$id.'{ display:none !important;}
			</style>
		';

	}
	wp_reset_query();
	return $html;
}

add_shortcode( 'related_post', 'parse_related_post_func' );

function related_post_js(){
	global $related_post_js;
	if($related_post_js) foreach($related_post_js as $arelated_post_js){echo $arelated_post_js;}
}
add_action('wp_footer', 'related_post_js', 100);


/* Register shortcode with Visual Composer */





