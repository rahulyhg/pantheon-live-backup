<?php
$queried_object = get_queried_object();
$series_id = $queried_object->term_id;
$style = get_option("cat_layout_$series_id")?get_option( "cat_layout_$series_id"):ot_get_option('series_style','video');
$series_header_style = get_option("cat_header_$series_id")?get_option("cat_header_$series_id"):ot_get_option('series_header_style','playlist');
if($series_header_style=='banner'){
	$cat_height = get_option("cat_height_$cat_id")?get_option("cat_height_$cat_id"):'';
	$cat_link = get_option("cat_link_$cat_id")?get_option("cat_link_$cat_id"):'';
	if(function_exists('z_taxonomy_image_url')){ $cat_img = z_taxonomy_image_url();}
	?>
	<div class="category-banner" <?php if($cat_height){ echo 'style="height:'.$cat_height.'px"';} ?>>
		<?php if($cat_link){ echo '<a href="'.$cat_link.'">';} ?>
		<?php if(isset($cat_img)&&$cat_img){ echo '<img src="'.$cat_img.'" />';} ?>
		<?php if($cat_link){ echo '</a>';} ?>
	</div>
	<?php
	
}elseif($series_header_style=='playlist'){
	$header_bg = get_post_meta(get_the_ID(),'background', true);
	if(!$header_bg) {
		$header_bg = ot_get_option('header_home_bg'); 
	}
	$args = array(
		'video-series' => $queried_object->slug,
		'order' => 'ASC',
		'posts_per_page' => -1,
		'paged' => 1
	);
	$header_query = new WP_Query( $args );
	if($header_query->have_posts()) {
?>
<style type="text/css">
#classy-carousel{
<?php if(isset($header_bg['background-color']) && $header_bg['background-color']){ echo 'background-color:'.$header_bg['background-color'].';';} ?>
<?php if(isset($header_bg['background-attachment']) && $header_bg['background-attachment']){ echo 'background-attachment:'.$header_bg['background-attachment'].';';} ?>
<?php if(isset($header_bg['background-repeat']) && $header_bg['background-repeat']){
	echo 'background-repeat:'.$header_bg['background-repeat'].';';
	echo 'background-size: initial;';
} ?>
<?php if(isset($header_bg['background-position']) && $header_bg['background-position']){ echo 'background-position:'.$header_bg['background-position'].';';} ?>
<?php if(isset($header_bg['background-image']) && $header_bg['background-image']){ echo 'background-image:url('.$header_bg['background-image'].');';} ?>
}
</style>
<div id="slider" class="playlist-header">
<div id="classy-carousel">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="is-carousel" id="stage-carousel" data-notauto=1 >
                    <div class="classy-carousel-content">
                    <?php
						$item_count=0;
						while($header_query->have_posts()): $header_query->the_post();
						$item_count++;
						$format = get_post_format(get_the_ID());
					?>
                        <div class="video-item">
                            <div class="item-thumbnail">
                                <?php
									$url = (tm_video($post->ID, false));
								?>
                            </div>
                        </div><!--/video-item-->
                    <?php
						endwhile;
						wp_reset_postdata();
					?>
                    </div><!--/carousel-content-->
                    <div class="clearfix"></div>
                </div><!--stage-->
            </div><!--col8-->
            <div class="col-md-4">
                <div class="is-carousel" id="control-stage-carousel">
                    <a class="control-up"><i class="fa fa-angle-up"></i></a>
                    <div class="classy-carousel-content">
                    <?php
						$item_count=0;
						while($header_query->have_posts()): $header_query->the_post();
						$item_count++;
					?>
                        <div class="video-item">
                            <div class="item-thumbnail">
                            <?php
								if(has_post_thumbnail()){
									$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(),'thumb_72x72', true);
								}else{
									$thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
								}
								?>
								<img src="<?php echo $thumbnail[0] ?>" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>" width="72">
                            </div>
                            <div class="item-head">
                                <h3><?php the_title(); ?></h3>
                                <span><?php the_time(get_option('date_format')); ?></span>
                            </div>
                            <div class="clearfix"></div>
                        </div><!--/video-item-->
                    <?php
						endwhile;
						wp_reset_postdata();
					?>
                    </div><!--/carousel-content-->
                    <a class="control-down"><i class="fa fa-angle-down"></i></a>
                </div><!--control-stage-->
            </div>
        </div><!--/row-->
    </div><!--/container-->
</div><!--classy-->
</div>
<?php }
}