<?php
/*
Plugin Name: WebTalkies Custom Endpoints
Version: 2.0-alpha
Description: Add custom REST enpoints for WebTalkies, added bookmyshow, fortumo, instamojo
Author: BS and VS
Author URI: www.sanganaktechnologies.com
Plugin URI: www.sanganaktechnologies.com
Text Domain: wt-custom-endpoints
Domain Path: /languages
*/
if ( !function_exists( 'pmpro_changeMembershipLevel' ) ) { 
        require_once plugin_dir_path( __FILE__).'../paid-memberships-pro/includes/functions.php'; 
} 

if ( !function_exists( 'wtvs_report_error' ) ){
    require_once plugin_dir_path(__FILE__) . '../pmpro-customizations/pmpro-customizations.php';
}
/**
 * Grab video series list
 *
 * @return string|null List of series 
 */
function wt_get_series_list() {
    $taxonomy = 'video-series';
    $terms = get_terms( array(
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
            'fields' => 'all') );
    $series = [];
    $url = 'no posts';

    foreach($terms as $term){
        if($term->count > 0){
                $url = 'Positive for ' . $term->slug;
                $posts_array = get_posts(
                        array(
                                'posts_per_page' => -1,
                                'post_type' => 'post',
                                'tax_query' => array(
                                        array(
                                                'taxonomy' => $taxonomy,
                                                'field' => 'slug',
                                                'terms' => $term->slug,
                                                )
                                        )
                                )
                        );
                // if(sizeof($posts_array)){
                //         $url = z_taxonomy_image_url( $term->term_id );
                //         // $url = wp_get_attachment_url( get_post_thumbnail_id($posts_array[0]->ID), 'medium' );
                // }
        } // If count > 0, query posts
        $url = z_taxonomy_image_url( $term->term_id );

        // else 
        // {
        //         // no posts found
        //         $url = 'no posts';
        // }
        $series[] = ['term_id' => $term->term_id,
                'term_taxonomy_id' => $term->term_taxonomy_id,
                'name'=> $term->name, 
                'slug'=>$term->slug,
                'description'=>$term->description, 
                'count' => $term->count,
                'poster_url'=> $url];
    }

    return wp_send_json($series);
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'webtalkies/v1', '/series', array(
		'methods' => 'GET',
		'callback' => 'wt_get_series_list',
	) );
} );

// Create user API
add_action( 'rest_api_init', function () {
	register_rest_route( 'webtalkies/v1', '/create_user', array(
		'methods' => 'POST',
		'callback' => 'wt_create_user',
	) );
} );

function wt_create_user(WP_REST_Request $request) {
        
        // Extract relevant parameters
        $parameters = $request->get_params();
        $email = $request->get_param('email');
        $password = $request->get_param('password');
        // $level_id = $request->get_param('level_id');
        $level_id = 1;
        $return = array();
        
        $user_login = sanitize_user( current( explode( '@', $email ) ), true );

        // $user_id = username_exists( $user_login );

        // user name should be unique
        if( username_exists( $user_login ) )
        {
                $i = 1;
                $user_login_tmp = $user_login;

                do
                {
                        $user_login_tmp = $user_login . "_" . ($i++);
                }
                while( username_exists ($user_login_tmp));

                $user_login = $user_login_tmp;
        }

        if ( /*!$user_id and */email_exists($email) == false ) {
                // $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
                // $user_id = wp_create_user( $user_login, $random_password, $email );
                $user_id = wp_create_user( $user_login, $password, $email );
                  
                // The old level status. 
                $old_level_status = 'inactive'; 
                
                // NOTICE! Understand what this does before running. 
                $result = wtvs_change_membershiplevel(intval($level_id), $user_id);
                if($result){
                        $return = array('status' => 'SUCCESS', 'message'=>'User registered for default membership');
                }
                else{
                        $return = array('status' => 'ERROR', 'message'=>'User could not be registered for default membership');
                }
        } else {
                $return = array('status' => 'ERROR', 'message'=>'User email or username already exists');
        }
        // Return success message
        return wp_send_json($return);
        // return "Hi";
}

