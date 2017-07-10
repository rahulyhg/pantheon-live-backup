<?php
// Logging utility function for dev
function log_me($message) {
    if ( WP_DEBUG === true ) {
        if ( is_array($message) || is_object($message) ) {
            error_log( print_r($message, true) );
        } else {
            error_log( $message );
        }
    }
}

add_action( 'wp_enqueue_scripts', 'truemag_parent_theme_enqueue_styles' );

function truemag_parent_theme_enqueue_styles() {
    wp_enqueue_style( 'truemag-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( '-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('truemag-style')
    );

}
function list_terms_custom_taxonomy( $atts ) {
        extract( shortcode_atts( array(
                'custom_taxonomy' => '',
                ), $atts )
        );
        $custom_taxonomy = 'video-series';

        $args = array(
                taxonomy => $custom_taxonomy,
                title_li => ''
        );

        // We wrap it in unordered list
        $html = '<ul>';  
        $taxonomy = 'video-series';
        $terms = get_terms( array(
                'taxonomy' => $taxonomy,
                'hide_empty' => false,
                'fields' => 'all') );
        foreach($terms as $term){
                $term_link = get_term_link( $term, $taxonomy );
                if( is_wp_error( $term_link ) )
                    continue;

                $li_class = "cat-item cat-item-".$term->term_id;
                $count_text = 'Coming soon';
                if($term->count > 0){
                        $count_text = "$term->count videos";
                }
                $html .= '<li class="'.$li_class.'"><a href="' . $term_link . '">' 
                        . $term->name . '</a>'."<p>$count_text</p></li>";
        }
        $html .= '</ul>';
        return $html;
}
 
// Add a shortcode that executes our function
add_shortcode( 'series_terms', 'list_terms_custom_taxonomy' );
 
//Allow Text widgets to execute shortcodes
 
add_filter('widget_text', 'do_shortcode');

// Redirect after login to membership-account for non-admin users
function wt_login_redirect( $redirect_to, $request, $user  ) {
        $my_profile = site_url() . '/membership-account'; 
	return ( is_array( $user->roles ) && in_array( 'administrator', $user->roles ) ) ? admin_url() : $my_profile;
}
add_filter( 'login_redirect', 'wt_login_redirect', 10, 3 );

// Update video URL for Wistia on post save
// add_action( 'save_post', 'wt_update_wistia_video_url' );

// function wt_update_wistia_video_url($post_id){

// }
// Update Vimeo URL 
add_action( 'added_post_meta', 'wt_update_vimeo_video_url', 10, 4 );
add_action( 'updated_post_meta', 'wt_update_vimeo_video_url', 10, 4 );
function wt_update_vimeo_video_url( $meta_id, $post_id, $meta_key, $meta_value )
{
    log_me('In wt_update_vimeo_video_url, key :'. $meta_key . ', value :' . $meta_value);
    if ( 'tm_video_url' == $meta_key ) {
        log_me('got tm_video_url...');        
      
        $vimeo_id = substr($meta_value, strrpos( $meta_value, '/')+1);
        
        // Get HLS URL
        $token = '63100f4f5dcedd6df1b4bebb2f83b298';
        $args = array(
                'headers' => array(
                        'Authorization' => 'Bearer ' . $token
                        )
                );
        $url = 'https://api.vimeo.com/me/videos/'. $vimeo_id;
        $request = wp_remote_get($url, $args);
        $body = json_decode( $request['body']);
        log_me('before log_me...');        
        $files = $body->files;
        foreach ($files as $key => $value) {
            if($value->quality == 'hls'){
                log_me('HLS link is : '. $value->link_secure);
                update_post_meta( $post_id, 'hls_video_url', $value->link_secure );
            }
        }
        log_me('after log_me...');
    }
}

