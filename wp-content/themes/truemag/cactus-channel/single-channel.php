<?php
/**
 * The Template for displaying single channel.
 *
 * @package cactus
 */
get_header();
$layout = op_get('ct_channel_settings','channel_sg_sidebar');
$channel_layout = 'video';
global $sidebar_width;
global $cpage;
?>

    <div id="cactus-body-container"> <!--Add class cactus-body-container for single page-->
          <div class="cactus-listing-wrap cactus-sidebar-control">
              <!--Config-->        
              <div class="cactus-listing-config style-1 style-3 style-channel <?php if((isset($_GET['view']) && $_GET['view'] =='playlists')){?> tab-channel-playlist <?php }?>"> <!--addClass: style-1 + (style-2 -> style-n)-->
              		<?php
                    $thumbnail_id = get_post_thumbnail_id( get_the_ID() );
                    $thumb_url = wp_get_attachment_url( $thumbnail_id );
                    ?> 
                    <div class="header-channel" style="background-image:url(<?php echo $thumb_url ?>)">                                    	
                        <div class="header-channel-content dark-div">
                            <div class="table-content">                                        		
                                <div class="table-cell">
                                    <h1><?php the_title(); ?></h1>
                                    <!--Share-->
                                    <?php cactus_print_channel_social_accounts(''); ?>
                                    <div class="hidden-share" style="display:none"> <?php cactus_print_social_share(''); ?></div>
                                    <!--Share-->
                                </div>    
                            </div>
                            <div class="sc-button">
                                <?php cactus_subcribe_button(); ?>
                            </div>
                        </div>
                    </div>
                  <div class="container">
                      <div class="row">
                            <div id="content" class="<?php echo $layout!='full'?($sidebar_width?'col-md-9':'col-md-8'):'col-md-12' ?><?php echo ($layout == 'left') ? " revert-layout":"";?> cactus-listing-content main-content-col"> <!--ajax div-->
                              <?php while ( have_posts() ) : the_post();
                              ?>                              
                              <div class="cactus-listing-heading fix-channel">
                                  <div class="navi-channel">                                        	
                                      <div class="navi pull-left">
                                          <div class="navi-content">
                                              <div class="navi-item <?php if( (!isset($_GET['view']) &&  ($cpage =='')) || ( (isset($_GET['view']) && $_GET['view'] =='videos') &&  ($cpage =='')) || ((isset($_GET['view']) && $_GET['view'] =='')&&  ($cpage ==''))){?> active <?php }?>"><a href="<?php echo add_query_arg( array('view' => 'videos'), get_the_permalink() ); ?>" title=""><?php _e('videos','cactusthemes');?></a></div>
                                              <div class="navi-item <?php if((isset($_GET['view']) && $_GET['view'] =='playlists')){?> active <?php }?>"><a href="<?php echo add_query_arg( array('view' => 'playlists'), get_the_permalink() ); ?>" title=""><?php _e('playlists','cactusthemes');?></a></div>
                                              
                                              <?php if ( comments_open() || '0' != get_comments_number() ) :?>
                                              <div class="navi-item <?php if((isset($_GET['view']) && $_GET['view'] =='discussion') || ($cpage !='')){?> active <?php }?>"><a href="<?php echo add_query_arg( array('view' => 'discussion'), get_the_permalink() ); ?>" title=""><?php _e('discussion','cactusthemes');?></a></div>
                                              <?php endif;?>
                                              
                                              <div class="navi-item <?php if((isset($_GET['view']) && $_GET['view'] =='about')){?> active <?php }?>"><a href="<?php echo add_query_arg( array('view' => 'about'), get_the_permalink() ); ?>" title=""><?php _e('about','cactusthemes');?></a></div>
                                          </div>
                                      </div>
                                      
                                      <div class="open-tab-menu">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <div class="navi-sub-content">
                                              <div class="navi-sub-item <?php if( (!isset($_GET['view']) &&  ($cpage =='')) || ( (isset($_GET['view']) && $_GET['view'] =='videos') &&  ($cpage =='')) || ((isset($_GET['view']) && $_GET['view'] =='')&&  ($cpage ==''))){?> active <?php }?>"><a href="<?php echo add_query_arg( array('view' => 'videos'), get_the_permalink() ); ?>" title=""><?php _e('videos','cactusthemes');?></a></div>
                                              <div class="navi-sub-item <?php if((isset($_GET['view']) && $_GET['view'] =='playlists')){?> active <?php }?>"><a href="<?php echo add_query_arg( array('view' => 'playlists'), get_the_permalink() ); ?>" title=""><?php _e('playlists','cactusthemes');?></a></div>
                                              <div class="navi-sub-item <?php if((isset($_GET['view']) && $_GET['view'] =='discussion') || ($cpage !='')){?> active <?php }?>"><a href="<?php echo add_query_arg( array('view' => 'discussion'), get_the_permalink() ); ?>" title=""><?php _e('discussion','cactusthemes');?></a></div>
                                              <div class="navi-sub-item <?php if((isset($_GET['view']) && $_GET['view'] =='about')){?> active <?php }?>"><a href="<?php echo add_query_arg( array('view' => 'about'), get_the_permalink() ); ?>" title=""><?php _e('about','cactusthemes');?></a></div>
                                          </div>
                                      </div>
                                      
                                  </div>
                              </div>
                                  <?php 
                                  if(isset($_GET['view']) && $_GET['view'] =='playlists'){
                                      get_template_part( 'cactus-channel/ct-channel-playlist' );
                                  }elseif(isset($_GET['view']) && $_GET['view'] =='discussion' || ($cpage !='')){
                                      get_template_part( 'cactus-channel/ct-channel-discus' );
                                  }elseif(isset($_GET['view']) && $_GET['view'] =='about'){
                                      get_template_part( 'cactus-channel/ct-channel-about' );
                                  }else{
                                      //include( '.php');
                                      get_template_part( 'cactus-channel/ct-channel-video' );
                                  }
                                  ?>                                
                                  <div class="discus-none" style="display:none">
                                      <?php get_template_part( 'cactus-channel/ct-channel-discus' ); ?>
                                  </div>
                                  <script>
                                      var locationHashComment = window.location.hash;
                                      var showElementstag = jQuery('.cactus-listing-wrap .style-channel .combo-change, .cactus-listing-wrap .style-channel .cactus-sub-wrap, .cactus-listing-wrap .style-channel .page-navigation');
                                      if(locationHashComment!='' && locationHashComment!=null && typeof(locationHashComment)!='undefined' && locationHashComment.toString().split("-").length == 2){
                                          showElementstag.css({'display':'none'});
                                          jQuery('.cactus-listing-wrap .style-channel .discus-none').show();
                                          jQuery('.cactus-listing-wrap .style-channel .navi-content .navi-item').removeClass('active');
                                          jQuery('.cactus-listing-wrap .style-channel .navi-content .navi-item').eq(2).addClass('active');
                                      };
                                  </script>
                              <?php endwhile; // end of the loop. ?>
                            </div>
                            <?php if($layout != 'full'){
								get_sidebar();
							}?>                        
                    </div><!--.row-->
                </div><!--.container-->
            </div>
        </div><!--#cactus-single-page-->
    </div><!--#cactus-body-container-->

<?php get_footer(); ?>