// Create user social API
add_action( 'rest_api_init', function () {
	register_rest_route( 'webtalkies/v1', '/create_user_social', array(
		'methods' => 'POST',
		'callback' => 'wt_create_user_social',
	) );
} );

// TODO : Due to new flow introduced before handover, there is lot of repetition of code
// This can be refactored to make it compact. 
function wt_create_user_social(WP_REST_Request $request) {
        
        // Extract relevant parameters
        $parameters = $request->get_params();
        $provider = $request->get_param('provider');
        $email = $request->get_param('email');
        $user_login = $request->get_param('user_login');
        $display_name = $request->get_param('display_name');
        $first_name = $request->get_param('first_name');
        $last_name = $request->get_param('last_name');
        $provider_uid = $request->get_param('provider_uid');
        $profile_url = $request->get_param('profile_url');
        $photo_url = $request->get_param('photo_url');
        $description = $request->get_param('description');
        $gender = $request->get_param('gender');
        $locale = $request->get_param('locale');

        // $level_id = $request->get_param('level_id');
        $level_id = 1;
        $return = array();
        $wp_user_exists = false;
        $other_provider = $provider == 'Google' ? 'Facebook' : 'Google';

        // 0(New Flow) Check if email exists within WP
        if($email){
                if(email_exists($email)){
                        $wp_user_exists = true;
                }
        }
        
        // 1. Check if user already exists in social
        global $wpdb;
        $sql = "SELECT user_id FROM `{$wpdb->prefix}wslusersprofiles` WHERE provider = %s AND identifier = %s";
        $social_profile_id =  $wpdb->get_var( $wpdb->prepare( $sql, $provider, $provider_uid ) );

        // 1.01 (New Flow) Check if it exists on another social profile
        global $wpdb;
        $sql = "SELECT user_id FROM `{$wpdb->prefix}wslusersprofiles` WHERE provider = %s AND email = %s";
        $other_social_profile_id =  $wpdb->get_var( $wpdb->prepare( $sql, $other_provider, $email ) );
        
        // 1.1 (New Flow) If email exists (existing user/not new) and social profile exists for other social media, disallow
        if($wp_user_exists && $other_social_profile_id && ($other_social_profile_id != $social_profile_id)){
                $return = array('status' => 'ERROR', 'message'=>"You are already registered with $other_provider. Please use $other_provider to login");
                return wp_send_json($return); // Attempt login and return token
        }
        // 1.2 (New Flow) If email exists (existing user/not new) and social profile exists, directly login and return token
        if($wp_user_exists && $social_profile_id){
                $return = wt_login_app_user($email, $provider);
                return wp_send_json($return); // Attempt login and return token
        }

        
       /* return wp_send_json(
                array(
                        'other_social_id' => $other_social_profile_id, 
                        'social_id' => $social_profile_id, 
                        'user_exists' => $wp_user_exists)
                );
                */
        // 2. If it does, skip WP creation , any more checks ?
        // 3. If it does not exist create WP user
        if(!$social_profile_id){
                
                // attempt to generate user_login from hybridauth user profile display name
                $user_login = $display_name;

                // sanitize user login
                $user_login = sanitize_user( $user_login, true );

                // remove spaces and dots
                $user_login = trim( str_replace( array( ' ', '.' ), '_', $user_login ) );
                $user_login = trim( str_replace( '__', '_', $user_login ) );

                // if user profile display name is not provided
                if( empty( $user_login ) )
                {
                        $user_login = sanitize_user( current( explode( '@', $user_email ) ), true );
                }
                // user name should be unique
                if( username_exists( $user_login ) )
                {
                        $i = 1;
                        $user_login_tmp = $user_login;

                        do
                        {
                                $user_login_tmp = $user_login . "_" . ($i++);
                        }
                        while( username_exists ($user_login_tmp));

                        $user_login = $user_login_tmp;
                }
                
                $user_email = $email;
                if( ! $user_email )
                {
                        // generate an email if none
                        if( ! isset ( $user_email ) OR ! is_email( $user_email ) )
                        {
                                $user_email = strtolower( $provider . "_user_" . $user_login ) . '@example.com';
                        }

                        // email should be unique
                        if( wsl_wp_email_exists ( $user_email ) )
                        {
                                do
                                {
                                        $user_email = md5( uniqid( wp_rand( 10000, 99000 ) ) ) . '@example.com';
                                }
                                while( wsl_wp_email_exists( $user_email ) );
                        }
                }/*else{ (New Flow) This condition is now changed in new flow
                        if(email_exists($email)){
                                $return = array('status' => 'ERROR', 'message'=>'User email already exists !');
                                 return wp_send_json($return);
                        }
                } */       

                // $display_name = $hybridauth_user_profile->displayName;

                if( empty( $display_name ) )
                {
                        $display_name = $first_name;
                }

                if( empty( $display_name ) )
                {
                        $display_name = strtolower( $provider ) . "_user";
                }
                // (New Flow) WP user does not exists, so create one
                $user_id = 0;
                if($wp_user_exists == false){ 
                        $userdata = array(
                                'user_login'    => $user_login,
                                'user_email'    => $user_email,
                                'display_name'  => $display_name,
                                'first_name'    => $first_name,
                                'last_name'     => $last_name,
                                'user_url'      => $profile_url,
                                'description'   => $description,
                                'user_pass'     => wp_generate_password()
                        );

                        $user_id = wp_insert_user( $userdata );
                        
                        if( ! $user_id || ! is_integer( $user_id ) )
                        {
                                if( is_wp_error( $user_id ) )
                                {
                                        $return = array('status' => 'ERROR', 'message'=>'An error occurred while creating a new user : '. $user_id->get_error_message() );
                                }
                                $return = array('status' => 'ERROR', 'message'=>'An error occurred while creating a new user : '. $user_id->get_error_message() );
                        }
                        else{
                                $return = array('status' => 'SUCCESS', 'message'=>"New user registered successfully using $provider.");
                        }
                        // wp_insert_user may fail on first and last name meta, expliciting setting to correct.
                        update_user_meta($user_id, 'first_name', $userdata['first_name']);
                        update_user_meta($user_id, 'last_name', $userdata['last_name']);
                        wp_new_user_notification($user_id);
                        
                        // 3.5 Change user level 
                        $result = pmpro_changeMembershipLevel($level_id, $user_id);
                }
                else // 3.6 (New Flow) Get existing user id to join
                { 
                        $user = get_user_by( 'email', $user_email );
                        $user_id =  $user->ID;
                }

                // 4. Update / insert social profile
                global $wpdb;
                $object_sha = sha1( serialize( $userdata ) ); // This is incorrect will be corrected in users first web login
                $table_data = array(
                        "id"         => 'null',
                        "user_id"    => $user_id,
                        'identifier' => $provider_uid, 
                        "provider"   => $provider,
                        "object_sha" => $object_sha,
                        'profileurl' => $profile_url, 
                        'photourl' => $photo_url, 
                        'displayname' => $display_name, 
                        'description' => $description, 
                        'firstname' => $first_name, 
                        'lastname' => $last_name, 
                        'gender' => $gender, 
                        'language' => $locale, 
                        'email' => $email, 
                );
                
                $social_id = $wpdb->replace( "{$wpdb->prefix}wslusersprofiles", $table_data );
                //  return $social_id;
                
                if( ! $social_id || ! is_integer( $social_id ) )
                {
                        if( is_wp_error( $social_id ) )
                        {
                                $return = array('status' => 'ERROR', 'message'=>'An error occurred while creating a new user : '. $social_id->get_error_message() );
                        }
                }
                else{
                        // $return = array('status' => 'SUCCESS', 'message'=>"New user profile registered successfully using $provider.");
                         $return = wt_login_app_user($email, $provider);
                }
        }
        else{ // This should never happen as user has to exists for social profile to exist
                $return = array('status' => 'ERROR', 'message'=>'There was an error in registration. Please contact support');
        }
        
        // Return appropriate message
        return wp_send_json($return);
}

