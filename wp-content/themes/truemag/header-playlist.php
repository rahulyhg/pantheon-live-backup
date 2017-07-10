<?php
if (function_exists('wpfp_link')) { //check if active wp favorites post
$header_bg = ot_get_option('header_home_bg');
$current_bb_user = get_userdata( bp_displayed_user_id() );
	$favorite_post_ids = wpfp_get_users_favorites($current_bb_user->user_login);
    if ($favorite_post_ids) {
		$favorite_post_ids = array_reverse($favorite_post_ids);
        $content_helper = new CT_ContentHelper;	
		global $header_query;
		$playlist_number = ot_get_option('playlist_number',10);
		$header_query = $content_helper->tm_get_popular_posts('playlist', '', $playlist_number, implode(",", $favorite_post_ids),'','', $args = array(),'');
?>
<style type="text/css">
#classy-carousel{
<?php if($header_bg['background-color']){ echo 'background-color:'.$header_bg['background-color'].';';} ?>
<?php if($header_bg['background-attachment']){ echo 'background-attachment:'.$header_bg['background-attachment'].';';} ?>
<?php if($header_bg['background-repeat']){
	echo 'background-repeat:'.$header_bg['background-repeat'].';';
	echo 'background-size: initial;';
} ?>
<?php if($header_bg['background-position']){ echo 'background-position:'.$header_bg['background-position'].';';} ?>
<?php if($header_bg['background-image']){ echo 'background-image:url('.$header_bg['background-image'].');';} ?>
}
</style>
<div id="slider" class="playlist-header">
<div id="classy-carousel">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="is-carousel" id="stage-carousel" <?php echo !ot_get_option('header_home_auto')?'data-notauto=1':'' ?>>
                    <div class="classy-carousel-content">
                    <?php if($header_query->have_posts()){
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
					}?>
                    </div><!--/carousel-content-->
                    <div class="clearfix"></div>
                </div><!--stage-->
            </div><!--col8-->
            <div class="col-md-4">
                <div class="is-carousel" id="control-stage-carousel">
                    <a class="control-up"><i class="fa fa-angle-up"></i></a>
                    <div class="classy-carousel-content">
                    <?php if($header_query->have_posts()){
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
					}?>
                    </div><!--/carousel-content-->
                    <a class="control-down"><i class="fa fa-angle-down"></i></a>
                </div><!--control-stage-->
            </div>
        </div><!--/row-->
    </div><!--/container-->
</div><!--classy-->
</div>
<?php }
}//check if active wp favorites post ?>