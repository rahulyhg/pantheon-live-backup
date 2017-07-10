<?php
global $wp_query;
global $listing_query;
if($listing_query){
	$wp_query = $listing_query;
}
echo '<div class="post_ajax_tm" >';
while ($wp_query->have_posts()) : $wp_query->the_post();
$url = trim(get_post_meta($post->ID, 'tm_video_url', true));
?>
	<div id="post-<?php the_ID(); ?>" <?php post_class('blog-item video-item'.(has_post_thumbnail()?'':' no-thumbnail')) ?>>
      <div class="row <?php if(strpos($url,'facebook.com') !== false){ echo 'fb-video';}?>">
        <div class="col-md-6 col-sm-6">
            <div class="item-thumbnail">
                <?php get_template_part('blog-thumbnail'); ?>
            </div>
            <div class="clearfix"></div>
        </div><!--/col6-->
        <div class="col-md-6 col-sm-6">
            <div class="item-head row">
                <div class="col-md-10 col-sm-10 col-xs-9">
                    <h3><a class="maincolor2hover" href="<?php the_permalink() ?>" rel="<?php the_ID(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
                    <div class="blog-meta">
                    	<?php if(ot_get_option('blog_show_meta_author',1)){ ?>
                        <span><?php the_author_posts_link(); ?></span> | 
                        <?php }?>
                        <span><?php the_category(', '); ?></span>
                        <?php if(ot_get_option('blog_show_meta_comment',1)){?>
                        <?php if(comments_open()){ ?> | 
                        <span><a href="<?php comments_link(); ?>"><?php comments_number(__('0 Comments','cactusthemes'),__('1 Comment','cactusthemes')); ?></a></span>
                        <?php } //check comment open 
						}?>
                    </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-3">
                <?php if(ot_get_option('blog_show_meta_date',1)){ ?>
                    <div class="blog-date">
                        <span><?php the_time('d') ?></span>
                        <span><?php the_time('M') ?></span>
                    </div>
                <?php } ?>
                </div>
            </div>
            <div class="blog-excerpt">
                <?php the_excerpt(); ?>
                <a href="<?php the_permalink() ?>" class="readmore maincolor2 bordercolor2 bgcolor2hover bordercolor2hover"><?php _e('Watch Now','cactusthemes') ?> <i class="fa fa-angle-right"></i></a>
            </div>
        </div><!--/col6-->
      </div><!--/row-->
      <div class="clearfix"></div>
    </div><!--/blog-item-->
<?php
endwhile;
tm_display_ads('ad_recurring');
echo '</div>';
?>
