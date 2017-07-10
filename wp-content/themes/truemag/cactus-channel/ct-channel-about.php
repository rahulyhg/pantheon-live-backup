<div class="main-about">
    <div class="about-content">
        <?php the_content();?>
    </div>
    
    <!--Share-->
    <?php cactus_print_channel_social_accounts('change-color'); ?>
    <div class="hidden-share" style="display:none"> <?php cactus_print_social_share('change-color'); ?></div>
    <!--Share-->
    
    <div class="channel-information">
     	<?php 
		$subscribe_counter = get_post_meta(get_the_ID(), 'subscribe_counter',true);
		if($subscribe_counter){
			$subscribe_counter = tm_short_number($subscribe_counter);
		}else{$subscribe_counter = 0;}
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
        <span><i class="fa fa-play-circle"></i> <?php echo $n_video; _e(' videos','cactusthemes'); ?></span>
        <span><i class="fa fa-users"></i> <?php echo $subscribe_counter; _e(' subscribers ','cactusthemes'); ?></span>
        <span><i class="fa fa-eye"></i> <?php echo $view_channel; _e(' views','cactusthemes'); ?></span>
    </div>
    
</div>