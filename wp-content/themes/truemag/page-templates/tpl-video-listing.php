<?php
/*
 * Template Name: Video listing
 */
get_header();
$default_style = ot_get_option('default_listing_layout');
if($default_style =='style-list-1'){?>
	<style>
		.tooltipster-base{ display:none !important}
	</style>
<?php }
$layout = ot_get_option('blog_layout','right');
global $sidebar_width;
$style = 'video';
?>
    <div id="body">
        <div class="container">
            <div class="row">
				<?php $pagination = ot_get_option('pagination_style','page_def');?>
  				<div id="content" class="<?php echo $layout!='full'?($sidebar_width?'col-md-9':'col-md-8'):'col-md-12' ?><?php echo ($layout == 'left') ? " revert-layout":"";?>" role="main">
                	<?php
					$paged = get_query_var('paged')?get_query_var('paged'):(get_query_var('page')?get_query_var('page'):1);
					$args=array(
						'post_type' => 'post',
						'post_status' => 'publish',
						'paged' => $paged,
						'tax_query' => array(
							array(                
								'taxonomy' => 'post_format',
								'field' => 'slug',
								'terms' => array(
									'post-format-video'
								),
								'operator' => 'IN'
							)
						)
					);
					$listing_query = null;
					$listing_query = new WP_Query($args);
					if ($listing_query->have_posts()) : ?>
						<section class="video-listing <?php echo ot_get_option('default_listing_layout')?ot_get_option('default_listing_layout'):''; ?>">
                            <div class="video-listing-head">
                            	<?php $show_hide_title = ot_get_option('show_hide_title','1');
								if($show_hide_title=='1'){ ?>
                                <h2 class="light-title"><?php global $post; echo $post->post_title ?></h2>
								<?php } ?>
                                <?php get_template_part('loop-filter'); ?>
                            </div>
                            <div class="video-listing-content <?php if($pagination=='page_ajax'||$pagination==''){ echo 'tm_load_ajax';} ?>  ">
								<?php get_template_part('loop-item'); ?>
                            </div><!--/video-listing-content-->
                            <div class="clearfix"></div>
						<?php if($pagination=='page_navi' && function_exists( 'wp_pagenavi' )){
							wp_pagenavi(array( 'query' => $listing_query ));
						}else if($pagination=='page_def'){
							cactusthemes_content_nav('paging');
						}?>
                        </section>
					<?php endif; wp_reset_postdata(); ?>
                </div><!--#content-->
                <?php if($layout != 'full'){ get_sidebar(); } ?>
            </div><!--/row-->
        </div><!--/container-->
    </div><!--/body-->
<?php get_footer(); ?>