<?php
/**
 * The Template for displaying all single posts.
 *
 * @package cactus
 */
get_header();

$sidebar = get_post_meta(get_the_ID(),'post_sidebar',true);
if(!$sidebar){
	$sidebar = ot_get_option('post_sidebar','right');
}
global $cactus_width;
$cactus_width = $sidebar!='full'?8:12;
global $sidebar_width;
?>

<div id="cactus-body-container"> <!--Add class cactus-body-container for single page-->
	<!--Listing-->
    <div class="cactus-listing-wrap cactus-sidebar-control"> <!--add config side bar right-->
        <!--Config-->
        <div class="cactus-listing-config style-1 style-3 playlist-no"> <!--addClass: style-1 + (style-2 -> style-n)-->

            <div class="container">
                <div class="row">


                    <div id="content" class="cactus-listing-content main-content-col <?php echo $layout!='full'?($sidebar_width?'col-md-9':'col-md-8'):'col-md-12' ?><?php echo ($layout == 'left') ? " revert-layout":"";?>">

                        <!--breadcrumb-->
                        <?php if(function_exists('ct_breadcrumbs')){ ct_breadcrumbs(); } ?>
                        <!--breadcrumb-->
                        <?php while ( have_posts() ) : the_post();
							global $id_cr_pos;
							$id_cr_pos = get_the_ID();
						?>
                            <div class="cactus-listing-heading">
                                <h1><?php the_title();?></h1>
                            </div>
                            <div class="list-cactus-text-block">
                            	<?php the_content();?>
                            </div>
                            <input type="hidden" id="single-playlist" class="id_post_playlist" value="<?php echo $id_cr_pos ?>" />
							<?php 
							$paged = get_query_var('paged')?get_query_var('paged'):(get_query_var('page')?get_query_var('page'):1);
                            $args = array(
                                'post_type' => 'post',
                                'paged' => $paged,
                                'post_status' => 'publish',
                                'ignore_sticky_posts' => 1,
                                'orderby' => 'date',
								'order' => 'ASC',
                                'post__not_in' => array(get_the_ID()),
                                'meta_query' => array(
                                    array(
                                        'key' => 'playlist_id',
                                         'value' => get_the_ID(),
                                         'compare' => 'LIKE',
                                    ),
                                )
                            );
                            global $post;
                            $the_query = new WP_Query( $args );
                            $it = $the_query->post_count;
                            if($the_query->have_posts()){
                                global $wp_query,$wp, $main_query;
                                $main_query = $wp_query;
                                $wp_query = $the_query;
                                $i =0;
                                ?>
								<script type="text/javascript">
                                    var cactus_ajax_paging = {"ajaxurl":"<?php echo admin_url( 'admin-ajax.php' );?>","query_vars":<?php echo str_replace('\/', '/', json_encode($args)) ?>,"current_url":"<?php echo home_url($wp->request);?>" }
                                </script>
                                <div class="cactus-sub-wrap">
                                <?php
									  while($the_query->have_posts()){ $the_query->the_post();
										  $i ++;
										  if($i==1){?>
											  <div class="cactus-listing-heading fix-channel">
												  <div class="navi-channel playlist-fix">                                        	
													  <div class="subs pull-right">                                            	
														  <?php cactus_print_social_share($class_css = 'change-color-1', $id_cr_pos); ?>                                              
													  </div>
													  
													  <div class="navi pull-left">
														  <a href="<?php echo add_query_arg( array('list' => $id_cr_pos), get_the_permalink() );?>" class="btn btn-default btn-playall"><i class="fa fa-play-circle"></i> <?php _e('Play All Videos','cactusthemes');?></a>
													  </div>
													  
												  </div>
											  </div>                                        
										  <?php }
										  get_template_part( 'cactus-video/content-video' );
									  }
									 // wp_reset_postdata();
                                 ?>
                                </div>
                            	<div class="page-navigation"><?php cactus_paging_nav('.cactus-listing-config.playlist-no .cactus-sub-wrap','cactus-video/content-video'); ?></div>
                            <?php }
							if($it>0){
								$wp_query = $main_query;
							}
							?>
                        </div>
                    <?php endwhile;?>
                
                    <?php if($layout != 'full'){ get_sidebar(); } ?>


                </div>
            </div>

        </div><!--Config-->
    </div><!--Listing-->
</div>
<?php get_footer(); ?>