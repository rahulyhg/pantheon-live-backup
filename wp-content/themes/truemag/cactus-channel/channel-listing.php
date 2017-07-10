<?php
get_header();
global $global_page_layout;
$layout = get_post_meta(get_the_ID(),'sidebar',true);
if(!$layout){
	$layout = $global_page_layout ? $global_page_layout : ot_get_option('page_layout','right');
}
global $sidebar_width;
?>

<div id="cactus-body-container"> <!--Add class cactus-body-container for single page-->
	<!--Listing-->
    <div class="cactus-listing-wrap cactus-sidebar-control"> <!--add config side bar right-->
        <!--Config-->
        <div class="cactus-listing-config style-1 style-2 channel-list"> <!--addClass: style-1 + (style-2 -> style-n)-->

            <div class="container">
                <div class="row">


                    <div id="content" class="<?php echo $layout!='full'?($sidebar_width?'col-md-9':'col-md-8'):'col-md-12' ?><?php echo ($layout == 'left') ? " revert-layout":"";?> cactus-listing-content main-content-col">

                        <!--breadcrumb-->
                        <?php if(function_exists('ct_breadcrumbs')){ ct_breadcrumbs(); } ?>
                        <!--breadcrumb-->
                            <div class="cactus-listing-heading">
                                <h1><?php echo esc_html('Channel', 'cactusthemes');?></h1>
                            </div>
                            <?php 
                            $paged = get_query_var('paged')?get_query_var('paged'):(get_query_var('page')?get_query_var('page'):1);
                            $args = array(
                                'post_type' => 'ct_channel',
                                'posts_per_page' => get_option('posts_per_page'),
								'post_status' => 'publish',
                                'ignore_sticky_posts' => 1,
                                'paged' => $paged,
                            );
                            $list_query = new WP_Query( $args );
                            $it = $list_query->post_count;
                            if($list_query->have_posts()){?>
							<?php
                            global $wp_query,$wp;
                            $main_query = $wp_query;
                            $wp_query = $list_query;
                            ?>
                            
                            <script type="text/javascript">
                             var cactus_ajax_paging = {"ajaxurl":"<?php echo admin_url( 'admin-ajax.php' );?>","query_vars":<?php echo str_replace('\/', '/', json_encode($args)) ?>,"current_url":"<?php echo home_url($wp->request);?>" }
                            </script>    
                            <div class="cactus-sub-wrap">
                                <?php if ( have_posts() ) : ?>
                                    <?php while ( have_posts() ) : the_post(); ?>
                                        <?php get_template_part( 'cactus-channel/content-listing'); ?>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php //get_template_part( 'html/loop/content', 'none' ); ?>
                                <?php endif; ?>
                            </div>
                            <?php }?>
                            <div class="page-navigation"><?php cactus_paging_nav('.cactus-listing-config.channel-list .cactus-sub-wrap','cactus-channel/content-listing', esc_html__('Load More Channels','cactusthemes')); ?></div>
							<?php 
                            wp_reset_query();
                            wp_reset_postdata();
                            if($it>0){
                              $wp_query = $main_query;
                            }?>
                    </div>

                    <?php if($layout!='full'){ get_sidebar(); } ?>

                </div>
            </div>

        </div><!--Config-->
    </div><!--Listing-->
</div>
<?php
get_footer();