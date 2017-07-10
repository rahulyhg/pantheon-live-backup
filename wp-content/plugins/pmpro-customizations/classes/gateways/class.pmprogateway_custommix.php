<?php
//custommix gateway integration pmprogateway added by virendra
//Plugin : Paytm integration based on custommix donate

	require_once(PMPRO_DIR. "/classes/gateways/class.pmprogateway.php");
	require_once(dirname(__FILE__) . "/../../includes/lib/paytm-donation-viren/encdec_paytm.php");
	require_once(dirname(__FILE__) . "/../../includes/lib/paytm-donation-viren/viren-paytm-gateway.php");

    //load classes init method
    add_action('init', array('PMProGateway_custommix', 'init'));


    /**
     * PMProGateway_gatewayname Class
     *
     * Handles example integration.
     *
     */
    class PMProGateway_custommix extends PMProGateway
    {
        function PMProGateway($gateway = NULL)
        {
            if (!class_exists("custommix"))
                require_once(dirname(__FILE__) . "/../../includes/lib/paytm-donation-viren/viren-paytm-gateway.php");

            //set API connection vars for Paytm
            custommix::custommix_merchant_id(pmpro_getOption('custommix_merchant_id'));
            custommix::custommix_merchant_key(pmpro_getOption('custommix_merchant_key'));
            custommix::custommix_industry_type_id(pmpro_getOption('custommix_industry_type_id'));
            custommix::custommix_website(pmpro_getOption('custommix_website'));
            custommix::custommix_channel_id(pmpro_getOption('custommix_channel_id'));
            custommix::custommix_paytm_mode(pmpro_getOption('custommix__paytm_mode'));
            custommix::custommix_amount(pmpro_getOption('custommix_amount'));
            custommix::custommix_paytm_callback_url(pmpro_getOption('custommix_paytm_callback_url'));
            custommix::$verifySSL = false;
            //set API connection vars for INstamojo
            custommix::custommix_api_key(pmpro_getOption('custommix_api_key'));
            custommix::custommix_auth_token(pmpro_getOption('custommix_auth_token'));
            custommix::custommix_redirect_url(pmpro_getOption('custommix_redirect_url'));
            custommix::custommix_instamojo_webhook(pmpro_getOption('custommix_instamojo_webhook'));
            custommix::custommix_instamojo_mode(pmpro_getOption('custommix_instamojo_mode'));
            //set API connection vars for Fortumo
            custommix::custommix_fortumo_service_id(pmpro_getOption('custommix_fortumo_service_id'));
            custommix::custommix_fortumo_secret(pmpro_getOption('custommix_fortumo_secret'));
            custommix::custommix_fortumo_callback_url(pmpro_getOption('custommix_fortumo_callback_url'));
            custommix::custommix_fortumo_webhook(pmpro_getOption('custommix_fortumo_webhook'));
            custommix::custommix_fortumo_mode(pmpro_getOption('custommix_fortumo_mode'));


            $this->gateway = $gateway;
            return $this->gateway;
        }

        /**
         * Run on WP init
         *
         * @since 1.8
         */
        static function init()
        {
            //make sure custommix                                                          is a gateway option
            add_filter('pmpro_gateways', array('PMProGateway_custommix', 'pmpro_gateways'));

            //add fields to payment settings
            add_filter('pmpro_payment_options', array('PMProGateway_custommix', 'pmpro_payment_options'));
            add_filter('pmpro_payment_option_fields', array('PMProGateway_custommix', 'pmpro_payment_option_fields'), 10, 2);

            //add some fields to edit user page (Updates)
            //following w2 lines are in w2checkout - virendra but are in example
          //  add_action('pmpro_after_membership_level_profile_fields', array('PMProGateway_custommix', 'user_profile_fields'));
          //  add_action('profile_update', array('PMProGateway_custommix', 'user_profile_fields_save'));

            //updates cron NOY IN w2CHECKOUT - virendra retained from example
            add_action('pmpro_activation', array('PMProGateway_custommix', 'pmpro_activation'));
            add_action('pmpro_deactivation', array('PMProGateway_custommix', 'pmpro_deactivation'));
            add_action('pmpro_cron_custommix_subscription_updates', array('PMProGateway_custommix', 'pmpro_cron_custommix_subscription_updates'));

            //code to add at checkout if custommix is the current gateway VIRENDRA THIE FOLLOWING LINE IS DIFF IN EXAMPLE AND w2CHECKOUT
            $gateway = pmpro_getOption("gateway");
            if ($gateway == "custommix") {
                //add_action('pmpro_checkout_preheader', array('PMProGateway_custommix', 'pmpro_checkout_preheader'));
                //add_filter('pmpro_checkout_order', array('PMProGateway_custommix', 'pmpro_checkout_order'));
                //add_filter('pmpro_include_billing_address_fields', array('PMProGateway_custommix', 'pmpro_include_billing_address_fields'));
                //add_filter('pmpro_include_cardtype_field', array('PMProGateway_custommix', 'pmpro_include_billing_address_fields'));
                //add_filter('pmpro_include_payment_information_fields', array('PMProGateway_custommix', 'pmpro_include_payment_information_fields'));

                add_filter('pmpro_include_billing_address_fields', '__return_false');
                add_filter('pmpro_include_payment_information_fields', '__return_false');
                add_filter('pmpro_required_billing_fields', '__return_empty_array');
                add_filter('pmpro_checkout_default_submit_button', array('PMProGateway_custommix', 'pmpro_checkout_default_submit_button'));
                add_filter('pmpro_checkout_before_change_membership_level', array('PMProGateway_custommix', 'pmpro_checkout_before_change_membership_level'), 10, 2);
            }
        }

        /**
         * Make sure custommix is in the gateways list
         *
         * @since 1.8
         */
        static function pmpro_gateways($gateways)
        {
            if (empty($gateways['custommix']))
                $gateways['custommix'] = __('custommix', 'pmpro');

            return $gateways;
        }


        /**
         * Get a list of payment options that custommix gateway needs/supports.
         */

        static function getGatewayOptions()
        {
            $options = array(
                'sslseal',
                'nuclear_HTTPS',
                'gateway_environment',
                'currency',
                'custommix_merchant_id',
                'custommix_merchant_key',
                'custommix_website',
                'custommix_industry_type_id',
                'custommix_channel_id',
                'custommix_paytm_mode',
                'custommix_amount',
                'custommix_paytm_callback_url',
                'custommix_api_key',
                'custommix_auth_token',
                'custommix_instamojo_webhook',
                'custommix_redirect_url',
                'custommix_instamojo_mode',
                'custommix_fortumo_service_id',
                'custommix_fortumo_secret',
                'custommix_fortumo_callback_url',
                'custommix_fortumo_webhook',
                'custommix_fortumo_mode',
                'use_ssl',
                'tax_state',
                'tax_rate',
                'accepted_credit_cards'
            );

            return $options;
        }


        /*
         * Set payment options for payment settings page.
         *
         * @since 1.8
         I*/

        static function pmpro_payment_options($options)
        {
            //get custommix options
            $custommix_options = PMProGateway_custommix::getGatewayOptions();

            //merge with others.
            $options = array_merge($custommix_options, $options);

            return $options;
        }


        /*
        * Display fields for custommix options.
         */

        static function pmpro_payment_option_fields($values, $gateway)
        {
            ?>
            <tr class="pmpro_settings_divider gateway gateway_custommix"
                <?php if ($gateway != "custommix") { ?>style="display: none;"<?php } ?>>
                <td colspan="2">
                    <?php _e('Paytm Settings', 'pmpro'); ?>
                </td>
            </tr>
            <tr class="gateway gateway_custommix" <?php if ($gateway != "custommix") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="custommix_merchant_id"><?php _e('PAYTM MERCHANT ID', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" id="custommix_merchant_id" name="custommix_merchant_id" size="60"
                           value="<?php echo esc_attr($values['custommix_merchant_id']) ?>"/>
                    <br/>
                    <small><?php _e('Merchant id from Paytm'); ?></small>
                </td>
            </tr>
            <tr class="gateway gateway_custommix" <?php if ($gateway != "custommix") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="custommix_merchant_key"><?php _e('PAYTM MERCHANT KEY', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" id="custommix_merchant_key" name="custommix_merchant_key" size="60"
                           value="<?php echo esc_attr($values['custommix_merchant_key']) ?>"/>
                    <br/>
                    <small><?php _e('Merchant Key from Paytm'); ?></small>
                </td>
            </tr>
            <tr class="gateway gateway_custommix" <?php if ($gateway != "custommix") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="custommix_website"><?php _e('PAYTM WEBSITE', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" name="custommix_website" size="60" value="<?php echo $values['custommix_website'] ?>"/>
                    <br/>
                    <small><?php _e('Website given by Paytm'); ?></small>
                </td>
            </tr>
            <tr class="gateway gateway_custommix" <?php if ($gateway != "custommix") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="custommix_industry_type_id"><?php _e('PAYTM INDUSTRY ID', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" name="custommix_industry_type_id" size="60"
                           value="<?php echo $values['custommix_industry_type_id'] ?>"/>
                    <br/>
                    <small><?php _e('Industy Type id provided by Paytm'); ?></small>
                </td>
            </tr>
            <tr class="gateway gateway_custommix" <?php if ($gateway != "custommix") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="custommix_channel_id"><?php _e('PAYTM CHANNEL ID', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" name="custommix_channel_id" size="60"
                           value="<?php echo $values['custommix_channel_id'] ?>"/>
                    <br/>
                    <small><?php _e('Channel_id by Paytm'); ?></small>
                </td>
            </tr>
            <tr class="gateway gateway_custommix" <?php if ($gateway != "custommix") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="custommix_paytm_mode"><?php _e('PAYTM MODE(Live/Test)', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" name="custommix_paytm_mode" size="60" value="<?php echo $values['custommix_paytm_mode'] ?>"/>
                    <br/>
                    <small><?php _e('write LIVE or TEST case-sensitive'); ?></small>
                </td>
            </tr>
            <tr class="gateway gateway_custommix" <?php if ($gateway != "custommix") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="custommix_amount"><?php _e('PAYTM Default Amount', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" name="custommix_amount" size="60" value="<?php echo $values['custommix_amount'] ?>"/>
                    <br/>
                    <small><?php _e('Default Amount is depricated item on web Talkies. Leave Empty.'); ?></small>
                </td>
            </tr>
            <tr class="gateway gateway_custommix" <?php if ($gateway != "custommix") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="custommix_paytm_callback_url"><?php _e('PAYTM CALLBACK URL', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" name="custommix_paytm_callback_url" size="60" value="<?php echo $values['custommix_paytm_callback_url'] ?>"/>
                    <br/>
                    <small><?php _e('Return URL where you want to take users after paytm payment'); ?></small>
                </td>
            </tr>
             <tr class="pmpro_settings_divider gateway gateway_custommix"
                <?php if ($gateway != "custommix") { ?>style="display: none;"<?php } ?>>
                <td colspan="2">
                    <?php _e('Instamojo Settings', 'pmpro'); ?>
                </td>
            </tr>
            
            <tr class="gateway gateway_custommix" <?php if ($gateway != "custommix") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="custommix_api_key"><?php _e('INSTAMOJO API KEY', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" id="custommix_api_key" name="custommix_api_key" size="60"
                           value="<?php echo esc_attr($values['custommix_api_key']) ?>"/>
                    <br/>
                    <small><?php _e('API Key from Instamojo'); ?></small>
                </td>
            </tr>
            <tr class="gateway gateway_custommix" <?php if ($gateway != "custommix") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="custommix_auth_token"><?php _e('INSTAMOJO AUTH TOKEN', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" id="custommix_auth_token" name="custommix_auth_token" size="60"
                           value="<?php echo esc_attr($values['custommix_auth_token']) ?>"/>
                    <br/>
                    <small><?php _e('AUTH TOKEN FROM INSTAMOJO'); ?></small>
                </td>
            </tr>
             <tr class="gateway gateway_custommix" <?php if ($gateway != "custommix") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="custommix_instamojo_mode"><?php _e('INSTAMOJO MODE(Live/Test)', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" name="custommix_instamojo_mode" size="60" value="<?php echo $values['custommix_instamojo_mode'] ?>"/>
                    <br/>
                    <small><?php _e('Write either LIVE or TEST (case-sensitive)'); ?></small>
                </td>
            </tr>
            <tr class="gateway gateway_custommix" <?php if ($gateway != "custommix") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="custommix_instamojo_webhook"><?php _e('WEBHOOK FOR INSTAMOJO', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" name="custommix_instamojo_webhook" size="60" value="<?php echo $values['custommix_instamojo_webhook'] ?>"/>
                    <br/>
                    <small><?php _e('WEBHOOK ENDPOINT FOR INSTAMOJO'); ?></small>
                </td>
            </tr>
            <tr class="gateway gateway_custommix" <?php if ($gateway != "custommix") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="custommix_redirect_url"><?php _e('INSTAMOJO REDIRECT URL', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" name="custommix_redirect_url" size="60"
                           value="<?php echo $values['custommix_redirect_url'] ?>"/>
                    <br/>
                    <small><?php _e('Desired Redirect URL for Instamojo users'); ?></small>
                </td>
            </tr>
             <tr class="pmpro_settings_divider gateway gateway_custommix"
                <?php if ($gateway != "custommix") { ?>style="display: none;"<?php } ?>>
                <td colspan="2">
                    <?php _e('Fortumo Settings', 'pmpro'); ?>
                </td>
            </tr>
             <tr class="gateway gateway_custommix" <?php if ($gateway != "custommix") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="custommix_fortumo_service_id"><?php _e('FORTUMO SERVICE ID', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" name="custommix_fortumo_service_id" size="60"
                           value="<?php echo $values['custommix_fortumo_service_id'] ?>"/>
                    <br/>
                    <small><?php _e('Multiple Service Ids seperated by comma preceded by level e.g. membershiplevel,serviceid IMPORTANT NO SPACES'); ?></small>
                </td>
            </tr>
            <tr class="gateway gateway_custommix" <?php if ($gateway != "custommix") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="custommix_fortumo_secret"><?php _e('FORTUMO SECRET', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" name="custommix_fortumo_secret" size="60"
                           value="<?php echo $values['custommix_fortumo_secret'] ?>"/>
                    <br/>
                    <small><?php _e('Secret give by Fortumo preceded by respective level e.g. levelid,secret NO SPACES'); ?></small>
                </td>
            </tr>
                <tr class="gateway gateway_custommix" <?php if ($gateway != "custommix") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="custommix_fortumo_callback_url"><?php _e('FORTUMO REDIRECT URL', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" name="custommix_fortumo_callback_url" size="60"
                           value="<?php echo $values['custommix_fortumo_callback_url'] ?>"/>
                    <br/>
                    <small><?php _e('Where do you want fortumo users to go after payment?'); ?></small>
                </td>
            </tr>
                 <tr class="gateway gateway_custommix" <?php if ($gateway != "custommix") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="custommix_fortumo_webhook"><?php _e('FORTUMO WEBHOOK', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" name="custommix_fortumo_webhook" size="60"
                           value="<?php echo $values['custommix_fortumo_webhook'] ?>"/>
                    <br/>
                    <small><?php _e('WEBHOOK GIVEN TO FORTUMO'); ?></small>
                </td>
            </tr>
                 <tr class="gateway gateway_custommix" <?php if ($gateway != "custommix") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="custommix_fortumo_mode"><?php _e('FORTUMO MODE (Live/Test)', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" name="custommix_fortumo_mode" size="60"
                           value="<?php echo $values['custommix_fortumo_mode'] ?>"/>
                    <br/>
                    <small><?php _e('Write LIVE, or write TEST-OK to test successful trxn, leave blank for failed test.'); ?></small>
                </td>
            </tr>
                
                
                <?php
        }

        /**
         * Remove required billing fields
         */
        static function pmpro_required_billing_fields($fields)
        {
            return array();
        }

        /**
         * Swap in PAYTM submit buttons.
         */
        static function pmpro_checkout_default_submit_button($show)
        {
            global $gateway, $pmpro_requirebilling;

            //show our submit buttons
            ?>
            <span id="pmpro_submit_span">
				<input type="hidden" name="submit-checkout" value="1"/>
				<input type="submit" class="pmpro_btn pmpro_btn-submit-checkout"
                       value="<?php if ($pmpro_requirebilling) {
                           _e('Proceed to Pay', 'pmpro');
                       } else {
                           _e('Submit and Confirm', 'pmpro');
                       } ?> &raquo;"/>
			</span>
            <?php
            //don't show the default
            return false;
        }

        /**
         * Instead of change membership levels, send users to gateway to pay.
         */

        static function pmpro_checkout_before_change_membership_level($user_id, $morder)
        {
            global $wpdb, $discount_code_id;

            //if no order, no need to pay
            if (empty($morder))
                return;

            $morder->user_id = $user_id;
            $morder->saveOrder();

            //save discount code use
            if (!empty($discount_code_id))
                $wpdb->query("INSERT INTO $wpdb->pmpro_discount_codes_uses (code_id, user_id, order_id, timestamp) VALUES('" . $discount_code_id . "', '" . $user_id . "', '" . $morder->id . "', now())");

            do_action("pmpro_checkout_before_processing", $user_id, $morder);

            $morder->Gateway->pmpro_checkout_order_fork($morder); //sendTocustommix($morder);
           
        }
        
        
        //Decide which gateway to send user to, which function to run -- depending on radio button selection.
        function pmpro_checkout_order_fork(&$order)
        {
            $gateway_selection = $_POST;
            
           if (isset($gateway_selection)){
             if ($gateway_selection ['my_choice'] === 'instamojo'){$gateway_function = $this->pmpro_checkout_order_instamojo($order);}
             elseif ($gateway_selection['my_choice'] === 'paytm'){$gateway_function = $this->pmpro_checkout_order_paytm($order);}
             elseif ($gateway_selection['my_choice'] === 'fortumo'){$gateway_function = $this->pmpro_checkout_order_fortumo($order);}
            else {echo 'Oops, a rare system error occurred. Please go back to ' ?><a href = "https://www.webtalkies.in/membership-account/">Your Account Page.</a><?php ; exit();}
        }
        else {echo 'Oops, your choice was not registered by the system. Please go back to '?><a href = "https://www.webtalkies.in/membership-account/">Your Account Page.</a><?php ; exit();}
        }

        //Send user to Paytm
        public function pmpro_checkout_order_paytm(&$order) //sendTocustommix(&$order)
        {

            global $wpdb, $pmpro_currency;

            extract(
                array(
                    'custommix_merchant_id' => pmpro_getOption('custommix_merchant_id'),
                    'custommix_merchant_key' => pmpro_getOption('custommix_merchant_key'),
                    'custommix_website' => pmpro_getOption('custommix_website'),
                    'custommix_industry_type_id' => pmpro_getOption('custommix_industry_type_id'),
                    'custommix_channel_id' => pmpro_getOption('custommix_channel_id'),
                    'custommix_paytm_mode' => pmpro_getOption('custommix_paytm_mode'),
                    'custommix_amount' => $order->InitialPayment,
                    'custommix_paytm_callback_url' => pmpro_getOption('custommix_paytm_callback_url')
                    
                )
            );
            
            //taxes on initial amount
            $initial_payment = $order->InitialPayment;
            $initial_payment_tax = $order->getTaxForPrice($initial_payment);
            $initial_payment = round((float)$initial_payment + (float)$initial_payment_tax, 2);

            //taxes on the amount (NOT CURRENTLY USED)
            $amount = $order->PaymentAmount;
            $amount_tax = $order->getTaxForPrice($amount);
            $amount = round((float)$amount + (float)$amount_tax, 2);

            //Essential user data
            $current_user = wp_get_current_user();
            $donor_name = $current_user->user_lastname;
            $donor_amount = $order->InitialPayment;
            $donor_email = $current_user->user_email;
            $order_id = $order->code;
            $order_for_level = $order->membership_id;
            
            //Set paytm url and post parameters in case of LIVE or TEST
            if ($custommix_paytm_mode == 'LIVE') {
                $custommix_action_url = "https://secure.paytm.in/oltp-web/processTransaction?orderid=$order_id";
                
            } else {
                $custommix_action_url = "https://pguat.paytm.com/oltp-web/processTransaction?orderid=$order_id";
                $custommix_merchant_key = 'bKMfNxPPf_QdZppa';
                $custommix_merchant_id = 'DIY12386817555501617';
                $custommix_website = 'DIYtestingweb';
                $custommix_channel_id = 'WEB/WAP';
		$custommix_industry_type_id = 'Retail';
                        
            }
            
               $post_params = array(
                'MID' => $custommix_merchant_id,
                'ORDER_ID' => $order_id,
                'WEBSITE' => $custommix_website,
                'CHANNEL_ID' => $custommix_channel_id,
                'INDUSTRY_TYPE_ID' => $custommix_industry_type_id,
                'TXN_AMOUNT' => $custommix_amount,
                'CUST_ID' => $donor_email,
                'EMAIL' => $donor_email,
            );
                        
            

            //Set order status as Pending in Database         
             $wpdb->query($wpdb->prepare("UPDATE $wpdb->pmpro_membership_orders SET status = 'Pending', gateway = 'PayTm' WHERE code = %s", $post_params["ORDER_ID"]));

            
                $post_params["CALLBACK_URL"] = get_site_url().$custommix_paytm_callback_url;
                $call = get_site_url().$custommix_paytm_callback_url;
                
                $checkSum = getChecksumFromArray($post_params, $custommix_merchant_key);

            //Append key paramters to custommix url and turn it into html
            $html = <<<EOF
					
						<center><h1>You are being redirected to PayTm payment dashboard.</br>Please do not refresh this page...</h1></center>
							
							<form method="post" action="$custommix_action_url" name="f1">
							<table border="1">
								<tbody>
									<input type="hidden" name="MID" value="$custommix_merchant_id">
									<input type="hidden" name="WEBSITE" value="$custommix_website">
									<input type="hidden" name="CHANNEL_ID" value="$custommix_channel_id">
									<input type="hidden" name="ORDER_ID" value="$order_id">
									<input type="hidden" name="INDUSTRY_TYPE_ID" value="$custommix_industry_type_id">									
									<input type="hidden" name="TXN_AMOUNT" value="$donor_amount">
									<input type="hidden" name="CUST_ID" value="$donor_email">
									<input type="hidden" name="EMAIL" value="$donor_email">
									<input type="hidden" name="CALLBACK_URL" value="$call">
									<input type="hidden" name="CHECKSUMHASH" value="$checkSum">
								</tbody>
							</table>
							<script type="text/javascript">document.f1.submit();</script> 
						</form>
EOF;
            //redirect to custommix
            echo $html;
            exit;
        }


 public function pmpro_checkout_order_instamojo(&$order) //sendTocustommix(&$order)
        {

            global $wpdb, $pmpro_currency;

            extract(
                array(
                    'custommix_api_key' => pmpro_getOption('custommix_api_key'),
                    'custommix_auth_token' => pmpro_getOption('custommix_auth_token'),
                    'custommix_instamojo_webhook' => pmpro_getOption('custommix_instamojo_webhook'),
                    'custommix_instamojo_mode' => pmpro_getOption('custommix_instamojo_mode'),
                    'custommix_amount' => $order->InitialPayment,
                     'custommix_redirect_url' => pmpro_getOption('custommix_redirect_url')
                )
            );  
            
  //  echo 'I arrived at instamojo'; exit();
            
            //taxes on initial amount
            $initial_payment = $order->InitialPayment;
            $initial_payment_tax = $order->getTaxForPrice($initial_payment);
            $initial_payment = round((float)$initial_payment + (float)$initial_payment_tax, 2);

            //taxes on the amount (NOT CURRENTLY USED)
            $amount = $order->PaymentAmount;
            $amount_tax = $order->getTaxForPrice($amount);
            $amount = round((float)$amount + (float)$amount_tax, 2);
                                 
            $current_user = wp_get_current_user();
            $buyer_name = $current_user->user_login;
            $buyer_email = $current_user->user_email;
            $webhook = get_site_url().$custommix_instamojo_webhook;
            $redirect_url = get_site_url().$custommix_redirect_url;
            $purpose = $order->code;
            $custommix_amount = $custommix_amount;
            
            if($custommix_instamojo_mode === 'LIVE'){$instaurl = 'https://www.instamojo.com/api/1.1/payment-requests/';}
            else {$instaurl = 'https://test.instamojo.com/api/1.1/payment-requests/'; $custommix_api_key = 'd8b83ae78f64b1de303a5a77392e767c'; $custommix_auth_token = 'a4f88bee53cff797dd21544aeacb7d5c';};
             
           $wpdb->query($wpdb->prepare("UPDATE $wpdb->pmpro_membership_orders SET status = 'Pending', gateway = 'Instamojo' WHERE code = %s", $purpose ));
           
                      
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $instaurl,
  CURLOPT_SSL_VERIFYHOST => is_ssl(),
  CURLOPT_SSL_VERIFYPEER => is_ssl(),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"purpose\"\r\n\r\n$purpose\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"buyer_name\"\r\n\r\n$buyer_name\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"email\"\r\n\r\n$buyer_email\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"amount\"\r\n\r\n$custommix_amount\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"webhook\"\r\n\r\n$webhook\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"redirect_url\"\r\n\r\n$redirect_url\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
   // "postman-token: 45f884fe-daba-bfc7-aa0d-3f523f6fa95a",
    "x-api-key: $custommix_api_key",
    "x-auth-token: $custommix_auth_token"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
    $response_array = json_decode($response, True);
   $longurl = $response_array['payment_request']['longurl'];
   
   }
    
$html = <<<EOF
 <form method="GET" action="$longurl" name="f1">
       
      <script type="text/javascript">document.f1.submit();</script> 
   
   </form>
EOF;
            //redirect to custommix
            echo $html;
            exit();
            
            
}

//Send user to fortumo
        public function pmpro_checkout_order_fortumo(&$order) //sendTocustommix(&$order)
        {

            global $wpdb, $pmpro_currency;

           extract(
                array(
                    'custommix_fortumo_service_id' => pmpro_getOption('custommix_fortumo_service_id'),
                    'custommix_fortumo_secret' => pmpro_getOption('custommix_fortumo_secret'),
                    'custommix_fortumo_webhook' => pmpro_getOption('custommix_fortumo_webhook'),
                    'custommix_fortumo_mode' => pmpro_getOption('custommix_fortumo_mode'),
                    'custommix_amount' => $order->InitialPayment,
                    'custommix_fortumo_callback_url' => pmpro_getOption('custommix_fortumo_callback_url')
                )
            );
           
          //taxes on initial amount
            $initial_payment = $order->InitialPayment;
            $initial_payment_tax = $order->getTaxForPrice($initial_payment);
            $initial_payment = round((float)$initial_payment + (float)$initial_payment_tax, 2);

            //taxes on the amount (NOT CURRENTLY USED)
            $amount = $order->PaymentAmount;
            $amount_tax = $order->getTaxForPrice($amount);
            $amount = round((float)$amount + (float)$amount_tax, 2);

            //Current User Data to send
            $current_user = wp_get_current_user();
            $customer_name = $current_user->user_lastname;
            $order_amount = $order->InitialPayment;
            $customer_email = $current_user->user_email;
            $order_id = $order->code;
            $user_id = $current_user->ID;
            $CUID = $user_id.'@'.$order_id;
            $credit_name = 'Membership-level:'.$order->membership_id;
            $order_level = $order->membership_id;
            
            //Set Service Id and Secret as per Membership Level Desired
            
            //Step.1 Get options of commaseperated values of service Id and Secret and explode to get service Id and Secret as per the order level.
            $custommix_service_id_level_combo_array = explode (',', $custommix_fortumo_service_id); // (You get array of values like 1, serviceId where 1 is level and service id is service id.)
            $custommix_secret_level_combo_array = explode (',', $custommix_fortumo_secret); // (You get array of values like 1, serviceId where 1 is level and service id is service id.)
            
            //Extract particular level
            $i=0;
            
            foreach ($custommix_service_id_level_combo_array as $custommix_service_id_level){
                
                if ($custommix_service_id_level == $order_level){$custommix_fortumo_service_id = $custommix_service_id_level_combo_array[$i+1]; $custommix_fortumo_secret = $custommix_secret_level_combo_array[$i+1]; break;} 
                $i++;
            }
            
                        
            //Create parameters array to send to checksum. cuid is concated by joining user_id and order_id so both can be exploded and used in fortumo webhook.
               $fortumo_params = array(
                'cuid' => $CUID,
                'operation_reference' => $order_id,
                'credit_name' => $credit_name,
                'price' => $custommix_amount,
                'currency' => 'INR',
                'test' => 'ok',
                'callback_url' => $custommix_fortumo_callback_url,
                
            );
               
           //set database order status to Pending and Gateway to fortumo         
             $wpdb->query($wpdb->prepare("UPDATE $wpdb->pmpro_membership_orders SET status = 'Pending', gateway = 'Fortumo' WHERE code = %s", $order_id));

               $checkSum = getChecksumFromArray($fortumo_params, $custommix_fortumo_secret);
               
               $custommix_action_url = "http://pay.fortumo.com/mobile_payments/".$custommix_fortumo_service_id."?";
               
               //VIRENDRA: Three way Fork for LIVE OR TEST ENVIRONMENT
               
               
               
               if ($custommix_fortumo_mode === 'LIVE')
               
               {              
           $html = <<<EOF
					
						<center><h1>You are being redirected to Fortumo payment dashboard.</br>Please do not refresh this page...</h1></center>
							
							<form method="post" action="$custommix_action_url" name="f1">
							<table border="1">
								<tbody>
									<input type="hidden" name="cuid" value="$CUID">
									<input type="hidden" name="operation_reference" value="$order_id">
									<input type="hidden" name="credit_name" value="$credit_name">
									<input type="hidden" name="price" value="$custommix_amount">
									<input type="hidden" name="currency" value="INR">									
									<input type="hidden" name="callback_url" value="$custommix_fortumo_callback_url">
									<input type="hidden" name="sig" value="$checkSum">
								</tbody>
							</table>
							<script type="text/javascript">document.f1.submit();</script> 
						</form>
EOF;
               }
               

elseif ($custommix_fortumo_mode === 'TEST-OK') 
    
{
    
    $html = <<<EOF
					
						<center><h1>You are being redirected to Fortumo payment dashboard.</br>Please do not refresh this page...</h1></center>
							
							<form method="POST" action="$custommix_action_url" name="f1">
							<table border="1">
								<tbody>
                                                                        <input type="hidden" name="test" value="ok">
                                                                        <input type="hidden" name="cuid" value="$CUID">
									<input type="hidden" name="operation_reference" value="$order_id">
									<input type="hidden" name="credit_name" value="$credit_name">
									<input type="hidden" name="price" value="$custommix_amount">
									<input type="hidden" name="currency" value="INR">									
									<input type="hidden" name="callback_url" value="$custommix_fortumo_callback_url">
									<input type="hidden" name="sig" value="$checkSum">
									
								</tbody>
							</table>
							<script type="text/javascript">document.f1.submit();</script> 
						</form>
EOF;

}

else
    
{
    
    $html = <<<EOF
					
						<center><h1>You are being redirected to Fortumo payment dashboard.</br>Please do not refresh this page...</h1></center>
							
							<form method="post" action="$custommix_action_url" name="f1">
							<table border="1">
								<tbody>
									<input type="hidden" name="test" value="fail">
                                                                        <input type="hidden" name="cuid" value="$CUID">
									<input type="hidden" name="operation_reference" value="$order_id">
									<input type="hidden" name="credit_name" value="$credit_name">
									<input type="hidden" name="price" value="$custommix_amount">
									<input type="hidden" name="currency" value="INR">									
									<input type="hidden" name="callback_url" value="$custommix_fortumo_callback_url">
									<input type="hidden" name="sig" value="$checkSum">
								</tbody>
							</table>
							<script type="text/javascript">document.f1.submit();</script> 
						</form>
EOF;


}

//End of Live or Test Fork

//move on to fortumo website
            echo $html;
            exit;
        }


/**
		 * Use our own payment fields at checkout. (Remove the name attributes.)		
		 * @since 1.8
 */

		static function pmpro_include_payment_information_fields($include)
		{
		}
/**

		 * Fields shown on edit user page
		 *
		 * @since 1.8
*/
		static function user_profile_fields($user)
		{
		}

		/**
		 * Process fields from the edit user page
		 *
		 * @since 1.8
*/
		static function user_profile_fields_save($user_id)
		{
		}

		/**
		 * Cron activation for subscription updates.
		 *
		 * @since 1.8
		 */


		static function pmpro_activation()
		{
			wp_schedule_event(time(), 'daily', 'pmpro_cron_example_subscription_updates');
		}

		/**
		 * Cron deactivation for subscription updates.
		 *
		 * @since 1.8
		 */
		static function pmpro_deactivation()
		{
			wp_clear_scheduled_hook('pmpro_cron_example_subscription_updates');
		}

		/**
		 * Cron job for subscription updates.
		 *
		 * @since 1.8
		 */
		static function pmpro_cron_example_subscription_updates()
		{
		}

		
		function process(&$order)
		{
			//check for initial payment
			if(floatval($order->InitialPayment) == 0)
			{
				//auth first, then process
				if($this->authorize($order))
				{						
					$this->void($order);										
					if(!pmpro_isLevelTrial($order->membership_level))
					{
						//subscription will start today with a 1 period trial (initial payment charged separately)
						$order->ProfileStartDate = date("Y-m-d") . "T0:0:0";
						$order->TrialBillingPeriod = $order->BillingPeriod;
						$order->TrialBillingFrequency = $order->BillingFrequency;													
						$order->TrialBillingCycles = 1;
						$order->TrialAmount = 0;
						
						//add a billing cycle to make up for the trial, if applicable
						if(!empty($order->TotalBillingCycles))
							$order->TotalBillingCycles++;
					}
					elseif($order->InitialPayment == 0 && $order->TrialAmount == 0)
					{
						//it has a trial, but the amount is the same as the initial payment, so we can squeeze it in there
						$order->ProfileStartDate = date("Y-m-d") . "T0:0:0";														
						$order->TrialBillingCycles++;
						
						//add a billing cycle to make up for the trial, if applicable
						if($order->TotalBillingCycles)
							$order->TotalBillingCycles++;
					}
					else
					{
						//add a period to the start date to account for the initial payment
						$order->ProfileStartDate = date("Y-m-d", strtotime("+ " . $order->BillingFrequency . " " . $order->BillingPeriod, current_time("timestamp"))) . "T0:0:0";
					}
					
					$order->ProfileStartDate = apply_filters("pmpro_profile_start_date", $order->ProfileStartDate, $order);
					return $this->subscribe($order);
				}
				else
				{
					if(empty($order->error))
						$order->error = __("Unknown error: Authorization failed.", "pmpro");
					return false;
				}
			}
			else
			{
				//charge first payment
				if($this->charge($order))
				{							
					//set up recurring billing					
					if(pmpro_isLevelRecurring($order->membership_level))
					{						
						if(!pmpro_isLevelTrial($order->membership_level))
						{
							//subscription will start today with a 1 period trial
							$order->ProfileStartDate = date("Y-m-d") . "T0:0:0";
							$order->TrialBillingPeriod = $order->BillingPeriod;
							$order->TrialBillingFrequency = $order->BillingFrequency;													
							$order->TrialBillingCycles = 1;
							$order->TrialAmount = 0;
							
							//add a billing cycle to make up for the trial, if applicable
							if(!empty($order->TotalBillingCycles))
								$order->TotalBillingCycles++;
						}
						elseif($order->InitialPayment == 0 && $order->TrialAmount == 0)
						{
							//it has a trial, but the amount is the same as the initial payment, so we can squeeze it in there
							$order->ProfileStartDate = date("Y-m-d") . "T0:0:0";														
							$order->TrialBillingCycles++;
							
							//add a billing cycle to make up for the trial, if applicable
							if(!empty($order->TotalBillingCycles))
								$order->TotalBillingCycles++;
						}
						else
						{
							//add a period to the start date to account for the initial payment
							$order->ProfileStartDate = date("Y-m-d", strtotime("+ " . $this->BillingFrequency . " " . $this->BillingPeriod, current_time("timestamp"))) . "T0:0:0";
						}
						
						$order->ProfileStartDate = apply_filters("pmpro_profile_start_date", $order->ProfileStartDate, $order);
						if($this->subscribe($order))
						{
							return true;
						}
						else
						{
							if($this->void($order))
							{
								if(!$order->error)
									$order->error = __("Unknown error: Payment failed.", "pmpro");
							}
							else
							{
								if(!$order->error)
									$order->error = __("Unknown error: Payment failed.", "pmpro");
								
								$order->error .= " " . __("A partial payment was made that we could not void. Please contact the site owner immediately to correct this.", "pmpro");
							}
							
							return false;								
						}
					}
					else
					{
						//only a one time charge
						// $order->status = "success";	//saved on checkout page											
						return true;
					}
				}
				else
				{
					if(empty($order->error))
						$order->error = __("Unknown error: Payment failed.", "pmpro");
					
					return false;
				}	
			}	
		}
		
		/*
			DONOT DELETE Add order code with WT
		    Run an authorization at the gateway.

			Required if supporting recurring subscriptions
			since we'll authorize $1 for subscriptions
			with a $0 initial payment.
		*/
		function authorize(&$order)
		{
			//create a code for the order
			if(empty($order->code))
				$order->code = $order->getRandomCode();
			
			//code to authorize with gateway and test results would go here


			//simulate a successful authorization
			$order->payment_transaction_id = "WT" . $order->code;
			// $order->updateStatus("authorized");													
			return true;					
		}
		
		/*
			Void a transaction at the gateway.

			Required if supporting recurring transactions
			as we void the authorization test on subs
			with a $0 initial payment and void the initial
			payment if subscription setup fails.
		*/
		function void(&$order)
		{
			//need a transaction id
			if(empty($order->payment_transaction_id))
				return false;
			
			//code to void an order at the gateway and test results would go here

			//simulate a successful void
			$order->payment_transaction_id = "WT" . $order->code;
			// $order->updateStatus("voided");					
			return true;
		}	
		
		/*
			Make a charge at the gateway.

			Required to charge initial payments.
		*/
		function charge(&$order)
		{
			//create a code for the order
			if(empty($order->code))
				$order->code = $order->getRandomCode();
			
			//code to charge with gateway and test results would go here


			//simulate a successful charge
			$order->payment_transaction_id = "WT" . $order->code;
			// $order->updateStatus("success");					
			return true;						
		}
		
		/*
			Setup a subscription at the gateway.

			Required if supporting recurring subscriptions.
		*/
	
                //VIRENDRA : Removing this function temporarily suspecting this shows fatal php error called call to undefined function subscribe()
                 
                  function subscribe(&$order)
                 
		{
			//create a code for the order
			if(empty($order->code))
				$order->code = $order->getRandomCode();
			
			//filter order before subscription. use with care.
			$order = apply_filters("pmpro_subscribe_order", $order, $this);
			
			//code to setup a recurring subscription with the gateway and test results would go here


			//simulate a successful subscription processing
			// $order->status = "success";		
			$order->subscription_transaction_id = "WT" . $order->code;	
                        
                         
			return true;
		}	
                 
                  
		
		/*
			Update billing at the gateway.

			Required if supporting recurring subscriptions and
			processing credit cards on site.
		*/
		function update(&$order)
		{
			//code to update billing info on a recurring subscription at the gateway and test results would go here

			//simulate a successful billing update
			return true;
		}
		
		/*
			Cancel a subscription at the gateway.

			Required if supporting recurring subscriptions.
		*/
		function cancel(&$order)
		{
			//require a subscription id
			if(empty($order->subscription_transaction_id))
				return false;
			
			//code to cancel a subscription at the gateway and test results would go here

			//simulate a successful cancel			
			// $order->updateStatus("cancelled");					
			return true;
		}	
		
		/*
			Get subscription status at the gateway.

			Optional if you have code that needs this or
			want to support addons that use this.
		*/
		function getSubscriptionStatus(&$order)
		{
			//require a subscription id
			if(empty($order->subscription_transaction_id))
				return false;
			
			//code to get subscription status at the gateway and test results would go here

			//this looks different for each gateway, but generally an array of some sort
			return array();
		}

		/*
			Get transaction status at the gateway.

			Optional if you have code that needs this or
			want to support addons that use this.
		*/
		function getTransactionStatus(&$order)
		{			
			
			//code to get transaction status at the gateway and test results would go here

			//this looks different for each gateway, but generally an array of some sort
			
	return array();
		
	}


    } //End of class
    
    
    