// Create user social API
add_action( 'rest_api_init', function () {
	register_rest_route( 'webtalkies/v1', '/login_user_social', array(
		'methods' => 'POST',
		'callback' => 'wt_login_user_social',
	) );
} );

// TODO : Due to new flow introduced before handover, there is lot of repetition of code
// This can be refactored to make it compact. 
function wt_login_user_social(WP_REST_Request $request) {
        // Check if user social id exists for given provider
        $provider = $request->get_param('provider');
        $provider_uid = $request->get_param('provider_uid');
        $user_email = $request->get_param('email');
        // For (New Flow)
        $provider = $request->get_param('provider');
        $email = $request->get_param('email');
        $user_login = $request->get_param('user_login');
        $display_name = $request->get_param('display_name');
        $first_name = $request->get_param('first_name');
        $last_name = $request->get_param('last_name');
        $provider_uid = $request->get_param('provider_uid');
        $profile_url = $request->get_param('profile_url');
        $photo_url = $request->get_param('photo_url');
        $description = $request->get_param('description');
        $gender = $request->get_param('gender');
        $locale = $request->get_param('locale');

        $other_provider = $provider == 'Google' ? 'Facebook' : 'Google';
        $return = array();
        $wp_user_exists = false;
        $level_id = 1;
       // 0(New Flow) Check if email exists within WP
        if($email){
                if(email_exists($email)){
                        $wp_user_exists = true;
                }
        }
        
        // 1. Check if user already exists in social
        global $wpdb;
        $sql = "SELECT user_id FROM `{$wpdb->prefix}wslusersprofiles` WHERE provider = %s AND identifier = %s";
        $social_profile_id =  $wpdb->get_var( $wpdb->prepare( $sql, $provider, $provider_uid ) );

        // 1.01 (New Flow) Check if it exists on another social profile
        global $wpdb;
        $sql = "SELECT user_id FROM `{$wpdb->prefix}wslusersprofiles` WHERE provider = %s AND email = %s";
        $other_social_profile_id =  $wpdb->get_var( $wpdb->prepare( $sql, $other_provider, $email ) );

        // 1.1 (New Flow) If email exists (existing user/not new) and social profile exists for other social media, disallow
        if($wp_user_exists && $other_social_profile_id && ($other_social_profile_id != $social_profile_id)){
                $return = array('status' => 'ERROR', 'message'=>"You are already registered with $other_provider. Please use $other_provider to login");
                return wp_send_json($return); // Attempt login and return token
        }

        // 1.2 (New Flow) If email exists (existing user/not new) and social profile exists, directly login and return token
        if($wp_user_exists && $social_profile_id){
                $return = wt_login_app_user($email, $provider);
                return wp_send_json($return); // Attempt login and return token
        }

        // User social profile doesn't exists  
        if(!$social_profile_id){
                log_me('User social profile not found');
                
                // (New Flow) : Update / insert social profile and WP user as required
                $user_id = 0;
                
                // (New Flow) : Sanitize data start (this can be a function)
                // attempt to generate user_login from hybridauth user profile display name
                $user_login = $display_name;

                // sanitize user login
                $user_login = sanitize_user( $user_login, true );

                // remove spaces and dots
                $user_login = trim( str_replace( array( ' ', '.' ), '_', $user_login ) );
                $user_login = trim( str_replace( '__', '_', $user_login ) );

                // if user profile display name is not provided
                if( empty( $user_login ) )
                {
                        $user_login = sanitize_user( current( explode( '@', $user_email ) ), true );
                }
                // user name should be unique
                if( username_exists( $user_login ) )
                {
                        $i = 1;
                        $user_login_tmp = $user_login;

                        do
                        {
                                $user_login_tmp = $user_login . "_" . ($i++);
                        }
                        while( username_exists ($user_login_tmp));

                        $user_login = $user_login_tmp;
                }
                
                $user_email = $email;
                if( ! $user_email )
                {
                        // generate an email if none
                        if( ! isset ( $user_email ) OR ! is_email( $user_email ) )
                        {
                                $user_email = strtolower( $provider . "_user_" . $user_login ) . '@example.com';
                        }

                        // email should be unique
                        if( wsl_wp_email_exists ( $user_email ) )
                        {
                                do
                                {
                                        $user_email = md5( uniqid( wp_rand( 10000, 99000 ) ) ) . '@example.com';
                                }
                                while( wsl_wp_email_exists( $user_email ) );
                        }
                }
                if( empty( $display_name ) )
                {
                        $display_name = $first_name;
                }

                if( empty( $display_name ) )
                {
                        $display_name = strtolower( $provider ) . "_user";
                } 
                // (New Flow) : Sanitize data end 


                // (New Flow) : Create new WP user if required
                if($wp_user_exists == false){ 
                        $userdata = array(
                                'user_login'    => $user_login,
                                'user_email'    => $user_email,
                                'display_name'  => $display_name,
                                'first_name'    => $first_name,
                                'last_name'     => $last_name,
                                'user_url'      => $profile_url,
                                'description'   => $description,
                                'user_pass'     => wp_generate_password()
                        );

                        $user_id = wp_insert_user( $userdata );
                        
                        if( ! $user_id || ! is_integer( $user_id ) )
                        {
                                if( is_wp_error( $user_id ) )
                                {
                                        $return = array('status' => 'ERROR', 'message'=>'An error occurred while creating a new user : '. $user_id->get_error_message() );
                                }
                                $return = array('status' => 'ERROR', 'message'=>'An error occurred while creating a new user : '. $user_id->get_error_message() );
                        }
                        else{
                                $return = array('status' => 'SUCCESS', 'message'=>"New user registered successfully using $provider.");
                        }
                        // wp_insert_user may fail on first and last name meta, expliciting setting to correct.
                        update_user_meta($user_id, 'first_name', $userdata['first_name']);
                        update_user_meta($user_id, 'last_name', $userdata['last_name']);
                        wp_new_user_notification($user_id);
                        
                        //  Change user level 
                        $result = pmpro_changeMembershipLevel($level_id, $user_id);
                }
                else //  (New Flow) Get existing user id to join
                { 
                        $user = get_user_by( 'email', $user_email );
                        $user_id =  $user->ID;
                }

                // New Flow : Update / insert social profile       
                global $wpdb;
                $object_sha = sha1( serialize( $userdata ) ); // This is incorrect will be corrected in users first web login
                $table_data = array(
                        "id"         => 'null',
                        "user_id"    => $user_id,
                        'identifier' => $provider_uid, 
                        "provider"   => $provider,
                        "object_sha" => $object_sha,
                        'profileurl' => $profile_url, 
                        'photourl' => $photo_url, 
                        'displayname' => $display_name, 
                        'description' => $description, 
                        'firstname' => $first_name, 
                        'lastname' => $last_name, 
                        'gender' => $gender, 
                        'language' => $locale, 
                        'email' => $email, 
                );
                
                $social_id = $wpdb->replace( "{$wpdb->prefix}wslusersprofiles", $table_data );
                //  return $social_id;
                
                if( ! $social_id || ! is_integer( $social_id ) )
                {
                        if( is_wp_error( $social_id ) )
                        {
                                $return = array('status' => 'ERROR', 'message'=>'An error occurred while creating a new user : '. $social_id->get_error_message() );
                        }
                }
                else{
                        // $return = array('status' => 'SUCCESS', 'message'=>"New user profile registered successfully using $provider.");
                         $return = wt_login_app_user($email, $provider);
                }

                // $return = array('status' => 'ERROR', 'message'=>"$provider user not found. Did you register using email or $other_provider ?");
                // return wp_send_json($return);
        }
        // If exists, use application user credentials
        else{ 
                $return = wt_login_app_user($user_email, $provider);
        }
        // Return token

        // Return appropriate message
        return wp_send_json($return);
}

