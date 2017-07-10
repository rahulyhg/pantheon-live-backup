<?php
/*
Plugin Name: PMPro Customizations by Web Talkies
Plugin URI: https://www.webtalkies.in
Description: Customizations for Paid Memberships Pro
Version: .2
Author: Virendra Shahaney
Author URI: http://www.virendrashahaney.com
*/

require_once(dirname(__FILE__). "/classes/gateways/class.pmprogateway_paytm.php");
require_once(dirname(__FILE__). "/classes/gateways/class.pmprogateway_instamojo.php");
require_once(dirname(__FILE__). "/classes/gateways/class.pmprogateway_custommix.php");

//add customised shortcodes to pmpro
require_once(dirname(__FILE__). "/shortcodes/wtvs-pmpro-account.php"); //This file produces an my-account page with accurate invoice data.

//Virendra : Very important. Change Membership Level with order id. Helps change all necessary DB elements which only pmpro_changeMembershipLevel does not do. 
function  wtvs_change_membershiplevel ($order_id){
   
    global $wpdb, $current_user;
    
    $order_vobjs = $wpdb->get_results("SELECT * FROM $wpdb->pmpro_membership_orders WHERE code = '". $order_id . "' LIMIT 1" );					
		
            foreach ($order_vobjs as $order_vobj) 
            {$level_vid = $order_vobj->membership_id;}
           // echo  ' space ';
           // echo $level_vid;
           //  echo  ' space LEVEL OBJECT IS =>>> ';
             $level_vobj = pmpro_getLevel($level_vid);
            // print_r($level_vobj);
            // echo  ' space ORDER OBJECT IS =>>> ';
            // print_r($order_vobj);
             
              //create custom level to complete PMPro checkout
                    $custom_level = ['user_id' => $order_vobj->user_id,
                        'membership_id' => $order_vobj->membership_id,
                        'code_id' => '', //will support PMPro discount codes later
                        'initial_payment' => $level_vobj->initial_payment,
                        'billing_amount' => $level_vobj->billing_amount,
                        'cycle_number' => $level_vobj->cycle_number,
                        'cycle_period' => $level_vobj->cycle_period,
                        'billing_limit' => $level_vobj->billing_limit,
                        'trial_amount' => $level_vobj->trial_amount,
                        'trial_limit' => $level_vobj->trial_limit,
                        'startdate' => current_time('mysql'),
                        'enddate' => '0000-00-00 00:00:00'
                        ];
                    
                    //set enddate
		if(!empty($level_vobj->expiration_number)){
		$custom_level['enddate'] = date("Y-m-d H:m:s", strtotime("+ " . $level_vobj->expiration_number . " " . $level_vobj->expiration_period, current_time('timestamp')));
                }					
                    
             // print_r($custom_level);
                    
             $result = pmpro_changeMembershipLevel($custom_level, $custom_level['user_id']); 
         //    if (empty($result)){$result = 'No Result';} elseif($result == False) {$result = 'Change Failed @ PMPRO';} else{$result = 'Level chaned successfully.';}
            
        //If result is one get new level id of user and return it.             
        $new_level_id = $wpdb->get_var ("SELECT membership_id FROM $wpdb->pmpro_memberships_users WHERE user_id = '" . $custom_level['user_id'] . "' AND status = 'active' LIMIT 1" );
              //  echo $new_level_id; exit();
                    
                    return ($new_level_id);
            
}



//Function to send Thank you Card

function wtvs_send_thank_you_mail_to_user ($user_email)

{
       
				$body = '<html><body><img src="https://www.webtalkies.in/wp-content/uploads/2016/01/thank-you.jpg" /></body></html>';
				$subject =  'Thank you. Your transaction was successful.';
				$to = $user_email;
				$headers = array('Content-Type: text/html; charset=UTF-8');
				log_me('Body of email is: '. $body);
				log_me('Subject of email is: '. $subject);
				$mail_sent = wp_mail( $to, $subject, $body, $headers );
                                
     return $mail_sent;   
                                
}

 

//Function to return details of things done after successful payment from any gateway.

function wtvs_action_after_successful_payment ($user_id, $order_id)

{
    global $wpdb;
    
   //set return variable.
        $return = array();

if(!empty($user_id) && !empty($order_id)){

//Get currrent user by user_id
        $current_user = get_user_by('ID', $user_id);

//Retrieve exising level id of the user
        $original_level_id = $wpdb->get_var ("SELECT membership_id FROM $wpdb->pmpro_memberships_users WHERE user_id = '$user_id' AND status = 'active' LIMIT 1" );

//Retrieve desired level id by order id
 $desired_level_id = $wpdb->get_var("SELECT membership_id FROM $wpdb->pmpro_membership_orders WHERE code = '$order_id' LIMIT 1" );
 
         
//Before going to change membership, Ensure that the order was for a different membership level than existing.
        if ($desired_level_id == $original_level_id){$return = wtvs_report_error(04);}

//Change Membership by Order Id and check if it changed
        $new_level_id = wtvs_change_membershiplevel($order_id);
        if ($new_level_id == $desired_level_id) {$result = 'Yes';} else {$result = 'No';}
        
//Update order status in database
        $sql= $wpdb->query($wpdb->prepare("UPDATE $wpdb->pmpro_membership_orders SET status = 'Success' WHERE code = %s", $order_id )); //'" . $_POST['ORDERID'] . "' LIMIT 1" ));
        
// Send mail to user
        $mail_sent = wtvs_send_thank_you_mail_to_user ($current_user->user_email);
        
//Final result output
$return = array('Order Status Updated to Success?' => $sql, 'Membership Level Changed?' => $result, 'Order ID' => $order_id, 'User Email' => $current_user->user_email, 'Original User Level' => $original_level_id, 'Desired User Level' => $desired_level_id, 'New User Level' => $new_level_id, 'Mail sent to user?' => $mail_sent);
   	        
}
        
        return $return;
        
}

