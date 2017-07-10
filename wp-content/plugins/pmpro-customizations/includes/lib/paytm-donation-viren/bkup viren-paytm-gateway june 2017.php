<?php
/**
 * Plugin Name: Web Talkies Paytm Integration
 * Plugin URI: http://webtalkies.in
 * Description: This plugin allows user to pay onetime via PayTM
 * Version: 1.0.1
 * Author: Virendra
 * Author URI: http://www.webtalkies.in
 * License: GPL2
  Virendra*/

//ini_set('display_errors','On');
if ( !function_exists( 'pmpro_changeMembershipLevel' ) ) { 
        require_once plugin_dir_path( __FILE__).'../paid-memberships-pro/includes/functions.php'; 
}
require_once(dirname(__FILE__) . '/encdec_paytm.php');
register_activation_hook(__FILE__, 'wtvs_paytm_activation');
//register_deactivation_hook(__FILE__, 'wtvs_paytm_deactivation');
require_once (dirname(__FILE__) . '../../../../pmpro-customizations.php');

// add_action('init', 'wtvs_paytm_donation_response');

if($_GET['donation_msg']!=''){
    add_action('the_content', 'wtvs_paytmDonationShowMessage');
}

function wtvs_paytmDonationShowMessage($content){
    return '<div class="box">'.htmlentities(urldecode($_GET['donation_msg'])).'</div>'.$content;
}
		
function wtvs_paytm_activation() {
	global $wpdb, $wp_rewrite;
	$settings = wtvs_paytm_settings_list();
	foreach ($settings as $setting) {
		add_option($setting['name'], $setting['value']);
	}
	add_option( 'paytm_donation_details_url', '', '', 'yes' );
	$post_date = date( "Y-m-d H:i:s" );
	$post_date_gmt = gmdate( "Y-m-d H:i:s" );

	$paytm_pages = array(
		'paytm-page' => array(
			'name' => 'Paytm Transaction Details page',
			'title' => 'Paytm Transaction Details page',
			'tag' => '[paytm_donation_details]',
			'option' => 'paytm_donation_details_url'
		),
	);
	
	$newpages = false;
	log_me('...About to query...');
	$paytm_page_id = $wpdb->get_var("SELECT id FROM `" . $wpdb->posts . "` WHERE `post_content` LIKE '%" . $paytm_pages['paytm-page']['tag'] . "%'	AND `post_type` != 'revision'");
	log_me($wpdb->last_query);
	log_me('This is page_id:' . $paytm_page_id);
	log_me('...Done...');

	if(empty($paytm_page_id)){
		$paytm_page_id = wp_insert_post( array(
			'post_title' 	=>	$paytm_pages['paytm-page']['title'],
			'post_type' 	=>	'page',
			'post_name'		=>	$paytm_pages['paytm-page']['name'],
			'comment_status'=>	'closed',
			'ping_status' 	=>	'closed',
			'post_content' 	=>	$paytm_pages['paytm-page']['tag'],
			'post_status' 	=>	'publish',
			'post_author' 	=>	1,
			'menu_order'	=>	0
		));
		$newpages = true;
	}
	update_option( $paytm_pages['paytm-page']['option'], _get_page_link($paytm_page_id) );
	
	unset($paytm_pages['paytm-page']);	

	
	
	$table_name = $wpdb->prefix . "paytm_donation";
    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) CHARACTER SET utf8 NOT NULL,
        `phone` varchar(255) NOT NULL,
        `email` varchar(255) NOT NULL,
				`address` varchar(255) CHARACTER SET utf8 NOT NULL,
        `city` varchar(255) CHARACTER SET utf8 NOT NULL,
        `country` varchar(255) CHARACTER SET utf8 NOT NULL,
        `state` varchar(255) CHARACTER SET utf8 NOT NULL,
        `zip` varchar(255) CHARACTER SET utf8 NOT NULL,
        `amount` varchar(255) NOT NULL,
        `comment` text NOT NULL,
        `payment_status` varchar(255) NOT NULL,
        `payment_method` varchar(255) NOT NULL,
        `date` datetime NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `id` (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta($sql);
	if($newpages){
		wp_cache_delete( 'all_page_ids', 'pages' );
		$wp_rewrite->flush_rules();
	}
}

function wtvs_paytm_donation_meta_box()
{
   $screens = array( 'paytmcheckout' );
	
   foreach ( $screens as $screen ) {
      add_meta_box(  'myplugin_sectionid', __( 'Paytm', 'myplugin_textdomain' ),'wtvs_paytm_donation_meta_box_callback', $screen, 'normal','high' );
   }
}

add_action( 'add_meta_boxes', 'wtvs_paytm_donation_meta_box' );

function wtvs_paytm_donation_meta_box_callback($post)
{

echo "admin";
}


/**
 *
 */