// Convenience function to login using app user
function wt_login_app_user($user_email, $provider){
        $site = get_site_url();
        $pwd = '';
        $return = array();
        
        if(strpos($site, 'localhost') !== false) {
                $pwd = 'h%5!WAUzqQO)4qq)&*(%px*J';
        }else if(strpos($site, 'dev-wtalkies') !== false){
                $pwd = '&SpKBEAsy*eU0fYYeRowkR3B';
        }else if(strpos($site, 'webtalkies') !== false){
                $pwd = 'AV1gpBaU!3AhuH$jSSYlvvc!';
        }
        
        $user_login = 'bsjawle@yahoo.com';
       $url = $site . '/wp-json/jwt-auth/v1/token';
        

        $args = array(
                'body' => array(
                        "username" => $user_login,
                        "password"  => $pwd
                        )
                );

        $response =     wp_remote_post( $url, $args );
        if ( 200 == $response['response']['code'] ) {
        // return "Before post";
                $response_obj = json_decode( substr($response['body'],3 )); // Don't know what is this magic character
                $return = array('status' => 'SUCCESS', 
                                'message'=>"User successfully logged in using $provider.",
                                'user_email'=>$user_email,
                                'token' => $response_obj->{'token'}
                                );
                // if (get_magic_quotes_gpc()) {
                //         $return = stripslashes(stripslashes($response['body']));
                // }
                // else{
                        // $decodedText = html_entity_decode($response['body']);
                        // $return = json_decode(substr("﻿{\"token\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL3d0YWxraWVzIiwiaWF0IjoxNDc5MjkzMzQ3LCJuYmYiOjE0NzkyOTMzNDcsImV4cCI6MTQ3OTg5ODE0NywiZGF0YSI6eyJ1c2VyIjp7ImlkIjoiMjgifX19.EMN0zDWdEUfJ_fWEgXX2P27z8GW_HVFXns1q5oC_B2U\",\"user_email\":\"bsjawle@yahoo.com\",\"user_nicename\":\"webtalkiesapp\",\"user_display_name\":\"WebTalkies App\"}"), 3);
                // }
                        // $return = substr($response['body'],3 );
                // $return = $response_obj;
                // $return = $errorMsg;
        }
        else {
                $return = array('status' => 'ERROR', 'message'=>"User could not be authenticated properly");
                // $return = $response;
        }
        return $return;
}

