<?php
/**
 * Template Name: Subscribed Channels
 *
 * @package cactus
 */
if( !is_user_logged_in()){
	header('Location: ' . wp_login_url( get_permalink() ));
	exit();
}
get_header();
$layout = op_get('ct_channel_settings','channel_sg_sidebar');
?>
            <div id="cactus-body-container"> <!--Add class cactus-body-container for single page-->
            
            	<!--Listing-->
                <div class="cactus-listing-wrap cactus-sidebar-control category-change-style-slider">
                    <!--Config-->        
                    <div class="cactus-listing-config style-1 style-3 cactus-newsfeed"> <!--addClass: style-1 + (style-2 -> style-n)-->
                    
                        <div class="container">
                            <div class="row">
                            
                                <div id="content" class="<?php echo $layout!='full'?($sidebar_width?'col-md-9':'col-md-8'):'col-md-12' ?><?php echo ($layout == 'left') ? " revert-layout":"";?> cactus-listing-content main-content-col"> <!--ajax div-->
                                	<div class="cactus-new-feed">
                                        <!--breadcrumb-->
                                        <?php 
                                         if(function_exists('ct_breadcrumbs')){  ct_breadcrumbs(); } 
                                        ?>
                                        <!--breadcrumb-->
                                                                           
                                        <div class="cactus-listing-heading">
                                            <h1><?php _e('WHAT TO WATCH','cactusthemes'); ?></h1>
                                        </div>
                                        <?php 
                                        $meta_user = get_user_meta(get_current_user_id(), 'subscribe_channel_id',true);
                                        if(!is_array($meta_user)){
                                            $meta_user = explode(" ", $meta_user );
                                        }
                                        $paged = get_query_var('paged')?get_query_var('paged'):(get_query_var('page')?get_query_var('page'):1);
                                        $query = new WP_Query( array( 'post_type' => 'ct_channel', 'post__in' => $meta_user , 'paged' => $paged ) );
										$it = $query->post_count;
                                        if($query->have_posts()&& !empty($meta_user)){
                                            global $wp_query,$wp;
                                            $main_query = $wp_query;
                                            $wp_query = $query;
                                            ?>
                                            
                                            <script type="text/javascript">
                                             var cactus_ajax_paging = {"ajaxurl":"<?php echo admin_url( 'admin-ajax.php' );?>","query_vars":<?php echo str_replace('\/', '/', json_encode(array( 'post_type' => 'ct_channel', 'post__in' => $meta_user ))) ?>,"current_url":"<?php echo home_url($wp->request);?>" }
                                            </script> 
                                            <?php	
                                            while ( $query->have_posts() ) : $query->the_post(); 
                                                get_template_part( 'cactus-channel/content-feed' );
                                            endwhile;
                                            wp_reset_postdata();
										}else{?>
                                        <div class="list-cactus-text-block no-subscribe">
                                        	<?php _e('You have not subscribed to any channel. Please explore channel by follow the link bellow.','cactusthemes'); ?>
                                        </div>
                                        <div class="cactus-listing-heading fix-channel">
                                            <div class="navi-channel">                                        	
                                                <div class="navi pull-left">
                                                    <a href="<?php echo get_post_type_archive_link('ct_channel');?>" class="btn btn-default btn-tdep"><i class="fa fa-play-circle"></i> <?php _e('EXPREORE','cactusthemes'); ?></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php }?>
                                    </div>
                                    <div class="page-navigation"><?php cactus_paging_nav('.cactus-new-feed','cactus-channel/content-feed', esc_html__('Load More Channels','cactusthemes')); ?></div>
									<?php
									if($it>0){ 
										$wp_query = $main_query;
									}
									?>
                                </div>
                                
                                <?php if($layout != 'full'){
									get_sidebar();
								}?>  
                                
                            </div>
                        </div>
                        
                    </div><!--Config-->
                </div><!--Listing-->            
            	
            </div>
            
<?php get_footer(); ?>