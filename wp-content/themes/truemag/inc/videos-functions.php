<?php
/**
 * Plugin: JW Player for WordPress
 *
 * @link http://wordpress.org/plugins/jw-player-plugin-for-wordpress/
 * @since 1.0
 */ 
if(!function_exists('cactus_jwplayer7')) {
	function cactus_jwplayer7(){
		echo '<div class="jwplayer playlist-none cactus-jw7">';
		echo do_shortcode('[jw7-video]');
		echo '</div>';
	}
}
if(class_exists('JWP6_Plugin')) {
	if (JWP6_USE_CUSTOM_SHORTCODE_FILTER)
		add_filter('tm_video_filter', array('JWP6_Shortcode', 'widget_text_filter'));
}
	
if(function_exists('jwplayer_tag_callback')) {
	add_filter('tm_video_filter', 'jwplayer_tag_callback');
}
/**
 * Determines if the specified post is a video post.
*/
require_once locate_template('/inc/classes/class.video-fetcher.php');
function tm_is_post_video($post_id = null){
	wp_reset_query();
	if($post_id){
		$post = get_post($post_id);
	}else{
		$post = get_post(get_the_ID());
	}
	
	if(!$post->ID){
		return false;
	}
	
	// Back compat, if the post has any video field, it also is a video. 
	$video_file = get_post_meta($post->ID, 'tm_video_file', true);
	$video_url = get_post_meta($post->ID, 'tm_video_url', true);
	$video_code = get_post_meta($post->ID, 'tm_video_code', true);
	// Post meta by Automatic Youtube Video Post plugin
	if(!empty($video_code) || !empty($video_url) || !empty($video_file))
		return $post->ID;
	
	return has_post_format('video', $post->ID);
}

function tm_player($player = '', $args = array()) {
	if(empty($player) || empty($args['files']))
		return;
	
	$defaults = array(
		'files' => array(),
		'poster' => '',
		'autoplay' => false
	);
	$args = wp_parse_args($args, $defaults);
	
	extract($args);
	
		
	/* JWPlayer */
	if($player == 'jwplayer') {
		$options = array(
			'file' => trim($files[0]), // JWPlayer WordPress Plugin doesn't support multiple codecs
			'image' => $poster
		);
		$atts = arr2atts($options);
		$jwplayer_shortcode = '[jwplayer'.$atts.']';
		echo apply_filters('tm_video_filter', $jwplayer_shortcode);
	}	
	/* FlowPlayer */
	elseif($player == 'flowplayer' && function_exists('flowplayer_content_handle')) {
		$atts = array(
			'splash' => $poster
		);
		foreach($files as $key => $file) {
			$att = ($key == 0) ? 'src' : 'src'.$key;
			$atts[$att] = $file;
		}
		echo flowplayer_content_handle($atts, '', '');
		tm_flowplayer_script();
	}	
	elseif($player == 'videojs' && function_exists('video_shortcode')){
		$atts = array(
			'poster' => $poster,
		);
		foreach($files as $key => $file) {
			$att = ($key == 0) ? 'src' : 'src'.$key;
			if(strpos($file, '.mp4') !== false){$atts['mp4'] = $file;}
			if(strpos($file, '.webm') !== false){$atts['webm'] = $file;}
			if(strpos($file, '.ogg') !== false){$atts['ogg'] = $file;}
		}
		echo video_shortcode($atts, '', '');
		tm_add_videojs_swf();
	}
	/* WordPress Native Player: MediaElement */
	else{
		$atts = array();
		foreach($files as $file) {
			$file = trim($file);
			
			if(strpos($file, 'youtube.com') !== false)
				$atts['youtube'] = $file;
			else {
				$type = wp_check_filetype($file, wp_get_mime_types());
				$atts[$type['ext']] = $file;
			}
		}
			
		echo wp_video_shortcode($atts);
	} 
}
/**
 */
