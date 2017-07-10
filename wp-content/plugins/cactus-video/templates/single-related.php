<?php
/**
 * The Template for displaying all related posts by Category & tag.
 *
 * @package cactus
 */
?>
<?php
	
	$show_related_post = ot_get_option('show_related_post');
		
	if($show_related_post == 'off') return;
		
	$related_items = cactus_get_related_posts();
	
	if(!$related_items->have_posts()) return;
	
?>
        <!--related post-->
    <div class="cactus-related-posts">
        <div class="title-related-post">
            <?php echo __('Related Posts','cactus');?>
            <a class="pre-carousel" href="javascript:;"><i class="fa fa-angle-left"></i></a>
            <a class="next-carousel" href="javascript:;"><i class="fa fa-angle-right"></i></a>
            <div class="pagination"></div>
        </div>
        <div class="related-posts-content">
            
            <!--Listing-->
            <div class="cactus-listing-wrap">
                <!--Config-->        
                <div class="cactus-listing-config style-1 style-3"> <!--addClass: style-1 + (style-2 -> style-n)-->
                
                    <div class="container">
                        <div class="row">
                        
                            <div class="col-md-12 cactus-listing-content"> <!--ajax div-->
                            
                                <div class="cactus-sub-wrap">
                                    <div class="cactus-swiper-container" data-settings='["mode":"cactus-fix-composer"]'>
                                        <div class="swiper-wrapper">
                                            <?php 
											while ( $related_items->have_posts() ) : $related_items->the_post();
											?>
                                            <div class="swiper-slide">
                                                <!--item listing-->
                                                <div class="cactus-post-item hentry">
                                                    
                                                    <!--content-->
                                                    <div class="entry-content">
                                                        <div class="primary-post-content"> <!--addClass: related-post, no-picture -->
                                                                                        
                                                            <!--picture-->
                                                            <div class="picture">
                                                                <div class="picture-content">
                                                                    <a href="<?php the_permalink();?>" title="<?php the_title_attribute();?>">
                                                                        <?php if(has_post_thumbnail()){ 
																			echo get_the_post_thumbnail(get_the_ID(), 'thumb_246x184', array('alt' => get_the_title()));
																		}?>
                                                                        <div class="thumb-overlay"></div>
                                                                        <i class="fa fa-play-circle-o cactus-icon-fix"></i>
                                                                    </a>
                                                                </div>
                                                                
                                                            </div><!--picture-->
                                                            
                                                            <div class="content">
                                                                
                                                                <!--Title-->
                                                                <h3 class="h6 cactus-post-title entry-title"> 
                                                                    <a href="<?php the_permalink();?>" title="<?php the_title_attribute();?>"><?php the_title(); ?></a> 
                                                                </h3><!--Title-->
                                                                
                                                                <!--info-->
                                                                <div class="posted-on">
                                                                    <?php echo cactus_get_datetime();?>
                                                                    <a href="<?php comments_link(); ?>" class="comment cactus-info"><?php echo get_comments_number(get_the_ID());?></a>
                                                              
                                                                </div><!--info-->
                                                                

                                                                <div class="cactus-last-child"></div> <!--fix pixel no remove-->
                                                            </div>
                                                        </div>
                                                        
                                                    </div><!--content-->
                                                    
                                                </div><!--item listing-->
                                            </div>
                                            <?php 
											endwhile;
											?>
                                        </div>        
                                    </div> 
                                       
                                </div>
                                    
                            </div>
                            
                        </div>
                    </div>
                    
                </div><!--Config-->
            </div><!--Listing-->
            
        </div>
    </div>
    <!--related post-->
<?php
    wp_reset_postdata(); 