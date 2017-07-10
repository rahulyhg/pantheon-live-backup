<?php
/**
 * 
 *
 */ 
add_action( 'wp_head', 'ct_hook_baw_pvc_main' );
function ct_hook_baw_pvc_main()
{
	global $post, $bawpvc_options;
	$bots = array( 	'wordpress', 'googlebot', 'google', 'msnbot', 'ia_archiver', 'lycos', 'jeeves', 'scooter', 'fast-webcrawler', 'slurp@inktomi', 'turnitinbot', 'technorati',
					'yahoo', 'findexa', 'findlinks', 'gaisbo', 'zyborg', 'surveybot', 'bloglines', 'blogsearch', 'pubsub', 'syndic8', 'userland', 'gigabot', 'become.com' );
	if( 	!( ( $bawpvc_options['no_members']=='on' && is_user_logged_in() ) || ( $bawpvc_options['no_admins']=='on' && current_user_can( 'administrator' ) ) ) &&
			!empty( $_SERVER['HTTP_USER_AGENT'] ) &&
			is_singular( $bawpvc_options['post_types'] ) &&
			!preg_match( '/' . implode( '|', $bots ) . '/i', $_SERVER['HTTP_USER_AGENT'] )
		)
	{
		global $timings;
		$IP = substr( md5( getenv( 'HTTP_X_FORWARDED_FOR' ) ? getenv( 'HTTP_X_FORWARDED_FOR' ) : getenv( 'REMOTE_ADDR' ) ), 0, 16 );
		$time_to_go = $bawpvc_options['time']; // Default: no time between count
		if( (int)$time_to_go == 0 || !get_transient( 'baw_count_views-' . $IP . $post->ID ) ) {
				$channel = get_post_meta( $post->ID, 'channel_id', true );
				if(!is_array($channel)){
					$count_channel = (int)get_post_meta( $channel, 'view_channel', true );
					$count_channel++;
					update_post_meta( $channel, 'view_channel', $count_channel );
				}else{
					foreach($channel as $channel_item){
						$count_channel = (int)get_post_meta( $channel_item, 'view_channel', true );
						$count_channel++;
						update_post_meta( $channel_item, 'view_channel', $count_channel );
					}
				}
				$playlist_v = get_post_meta( $post->ID, 'playlist_id', true );
				if(!is_array($playlist_v)){
					$count_playlist = (int)get_post_meta( $playlist_v, 'view_playlist', true );
					$count_playlist++;
					update_post_meta( $playlist_v, 'view_playlist', $count_playlist );
				}else{
					foreach($playlist_v as $playlist_item){
						$count_playlist = (int)get_post_meta( $playlist_item, 'view_playlist', true );
						$count_playlist++;
						update_post_meta( $playlist_item, 'view_playlist', $count_playlist );
					}
				}
			if( (int)$time_to_go > 0 )
				set_transient( 'baw_count_views-' . $IP . $post->ID, $IP, $time_to_go );
		}
	}
}

function get_sticky_posts_count() {
	 global $wpdb;
	 $sticky_posts = array_map( 'absint', (array) get_option('sticky_posts') );
	 return count($sticky_posts) > 0 ? $wpdb->get_var( "SELECT COUNT( 1 ) FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' AND ID IN (".implode(',', $sticky_posts).")" ) : 0;
}
// when the request action is 'load_more', the cactus_ajax_load_next_page() will be called
add_action( 'wp_ajax_load_more', 'cactus_ajax_load_next_page' );
add_action( 'wp_ajax_nopriv_load_more', 'cactus_ajax_load_next_page' );

function cactus_ajax_load_next_page() {
	//get blog listing style
	global $blog_layout;

	$test_layout = isset($_POST['blog_layout']) ? $_POST['blog_layout'] : '';

	if(isset($test_layout) && $test_layout != '' && ($test_layout == 'layout_1' || $test_layout == 'layout_2' || $test_layout == 'layout_3' || $test_layout == 'layout_4' || $test_layout == 'layout_5' || $test_layout == 'layout_6' || $test_layout == 'layout_7'))
	    $blog_layout = $test_layout;
	else
	    $blog_layout = ot_get_option('blog_layout', 'layout_1');


    // Get current page
	$page = $_POST['page'];

	// number of published sticky posts
	$sticky_posts = get_sticky_posts_count();

	// current query vars
	$vars = $_POST['vars'];


	// convert string value into corresponding data types
	foreach($vars as $key=>$value){
		if(is_numeric($value)) $vars[$key] = intval($value);
		if($value == 'false') $vars[$key] = false;
		if($value == 'true') $vars[$key] = true;
	}

	// item template file
	$template = $_POST['template'];

	// Return next page
	$page = intval($page) + 1;

	$posts_per_page = isset($_POST['post_per_page']) ? $_POST['post_per_page'] : get_option('posts_per_page');

	if($page == 0) $page = 1;
	$offset = ($page - 1) * $posts_per_page;
	/*
	 * This is confusing. Just leave it here to later reference
	 *

	if(!$vars['ignore_sticky_posts']){
		$offset += $sticky_posts;
	}
	 *
	 */


	// get more posts per page than necessary to detect if there are more posts
	$args = array('post_status'=>'publish','posts_per_page' => $posts_per_page + 1,'offset' => $offset);
	$args = array_merge($vars,$args);

	// remove unnecessary variables
	unset($args['paged']);
	unset($args['p']);
	unset($args['page']);
	unset($args['pagename']); // this is neccessary in case Posts Page is set to a static page

	$query = new WP_Query($args);




	$idx = 0;
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();

			$idx = $idx + 1;
			if($idx < $posts_per_page + 1)
				get_template_part( $template, get_post_format() );
		}

		if($query->post_count <= $posts_per_page){
			// there are no more posts
			// print a flag to detect
			echo '<div class="invi no-posts"><!-- --></div>';
		}
	} else {
		// no posts found
	}

	/* Restore original Post Data */
	wp_reset_postdata();

	die('');
}
 