/*
Wistia code which was moved
 $arr = explode(' ', $meta_value);
        $src = $arr[1];
        $firstPart = explode('?', $src);
        $wistia_id = substr(strrchr( $firstPart[0], '/'), 1);
        $url = 'https://api.wistia.com/v1/medias/'. $wistia_id . '.json?api_password=c86b1325279105c87bbd3d78bdd78fa59d39378ba3cba5c4c19b92be20e8386e';
        $json = wp_remote_get( $url );
        $json_obj = json_decode($json['body']);
        log_me('before var_dump');
        $assets = $json_obj->assets; 
        foreach ($assets as $key => $value) {
                if($value->type == 'IphoneVideoFile'){
                        update_post_meta( $post_id, 'tm_video_url', $value->url );
                }
        }
*/
add_action( 'deleted_post_meta', 'wt_delete_vimeo_video_url', 10, 4 );
function wt_delete_vimeo_video_url( $deleted_meta_ids, $post_id, $meta_key, $only_delete_these_meta_values )
{
    if ( 'tm_video_code' == $meta_key ) {
        // wpse16835_undo_something( $post_id );
    }
}

// add_rewrite_tag('%pmt_id%','([^&]+)');
// add_rewrite_tag('%pmt_status%','([^&]+)');
// add_filter( 'query_vars', 'add_query_vars_filter' );
// function wt_add_query_vars_filter( $vars ){
//   $vars[] = "pmt_id";
//   $vars[] = "pmt_status";
//   return $vars;
// }

// add_filter('rewrite_rules_array', 'wt_add_rewrite_rules');
// function wt_add_rewrite_rules($aRules) {
// 	$aNewRules = array('^payment-status/([^/]+)/([^/]+)/?$' => 'index.php?pagename=payment-status&pmt_id=$matches[1]&pmt_status=$matches[2]');
// 	$aRules = $aNewRules + $aRules;
// 	return $aRules;
// } 

// Hook in woocommerce to remove filed and change required status - Virendra

 
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
     unset($fields['order']['order_comments']);
      unset($fields['billing']['billing_company']);
       unset($fields['billing']['billing_address_1']);
       unset($fields['billing']['billing_address_2']);
       unset($fields['billing']['billing_city']);
       unset($fields['billing']['billing_postcode']);
       unset($fields['billing']['billing_country']);
       unset($fields['billing']['billing_state']);
       unset($fields['billing']['billing_first_name']);
       unset($fields['billing']['billing_last_name']);
       
       
       unset($fields['shipping']['shipping_state']);
       unset($fields['shipping']['shipping_country']);
       unset($fields['shipping']['shipping_city']);
       unset($fields['shipping']['shipping_first_name']);
       unset($fields['shipping']['shipping_last_name']);
       unset($fields['shipping']['shipping_postcode']);
       unset($fields['shipping']['shipping_company']);
       unset($fields['shipping']['shipping_address_1']);
       unset($fields['shipping']['shipping_address_2']);
       
     return $fields;
}

//function to include a file into a page via shortcode
function includeme_call($attrs, $content = null) {

    if (isset($attrs['file'])) {
        $file = strip_tags($attrs['file']);
        if ($file[0] != '/')
            $file = ABSPATH . $file;

        ob_start();
        include($file);
        $buffer = ob_get_clean();
        $options = get_option('includeme', array());
        if (isset($options['shortcode'])) {
            $buffer = do_shortcode($buffer);
        }
    } else {
        $tmp = '';
        foreach ($attrs as $key => $value) {
            if ($key == 'src') {
                $value = strip_tags($value);
            }
            $value = str_replace('&amp;', '&', $value);
            if ($key == 'src') {
                $value = strip_tags($value);
            }
            $tmp .= ' ' . $key . '="' . $value . '"';
        }
        $buffer = '<iframe' . $tmp . '></iframe>';
    }
    return $buffer;
}

// Here because the funciton MUST be define before the "add_shortcode" since 
// "add_shortcode" check the function name with "is_callable".
add_shortcode('includeme', 'includeme_call');
// }