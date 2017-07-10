<?php
/**
 * Initialize the meta boxes. 
 */


add_filter( 'rwmb_meta_boxes', 'post_register_meta_boxes' );

/**
 * Register meta boxes
 *
 * @return void
 */
function post_register_meta_boxes( $meta_boxes )
{
	/**
	 * Prefix of meta keys (optional)
	 * Use underscore (_) at the beginning to make keys hidden
	 * Alt.: You also can make prefix empty to disable it
	 */
	// Better has an underscore as last sign
	$prefix = 'YOUR_PREFIX_';
	if ( is_plugin_active( 'contus-video-gallery/hdflvvideoshare.php' ) || is_plugin_active( 'all-in-one-video-pack/all_in_one_video_pack.php' ) ) {
		// 1st meta box
		$meta_boxes[] = array(
			// Meta box id, UNIQUE per meta box. Optional since 4.1.5
			'id' => 'standard',
	
			// Meta box title - Will appear at the drag and drop handle bar. Required.
			'title' => __( 'Post Settings', 'rwmb' ),
	
			// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
			'pages' => array( 'post'),
	
			// Where the meta box appear: normal (default), advanced, side. Optional.
			'context' => 'normal',
	
			// Order of meta box: high (default), low. Optional.
			'priority' => 'high',
	
			// Auto save: true, false (default). Optional.
			'autosave' => true,
	
			// List of meta fields
			'fields' => array(
				array(
					'name'  => __( 'Video ID','cactusthemes'),
					'id'    => "tm_video_id",
					'desc'  => __( 'Paste the id from Video Gallery (Contus Video Player) or Kaltura Video Flatform','cactusthemes'),
					'type'  => 'text',
					'std'   => __( '','cactusthemes'),
					'clone' => false,
				),
				array(
					'name'  => __( 'Video URL','cactusthemes'),
					'id'    => "tm_video_url",
					'desc'  => __( 'Paste the url from popular video sites like YouTube or Vimeo. For example: <br/><code>http://www.youtube.com/watch?v=nTDNLUzjkpg</code><br/>or<br/><code>http://vimeo.com/23079092</code>','cactusthemes'),
					'type'  => 'text',
					'std'   => __( '','cactusthemes'),
					'clone' => false,
				),
				array(
					'name' => __( 'Video File','cactusthemes'),
					'desc' => __( 'Paste your video file url to here. Supported Video Formats: mp4, m4v, webmv, webm, ogv and flv.<br/><b>About Cross-platform and Cross-browser Support</b><br/>If you want your video works in all platforms and browsers(HTML5 and Flash), you should provide various video formats for same video, if the video files are ready, enter one url per line.<br/> For Example:<br/> <code>http://yousite.com/sample-video.m4v</code><br/><code>http://yousite.com/sample-video.ogv</code><br/> <b>Recommended Format Solution:</b> WEBMV + MP4 (H.264 AAC) + OGV. ','cactusthemes'),
					'id'   => "tm_video_file",
					'type' => 'textarea',
					'cols' => 20,
					'rows' => 7,
				),
				array(
					'name' => __( 'Video Code','cactusthemes'),
					'desc' => __('Paste the raw video code to here, such as <code>&lt;</code><code>object</code><code>&gt;</code>,<code>&lt;</code><code>embed</code><code>&gt;</code> or <code>&lt;</code><code>iframe</code><code>&gt;</code> code.','cactusthemes'),
					'id'   => "tm_video_code",
					'type' => 'textarea',
					'cols' => 20,
					'rows' => 7,
				),
				array(
					'name'  => __( 'Duration','cactusthemes'),
					'id'    => "time_video",
					'desc'  => __( ''),
					'type'  => 'text',
					'std'   => __( '','cactusthemes'),
					'clone' => false,
				),
				array(
					'name'     => __( 'Show Hide Feature Image', 'cactusthemes' ),
					'id'       => "show_feature_image",
					'type'     => 'select',
					// Array of 'value' => 'Label' pairs for select box
					'options'  => array(
						'3' => __( 'Default','cactusthemes'),
						'2' => __( 'Show','cactusthemes'),
						'1' => __( 'Hide','cactusthemes'),
					),
					'multiple'    => false,
					'std'         => '',
					'placeholder' => false,
				),
				array(
					'name'     => __('Video Layout/Feature Image','cactusthemes'),
					'id'       => "page_layout",
					'type'     => 'select',
					// Array of 'value' => 'Label' pairs for select box
					'options'  => array(
						'def' => __( 'Default','cactusthemes'),
						'full_width' => __( 'Full Width','cactusthemes'),
						'inbox' => __( 'Inboxed','cactusthemes'),
					),
					// Select multiple values, optional. Default is false.
					'multiple'    => false,
					'std'         => '',
					'placeholder' => false,
				),
				array(
					'name'     => __('Page Layout','cactusthemes'),
					'id'       => "single_ly_ct_video",
					'type'     => 'select',
					// Array of 'value' => 'Label' pairs for select box
					'options'  => array(
						'def' => __( 'Default','cactusthemes'),
						'full' => __( 'Full Width','cactusthemes'),
						'right' => __( 'Sidebar Right','cactusthemes'),
						'left' => __( 'Sidebar Left','cactusthemes'),
					),
					'multiple'    => false,
					'std'         => '',
					'placeholder' => false,
				),
				array(
					'name'             => __( 'Background Image', 'cactusthemes' ),
					'id'               => "ct_bg_image",
					'type'             => 'image_advanced',
					'max_file_uploads' => 4,
				),
				array(
					'name'     => __('Background repeat','cactusthemes'),
					'id'       => "ct_bg_repeat",
					'type'     => 'select',
					// Array of 'value' => 'Label' pairs for select box
					'options'  => array(
						'no-repeat' => __( 'No Repeat','cactusthemes'),
						'repeat' => __( 'Repeat All','cactusthemes'),
						'repeat-x' => __( 'Repeat Horizontally','cactusthemes'),
						'repeat-y' => __( 'Repeat Vertically','cactusthemes'),
						'inherit' => __( 'Inherit','cactusthemes'),
					),
					'multiple'    => false,
					'std'         => '',
					'placeholder' => false,
				),
				array(
					'name'     => __('Background attachment','cactusthemes'),
					'id'       => "ct_bg_attachment",
					'type'     => 'select',
					// Array of 'value' => 'Label' pairs for select box
					'options'  => array(
						'' => __( '','cactusthemes'),
						'fixed' => __( 'Fixed','cactusthemes'),
						'scroll' => __( 'Scroll','cactusthemes'),
						'inherit' => __( 'Inherit','cactusthemes'),
					),
					'multiple'    => false,
					'std'         => '',
					'placeholder' => false,
				),
				array(
					'name'     => __('Background position','cactusthemes'),
					'id'       => "ct_bg_position",
					'type'     => 'select',
					// Array of 'value' => 'Label' pairs for select box
					'options'  => array(
						'' => __( '','cactusthemes'),
						'left top' => __( 'Left Top','cactusthemes'),
						'left center' => __( 'Left Center','cactusthemes'),
						'left bottom' => __( 'Left Bottom','cactusthemes'),
						'center top' => __( 'Center Top','cactusthemes'),
						'center center' => __( 'Center Center','cactusthemes'),
						'center bottom' => __( 'Center Bottom','cactusthemes'),
						'right top' => __( 'Right Top','cactusthemes'),
						'right center' => __( 'Right center','cactusthemes'),
						'right bottom' => __( 'Right bottom','cactusthemes'),
					),
					'multiple'    => false,
					'std'         => '',
					'placeholder' => false,
				),
				array(
					'name'  => __( 'Youtube start video at:','cactusthemes'),
					'id'    => "youtube_start",
					'desc'  => __( 'The time offset at which the video should begin playing (second), ex: 5','cactusthemes'),
					'type'  => 'text',
					'std'   => __( '','cactusthemes'),
					'clone' => false,
				),
				array(
					'name'  => __( 'Youtube start end at:','cactusthemes'),
					'id'    => "youtube_end",
					'desc'  => __( 'The time offset at which the video should ending playing (second), ex: 60','cactusthemes'),
					'type'  => 'text',
					'std'   => __( '','cactusthemes'),
					'clone' => false,
				),
				array(
					'name'  => __( 'Title in Series','cactusthemes'),
					'id'    => "title_in_series",
					'desc'  => __( 'Enter custom title to show in series, ex: Episode 2','cactusthemes'),
					'type'  => 'text',
					'clone' => false,
				),
				/*array(
					'name' => __( 'Multiple Video Links','cactusthemes'),
					'desc' => __( 'Paste your video title and link here. Enter one title, url per line.<br/> For Example:<br/> <code>Trailer 1</code><br/><code>http://www.youtube.com/watch?v=nTDNLUzjkpg</code><br/><code>Trailer 2</code><br/><code>http://www.youtube.com/watch?v=nTDNLUzjkpg</code><br/>','cactusthemes'),
					'id'   => "tm_multi_video_link",
					'type' => 'textarea',
					'cols' => 20,
					'rows' => 7,
				),*/
			),
		);
	}else{
				// 1st meta box
		$meta_boxes[] = array(
			// Meta box id, UNIQUE per meta box. Optional since 4.1.5
			'id' => 'standard',
	
			// Meta box title - Will appear at the drag and drop handle bar. Required.
			'title' => __( 'Post Settings', 'rwmb' ),
	
			// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
			'pages' => array( 'post'),
	
			// Where the meta box appear: normal (default), advanced, side. Optional.
			'context' => 'normal',
	
			// Order of meta box: high (default), low. Optional.
			'priority' => 'high',
	
			// Auto save: true, false (default). Optional.
			'autosave' => true,
	
			// List of meta fields
			'fields' => array(
				array(
					'name'  => __( 'Video URL','cactusthemes'),
					'id'    => "tm_video_url",
					'desc'  => __( 'Paste the url from popular video sites like YouTube or Vimeo. For example: <br/><code>http://www.youtube.com/watch?v=nTDNLUzjkpg</code><br/>or<br/><code>http://vimeo.com/23079092</code>','cactusthemes'),
					'type'  => 'text',
					'std'   => __( '','cactusthemes'),
					'clone' => false,
				),
				array(
					'name' => __( 'Video File','cactusthemes'),
					'desc' => __( 'Paste your video file url to here. Supported Video Formats: mp4, m4v, webmv, webm, ogv and flv.<br/><b>About Cross-platform and Cross-browser Support</b><br/>If you want your video works in all platforms and browsers(HTML5 and Flash), you should provide various video formats for same video, if the video files are ready, enter one url per line.<br/> For Example:<br/> <code>http://yousite.com/sample-video.m4v</code><br/><code>http://yousite.com/sample-video.ogv</code><br/> <b>Recommended Format Solution:</b> webmv + m4v + ogv. ','cactusthemes'),
					'id'   => "tm_video_file",
					'type' => 'textarea',
					'cols' => 20,
					'rows' => 7,
				),
				array(
					'name' => __( 'Video Code','cactusthemes'),
					'desc' => __('Paste the raw video code to here, such as <code>&lt;</code><code>object</code><code>&gt;</code>,<code>&lt;</code><code>embed</code><code>&gt;</code> or <code>&lt;</code><code>iframe</code><code>&gt;</code> code.','cactusthemes'),
					'id'   => "tm_video_code",
					'type' => 'textarea',
					'cols' => 20,
					'rows' => 7,
				),
				array(
					'name'  => __( 'Duration','cactusthemes'),
					'id'    => "time_video",
					'desc'  => __( ''),
					'type'  => 'text',
					'std'   => __( '','cactusthemes'),
					'clone' => false,
				),
				array(
					'name'     => __( 'Show Hide Feature Image', 'cactusthemes' ),
					'id'       => "show_feature_image",
					'type'     => 'select',
					// Array of 'value' => 'Label' pairs for select box
					'options'  => array(
						'3' => __( 'Default','cactusthemes'),
						'2' => __( 'Show','cactusthemes'),
						'1' => __( 'Hide','cactusthemes'),
					),
					'multiple'    => false,
					'std'         => '',
					'placeholder' => false,
				),
				array(
					'name'     => __('Video Layout/Feature Image','cactusthemes'),
					'id'       => "page_layout",
					'type'     => 'select',
					// Array of 'value' => 'Label' pairs for select box
					'options'  => array(
						'def' => __( 'Default','cactusthemes'),
						'full_width' => __( 'Full Width','cactusthemes'),
						'inbox' => __( 'Inboxed','cactusthemes'),
					),
					// Select multiple values, optional. Default is false.
					'multiple'    => false,
					'std'         => '',
					'placeholder' => false,
				),
				array(
					'name'     => __('Page Layout','cactusthemes'),
					'id'       => "single_ly_ct_video",
					'type'     => 'select',
					// Array of 'value' => 'Label' pairs for select box
					'options'  => array(
						'def' => __( 'Default','cactusthemes'),
						'full' => __( 'Full Width','cactusthemes'),
						'right' => __( 'Sidebar Right','cactusthemes'),
						'left' => __( 'Sidebar Left','cactusthemes'),
					),
					'multiple'    => false,
					'std'         => '',
					'placeholder' => false,
				),
				array(
					'name'             => __( 'Background Image', 'cactusthemes' ),
					'id'               => "ct_bg_image",
					'type'             => 'image_advanced',
					'max_file_uploads' => 4,
				),
				array(
					'name'     => __('Background repeat','cactusthemes'),
					'id'       => "ct_bg_repeat",
					'type'     => 'select',
					// Array of 'value' => 'Label' pairs for select box
					'options'  => array(
						'no-repeat' => __( 'No Repeat','cactusthemes'),
						'repeat' => __( 'Repeat All','cactusthemes'),
						'repeat-x' => __( 'Repeat Horizontally','cactusthemes'),
						'repeat-y' => __( 'Repeat Vertically','cactusthemes'),
						'inherit' => __( 'Inherit','cactusthemes'),
					),
					'multiple'    => false,
					'std'         => '',
					'placeholder' => false,
				),
				array(
					'name'     => __('Background attachment','cactusthemes'),
					'id'       => "ct_bg_attachment",
					'type'     => 'select',
					// Array of 'value' => 'Label' pairs for select box
					'options'  => array(
						'' => __( '','cactusthemes'),
						'fixed' => __( 'Fixed','cactusthemes'),
						'scroll' => __( 'Scroll','cactusthemes'),
						'inherit' => __( 'Inherit','cactusthemes'),
					),
					'multiple'    => false,
					'std'         => '',
					'placeholder' => false,
				),
				array(
					'name'     => __('Background position','cactusthemes'),
					'id'       => "ct_bg_position",
					'type'     => 'select',
					// Array of 'value' => 'Label' pairs for select box
					'options'  => array(
						'' => __( '','cactusthemes'),
						'left top' => __( 'Left Top','cactusthemes'),
						'left center' => __( 'Left Center','cactusthemes'),
						'left bottom' => __( 'Left Bottom','cactusthemes'),
						'center top' => __( 'Center Top','cactusthemes'),
						'center center' => __( 'Center Center','cactusthemes'),
						'center bottom' => __( 'Center Bottom','cactusthemes'),
						'right top' => __( 'Right Top','cactusthemes'),
						'right center' => __( 'Right center','cactusthemes'),
						'right bottom' => __( 'Right bottom','cactusthemes'),
					),
					'multiple'    => false,
					'std'         => '',
					'placeholder' => false,
				),
				array(
					'name'  => __( 'YouTube Video starts at:','cactusthemes'),
					'id'    => "youtube_start",
					'desc'  => __( 'The time offset at which the video should begin playing (second), ex: 5','cactusthemes'),
					'type'  => 'text',
					'std'   => __( '','cactusthemes'),
					'clone' => false,
				),
				array(
					'name'  => __( 'YouTube Video ends at:','cactusthemes'),
					'id'    => "youtube_end",
					'desc'  => __( 'The time offset at which the video should stop playing (second), ex: 60','cactusthemes'),
					'type'  => 'text',
					'std'   => __( '','cactusthemes'),
					'clone' => false,
				),
				array(
					'name'  => __( 'Title in Series','cactusthemes'),
					'id'    => "title_in_series",
					'desc'  => __( 'Enter custom title to show in series, ex: Episode 2','cactusthemes'),
					'type'  => 'text',
					'clone' => false,
				),
				/*array(
					'name' => __( 'Multiple Video Links','cactusthemes'),
					'desc' => __( 'Paste your video title and link here. Enter one title, url per line.<br/> For Example:<br/> <code>Trailer 1</code><br/><code>http://www.youtube.com/watch?v=nTDNLUzjkpg</code><br/><code>Trailer 2</code><br/><code>http://www.youtube.com/watch?v=nTDNLUzjkpg</code><br/>','cactusthemes'),
					'id'   => "tm_multi_video_link",
					'type' => 'textarea',
					'cols' => 20,
					'rows' => 7,
				),*/
			),
		);
	}
	// 2nd meta box
	$meta_boxes[] = array(
		'title' => __( 'Videos Auto Fetch Data', 'cactusthemes' ),
		'context' => 'side',
		'fields' => array(
			// HEADING
			array(
				'name' => __( '', 'cactusthemes' ),
				'id'   => "fetch_info",
				'type' => 'checkbox_list',
				'desc' => __('Check above checkbox if you do not want to auto-fetch video data after save/edit. To chose which fields to fetch, go to appearance > Theme option > General ','cactusthemes'),
				// Options of checkboxes, in format 'value' => 'Label'
				'options' => array(
					'1' => __( '<strong>DO NOT FETCH</strong>', 'cactusthemes' ),
				),
			),
		)
	);
	
	// 3rd meta box
	$meta_boxes[] = array(
		'title' => __( 'Social Locker', 'cactusthemes' ),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			// HEADING
			array(
				'name' => __( 'Social Locker shortcode', 'cactusthemes' ),
				'id'   => "social_locker",
				'type' => 'text',
				'desc' => __('Enter shortcode to lock video player, for example: [sociallocker][/sociallocker]','cactusthemes'),
				// Options of checkboxes, in format 'value' => 'Label'
			),
		)
	);
	
	// 5rd meta box
	$meta_boxes[] = array(
		'title' => __( 'Player Logic', 'cactusthemes' ),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			array(
				'name' => __( 'Player logic', 'cactusthemes' ),
				'id'   => "player_logic",
				'type' => 'text',
				'desc' => __('Enter shortcode (ex: [my_shortcode][player][/my_shortcode], <strong>[player]</strong> is required)<br>or condition function (ex: <b>is_user_logged_in() && is_sticky()</b> ) to control video player visiblitily','cactusthemes'),
				// Options of checkboxes, in format 'value' => 'Label'
			),
			array(
				'name' => __( 'Alternative Content', 'cactusthemes' ),
				'id'   => "player_logic_alt",
				'type' => 'textarea',
				'desc' => __('Content to display when Condition is false (Not work with Shortcodes)','cactusthemes'),
				// Options of checkboxes, in format 'value' => 'Label'
			),
		)
	);
	return $meta_boxes;
}

