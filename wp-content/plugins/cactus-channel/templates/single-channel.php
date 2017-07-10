<?php
/**
 * The Template for displaying single channel.
 *
 * @package cactus
 */
get_header();
?>

    <div id="cactus-body-container"> <!--Add class cactus-body-container for single page-->
          <div class="cactus-listing-wrap cactus-sidebar-control">
              <!--Config-->        
              <div class="cactus-listing-config style-1 style-3 style-channel"> <!--addClass: style-1 + (style-2 -> style-n)-->
              
                  <div class="container">
                      <div class="row">
                      
                          <div class="col-md-12 cactus-listing-content main-content-col"> <!--ajax div-->
                            <?php while ( have_posts() ) : the_post();
							$thumbnail_id = get_post_thumbnail_id( get_the_ID() );
							$thumb_url = wp_get_attachment_url( $thumbnail_id );
							?>
                            <div class="header-channel" style="background-image:url(<?php echo $thumb_url ?>)">                                    	
                                <div class="header-channel-content">
                                
                                    <div class="table-content">                                        		
                                        <div class="table-cell">
                                            <h1><?php the_title(); ?></h1>
                                            <!--Share-->
                                            <?php cactus_print_social_share(); ?>
                                            <!--Share-->
                                        </div>    
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div class="cactus-listing-heading fix-channel">
                                <div class="navi-channel">                                        	
                                    <div class="subs pull-right">                                            	
                                        <div class="subs-button subscribed">
                                            <div class="subs-row">
                                                <div class="subs-cell"><a href="javascript:;"><i class="fa fa-eye"></i><i class="fa fa-times"></i><i class="fa fa-check"></i> <span class="first-title"><?php _e('Subscribe','cactus');?></span><span class="last-title"><?php _e('unSubscribe','cactus');?></span></a></div>
                                                <div class="subs-cell"><span>12,345</span></div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="navi pull-left">
                                        <div class="navi-content">
                                            <div class="navi-item <?php if(!isset($_GET['view']) || (isset($_GET['view']) && $_GET['view'] =='videos') || (isset($_GET['view']) && $_GET['view'] =='')){?> active <?php }?>"><a href="<?php echo add_query_arg( array('view' => 'videos'), get_the_permalink() ); ?>" title=""><?php _e('videos','cactus');?></a></div>
                                            <div class="navi-item <?php if((isset($_GET['view']) && $_GET['view'] =='playlists')){?> active <?php }?>"><a href="<?php echo add_query_arg( array('view' => 'playlists'), get_the_permalink() ); ?>" title=""><?php _e('playlists','cactus');?></a></div>
                                            <div class="navi-item <?php if((isset($_GET['view']) && $_GET['view'] =='discussion')){?> active <?php }?>"><a href="<?php echo add_query_arg( array('view' => 'discussion'), get_the_permalink() ); ?>" title=""><?php _e('discussion','cactus');?></a></div>
                                            <div class="navi-item <?php if((isset($_GET['view']) && $_GET['view'] =='about')){?> active <?php }?>"><a href="<?php echo add_query_arg( array('view' => 'about'), get_the_permalink() ); ?>" title=""><?php _e('about','cactus');?></a></div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
								<?php 
								if(isset($_GET['view']) && $_GET['view'] =='playlists'){
									
								}elseif(isset($_GET['view']) && $_GET['view'] =='discussion'){
									
								}elseif(isset($_GET['view']) && $_GET['view'] =='about'){
									
								}else{
									include( 'channel-video.php');
								}
								?>
                                <?php
                                    // If comments are open or we have at least one comment, load up the comment template
                                    if ( (comments_open() || '0' != get_comments_number()) ) :
                                        comments_template();
                                    endif;
                                ?>

                            <?php endwhile; // end of the loop. ?>
                        </div>
                    </div><!--.row-->
                </div><!--.container-->
            </div>
        </div><!--#cactus-single-page-->
    </div><!--#cactus-body-container-->

<?php get_footer(); ?>