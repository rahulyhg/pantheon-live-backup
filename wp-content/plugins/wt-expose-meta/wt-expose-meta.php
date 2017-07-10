<?php
/**
 * Plugin Name: Web Talkies Expose Post Meta 
 * Plugin URI: http://webtalkies.in
 * Description: This plugin exposes required post meta for consumption of rest API client
 * Version: 1.0.0
 * Author: Bhushan S. Jawle
 * Author URI: http://www.sanganktechnologies.com
 * License: GPL2
 */

///////////////////////////////////////////////////////////////////////////////////////
// Expose tm_video_url as video link for URL
///////////////////////////////////////////////////////////////////////////////////////
function wt_expose_get_post_meta_cb( $object, $field_name, $request ) {
    return get_post_meta( $object[ 'id' ], $field_name );
}
function wt_expose_update_post_meta_cb( $value, $object, $field_name ) {
    return update_post_meta( $object[ 'id' ], $field_name, $value );
}

add_action( 'rest_api_init', function() {
        register_api_field( 'post',
                'tm_video_url',
                array(
                       'get_callback'    => 'wt_expose_get_post_meta_cb',
                       'update_callback' => 'wt_expose_update_post_meta_cb',
                       'schema'          => null,
                )
        );
        }
);

add_action( 'rest_api_init', function() {
        register_api_field( 'post',
                'tm_video_code',
                array(
                       'get_callback'    => 'wt_expose_get_post_meta_cb',
                       'update_callback' => 'wt_expose_update_post_meta_cb',
                       'schema'          => null,
                )
        );
        }
);
///////////////////////////////////////////////////////////////////////////////////////
// Add video thumbnail, thumnail url and HLS video url to post
///////////////////////////////////////////////////////////////////////////////////////
function wt_expose_prepare_post( $data, $post, $request ) {
        $_data = $data->data;
        $thumbnail_id = get_post_thumbnail_id( $post->ID );
        $thumbnail = wp_get_attachment_image_src( $thumbnail_id );
        $_data['featured_image_thumbnail_url'] = $thumbnail[0];
        $_data['video_thumbnail_url'] = get_post_meta($post->ID, '_video_thumbnail', true);
        $_data['hls_video_url'] = get_post_meta($post->ID, 'hls_video_url', true);
        $data->data = $_data;
        return $data;
}
add_filter( 'rest_prepare_post', 'wt_expose_prepare_post', 10, 3 );

///////////////////////////////////////////////////////////////////////////////////////
// Add short code to add thumbnail from post edit UI to show when user is not logged in
///////////////////////////////////////////////////////////////////////////////////////
function wt_expose_post_thumbnail( $atts, $content = null ) {
        extract( shortcode_atts( array(
                'size' => 'post-thumbnail', // any of the possible post thumbnail sizes - defaults to 'thumbnail'
                'align' => 'none' // any of the alignments 'left', 'right', 'center', 'none' - defaults to 'none'
                ), $atts ) );	
         if( ! get_post_thumbnail_id( $post->ID ) ) return false; //no thumbnail found
       
        //alignment check
        if( !in_array( $align, array( 'left', 'right', 'center', 'none' ) ) ) $align = 'none';
        $align = 'align' . $align;
       
        //thumbnail size check
        //if( !(preg_match( '|array\((([ 0-9])+,([ 0-9])+)\)|', $size ) === 1) && !in_array( $size, get_intermediate_image_sizes() ) ) $size = 'post-thumbnail';
        //if( preg_match( '|array\((([ 0-9])+,([ 0-9])+)\)|', $size, $match ) === 1 ) $sizewh = explode( ',', $match[1] ); $size = array( trim( $sizewh[0] ), trim( $sizewh[1] ) );
       
        //get the post thumbnail
        $thumbnail = get_the_post_thumbnail( $post->ID, $size );
 
        //integrate the alignment class
        $thumbnail = str_replace( 'class="', 'class="' . $align . ' ', $thumbnail ); //add alignment class
 
        return $thumbnail;
        //return '<div id="post_thumbnail">' . get_the_post_thumbnail($post->ID, 'thumbnail') . '</div>';
}

add_shortcode("post_thumbnail", "wt_expose_post_thumbnail");

add_filter( 'rest_prepare_post', 'wt_use_raw_post_content', 10, 3 );
function wt_use_raw_post_content( $data, $post, $request ) {
//     $data->data['content']['plaintext'] = $post->post_content;
    $data->data['excerpt']['plaintext'] = $post->post_excerpt;
    return $data;
}
///////////////////////////////////////////////////////////////////////////////////////
// Make all calls authenticated
///////////////////////////////////////////////////////////////////////////////////////
//add_filter( 'rest_authentication_errors', function( $result ) {
//	if ( ! empty( $result ) ) {
//		return $result;
//	}
//	if ( ! is_user_logged_in() ) {
//		return new WP_Error( 'restx_logged_out', 'Sorry, you must be logged in to make a request.', array( 'status' => 401 ) );
//	}
//	return $result;
//});