function captions_post_meta(){
	//option tree
	if ( is_plugin_active( 'jw-player-plugin-for-wordpress/jwplayermodule.php' )) {
		$meta_boxes_caption = array(
			'id'        => 'video_captions',
			'title'     => __( 'Closed Captions', 'cactusthemes' ),
			'desc'      => '',
			'pages'     => array( 'post' ),
			'context'   => 'normal',
			'fields'    => array(
				array(
				'label'       => __( 'Closed Captions', 'cactusthemes' ),
				'id'          => 'caption_info',
				'type'        => 'list-item',
				'class'       => '',
				'desc'        => 'Video Caption Files in different languages',
				'choices'     => array(),
				'settings'    => array(
					 array(
						'label'       => __( 'Video Caption', 'cactusthemes' ),
						'id'          => 'file_language',
						'type'        => 'upload',
						'desc'        => '',
						'std'         => '',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => ''
					 ),
				)
		  	)
			)
		  );
		  
		if (function_exists('ot_register_meta_box')) {
		  ot_register_meta_box( $meta_boxes_caption );
		}
	}
}
add_action( 'admin_init', 'captions_post_meta' );
/*
 ********************
	 Multi links
 ********************
 */
add_action( 'add_meta_boxes', 'tm_mtl_add_custom_box' );

