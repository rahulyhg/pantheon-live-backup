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
global $post_standard_layout;
$post_standard_layout = get_post_meta(get_the_ID(),'post_standard_layout',true);
if(!$post_standard_layout){
	$post_standard_layout = ot_get_option('post_standard_layout','1');
}
global $post_gallery_layout;
$post_gallery_layout = get_post_meta(get_the_ID(),'post_gallery_layout',true);
if(!$post_gallery_layout){
	$post_gallery_layout = ot_get_option('post_gallery_layout','1');
}
$show_related_post = ot_get_option('show_related_post','on');
$show_comment = ot_get_option('show_comment','on');
global $thumb_url;
$thumbnail_id = get_post_thumbnail_id( get_the_ID() );
$thumb_url = wp_get_attachment_url( $thumbnail_id );
global $cactus_width;
$cactus_width = $sidebar!='full'?8:12;
?>

    <div id="cactus-body-container"> <!--Add class cactus-body-container for single page-->
        <div class="cactus-single-page cactus-sidebar-control <?php if($sidebar!='full'){ echo "sb-".$sidebar; } ?>">
            <div class="container">
                <div class="row">
                
                    <div class="cactus-top-style-post style-3">
                        <!--breadcrumb-->
                        <?php if( function_exists('ct_breadcrumbs')){
                             ct_breadcrumbs(); 
                        } 
                        ?>
                        <!--breadcrumb-->
                        <?php 
						$args = array(
							'post_type' => 'post',
							'posts_per_page' => -1,
							'post_status' => 'publish',
							'ignore_sticky_posts' => 1,
							'orderby' => 'meta_value_num',
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
						$author_id=$post->post_author;
						$url_author = get_author_posts_url( $author_id );
						$author_name = get_the_author_meta( 'display_name', $author_id);
						$the_query = new WP_Query( $args );
						$it = $the_query->post_count;
						if($the_query->have_posts()){
							$i =0;
							while($the_query->have_posts()){ $the_query->the_post();
							$i++;
							$file = get_post_meta($post->ID, 'tm_video_file', true);
							$url = trim(get_post_meta($post->ID, 'tm_video_url', true));
							$code = trim(get_post_meta($post->ID, 'tm_video_code', true));
							if(strpos($url, 'youtube.com') !== false){}
							if($i==1){
							?>           
							<div class="style-post-content dark-div">
								<div class="cactus-video-list-content" data-auto-first="1" data-label = "<?php _e(' videos','cactus');?>">
									<div class="player-content">
										<div class="player-iframe">
											<div class="iframe-change">
                                            	<?php tm_video(get_the_ID(), true);?>
											</div>
                                            
                                            <div class="iframe-change-upload" style="display:none">
                                            	<?php 
												$ct_query = get_posts($args);
												foreach ( $ct_query as $key_more => $post ) :
												$url_r = trim(get_post_meta($post->ID, 'tm_video_url', true));
													if(extractChanneldFromURL($url_r)==''){
													?>
                                                        <div class="item-upload" data-id-upload="<?php echo $post->ID;?>">
                                                            <?php tm_video($post->ID, true);?>
                                                        </div>
													<?php 
													}
												endforeach;?>
											</div>
                                            
                                            
                                            
											<div class="video-loading">
												<div class="circularG-wrap">
													<div class="circularG_1 circularG"></div>
													<div class="circularG_2 circularG"></div>
													<div class="circularG_3 circularG"></div>
													<div class="circularG_4 circularG"></div>
													<div class="circularG_5 circularG"></div>
													<div class="circularG_6 circularG"></div>
													<div class="circularG_7 circularG"></div>
													<div class="circularG_8 circularG"></div>
												</div>
											</div>
											<?php echo tm_post_rating(get_the_ID());?>
										</div>                                        	
									</div>
									
									<div class="video-listing">
										<div class="user-header">
											<h6></h6>
											<!--info-->
											<div class="posted-on">                                                    
												<a href="<?php echo $url_author; ?>" class="author cactus-info"> <?php echo $author_name;?></a>
												<div class="total-video cactus-info"></div>
											</div><!--info-->
											
											<a href="javascript:;" class="pull-right open-video-playlist">play list&nbsp; <i class="fa fa-sort-desc"></i></a>  
											<a href="javascript:;" class="pull-left open-video-playlist"><i class="fa fa-bars"></i></a>  
										</div>
										
										<div class="fix-open-responsive">
										
											<div class="video-listing-content">
											
												<div class="cactus-widget-posts">
                                                <?php }?>
										
													<!--item listing-->
													<div class="cactus-widget-posts-item active" data-source="<?php echo extractChanneldFromURL($url);?>" data-id-video="<?php echo extractIDFromURL($url); ?>" data-id-post"<?php echo get_the_ID();?>">
														  <!--picture-->
														  <div class="widget-picture">
															<div class="widget-picture-content"> 
															  <a href="javascript:;" class="click-play-video-1" title="<?php echo esc_attr(get_the_title(get_the_ID()));?>">
																<?php the_post_thumbnail( 'thumb_86x64' ); ?>                                                                  
															  </a>
															  <?php echo tm_post_rating(get_the_ID());?>
															  <div class="cactus-note-time">3:11</div>
															</div>
														  </div>
														  <!--picture-->
														  
														  <div class="cactus-widget-posts-content"> 
															<!--Title-->
															<h3 class="h6 widget-posts-title"> <a href="javascript:;" class="click-play-video" title="<?php echo esc_attr(get_the_title(get_the_ID()));?>"><?php echo esc_attr(get_the_title(get_the_ID()));?></a></h3>
															<!--Title--> 
															
															<!--info-->
															<div class="posted-on"> 
																<?php echo cactus_get_datetime();?>
															 	<div class="comment cactus-info"><?php echo get_comments_number(get_the_ID());?></div>
															</div>
															<!--info-->
													
														  </div>  
														  
														  <div class="order-number">1</div>   
														  <div class="video-active"></div>
													</div>
													<!--item listing-->
													
												<?php if($i==$it){?>														
												</div>
												
											</div>
											
										</div>
												
									</div>
								</div>
								
							</div>
							<?php }
							}
							
							wp_reset_postdata();
						}?>
                    </div>
                
                    <div class="main-content-col col-md-12 cactus-config-single">
                        
                        <div id='single-post' class="single-post-content">
							<?php while ( have_posts() ) : the_post();?>
                                    <article data-id="<?php echo get_the_ID();?>"  data-timestamp='<?php echo get_post_time('U');?>' id="post-<?php the_ID(); ?>" <?php post_class('cactus-single-content'); ?>>
									<?php include( 'content.php'); ?>
                                    <?php if($show_related_post!='off'){ include( 'single-related.php'); }?>
                                <?php
                                    // If comments are open or we have at least one comment, load up the comment template
                                    if ( ($show_comment!='off') && (comments_open() || '0' != get_comments_number()) ) :
                                        comments_template();
                                    endif;
                                ?>
                                    <div class="entry-content">
                                        <?php 
                                            $args = array(
                                                'post_type' => 'post',
                                                'posts_per_page' => -1,
                                                'post_status' => 'publish',
                                                'ignore_sticky_posts' => 1,
                                                'orderby' => 'meta_value_num',
                                                'meta_query' => array(
                                                    array(
                                                        'key' => 'playlist_id',
                                                         'value' => get_the_ID(),
                                                         'compare' => 'LIKE',
                                                    ),
                                                )
                                            );
                                            $the_query = new WP_Query( $args );
                                            $it = $the_query->post_count;
                                            if($the_query->have_posts()){
                                                while($the_query->have_posts()){ $the_query->the_post();
                                                    echo get_the_title(get_the_ID());
                                                }
                                            }
                                            wp_reset_postdata();
                                        ?>
                                    </div><!-- .entry-content -->
                                </article><!-- #post-## -->
                                <?php
                            endwhile;
                            ?>
                        </div>

                    </div>


                    <?php if($sidebar!='full'){ get_sidebar(); } ?>

                </div><!--.row-->
            </div><!--.container-->
        </div><!--#cactus-single-page-->
    </div><!--#cactus-body-container-->

<?php get_footer(); ?>