// Create user API
add_action( 'rest_api_init', function () {
	register_rest_route( 'webtalkies/v1', '/update_user_pwd', array(
		'methods' => 'POST',
		'callback' => 'wt_update_user_pwd',
	) );
} );

function wt_update_user_pwd(WP_REST_Request $request) {
        
        // Extract relevant parameters
        // $parameters = $request->get_params();
        $email = $request->get_param('email');
        $password = $request->get_param('password');
        $return = array();
        
        $user_id = email_exists($email);
        if ( $user_id  ) {
                wp_set_password( $password, $user_id );
                $return = array('status' => 'SUCCESS', 'message'=>"Password updated successfully !");
        }
        else{
                $return = array('status' => 'ERROR', 'message'=>"You are logged in using social media. You can't change password !");
        }
        // Return appropriate message
        return wp_send_json($return);
}

// Create Book My Show User user API
add_action( 'rest_api_init', function () {
	register_rest_route( 'webtalkies/v1', '/create_bms_user', array(
		'methods' => 'POST',
		'callback' => 'wt_create_bms_user',
	) );
} );


function wt_create_bms_user(WP_REST_Request $request) {
        
        // Extract relevant parameters
        $parameters = $request->get_params();
        $email = $request->get_param('email');
        $password = $request->get_param('transaction_id');
        $bms_email = $request->get_param('bms_email'); 
        $bms_token = $request->get_param('bms_token');
        // $level_id = $request->get_param('level_id');
        $level_id = 10; //This must be the number corresponding to UMG fans level id
        $return = array();
        
        //Validate if this access by bookmyshow or some other site...
        
        $is_it_bookmyshow = get_user_by( 'email', $bms_email );
            if ( $is_it_bookmyshow && wp_check_password( $bms_token, $is_it_bookmyshow->data->user_pass, $is_it_bookmyshow->ID ) ) {
             $yes_bookmyshow = 1;
                } else {
                 $yes_bookmyshow = 0;
                }
            
        //if bookmyshow - execute the request or reject
                
        if ($yes_bookmyshow == '1'){
            
          $user_login = sanitize_user( current( explode( '@', $email ) ), true );

        // $user_id = username_exists( $user_login );

        // user name should be unique
        if( username_exists( $user_login ) )
        {
                $i = 1;
                $user_login_tmp = $user_login;

                do
                {
                        $user_login_tmp = $user_login . "_bms_" . ($i++);
                }
                while( username_exists ($user_login_tmp));

                $user_login = $user_login_tmp;
        }

        if ( /*!$user_id and */email_exists($email) == false ) {
                // $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
                // $user_id = wp_create_user( $user_login, $random_password, $email );
                $user_id = wp_create_user( $user_login, $password, $email );
                  
                // The old level status. 
                $old_level_status = 'inactive'; 
                
                // NOTICE! Understand what this does before running. 
                $result = pmpro_changeMembershipLevel(intval($level_id), $user_id);
                if($result){
                        $return = array('status' => 'SUCCESS', 'message'=>'User registered for requested membership. Enjoy the video.');
                }
                else{
                        $return = array('status' => 'ERROR', 'message'=>'User could not be registered or updated for required membership. Please contact Web Talkies.');
                }
        }
            // if user email already registered we simply update his membership level.
            else {
                $user = get_user_by( 'email', $email );
                $user_id = $user->id;
                $result = pmpro_changeMembershipLevel(intval($level_id), $user_id);
                
                if ($result){
                    $return = array('status' => 'User Updated', 'message'=>'User email already exists at Web Talkies. User access updated successfully.');
        }
        else {
       $return = array('status' => 'Error', 'message'=>'Web Talkies User email already exists and User could not be updated. Please contact Web Talkies.', 'Click Here'=>'');
            
        }
             }
        // Return success message
             
       // return wp_send_json($return);
        
        // return "Hi";
            
        }
       
        else {
         $return = array('status' => 'Error', 'message'=>'Book My Show Credentials Not Validated. Please contact Book My Show.');
           
            
        }
        return wp_send_json($return);
}

