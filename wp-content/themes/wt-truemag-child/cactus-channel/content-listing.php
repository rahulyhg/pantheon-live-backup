<?php
	$channel_ID =  get_the_ID();
	$has_no_picture_class = !has_post_thumbnail() ? ' no-picture' : '';
	?>
	<!--item listing-->
	<div class="cactus-post-item hentry">

	    <!--content-->
	    <div class="entry-content">
	        <div class="primary-post-content<?php echo $has_no_picture_class;?>"> <!--addClass: related-post, no-picture -->

				<?php if(has_post_thumbnail()): ?>
		            <!--picture-->
		            <div class="picture">
		                <div class="picture-content">
		                    <a href="<?php esc_url(the_permalink());?>" title="<?php esc_attr(the_title_attribute());?>">
		                        <?php the_post_thumbnail('thumb_520x293');?>
		                        <div class="thumb-overlay"></div>
		                    </a>
		                </div>

		            </div>
		            <!--picture-->
	        	<?php endif;?>

	            <div class="content">
					
	                <!--Title-->
	                <h3 class="h4 cactus-post-title entry-title">
	                    <a href="<?php esc_url(the_permalink());?>" title="<?php esc_attr(the_title_attribute());?>"><?php the_title();?></a>
	                </h3><!--Title-->
	                <!--info-->
	                <?php
		                $args = array(
		                	'post_type' => 'post',
		                	'post_status' => 'publish',
		                	'ignore_sticky_posts' => 1,
		                	'posts_per_page' => -1,
		                	'orderby' => 'latest',
		                	'meta_query' => array(
		                		array(
		                			'key' => 'channel_id',
		                			 'value' => get_the_ID(),
		                			 'compare' => 'LIKE',
		                		),
		                	)
		                );
		                $video_query = new WP_Query( $args );
		                $n_video = $video_query->post_count;

		                $view_channel = (int)get_post_meta( get_the_ID(), 'view_channel', true );
                                
		                $args_pl = array(
		                	'post_type' => 'ct_playlist',
		                	'post_status' => 'publish',
		                	'ignore_sticky_posts' => 1,
		                	'posts_per_page' => -1,
		                	'orderby' => 'modified',
		                	'meta_query' => array(
		                		array(
		                			'key' => 'playlist_channel_id',
		                			 'value' => get_the_ID(),
		                			 'compare' => 'LIKE',
		                		),
		                	)
		                );
		                $playlist_query = new WP_Query( $args_pl );
		                if($playlist_query->have_posts()){
		                	while($playlist_query->have_posts()){$playlist_query->the_post();
		                		$view_playlist = (int)get_post_meta( get_the_ID(), 'view_playlist', true );
		                		$view_channel = $view_channel + $view_playlist;
		                	}
		                }
		                 wp_reset_postdata();
	                ?>
	                <div class="posted-on">
	                    <div class="videos cactus-info"><?php echo $n_video; esc_html_e(' videos', 'cactusthemes');?></div>
                            
                            <?php 
                            if ((intval($view_channel/1000) > 0)){$view_channel = number_format($view_channel/10000, 2); $wtvs_unit = 'M';}
                            else {$wtvs_unit = 'K';}
                            ?>
                            
	                    <div class="view cactus-info"><?php echo $view_channel; echo $wtvs_unit; esc_html_e(' views', 'cactusthemes');?></div>
	                </div><!--info-->

	                <div class="subs-button">
	                    <?php cactus_subcribe_button($channel_ID); ?>
	                </div>

	                <div class="cactus-last-child"></div> <!--fix pixel no remove-->
	            </div>
	        </div>

	    </div><!--content-->

	</div>
	<!--item listing-->