/* Do something with the data entered */
add_action( 'save_post', 'tm_mtl_save_postdata' );

/* Adds a box to the main column on the Post and Page edit screens */
function tm_mtl_add_custom_box() {
    add_meta_box(
        'tm_multilink_box',
        __( 'Multi Links', 'cactusthemes' ),
        'tm_mtl_inner_custom_box',
        'post');
}

/* Prints the box content */
function tm_mtl_inner_custom_box() {
    global $post;
    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'tm_mtl_noncename' );
    ?>
    <div id="meta_inner">
    <table id="tm_here" cellpadding="4">
    <tr><td width="240"><strong>Group Title</strong></td>
    <td><strong>Links</strong></td>
    <td></td></tr>
    <?php

    //get the saved meta as an arry
    $links = get_post_meta($post->ID,'tm_multi_link',true);
    $c = 0;
    if ( $links && count( $links ) > 0 ) {
        foreach( $links as $track ) {
            if ( (isset( $track['title'] ) && $track['title'] != '') || (isset( $track['links'] ) && $track['links'] != '') ) {
                printf( '
				<tr><td valign="top"><input type="text" name="tm_multi_link[%1$s][title]" value="%2$s" placeholder="Group Title" size=30 /></td><td valign="top"><textarea type="text" name="tm_multi_link[%1$s][links]" cols=90 rows=4>%3$s</textarea></td><td valign="top"><button class="mtl-remove button"><i class="fa fa-times"></i> Remove</button></td></tr>
				', $c, $track['title'], $track['links'] );
                $c = $c +1;
            }
        }
    }else{ ?>
		<tr>
            <td><?php _e( '<i>Click Add Group to start</i>','cactusthemes') ?></td>
            <td></td>
        </tr>
	<?php }

    ?>
    </table>
    <table cellpadding="4">
    <tr>
        <td width="240" valign="top"><button class="add_tm_link button-primary button-large"><i class="fa fa-plus"></i> <?php _e('Add Group'); ?></button></td>
        <td><?php _e( '<i>Paste your videos link (and title) here. Enter one per line.<br/> For Example:<br/> <code>Trailer 1</code><br/><code>http://www.youtube.com/watch?v=nTDNLUzjkpg</code><br/><code>Trailer 2</code><br/><code>http://www.youtube.com/watch?v=nTDNLUzjkpg</code><br> You could enter links without title</i>','cactusthemes') ?></td>
    </tr>
    </table>
<script>
    var $ =jQuery.noConflict();
    $(document).ready(function() {
        var count = <?php echo $c; ?>;
        $(".add_tm_link").click(function() {
            count = count + 1;

            $('#tm_here').append('<tr><td valign="top"><input type="text" name="tm_multi_link['+count+'][title]" value="" placeholder="Group Title" size=30 /></td><td valign="top"><textarea type="text" name="tm_multi_link['+count+'][links]" cols=90 rows=4></textarea></td><td valign="top"><button class="mtl-remove button"><i class="fa fa-times"></i> Remove</button></td></tr>' );
            return false;
        });
        $(".mtl-remove").live('click', function() {
            $(this).parent().parent().remove();
        });
    });
    </script>
</div><?php

}

/* When the post is saved, saves our custom data */
function tm_mtl_save_postdata( $post_id ) {
    // verify if this is an auto save routine. 
    // If it is our form has not been submitted, so we dont want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return;

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !isset( $_POST['tm_mtl_noncename'] ) )
        return;

    if ( !wp_verify_nonce( $_POST['tm_mtl_noncename'], plugin_basename( __FILE__ ) ) )
        return;

    // OK, we're authenticated: we need to find and save the data

    $links = $_POST['tm_multi_link'];

    update_post_meta($post_id,'tm_multi_link',$links);
}