// Create Fortumo User user API
add_action( 'rest_api_init', function () {
	register_rest_route( 'webtalkies/v1', '/wt_create_fortumo_user', array(
		'methods' => 'GET',
		'callback' => 'wt_create_fortumo_user',
	) );
} );


function wt_create_fortumo_user(WP_REST_Request $request) {
    
    global $wpdb; 
    
    //set return variable for json reporting
        $return = array();
        
   // Retrieve Data received from Webhook
  if(isset($_GET))    
  {
  $data = $_GET; 
  $customer_phone = $_GET['sender'];//phone num.
  $amount = $_GET['amount'];//credit
  $cuid = $_GET['cuid'];//resource i.e. user
  $payment_id = $_GET['payment_id'];//unique id
  $test = $_GET['test'];
  $status = $_GET['status'];
  $sig = $_GET['sig'];
  $data['gateway_name'] = 'Fortumo';
  }
        
   // check that the request comes from Fortumo server
 if (!in_array($_SERVER['REMOTE_ADDR'],
 array('127.0.0.1', '54.72.6.126', '54.72.6.27', '54.72.6.17', '54.72.6.23', '79.125.125.1', '79.125.5.95', '79.125.5.205'))) { 
 header("HTTP/1.0 403 Forbidden");
 $return = wtvs_report_error(05);
 $data['key-matched'] = 'DID NOT TRY';
 $return['Timestamp'] = current_time('mysql');
 $temp = wtvs_json_log(array_merge($data, $return));
 return wp_send_json ($return);
 die("Error: Unknown IP");
 }
 
//Get all level fortumo secret key to match signature  
$custommix_fortumo_secret = pmpro_getOption('custommix_fortumo_secret');
//Get options of commaseperated values of service Id and Secret and explode to get service Id and Secret as per the order level.
$custommix_secret_level_combo_array = explode (',', $custommix_fortumo_secret); // (You get array of values like 1, serviceId where 1 is level and service id is service id.)
//Get Order Level from CUID
$order_id = explode('@', $cuid);
$order_level = $wpdb->get_var($wpdb->prepare("SELECT membership_id FROM $wpdb->pmpro_membership_orders  WHERE code = %s", $order_id[1]));

            //Extract particular level
            $i=0;
            
            foreach ($custommix_secret_level_combo_array as $custommix_secret_level){
                
                if ($custommix_secret_level == $order_level){$secret = $custommix_secret_level_combo_array[$i+1]; break;} 
                $i++;
            }

 // check the signature
  if(empty($secret)||!wtvs_check_fortumo_sig($_GET, $secret)) {
    header("HTTP/1.0 404 Not Found");
    $return = wtvs_report_error(03);
    $return['Timestamp'] = current_time('mysql');
    $data['key-matched'] = 'NO';
    $temp = wtvs_json_log(array_merge($data, $return));
    return wp_send_json ($return);
    die("Error: Invalid signature");
  }
                         
  //explode CUID to seperate user_id and order_id. CUID that we send to fortumo is user_id@order_id.
        $cuid_array = explode('@', $cuid);
        $user_id = $cuid_array[0];
        $order_id = $cuid_array[1];
        $data['key-matched'] = 'YES';
        
  // Check if Data is proper otherwise rrport error and stop
         if(empty($user_id) || empty($order_id) || empty($status))
         {
             $return = wtvs_report_error(01);
             $return['Timestamp'] = current_time('mysql');
              $temp = wtvs_json_log(array_merge($data, $return));
              return wp_send_json ($return);
         }
        
  // If data is proper go ahead and do action according to data and payment status.
      
      //get order status parameters by get request
          
	             
            if(preg_match("/completed/i", $status))
            {$return = wtvs_action_after_successful_payment($user_id, $order_id);}
            else 
            {$return = wtvs_action_after_failed_payment($user_id, $order_id);}
           
   
   //Write to Log file and Send jason response 
       $return['Timestamp'] = current_time('mysql');
       $temp = wtvs_json_log(array_merge($data, $return));
      return wp_send_json ($return);   
}




 

