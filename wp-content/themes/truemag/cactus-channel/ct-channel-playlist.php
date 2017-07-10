    <?php 
	$paged = get_query_var('paged')?get_query_var('paged'):(get_query_var('page')?get_query_var('page'):1);
    $args = array(
        'post_type' => 'ct_playlist',
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
		'paged' => $paged,
        'orderby' => 'modified',
        'meta_query' => array(
            array(
                'key' => 'playlist_channel_id',
                 'value' => get_the_ID(),
                 'compare' => 'LIKE',
            ),
        )
    );
    $list_query = new WP_Query( $args );
	$total_page = ceil($list_query->found_posts / get_option('posts_per_page'));
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
    	<?php while(have_posts()){the_post(); 
			get_template_part( 'cactus-channel/content-playlist' );
		}?>
    </div>
    <?php 
	
	}else{
		esc_html_e("There isn't any playlist in this channel","cactusthemes");
	}
	?>
	<div class="page-navigation"><?php cactus_paging_nav('.cactus-sub-wrap','cactus-channel/content-playlist', esc_html__('Load More Playlists','cactusthemes')); ?></div>
    <?php wp_reset_postdata();
	if($it>0){
	$wp_query = $main_query;
	}