function tm_extend_video_html($html, $autoplay = false, $wmode = 'opaque') {
	$replace = false;
	if(function_exists('ot_get_option')){$color_bt = ot_get_option('main_color_1');}
	if($color_bt==''){$color_bt = 'f9c73d';}
	preg_match('/src=[\"|\']([^ ]*)[\"|\']/', $html, $matches);
	$color_bt = str_replace('#','',$color_bt);
	if(isset($matches[1])) {
		$url = $matches[1];
		
		// Vimeo
		if(strpos($url, 'vimeo.com')) {
			// Remove the title, byline, portrait on Vimeo video
			$url = add_query_arg(array('title'=>0,'byline'=>0,'portrait'=>0,'player_id'=>'player_1','color'=>$color_bt), $url);
			//
			// Set autoplay
			if($autoplay)
				$url = add_query_arg('autoplay', '1', $url);
			$replace = true;
		}
			
		// Youtube
		if(strpos($url, 'youtube.com')) {
			// Set autoplay
			if($autoplay)
				$url = add_query_arg('autoplay', '1', $url);
		
			// Add wmode
			if($wmode)
				$url = add_query_arg('wmode', $wmode, $url);
			
			// Disabled suggested videos on YouTube video when the video finishes
			$url = add_query_arg(array('rel'=>0), $url);
			// Remove top info bar
			$url = add_query_arg(array('showinfo'=>0), $url);
			$remove_annotations = ot_get_option('remove_annotations');
			if($remove_annotations!= '1'){
				$url = add_query_arg(array('iv_load_policy'=>3), $url);
			}
			// Remove YouTube Logo
			$url = add_query_arg(array('modestbranding'=>0), $url);
			// Remove YouTube video annotations
			// $url = add_query_arg('iv_load_policy', 3, $url);
			
			$replace = true;
		}
		
		if($replace) {
			$url = esc_attr($url);	
			$html = preg_replace('/src=[\"|\']([^ ]*)[\"|\']/', 'src="'.esc_url($url).'"', $html);
		}
	}
	
	return $html;
}



function tm_video($post_id, $autoplay = false) {
	$file = get_post_meta($post_id, 'tm_video_file', true);
	$files = !empty($file) ? explode("\n", $file) : array();
	$url = trim(get_post_meta($post_id, 'tm_video_url', true));
	$code = trim(get_post_meta($post_id, 'tm_video_code', true));
	$id_vid = trim(get_post_meta($post_id, 'tm_video_id', true));
	global $link_arr;
	if(isset($_GET['link']) && $_GET['link']!=''){
		$url = $link_arr[$_GET['link']]['url'];
	}
	// Define RELATIVE_PATH for Flowplayer in Ajax Call
	//if (!defined('RELATIVE_PATH') && defined('DOING_AJAX') && DOING_AJAX)
		//define('RELATIVE_PATH', plugins_url().'/fv-wordpress-flowplayer');
	if(!empty($id_vid)) {
		if(is_plugin_active( 'contus-video-gallery/hdflvvideoshare.php' )){
			if(is_numeric($id_vid)){
				echo do_shortcode( '[hdvideo  id="'.$id_vid.'"]');
			}else{
				echo do_shortcode( '[hdvideo  '.$id_vid.']');
			}
		}elseif(is_plugin_active( 'all-in-one-video-pack/all_in_one_video_pack.php' )){
			echo do_shortcode( '[kaltura-widget  '.$id_vid.']');
		}
	}
	elseif(!empty($code)) {
		if(strpos($code, 'fvplayer') !== false){
			$video = do_shortcode($code);
			echo $video;
		}else{
			$video = do_shortcode($code);
			$video = apply_filters('tm_video_filter', $video);
			$video = tm_extend_video_html($video, $autoplay);
			
			if(has_shortcode($code, 'fvplayer') || has_shortcode($code, 'flowplayer'))
				tm_flowplayer_script();
			
			echo $video;
		}
	} 
	elseif(!empty($url)) {
		$url = trim($url);
		$video = '';
		$youtube_player = '';
		
		// Youtube List
		if(preg_match('/http:\/\/www.youtube.com\/embed\/(.*)?list=(.*)/', $url)) {
			$video = '<iframe width="560" height="315" src="'.$url.'" frameborder="0" allowfullscreen></iframe>';
		
		} 
		// Youtube Player
		elseif(strpos($url, 'youtube.com') !== false && !empty($youtube_player)) {
			$args = array(
				'files' => array($url),
				'poster' => $poster,
				'autoplay' => $autoplay
			);
			tm_player($youtube_player, $args);
		} 
		// WordPress Embeds
		else {
			global $wp_embed;
			$orig_wp_embed = $wp_embed;
			
			$wp_embed->post_ID = $post_id;
			$video = $wp_embed->autoembed($url);
			
			if(trim($video) == $url) {
				$wp_embed->usecache = false;
				$video = $wp_embed->autoembed($url);
			}
			
			$wp_embed->usecache = $orig_wp_embed->usecache;
			$wp_embed->post_ID = $orig_wp_embed->post_ID;
		}
		
		$video = tm_extend_video_html($video, $autoplay);

		echo $video;
	} 
	elseif(!empty($files)) {
		if(has_post_thumbnail($post_id) && $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'custom-large')){
			$poster = $thumb[0];}
		$player = ot_get_option('single_player_video');	
		if($player =='' && class_exists('JWP6_Plugin')){
			$player ='jwplayer';}
		else if($player ==''){
			$player = 'mediaelement';
		}
		$args = array(
			'files' => $files,
			'poster' => $poster,
			'autoplay' => $autoplay
		);
		tm_player($player, $args);
	}
}

