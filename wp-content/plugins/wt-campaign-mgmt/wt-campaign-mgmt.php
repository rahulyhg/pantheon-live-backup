<?php
/**
 * Plugin Name: Web Talkies Campaign Submission Management 
 * Plugin URI: http://webtalkies.in
 * Description: This plugin manages data posted for Campaign form
 * Version: 1.0.0
 * Author: Bhushan S. Jawle
 * Author URI: http://www.sanganktechnologies.com
 * License: GPL2
 */

// Contact form 7 pre save hook
function wt_pre_save_contest($contestForm){
	$output = "This is pre save : ";
   $output .= "Name: " . $_POST['your-name'];
   $output .= "Email: " . $_POST['your-email'];
   $output .= "Mobile: " . $_POST['your-mobile'];
   $output .= "Subject: " . $_POST['your-subject'];
   $output .= "Message: " . $_POST['your-message'];
   log_me($output);
}
add_action('wpcf7_save_contact_form', 'wt_pre_save_contest');

// Contact form 7 pre send hook
function wt_pre_send_contest($contestForm) {
   $output = "This is pre send: ";
   $user_name = $_POST['your-name'];
   $email = $_POST['your-email'];
   $mobile =  $_POST['your-mobile'];
   $subject = $_POST['your-subject'];
   $message = $_POST['your-message'];
   $level_id = "1";
   $body = 'Thank you for participating';
   $subject = 'Thank you for participating';
   log_me($output. $user_name . ':'. $email . ':'. $mobile . ':' . $subject. ':' . $message);

   // Check if user exists
   $return = array();
        
    $user_id = username_exists( $user_name );
    $tmp_user_name = $user_name;
    // if ( !$user_id and email_exists($email) == false ) {
    if ( email_exists($email) == false ) {
        log_me('Useremail not found');
        $i =1;
        while($user_id){
            log_me('User name  found');
            $tmp_user_name = $user_name;
            
            $tmp_user_name .= "_$i";
            $user_id = username_exists( $tmp_user_name );
            $i++;
            log_me('New User name:' . $tmp_user_name);
        }
        $user_name = $tmp_user_name; 

        $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
        log_me('Password generated : ' . $random_password);
        
        // $user_id = wp_create_user( $user_name, $random_password, $email );
        $user_id = wp_create_user( $user_name, $random_password, $email );
        log_me('User created with id : '. $user_id);
        
        // The old level status. 
        $old_level_status = 'inactive'; 
        
        // NOTICE! Understand what this does before running. 
        $result = pmpro_changeMembershipLevel(intval($level_id), $user_id);
        if($result){
                log_me('User level changed');
                $return = array('status' => 'SUCCESS', 'message'=>'User level changed');
        }
        else{
                log_me('User level not changed');
                $return = array('status' => 'ERROR', 'message'=>'User level not changed');
        }
        $body = "<html><body><img src=\"https://www.webtalkies.in/wp-content/uploads/2017/02/thank-you.jpg\" />";
        $body .= "<p>Dear $user_name,<br/> ";
        $body .= "Thank you for your membership with WEBTALKIES. <br/>";
        $body .= "Your membership is now active.<br/> Below are the details of your membership account.<br/>";
        $body .= "Account : $email<br/>";
        $body .= "Password : $random_password<br/>";
        $body .= "To change password <a href='https://www.webtalkies.in/your-profile'>click here</a>.";
        $body .= "<br/><br/>Cheers,<br/>Team WEB TALKIES</body></html>";     
        $subject = 'Samadhan Ki Samasya Contest';
    } else {
        log_me('User found, level not changed');
        // $return = array('status' => 'ERROR', 'message'=>'User email or username already exists');
        $body = '<html><body><img src="https://www.webtalkies.in/wp-content/uploads/2017/02/thank-you.jpg" /></body></html>';
        $subject = 'Thanks for participating';
    }
    // Send email irrespective of is user exists or not
    $to = $email;
    $headers = array('Content-Type: text/html; charset=UTF-8');
    log_me('Body of email is: '. $body);
    log_me('Subject of email is: '. $subject);
    wp_mail( $to, $subject, $body, $headers );
}
add_action( 'wpcf7_before_send_mail', 'wt_pre_send_contest' );

/**
 * @param $formName string
 * @param $fieldName string
 * @param $fieldValue string
 * @return bool
 */
function wt_is_already_submitted($formName, $fieldName, $fieldValue) {
    require_once(ABSPATH . 'wp-content/plugins/contact-form-7-to-database-extension/CFDBFormIterator.php');
    $exp = new CFDBFormIterator();
    $atts = array();
    $atts['show'] = $fieldName;
    $atts['filter'] = "$fieldName=$fieldValue";
    $atts['unbuffered'] = 'true';
    $exp->export($formName, $atts);
    $found = false;
    while ($row = $exp->nextRow()) {
        $found = true;
    }
    return $found;
}
 
/**
 * @param $result WPCF7_Validation
 * @param $tag array
 * @return WPCF7_Validation
 */
function wt_validate_email($result, $tag) {
    log_me('In wt_validate_email');
    $formName = 'Manoranjan Ke 100 Din_SKS'; // Production
    // $formName = 'Maid In India Campaign 1'; // In local dev
    $fieldName = 'your-email'; // Change to your form's unique field name
    $errorMessage = 'You have already entered the contest, email already registered'; // Change to your error message
    $name = $tag['name'];
    log_me('name is : '. $name);
    
    if ($name == $fieldName) {
        if (wt_is_already_submitted($formName, $fieldName, $_POST[$name])) {
            $result->invalidate($tag, $errorMessage);
            log_me('In duplicate');
            
        }
    }
    return $result;
}
 
// use the next line if your field is a **required email** field on your form
add_filter('wpcf7_validate_email*', 'wt_validate_email', 10, 2);