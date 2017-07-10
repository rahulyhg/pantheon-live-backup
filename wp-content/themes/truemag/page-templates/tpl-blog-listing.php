<?php
/*
 * Template Name: Blog listing
 */
get_header();
$layout = ot_get_option('blog_layout','right');
global $sidebar_width;
$style = 'blog';
global $post;
$topnav_style = ot_get_option('topnav_style','dark');
?>
	<div class="blog-heading <?php echo $topnav_style=='light'?'heading-light':'' ?>">
    	<div class="container">
            <h1><?php echo $post->post_title ?></h1>
            <?php if($post->post_content){ ?><span><?php echo $post->post_content ?></span><?php }?>
        </div>
    </div><!--blog-heading-->
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
								'operator' => 'NOT IN'
							)
						)
					);
					$listing_query = null;
					$listing_query = new WP_Query($args);
					if ($listing_query->have_posts()) : ?>
						<section class="blog-listing">
                            <div class="blog-listing-content <?php if($pagination=='page_ajax'||$pagination==''){ echo 'tm_load_ajax';} ?>  ">
								<?php get_template_part('loop-blog'); ?>
                            </div><!--/video-listing-content(blog-listing-content)-->
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