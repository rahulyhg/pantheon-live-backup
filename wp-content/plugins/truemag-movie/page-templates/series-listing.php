<?php
/**
 * Template Name: Video Series Listing
 *
 * @package truemag
 */
global $global_page_layout;
$layout = get_post_meta(get_the_ID(),'sidebar',true);
if(!$layout){
	$layout = $global_page_layout ? $global_page_layout : ot_get_option('page_layout','right');
}

global $sidebar_width;
global $post;
 
get_header();


$topnav_style = ot_get_option('topnav_style','dark');	

?>
	<div class="blog-heading <?php echo $topnav_style=='light'?'heading-light':'' ?>">
    	<div class="container">
            <h1><?php echo get_the_title($post->ID); ?></h1>
        </div>
    </div><!--blog-heading-->

    <div id="body">
        <div class="container">
            <div class="row">
            	<?php 
				$layout = get_post_meta(get_the_ID(), 'sidebar', true);
				
				if($layout == ''){
					$front_page_layout = ot_get_option('front_page_layout');
				}
				
				?>
                    <div id="content" class="cactus-listing-content <?php echo $layout != 'full' ? ($sidebar_width ? 'col-md-9':'col-md-8'):'col-md-12' ?><?php echo ($layout == 'left') ? " revert-layout":"";?>" role="main">
                        <div class="cactus-sub-wrap">
                            <?php
                            $posts_per_page = apply_filters('truemag-video-series-limit', get_option('posts_per_page'));
                            
                            $total_items = wp_count_terms('video-series', array('hide_empty' => false));
                            
                            $total_pages = ceil( $total_items / $posts_per_page);
                            
                            $page = isset($_GET['page']) ? intval($_GET['page']) : (get_query_var('page') ? get_query_var('page') : 1);
                            
                            $offset = ($page - 1) * $posts_per_page;
                            
                            $args = array(
                                            'taxonomy' => 'video-series',
                                            'number'	=> $posts_per_page, // all items
                                            'hide_empty' => false,
                                            'offset'	=> $offset, // index
                                            'order'		=> 'ASC',
                                            'orderby'	=> 'name'
                                                );

                            $terms = get_terms(apply_filters('truemag-video-series-listing-args', $args));
                            
                            foreach($terms as $term){
                                include dirname(__FILE__) . '/loop-series-item.php';
                            }
                            ?>
                        </div><!-- /end .cactus-sub-wrap -->
                        
                        
                        <?php cactus_paginate($_SERVER['REQUEST_URI'],'page', $total_pages, $page, $posts_per_page); ?>
                    </div><!--#content-->
                    <?php if( $front_page_layout == '0' && is_front_page() ){
					} elseif( $layout != 'full' ){
						get_sidebar();
					}?>
            </div><!--/row-->
        </div><!--/container-->
    </div><!--/body-->
<?php get_footer(); ?>