if ( ! function_exists( 'cactus_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @params $content_div & $template are passed to Ajax pagination
 */
function cactus_paging_nav($content_div = '#main', $template = 'html/loop/content', $text_bt = false) {
	if(!isset($text_bt)){ $text_bt ='';}
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	
	$nav_type = ot_get_option('pagination_style','page_def');
	switch($nav_type){
		case 'page_def':
			cacus_paging_nav_default();
			break;
		case 'page_ajax':
			cacus_paging_nav_ajax($content_div, $template, $text_bt);
			break;
		case 'page_navi':
			if( ! function_exists( 'wp_pagenavi' ) ) {	
				// fall back to default navigation style
				cacus_paging_nav_default(); 
			} else {
				wp_pagenavi();
			}
			break;
	}
}
endif;

if ( ! function_exists( 'cacus_paging_nav_default' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable. Default WordPress style
 */
function cacus_paging_nav_default() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	
	?>
	<nav id="paging" class="paging-navigation channel" role="navigation">
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous alignleft"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'cactusthemes' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next alignright"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'cactusthemes' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'cacus_paging_nav_ajax' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable. Ajax loading
 *
 * @params $content_div (string) - ID of the DIV which contains items
 * @params $template (string) - name of the template file that hold HTML for a single item. It will look for specific post-format template files
			For example, if $template = 'content'
				it will look for content-$post_format.php first (i.e content-video.php, content-audio.php...)
				then it will look for content.php if no post-format template is found
*/
function cacus_paging_nav_ajax($content_div = '#main', $template = 'content', $text_bt = false) {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	
	?>
	<nav class="navigation-ajax" role="navigation">
		<div class="wp-pagenavi">
			<a href="javascript:void(0)" data-target="<?php echo $content_div;?>" data-template="<?php echo $template; ?>" id="navigation-ajax" class="load-more btn btn-default font-1">
				<div class="load-title"><?php if($text_bt){ echo __($text_bt); }else{ echo __('LOAD MORE VIDEOS','cactusthemes');} ?></div>
				<i class="fa fa-refresh hide" id="load-spin"></i>
			</a>
		</div>
	</nav>
	
	<?php
}
endif;

if ( ! function_exists( 'cactus_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function cactus_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'cactusthemes' ); ?></h1>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'cactusthemes' ) );
				next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link',     'cactusthemes' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;
 
if(!function_exists('cactus_print_social_share')){
function cactus_print_social_share($class_css = false, $id = false){
	if(!$id){
		$id = get_the_ID();
	}
?>
	<ul class="social-listing list-inline <?php if(isset($class_css)){ echo $class_css;} ?>">
  		<?php if(ot_get_option('sharing_facebook')!='off'){ ?>
	  		<li class="facebook">
	  		 	<a class="trasition-all" title="<?php _e('Share on Facebook','cactusthemes');?>" href="#" target="_blank" rel="nofollow" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+'<?php echo urlencode(get_permalink($id)); ?>','facebook-share-dialog','width=626,height=436');return false;"><i class="fa fa-facebook"></i>
	  		 	</a>
	  		</li>
    	<?php }

		if(ot_get_option('sharing_twitter')!='off'){ ?>
	    	<li class="twitter">
		    	<a class="trasition-all" href="#" title="<?php _e('Share on Twitter','cactusthemes');?>" rel="nofollow" target="_blank" onclick="window.open('http://twitter.com/share?text=<?php echo urlencode(get_the_title($id)); ?>&url=<?php echo urlencode(get_permalink($id)); ?>','twitter-share-dialog','width=626,height=436');return false;"><i class="fa fa-twitter"></i>
		    	</a>
	    	</li>
    	<?php }

		if(ot_get_option('sharing_linkedIn')!='off'){ ?>
			   	<li class="linkedin">
			   	 	<a class="trasition-all" href="#" title="<?php _e('Share on LinkedIn','cactusthemes');?>" rel="nofollow" target="_blank" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink($id)); ?>&title=<?php echo urlencode(get_the_title($id)); ?>&source=<?php echo urlencode(get_bloginfo('name')); ?>','linkedin-share-dialog','width=626,height=436');return false;"><i class="fa fa-linkedin"></i>
			   	 	</a>
			   	</li>
	   	<?php }

		if(ot_get_option('sharing_tumblr')!='off'){ ?>
		   	<li class="tumblr">
		   	   <a class="trasition-all" href="#" title="<?php _e('Share on Tumblr','cactusthemes');?>" rel="nofollow" target="_blank" onclick="window.open('http://www.tumblr.com/share/link?url=<?php echo urlencode(get_permalink($id)); ?>&name=<?php echo urlencode(get_the_title($id)); ?>','tumblr-share-dialog','width=626,height=436');return false;"><i class="fa fa-tumblr"></i>
		   	   </a>
		   	</li>
    	<?php }

		if(ot_get_option('sharing_google')!='off'){ ?>
	    	 <li class="google-plus">
	    	 	<a class="trasition-all" href="#" title="<?php _e('Share on Google Plus','cactusthemes');?>" rel="nofollow" target="_blank" onclick="window.open('https://plus.google.com/share?url=<?php echo urlencode(get_permalink($id)); ?>','googleplus-share-dialog','width=626,height=436');return false;"><i class="fa fa-google-plus"></i>
	    	 	</a>
	    	 </li>
    	 <?php }

		 if(ot_get_option('sharing_pinterest')!='off'){ ?>
	    	 <li class="pinterest">
	    	 	<a class="trasition-all" href="#" title="<?php _e('Pin this','cactusthemes');?>" rel="nofollow" target="_blank" onclick="window.open('//pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink($id)) ?>&media=<?php echo urlencode(wp_get_attachment_url( get_post_thumbnail_id($id))); ?>&description=<?php echo urlencode(get_the_title($id)) ?>','pin-share-dialog','width=626,height=436');return false;"><i class="fa fa-pinterest"></i>
	    	 	</a>
	    	 </li>
    	 <?php }

		 if(ot_get_option('sharing_email')!='off'){ ?>
	    	<li class="email">
		    	<a class="trasition-all" href="mailto:?subject=<?php echo get_the_title($id) ?>&body=<?php echo urlencode(get_permalink($id)) ?>" title="<?php _e('Email this','cactusthemes');?>"><i class="fa fa-envelope"></i>
		    	</a>
		   	</li>
	   	<?php }?>
    </ul>
        <?php
	}
}
// admin show channel, playlist
if(!function_exists('cactus_edit_columns')) { 
	function cactus_edit_columns($columns) {
	  return array_merge( $columns, 
				array('ct-channel' => esc_html__('Channel','cactus')) ,
				array('ct-playlist' => esc_html__('Playlist','cactus')) 
		  );
	}
}
add_filter('manage_posts_columns' , 'cactus_edit_columns');
if(!function_exists('ct_custom_columns')) {
	// return the values for each coupon column on edit.php page
	function ct_custom_columns( $column ) {
		global $post;
		global $wpdb;
		$channel_id = get_post_meta($post->ID,'channel_id', true );
		$channel_name = ''; 
		if(is_array($channel_id) && !empty($channel_id)){
			foreach($channel_id as $channel_it){
				if($channel_name==''){
					$channel_name .= '<a href="'.get_permalink($channel_it).'">'.get_the_title($channel_it).'</a>';
				}else{
					$channel_name .= ', <a href="'.get_permalink($channel_it).'">'.get_the_title($channel_it).'</a>';
				}
			}
		}elseif($channel_id!=''){
			$channel_id =explode(",",$channel_id);
			foreach($channel_id as $channel_it){
				if($channel_name==''){
					$channel_name .= '<a href="'.get_permalink($channel_it).'">'.get_the_title($channel_it).'</a>';
				}else{
					$channel_name .= ', <a href="'.get_permalink($channel_it).'">'.get_the_title($channel_it).'</a>';
				}
			}
		}
		$playlist_id = get_post_meta($post->ID,'playlist_id', true );
		$playlist_name = ''; 
		if(is_array($playlist_id) && !empty($playlist_id)){
			foreach($playlist_id as $playlist_it){
				if($playlist_name==''){
					$playlist_name .= '<a href="'.get_permalink($playlist_it).'">'.get_the_title($playlist_it).'</a>';
				}else{
					$playlist_name .= ', <a href="'.get_permalink($playlist_it).'">'.get_the_title($playlist_it).'</a>';
				}
			}
		}elseif($playlist_id!=''){
			$playlist_id =explode(",",$playlist_id);
			foreach($playlist_id as $playlist_it){
				if($playlist_name==''){
					$playlist_name .= '<a href="'.get_permalink($playlist_it).'">'.get_the_title($playlist_it).'</a>';
				}else{
					$playlist_name .= ', <a href="'.get_permalink($playlist_it).'">'.get_the_title($playlist_it).'</a>';
				}
			}
		}
		switch ( $column ) {
			case 'ct-channel':
				echo $channel_name;
				break;
			case 'ct-playlist':
				echo $playlist_name;
				break;
		}
	}
	add_action( 'manage_posts_custom_column', 'ct_custom_columns' );
}