//Function to return details of things done after failed payment from any gateway.

function wtvs_action_after_failed_payment ($user_id, $order_id)

{
    global $wpdb;
    
    //set return variable.
        $return = array();

if(!empty($user_id) && !empty($order_id)){

//Get currrent user by user_id
        $current_user = get_user_by('ID', $user_id);

//Retrieve exising level id of the user
        $original_level_id = $wpdb->get_var ("SELECT membership_id FROM $wpdb->pmpro_memberships_users WHERE user_id = '" . $user_id . "' AND status = 'active' LIMIT 1" );
                
//Retrieve desired level id by order id
        $desired_level_id = $wpdb->get_var ("SELECT membership_id FROM $wpdb->pmpro_membership_orders WHERE code = '" . $order_id . "' LIMIT 1" );
              
// DEBUG : echo $order_id.'gap'; echo $new_level_id; echo 'gap'.$current_level_id; echo 'gap'.$current_user->user_email; exit();
        
//DO NOT Change Membership       

//Update order status in database
        $sql= $wpdb->query($wpdb->prepare("UPDATE $wpdb->pmpro_membership_orders SET status = 'Failed' WHERE code = %s", $order_id )); //'" . $_POST['ORDERID'] . "' LIMIT 1" ));
	$return = array('Order Status Set to Failed' => $sql, 'Member Level Changed?' => 'NO', 'order ID' => $order_id, 'User E Mail' => $current_user->user_email);
   	// echo $sql; 
        
// DO NOT Send mail to user
        
        return $return;
        
}

}


//List of All errors that can be met during post payment process.

function wtvs_report_error ($error_code)

{
   //Set return variable for error 
    $return = array();
    
    switch ($error_code) {
    case "01":
        $return = array('wt-error' => 'Empty Webhook Response', 'message' => 'Please check gateway company records.');
   	break;
    case "02":
        $return = array('wt-error' => 'Database Error', 'message' => 'Database interaction did not happen.');
        break;
    case "03":
        $return = array('wt-error' => 'Mac/Hash/Key Mismatch', 'message' => 'Order was not be updated in Database.');
        break;
    case "04":
        $return = array('wt-error' => 'Operation Error', 'message' => 'User already has desired Membership.');
        break;
    case "05":
        $return = array('wt-error' => 'Unknown IP Error', 'message' => 'Order was not be updated in Database.');
        break;
     default:
        $return = array('wt-error' => 'Unknown Error', 'message' => 'Order was not be updated in Database.');
    }
    
    return $return;
         
}

// Put transaction status data and Return array in admin mailbox

function wtvs_json_log ($text){
 
//convert $text array into a string to send in mail body
    $body = '';
    foreach ($text as $k=>$v) {
    
        $body .= " [";
        $body .= "$k=$v";
        $body .= "]";
      
    }
    
    // send email to admin.
   $mail_sent = wp_mail('support@webtalkies.in', 'Web Talkies New Order details', $body, array('Content-Type: text/html; charset=UTF-8'));
   
   // return if mail sent otherwise write to log file.
  if ($mail_sent) {return;}
    // else write to log file
    else {
         $myfile = fopen ("./wp-content/uploads/webtalkies_json_log.txt", "a");
         fwrite($myfile, print_r($text, true));
         fclose($myfile);
         return;
    }   

}



//Function to check instamojos mac/secret salt.  NOT AN ENDPOINT - IT IS CALLED FROM ENDPOINT FUNCTIONS.      
function wtvs_check_instamojo_mac($data){
            
$mac_provided = $data['mac'];  // Get the MAC from the POST data
unset($data['mac']);  // Remove the MAC key from the data.
    
$ver = explode('.', phpversion());
$major = (int) $ver[0];
$minor = (int) $ver[1];
if($major >= 5 and $minor >= 4){ksort($data, SORT_STRING | SORT_FLAG_CASE);}
else{uksort($data, 'strcasecmp');}
// You can get the 'salt' from Instamojo's developers page(make sure to log in first): https://www.instamojo.com/developers
// Pass the 'salt' without <>
$mac_calculated_live = hash_hmac("sha1", implode("|", $data), "92e09180be7248e5bc823603ab6a95e6");
$mac_calculated_test = hash_hmac("sha1", implode("|", $data), "76f25113f9f849b5a2614c8ffb66e0ba");

// set new variable $data['key-matched'] into data arrray for later use. Never use this before hash-hmac
$data['key-matched'] = 'Technical Failure'; // set the status of this function result.

 if($mac_provided == $mac_calculated_live){$data['key-matched'] = 'Yes'; $data['gateway-mode'] = 'LIVE'; return $data;}
 elseif ($mac_provided == $mac_calculated_test){$data['key-matched'] = 'Yes'; $data['gateway-mode'] = 'TEST'; return $data;}
 else { $data['key-matched'] = 'NO'; $data['gateway-mode'] = 'Not-Specified'; return $data; }
   
   }
   
   // function to check signature of fortumo response
   function wtvs_check_fortumo_sig($params_array, $secret) {
    ksort($params_array);

    $str = '';
    foreach ($params_array as $k=>$v) {
      if($k != 'sig') {
        $str .= "$k=$v";
      }
    }
    $str .= $secret;
    $signature = md5($str);

    return ($params_array['sig'] == $signature);
  }
   
   
   
    