/*
 * Output Flowplayer script
 * 
 */
function tm_flowplayer_script(){
	if(!defined('DOING_AJAX') || !DOING_AJAX)
		return;

	echo '
	<script type="text/javascript">
		(function ($) {
			$(function(){typeof $.fn.flowplayer=="function"&&$("video").parent(".flowplayer").flowplayer()});
		}(jQuery));
	</script>
	';
	
	flowplayer_display_scripts();
}
/*
 * Output videojs script
 * 
 */
function tm_add_videojs_swf(){
		echo '
		<script type="text/javascript">
			videojs.options.flash.swf = "'. get_template_directory_uri().( '/js/videojs/video-js.swf') .'";
		</script>
		';
}
/*
Auto fetch data
*/
global $post_id;
global $post;
add_action( 'save_post', 'tm_save_postdata' );
function tm_save_postdata($post_id ){
	
	if('post' != get_post_type($post_id))
	return;
	$url = get_post_meta($post_id,'tm_video_url',true);
	$time = get_post_meta($post_id,'time_video',true);
	$data =  Video_Fetcher::fetchData($url,$fields = array(),$post_id);
	//print_r($a);exit;
	if($time==''||$time=='00:00'){
		update_post_meta( $post_id, 'time_video', tm_secondsToTime($data['duration']) );
	}
	//if(function_exists('ot_get_option')){$get_info = ot_get_option('auto_get_info');}
}


add_action( 'save_post', 'post_updated');
function post_updated( $post_id ) {
	if('post' != get_post_type($post_id))
	return;
	if( isset($_POST['tm_video_url']) )
    {
        $url = $_POST['tm_video_url'];
    }
	//print_r($url);exit;
	if($url==''){$url =get_post_meta($post_id,'tm_video_url',true);}
	$data =  Video_Fetcher::fetchData($url,$fields = array(),$post_id);
	$post['ID'] = $post_id;
	//print_r($data);exit;
	$auto_get_info = get_post_meta($post_id,'fetch_info',true);
	//var_dump($auto_get_info);
	if(function_exists('ot_get_option')){
		$google_api_key = ot_get_option('google_api_key');
	}
	if(($url !='' && (strpos($url, 'youtube.com') !== false)) || ($url !='' && (strpos($url, 'vimeo.com') !== false)) || ($url !='' && (strpos($url, 'dailymotion.com') !== false))){
		if(strpos($url, 'youtube.com') !== false && $google_api_key ==''){
		}else{
			if(empty($auto_get_info) || $auto_get_info['0']!='1'){
				if(function_exists('ot_get_option')){$get_info = ot_get_option('auto_get_info');}
				if($get_info['0']=='1'){
					//$post['post_title'] = get_post_meta($post_id,'title_video',true);
					$post['post_title'] =  $data['title'] ;
					$post['post_name'] =  $data['title'] ;
				}
				if($get_info['1']=='2'){
					//$post['post_content'] = get_post_meta($post_id,'ct_video',true);
					$post['post_content'] = $data['description'];
				}
				if($get_info['2']=='3'){
					//$post['tags'] = get_post_meta($post_id,'tags',true);
					//echo $data['tags'] ;exit;
					wp_set_post_tags( $post_id, $data['tags'], true );
					//$post['tags'] = $data['tags'] ;
				}
				if($get_info['3']=='4'){
					update_post_meta( $post_id, '_count-views_all', $data['viewCount']);
				}
		
				// update the post, removing the action to prevent an infinite loop
				remove_action( 'save_post', 'post_updated' );
				wp_update_post($post);
				add_action( 'save_post', 'post_updated' );
			}
		}
	}
}
// End Fetch

function auto_update_likes($post_id){
	if('post' != get_post_type())
	return;
	global $wpdb;
	$time = get_post_meta($post_id,'time_video',true);
	if($time==''){
		$wpdb->insert(
			 'wp_wti_like_post', 
			 array(
				'post_id' => $post_id ,
				'value' => '0',
				'date_time' => date('Y-m-d H:i:s'),
			)
		);
	}
}
add_action( 'save_post', 'auto_update_likes' );

///already - vote
function TmAlreadyVoted($post_id, $ip = null) {
	global $wpdb;
	
	if (null == $ip) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	
	$tm_has_voted = $wpdb->get_var("SELECT value FROM {$wpdb->prefix}wti_like_post WHERE post_id = '$post_id' AND ip = '$ip'");
	
	return $tm_has_voted;
}


add_filter('pre_post_title', 'wpse28021_mask_empty');
add_filter('pre_post_content', 'wpse28021_mask_empty');
function wpse28021_mask_empty($value)
{
    if ( empty($value) ) {
        return ' ';
    }
    return $value;
}