function wtvs_paytm_donation_response(){
	// log_me('In paytm donation response before print');
	// log_me($_POST);
	// log_me('In paytm donation response after print');
	
	if(! empty($_POST) && isset($_POST['ORDERID']))
	
	{
		global $wpdb;
		extract(
						array(
							'paytm_merchant_id' => pmpro_getOption('paytm_merchant_id'),
							'paytm_merchant_key' => pmpro_getOption('paytm_merchant_key'),
							'paytm_website' => pmpro_getOption('paytm_website'),
							'paytm_industry_type_id' => pmpro_getOption('paytm_industry_type_id'),
							'paytm_channel_id' => pmpro_getOption('paytm_channel_id'),
							'paytm_mode' => pmpro_getOption('paytm_mode'),
							'paytm_callback' => pmpro_getOption('paytm_callback'),
							'paytm_amount' => $order->InitialPayment,		
							'pautm_returnto' => pmpro_getOption('paytm_returnto')
						)
					);
					
			$current_user = wp_get_current_user();
                        $order_id = $_POST['ORDERID'];
                        $level_array = array();
			
					
		if(verifychecksum_e($_POST,$paytm_merchant_key,$_POST['CHECKSUMHASH']) === "TRUE"){
			
			if($_POST['RESPCODE'] =="01"){
			
			$level_id = $wpdb->get_var("SELECT membership_id FROM $wpdb->pmpro_membership_orders WHERE code = '" . $order_id . "' LIMIT 1" );					
		
			
			$msg= "Dear $current_user->display_name, your transaction has been successful."; // Thank you for your order. Your level id is now $level_id.";
			$msg.= 'Your order id is : '.$_POST['ORDERID'].' Please make note of it for any assistance required.';
			 
                       //change membership level
			$result = wtvs_change_membershiplevel($order_id);
                   //     log_me('THIS IS CUSTOM LOG MESSAGE FOR wtvs_change_membershiplevel STATUS');
                   //     log_me('$result');
                        
                        
                    //Update order status in database
			$wpdb->query($wpdb->prepare("UPDATE $wpdb->pmpro_membership_orders SET status = 'Success' WHERE code = %s", $order_id )); //'" . $_POST['ORDERID'] . "' LIMIT 1" ));
			

			// Send email to user with image
				$body = '<html><body><img src="https://www.webtalkies.in/wp-content/uploads/2016/10/Thank-you.jpg" /></body></html>';
				$subject = 'Successful Transaction at webtalkies.in';
				$to = $current_user->user_email;
				$headers = array('Content-Type: text/html; charset=UTF-8');
				log_me('Body of email is: '. $body);
				log_me('Subject of email is: '. $subject);
				wp_mail( $to, $subject, $body, $headers );
			}
			
			else {
					$msg= "Dear $current_user->display_name, your transaction has Failed For Reason  : "  . sanitize_text_field($_POST['RESPMSG']);
					$msg.= 'Please contact us on connect@webtalkies.in for any assistance and we shall be happy to help you.';
					$wpdb->query($wpdb->prepare("UPDATE $wpdb->pmpro_membership_orders SET status = 'Failed' WHERE code = %s", $order_id ));
				}
			
		}
		
		else {
				$msg= "Dear $current_user->display_name, a technical error aborted the order. Please try again after some time.";
				$wpdb->query($wpdb->prepare("UPDATE $wpdb->pmpro_membership_orders SET status = 'Error' WHERE code = %s", $order_id ));
			}


                $redirect_url = get_site_url() . get_permalink(get_the_ID());

		$redirect_url = add_query_arg( array('donation_msg'=> urlencode($msg)));
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

add_shortcode( 'pay_by_fortumo', 'wt_fortumo_button' );
 add_action('admin_post_paytm_donation_request','wt_fortumo_button');

function wt_fortumo_button() {
				
				$current_user = wp_get_current_user();
				$CUID = $current_user->ID;
			
                 
                               ?>       
                                                
                       <div style="text-align:left"> 
                        <a href="https://www.instamojo.com/webtalkies/u-me-aur-ghar/" rel="im-checkout" data-behaviour="remote" data-style="no-style" data-text="All Wallets, Cards & Banks"></a>
                        </div>
                        <script src="https://d2xwmjc4uy2hr5.cloudfront.net/im-embed/im-embed.min.js"></script>

                        
			 
                         <div style="text-align:left"> 
                        <a id="fmp-button" href="#" rel="0838807eeeafeb9e051161555454d402/<?php echo $CUID ?>" style="background-color:#ff0000; color:white; border:2px solid #ff0000;  padding : 8px 42px;">Pay by Mobile Carrier</a>
                        </div>
                      <script src='https://assets.fortumo.com/fmp/fortumopay.js' type='text/javascript'></script>   
							
<?php
                			return;
			 	}  
                                
                                
                                
  