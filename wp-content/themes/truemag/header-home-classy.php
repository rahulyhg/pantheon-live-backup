<?php
global $header_query;
$header_bg = get_post_meta(get_the_ID(),'background', true);
if(!$header_bg) {
	$header_bg = ot_get_option('header_home_bg'); 
}
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
<?php if($header_bg['background-attachment']=='fixed'){ ?>
	/*@media(min-width:768px){
		#body-wrap{
			position:fixed;
			top:0;
			bottom:0;
			left:0;
			right:0;
		}
		.admin-bar #body-wrap{
			top:32px;
		}
	}
	@media(min-width:768px) and (max-width:782px){
		.admin-bar #body-wrap{
			top:46px;
		}
		.admin-bar #off-canvas{
			top:46px;
		}
	}
	.bg-ad {
		right: 14px;
	}*/
	<?php if(ot_get_option('theme_layout')!=1){ ?>
		/*#body-wrap{
			position:fixed;
			top:0;
			bottom:0;
			left:0;
			right:0;
		}
		.admin-bar #body-wrap{
			top:32px;
		}
		@media(max-width:782px){
			.admin-bar #body-wrap{
				top:46px;
			}
			.admin-bar #off-canvas{
				top:46px;
			}
		}*/
<?php } 
}?>
</style>
<div id="classy-carousel">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="is-carousel" id="stage-carousel" <?php echo !ot_get_option('header_home_auto')?'data-notauto=1':'';
				echo ot_get_option('header_home_auto_timeout')?' data-auto_timeout='.ot_get_option('header_home_auto_timeout'):'';
				echo ot_get_option('header_home_auto_duration')?' data-auto_duration='.ot_get_option('header_home_auto_duration'):'';?>>
                    <div class="classy-carousel-content">
                    <?php if($header_query->have_posts()){
						$item_count=0;
						while($header_query->have_posts()): $header_query->the_post();
						$item_count++;
						$format = get_post_format(get_the_ID());
					?>
                        <div class="video-item">
                            <div class="item-thumbnail">
                            <?php if(ot_get_option('header_home_use_player') && get_post_format(get_the_ID())=='video'){
								$url = (tm_video(get_the_ID(), false));
							}else{ ?>
                                <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" >
                                <?php
									if(has_post_thumbnail()){
										global $_device_;
										global $_is_retina_;
										if($_device_== 'mobile' && !$_is_retina_){
											$thumb='thumb_520x293';
										}else{
											$thumb='thumb_748x421';
										}
										$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(),$thumb, true);
									}else{
										$thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
										$thumbnail[1]=748;
										$thumbnail[2]=421;
									}
									?>
									<img src="<?php echo $thumbnail[0] ?>" width="<?php echo $thumbnail[1] ?>" height="<?php echo $thumbnail[2] ?>" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>">
									<?php if($format=='' || $format =='standard'  || $format =='gallery'){ ?>
                                    <div class="link-overlay fa fa-search"></div>
                                    <?php }else {?>
                                    <div class="link-overlay fa fa-play"></div>
                                    <?php }  ?>

                                </a>
                                <div class="item-head">
                                    <h3><a href="<?php the_permalink() ?>"  title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
                                    <span><?php the_time(get_option('date_format')); ?></span>
                                </div>
                            <?php } ?>
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
									$thumbnail[1]=72;
									$thumbnail[2]=72;
								}
								?>
								<img src="<?php echo $thumbnail[0] ?>" width="<?php echo $thumbnail[1] ?>" height="<?php echo $thumbnail[2] ?>" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>" width="72">
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