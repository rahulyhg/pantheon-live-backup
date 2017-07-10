<?php
 
if ( !function_exists( 'pmpro_changeMembershipLevel' ) ) { 
        require_once plugin_dir_path( __FILE__).'../paid-memberships-pro/includes/functions.php'; 
}
require_once(dirname(__FILE__) . '/encdec_paytm.php');
require_once (dirname(__FILE__) . '../../../../pmpro-customizations.php');

add_action('init', 'wtvs_paytm_response');
 
 
if($_GET['paytm_msg']!=''){
    add_action('the_content', 'wtvs_paytmShowMessage');
}

function wtvs_paytmShowMessage($content){
    return '<div class="box">'.htmlentities(urldecode($_GET['paytm_msg'])).'</div>'.$content;
}
 
		
/**
 *
 */
function wtvs_paytm_response(){
    
         if(! empty($_POST) && isset($_POST['ORDERID']))
	
	{
		global $wpdb;
		extract(
						array(
							'paytm_merchant_id' => pmpro_getOption('custommix_merchant_id'),
							'paytm_merchant_key' => pmpro_getOption('custommix_merchant_key'),
							'paytm_website' => pmpro_getOption('custommix_website'),
							'paytm_industry_type_id' => pmpro_getOption('custommix_industry_type_id'),
							'paytm_channel_id' => pmpro_getOption('paytm_channel_id'),
							'paytm_mode' => pmpro_getOption('custommix_paytm_mode'),
							'paytm_amount' => $order->InitialPayment,
                                                        'paytm_callback' => pmpro_getOption('paytm_callback')
							
						)
					);
					
			$current_user = wp_get_current_user();
                        $data = $_POST;
                        $order_id = $_POST['ORDERID'];
                        $level_array = array();
                        
                        if ($paytm_mode != "LIVE"){
                            $paytm_merchant_key = 'TrFbxBrrs!Usy_9f';
                            $data['paytm-merchant-key'] = 'TrFbxBrrs!Usy_9f';
                        }
			
			                        
		if(verifychecksum_e($_POST,$paytm_merchant_key,$_POST['CHECKSUMHASH']) === "TRUE"){
                    
                    
			
			if($_POST['RESPCODE'] =="01"){
			
			        $return = wtvs_action_after_successful_payment($current_user->ID, $order_id);
                                $data['key-matched'] = 'YES';
                                $return['Timestamp'] = current_time('mysql');
                                $temp = wtvs_json_log (array_merge($data, $return));
                                $msg= "Dear $current_user->display_name, your transaction has been successful."; // Thank you for your order. Your level id is now $level_id.";
                                $msg.= 'Your order id is : '.$_POST['ORDERID'].' Please make note of it for any assistance required.';
		
			}
			
			else {
					$return = wtvs_action_after_failed_payment($current_user->ID, $order_id);
                                        $data['key-matched'] = 'YES';
                                        $return['Timestamp'] = current_time('mysql');
                                        $temp = wtvs_json_log (array_merge($data, $return));
                                        $msg= "Dear $current_user->display_name, your transaction has Failed For Reason  : "  . sanitize_text_field($_POST['RESPMSG']);
					$msg.= 'Please contact us on connect@webtalkies.in for any assistance and we shall be happy to help you.';
				}
			
		}
		
		else {
				$return = wtvs_report_error (03);
                                $data['key-matched'] = 'NO';
                                $return['Timestamp'] = current_time('mysql');
                                $temp = wtvs_json_log (array_merge($data, $return));
                                $msg= "Dear $current_user->display_name, a security error aborted the order. Please contact Web Talkies.";
                    }


                $redirect_url = get_site_url() . get_permalink(get_the_ID());

		$redirect_url = add_query_arg( array('paytm_msg'=> urlencode($msg)));
                
		wp_redirect( $redirect_url,301 );
		
		exit;
      
	}
	
	
}

add_filter( 'the_content', 'wtvs_process_payment_status_content' );
function wtvs_process_payment_status_content($content)
{
	$status = 'Hi';
    if (is_page( 'payment-status' ))
	{
		$status = get_query_var( 'pmt_status' );
		// if($status == '1'){
		$content = 'On Payment status' . $status;
	}
	else if(is_page('payment-status-error')){
		$content = 'Payment status failed';
	}
	return $content;
}


                                
  