<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */


add_filter( 'rwmb_meta_boxes', 'advs_register_meta_boxes' );

/**
 * Register meta boxes
 *
 * @return void
 */
function advs_register_meta_boxes( $meta_boxes )
{
	/**
	 * Prefix of meta keys (optional)
	 * Use underscore (_) at the beginning to make keys hidden
	 * Alt.: You also can make prefix empty to disable it
	 */
	// Better has an underscore as last sign
	$prefix = 'advs_';


	$meta_boxes[] = array(
		'title' => __( 'Upload Fields', 'cactus' ),

		'pages' => array('video-advs' ),

		'fields' => array(

			// VIDEO ADS
			array(
				'name'     => __( 'Ads type', 'cactus' ),
				'id'       => "cactus_{$prefix}type",
				'type'     => 'select',
				// Array of 'value' => 'Label' pairs for select box
				'options'  => array(
					'image' => __( 'Image', 'cactus' ),
					'video' => __( 'Video', 'cactus' ),
					'html' => __( 'HTML', 'cactus' ),
				),
				// Select multiple values, optional. Default is false.
				'multiple'    => false,
				'std'         => '1',
			),

			// FILE ADVANCED (WP 3.5+)
			array(
				'name' => __( 'File Advanced Upload', 'cactus' ),
				'id'   => "{$prefix}file_advanced",
				'type' => 'file_advanced',
				'max_file_uploads' => 4,
				'mime_type' => '', // Leave blank for all file types
			),

			array(
				// Field name - Will be used as label
				'name'  => __( 'Video url', 'cactus' ),
				// Field ID, i.e. the meta key
				'id'    => "{$prefix}video_url",
				// Field description (optional)
				'desc'  => __( 'Video url from Youtube or Vimeo<br/>Ex: https://www.youtube.com/watch?v=CevxZvSJLk8', 'cactus' ),
				'type'  => 'text'
			),

			array(
				// Field name - Will be used as label
				'name'  => __( 'HTML Ad', 'cactus' ),
				// Field ID, i.e. the meta key
				'id'    => "{$prefix}adsense_code",
				// Field description (optional)
				'desc'  => __( '', 'cactus' ),
				'type'  => 'textarea'
			),

			// TEXT
			array(
				// Field name - Will be used as label
				'name'  => __( 'URL', 'cactus' ),
				// Field ID, i.e. the meta key
				'id'    => "{$prefix}url",
				// Field description (optional)
				'desc'  => __( 'Url when you click to adv', 'cactus' ),
				'type'  => 'text'
			),

			array(
				'name'     => __( 'URL Target', 'cactus' ),
				'id'       => "{$prefix}target",
				'type'     => 'select',
				// Array of 'value' => 'Label' pairs for select box
				'options'  => array(
					'1' => __( 'Open link in new window', 'cactus' ),
					'2' => __( 'Open link in current window', 'cactus' ),
				),
				// Select multiple values, optional. Default is false.
				'multiple'    => false,
				'std'         => '1'
			),

			// POSITION
			array(
				'name'     => __( 'Position', 'cactus' ),
				'id'       => "{$prefix}position",
				'type'     => 'select',
				// Array of 'value' => 'Label' pairs for select box
				'options'  => array(
					'1' => __( 'Full', 'cactus' ),
					'2' => __( 'Top', 'cactus' ),
					'3' => __( 'Bottom', 'cactus' ),
				),
				// Select multiple values, optional. Default is false.
				'multiple'    => false,
				'std'         => '1',
				'placeholder' => __( 'Select position', 'cactus' ),
			),

			// DATE
			array(
				'name' => __( 'Expiry date', 'cactus' ),
				'id'   => "{$prefix}expiry_date",
				'type' => 'datetime',

				// jQuery date picker options. See here http://api.jqueryui.com/datepicker
				'js_options' => array(
					'appendText'      => __( ' (yyyy-mm-dd)', 'cactus' ),
					'dateFormat'      => __( 'yy-mm-dd', 'cactus' ),
					'changeMonth'     => true,
					'changeYear'      => true,
					'showButtonPanel' => true,
					'stepMinute'     => 1,
					'showTimepicker' => true,
				),
			)
		)
	);
	return $meta_boxes;
}


