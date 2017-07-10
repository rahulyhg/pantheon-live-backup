<?php
global $header_query;
$header_bg = get_post_meta(get_the_ID(),'background', true);
if(!$header_bg) {
	$header_bg = ot_get_option('header_home_bg'); 
}
$header_style = ot_get_option('header_home_style');
if(is_page_template('page-templates/front-page.php')){
	$header_style = get_post_meta(get_the_ID(),'header_style',true)?get_post_meta(get_the_ID(),'header_style',true):$header_style;
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
<?php if($header_bg['background-size']){ echo 'background-size:'.$header_bg['background-size'].';';} ?>
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
<div id="classy-carousel" class="classy-carousel-horizon <?php echo $header_style ?>">
    <div class="container">
        <div class="is-carousel" id="stage-carousel" <?php echo !ot_get_option('header_home_auto')?'data-notauto=1':'';
		echo ot_get_option('header_home_auto_timeout')?' data-auto_timeout='.ot_get_option('header_home_auto_timeout'):'';
		echo ot_get_option('header_home_auto_duration')?' data-auto_duration='.ot_get_option('header_home_auto_duration'):''; ?>>
                <div class="classy-carousel-content">
                <?php if($header_query->have_posts()){
                    $item_count=0;
                    while($header_query->have_posts()): $header_query->the_post();
                    $item_count++;
                    $format = get_post_format(get_the_ID());
                ?>
                    <div class="video-item">
                    	<div class="row">
                        	<div class="<?php echo $header_style=='classy3'?'col-md-12':'col-md-8'; ?>">
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
                                                $thumb=$header_style=='classy3'?'full':'thumb_748x421';
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
                                <?php }?>
                                </div><!--/item-thumbnail-->
                            </div>
                            <div class="<?php echo $header_style=='classy3'?'col-md-12':'col-md-4'; ?>">
                                <div class="item-head">
                                    <h3 class="item-heading"><a href="<?php the_permalink() ?>"  title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
                                    <?php if($header_style!='classy3'){ ?>
                                    <div class="item-info">
                                    	<span class="item-date"><?php the_time(get_option('date_format')); ?></span>
                                        <span class="item-author"><?php _e('by','cactusthemes') ?> <?php the_author_posts_link(); ?></span>
                                    </div>
                                    <div class="item-content"><?php the_excerpt() ?></div>
                                    <?php }?>
                                    <a class="ct-btn small <?php echo $header_style=='classy3'?' pull-right':''; ?>" href="<?php the_permalink() ?>"><?php _e('View Details','cactusthemes'); ?></a>
                                    <div class="clearfix"></div>
                                </div><!--/item-head-->
                            </div>
                        </div>
                    </div><!--/video-item-->
                <?php
                    endwhile;
                    wp_reset_postdata();
                }?>
                </div><!--/carousel-content-->
                <div class="clearfix"></div>
            </div><!--stage-->
        </div><!--/container-->
        <div id="top-carousel">
        	<div class="container">
                <div class="is-carousel" id="control-stage-carousel-horizon">
                    <div class="classy-carousel-content carousel-content">
                    <?php if($header_query->have_posts()){
						$item_count=0;
						while($header_query->have_posts()): $header_query->the_post();
						$item_count++;
					?>
                    	<div class="video-item">
                            <div class="item-thumbnail">
                                <?php
                                if(has_post_thumbnail()){
                                    $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(),'thumb_196x126', true);
                                }else{
                                    $thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
                                }
                        ?>
                                <img src="<?php echo $thumbnail[0] ?>" width="<?php echo $thumbnail[1] ?>" height="<?php echo $thumbnail[2] ?>" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>">
                                    <div class="link-overlay fa fa-play"></div>
                                <?php echo tm_post_rating(get_the_ID()) ?>
                                <div class="item-head">
                                    <h3><?php echo wp_trim_words(get_the_title(),4,$more = '...');?></h3>
                                </div>
                            </div>
                        </div><!--/video-item-->
                    <?php
						endwhile;
						wp_reset_postdata();
					}?>
                    </div><!--/carousel-content-->
                    <div class="carousel-button more-videos">
						<a href="#" class="prev control-up"><i class="fa fa-chevron-left"></i></a>
						<a href="#" class="next control-down"><i class="fa fa-chevron-right"></i></a>
					</div><!--/carousel-button-->
                </div><!--control-stage-->
            </div>
        </div>
</div><!--classy-->