add_filter('wp_insert_post_data', 'wpse28021_unmask_empty');
function wpse28021_unmask_empty($data)
{
    if ( ' ' == $data['post_title'] ) {
        $data['post_title'] = '';
    }
    if ( ' ' == $data['post_content'] ) {
        $data['post_content'] = '';
    }
    return $data;
}


// * Convert seconds to timecode
// * http://stackoverflow.com/q/8273804
// 
function tm_secondsToTime($inputSeconds) 
{

    $secondsInAMinute = 60;
    $secondsInAnHour  = 60 * $secondsInAMinute;
    $secondsInADay    = 24 * $secondsInAnHour;

    // extract days
    $days = floor($inputSeconds / $secondsInADay);

    // extract hours
    $hourSeconds = $inputSeconds % $secondsInADay;
    $hours = floor($hourSeconds / $secondsInAnHour);

    // extract minutes
    $minuteSeconds = $hourSeconds % $secondsInAnHour;
    $minutes = floor($minuteSeconds / $secondsInAMinute);

    // extract the remaining seconds
    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
    $seconds = ceil($remainingSeconds);

    // DAYS
    if( (int)$days == 0 )
        $days = '';
    elseif( (int)$days < 10 )
        $days = '0' . (int)$days . ':';
    else
        $days = (int)$days . ':';

    // HOURS
    if( (int)$hours == 0 )
        $hours = '';
    elseif( (int)$hours < 10 )
        $hours = '0' . (int)$hours . ':';
    else 
        $hours = (int)$hours . ':';

    // MINUTES
    if( (int)$minutes == 0 )
        $minutes = '00:';
    elseif( (int)$minutes < 10 )
        $minutes = '0' . (int)$minutes . ':';
    else 
        $minutes = (int)$minutes . ':';

    // SECONDS
    if( (int)$seconds == 0 )
        $seconds = '00';
    elseif( (int)$seconds < 10 )
        $seconds = '0' . (int)$seconds;

    return $days . $hours . $minutes . $seconds;
}

function tm_video_thumbnail_markup( $markup, $post_id ) {
	$markup .= ' ' . get_post_meta($post_id, 'tm_video_code', true);
	$markup .= ' ' . get_post_meta($post_id, 'tm_video_url', true);

	return $markup;
}
/**
 * Convert array to attributes string
 */
function arr2atts($array = array(), $include_empty_att = false) {
	if(empty($array))
		return;
	
	$atts = array();
	foreach($array as $key => $att) {
		if(!$include_empty_att && empty($att))
			continue;
		
		$atts[] = $key.'="'.$att.'"';
	}
	
	return ' '.implode(' ', $atts);
}
/**
 * Shorten long numbers
 */

if(!function_exists('tm_short_number')) {
function tm_short_number($n, $precision = 3) {
	$n = $n*1;
    if ($n < 1000000) {
        // Anything less than a million
        $n_format = number_format($n);
    } else if ($n < 1000000000) {
        // Anything less than a billion
        $n_format = number_format($n / 1000000, $precision) . 'M';
    } else {
        // At least a billion
        $n_format = number_format($n / 1000000000, $precision) . 'B';
    }

    return $n_format;
}
}

if(!function_exists('tm_build_multi_link')) {
function tm_build_multi_link($arr, $echo=false) {
	if($arr){
		ob_start(); ?>
        <div class="tm-multilink">
        <div class="multilink-table-wrap">
        <table class="table table-bordered"><tbody>
		<?php
		$link_arr = array();
		$link_count = 0;
		foreach($arr as $group){ ?>
			<tr>
                <td width="140" class="multilink-title"><?php echo isset($group['title'])?$group['title']:'' ?></td>
                <td>
                <?php 
				$multi_link = explode("\n",$group['links']); //raw array
				$temp_title = '';
				$link_number = 0;
				foreach($multi_link as $link){
					if(strpos($link, 'http') !== false){ //is a url
						$link_arr[]=array(
							'title' => $temp_title,
							'url' => $link
						);
						?>
                        <a class="multilink-btn btn btn-sm btn-default bordercolor2hover bgcolor2hover <?php if(isset($_GET['link']) && $_GET['link']==$link_count){ echo 'current-link'; } ?>" href="<?php echo esc_url(add_query_arg( 'link', $link_count, get_permalink(get_the_ID()) )); ?>"><i class="fa fa-play"></i> <?php echo $temp_title?$temp_title:__('Link ').($link_number+1); ?> </a>
                        <?php
						$temp_title = '';
						$link_count++;
						$link_number++;
					}else{
						$temp_title = $link;
					}
				}
				?>
                </td>
			</tr>
		<?php } ?>
        </tbody></table>
        </div>
        </div>
		<?php
		$html = ob_get_clean();
	}//if arr
	if($echo){
		echo $html;
	}else{
		return $link_arr;
	}
}
}