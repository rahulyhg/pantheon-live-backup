
    <?php 
	$cr_id_cn = get_the_ID();
	$paged = get_query_var('paged')?get_query_var('paged'):(get_query_var('page')?get_query_var('page'):1);
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
		'paged' => $paged,
		'orderby' => 'latest',
        'meta_query' => array(
            array(
                'key' => 'channel_id',
                 'value' => get_the_ID(),
                 'compare' => 'LIKE',
            ),
        )
    );
	global $first_it;
	if($first_it!=''){
		$args['post__not_in'] = array($first_it); 
	}
	if(isset($_GET['sortby'])&& $_GET['sortby']=='view'){
		$args['posts_per_page' ] = -1;
		$postlist = get_posts($args);
		$posts_id = array();
		foreach ( $postlist as $post ) {
		   $posts_id[] += $post->ID;
		}
		wp_reset_postdata();
		$args = null;
		$args = array(
			  'post_type' => 'post',
			  'posts_per_page' => -1,
			  'meta_key' => '_count-views_all',
			  'orderby' => 'meta_value_num',
			  'post_status' => 'publish',
			  'post__in' =>  $posts_id,
			  'ignore_sticky_posts' => 1,
			  'paged' => $paged,
		);
		$postlist_co = get_posts($args);
		$posts_id_co = array();
		foreach ( $postlist_co as $post ) {
		   $posts_id_co[] += $post->ID;
		}
		$co_result= array_diff($posts_id,$posts_id_co);
		$posts_id = array_merge($posts_id_co, $co_result);
		wp_reset_postdata();
		$args = null;
		$args = array(
			  'post_type' => 'post',
			  'orderby'=> 'post__in',
			  'post__in' =>  $posts_id,
			  'ignore_sticky_posts' => 1,
			  'paged' => $paged,
		);
	}elseif(isset($_GET['sortby'])&& $_GET['sortby']=='comment'){
		$args['orderby']= 'comment_count';
	}elseif(isset($_GET['sortby'])&& $_GET['sortby']=='like'){
		$args['posts_per_page' ] = -1;
		$postlist = get_posts($args);
		$posts_id = array();
		foreach ( $postlist as $post ) {
		   $posts_id[] += $post->ID;
		}
		wp_reset_postdata();
		global $wpdb;
		$time_range = 'all';
		//$show_type = $instance['show_type'];
		$order_by = 'ORDER BY like_count DESC, post_title';
		$show_excluded_posts = get_option('wti_like_post_show_on_widget');
		$excluded_post_ids = explode(',', get_option('wti_like_post_excluded_posts'));
		if(!$show_excluded_posts && count($excluded_post_ids) > 0) {
			$where = "AND post_id NOT IN (" . get_option('wti_like_post_excluded_posts') . ")";
		}
		else{$where = '';}
		$query = "SELECT post_id, SUM(value) AS like_count, post_title FROM `{$wpdb->prefix}wti_like_post` L, {$wpdb->prefix}posts P ";
		$query .= "WHERE L.post_id = P.ID AND post_status = 'publish' AND value > -1 $where GROUP BY post_id $order_by";
		$posts = $wpdb->get_results($query);
		//$cates_ar = $cates;
		$p_data = array();
		//print_r($posts);
		if(count($posts) > 0) {
			foreach ($posts as $post) {
				$p_data[] = $post->post_id;
			}
		}
		if (($key = array_search($first_it, $p_data)) !== false) {
			unset($p_data[$key]);
		}
		$args = array(
			'post_type' => 'post',
			'orderby'=> 'post__in',
			'order' => 'ASC',
			'post_status' => 'publish',
			'post__in' =>  $p_data,
			'ignore_sticky_posts' => 1,
			'paged' => $paged,
			'meta_query' => array(
				array(
					'key' => 'channel_id',
					 'value' => $cr_id_cn,
					 'compare' => 'LIKE',
				),
			)
		);
		$postlist_like = get_posts($args);
		$postlist_id_like = array();
		foreach ( $postlist_like as $post ) {
		   $postlist_id_like[] += $post->ID;
		}
		$like_result= array_diff($posts_id,$postlist_id_like);
		$posts_id = array_merge($postlist_id_like, $like_result);
		wp_reset_postdata();
		$args = null;
		$args = array(
			  'post_type' => 'post',
			  'orderby'=> 'post__in',
			  'post__in' =>  $posts_id,
			  'ignore_sticky_posts' => 1,
			  'paged' => $paged,
		);
	}
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
    	<?php while(have_posts()){the_post(); 
			get_template_part( 'cactus-channel/content-video' );
		}?>
    </div>
    <?php }else{
		esc_html_e("There isn't any video in this channel","cactusthemes");
	}
	
	?>
	<div class="page-navigation"><?php cactus_paging_nav('.cactus-sub-wrap','cactus-channel/content-video'); ?></div>
    <?php 
	wp_reset_query();
	wp_reset_postdata();
	if($it>0){
		$wp_query = $main_query;
	}