// Virendra : Create Instamojo User user API - PMPRO code : Gives user Membership level corresponding to their order.

add_action( 'rest_api_init', function () {
	register_rest_route( 'webtalkies/v1', '/wt_webhook_instamojo_pmpro', array(
		'methods' => 'POST',
		'callback' => 'wt_webhook_instamojo_pmpro',
	) );
} );

function wt_webhook_instamojo_pmpro(WP_REST_Request $request) {

  global $wpdb; 
    
    //set return variable for json reporting
        $return = array();
                         
  // Retrieve Data received from Webhook
       $data = $_POST;
     
  // Check if data is proper otherwise report error and stop.
     if(empty($data) || !isset($data['purpose']))
         {
             $return = wtvs_report_error(01);
             $return['Timestamp'] = current_time('mysql');
             $temp = wtvs_json_log(array_merge($data, $return));
             return wp_send_json ($return);
         }
         
    // Check hash/mac/key match and add status to data array     
   $data = wtvs_check_instamojo_mac($data);
   
     
  // do action according to result of key check.
   if ($data['key-matched'] == 'Technical Failure' || $data['key-matched'] == 'NO'){
         
         $return = wtvs_report_error(03);
         $return['Timestamp'] = current_time('mysql');
         $temp = wtvs_json_log(array_merge($data, $return));
         return wp_send_json ($return);
         
     }  

  //All well, go ahead and do action according to data and payment status.
        $payment_id = $data['payment_id'];
        $email = $data['buyer']; 
        $password = $data['payment_id']; 
        $status = $data['status']; 
        $order_id = $data['purpose']; 
        $current_user = get_user_by('email', $email);
        $user_id = $current_user->ID;
        
        //Add items to data array
        $data['gateway_name'] = 'Instamojo';
        $data['website'] = get_site_url();
    
   
        
  // Stop if we do not have User Id?
         if(empty($user_id))
         {
         $return = wtvs_report_error(02);
         $return['Timestamp'] = current_time('mysql');
         $temp = wtvs_json_log(array_merge($data, $return));
         return wp_send_json ($return);
         }
         
//If all fine, do things as per payment status.
              
            if($status === 'Credit')
            {$return = wtvs_action_after_successful_payment($user_id, $order_id);}
            else 
            {$return = wtvs_action_after_failed_payment($user_id, $order_id);}
           
   //log json data in a file and Send jason response 
     $return['Timestamp'] = current_time('mysql');
     $temp = wtvs_json_log(array_merge($data, $return));       
     return wp_send_json ($return);
     
}
