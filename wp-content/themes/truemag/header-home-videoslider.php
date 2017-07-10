<?php
$condition= get_post_meta(get_the_ID(),'header_home_condition',true)?get_post_meta(get_the_ID(),'header_home_condition',true):ot_get_option('header_home_condition','lastest');
$ids = get_post_meta(get_the_ID(),'header_home_postids',true)?get_post_meta(get_the_ID(),'header_home_postids',true):ot_get_option('header_home_postids','');
$categories = get_post_meta(get_the_ID(),'header_home_cat',true)?get_post_meta(get_the_ID(),'header_home_cat',true):ot_get_option('header_home_cat','');
$tags = get_post_meta(get_the_ID(),'header_home_tag',true)?get_post_meta(get_the_ID(),'header_home_tag',true):ot_get_option('header_home_tag','');
$sort_by = get_post_meta(get_the_ID(),'header_home_order',true)?get_post_meta(get_the_ID(),'header_home_order',true):ot_get_option('header_home_order','DESC');
$count = get_post_meta(get_the_ID(),'header_home_number',true)?get_post_meta(get_the_ID(),'header_home_number',true):ot_get_option('header_home_number',12);
		
$theme_layout = ot_get_option('theme_layout');
$page_layout = get_post_meta(get_the_ID(),'sidebar',true);
if($page_layout==''){
	
		$page_layout = ot_get_option('page_layout');
}
$page_layout_front = ot_get_option('front_page_layout');
if($page_layout !='full' && $page_layout_front !=0){
	echo do_shortcode('[video_silder condition="'.$condition.'" order="'.$sort_by.'" count="'.$count.'" ids="'.$ids.'" categories="'.$categories.'" tags="'.$tags.'"]');
	?>
	<style>
	@media (min-width: 991px){
		<?php 
		if($page_layout =='right'){?>
		#slider .video_slider .video-with{ padding-left:25px; padding-right:0}
		#slider .video_slider{ padding-right:0}
		<?php
		}else{?>
		#slider .video_slider .video-with{ float:left !important}
		#slider .video_slider .video-with{ padding-right:25px;padding-left:0;}
		#slider .video_slider{ padding-left:0}
		<?php }
		?>
		#slider{ height:550px}
		#slider .is-carousel.simple-carousel.tm-video-slider .name.title h2{white-space: nowrap; overflow: hidden;text-overflow: ellipsis;
		}
		.row #content{ margin-top:-490px; background-color:#fff; padding-left:30px; padding-right:30px; padding-top:40px}
		.row #sidebar{ padding-left:0; padding-right:0}
		<?php if($theme_layout ==1){ ?>
		#slider .video_slider .video-with{ padding-left:15px; padding-right:15px}
		<?php }?>
	}
	@media (max-width: 991px){
		#slider .video_slider .video-with{ width:100%}
		.dark-div #slider  .is-carousel.simple-carousel.tm-video-slider .tt-content .link-overlay{ width:100%}
		#slider .video_slider .video-with .tt-content img{ width:100%}
	}
	</style>
	<?php
}
?>