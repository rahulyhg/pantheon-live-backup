<?php
//paytm gateway integration pmprogateway added by virendra
//Plugin : Paytm integration based on paytm donate

	require_once(PMPRO_DIR. "/classes/gateways/class.pmprogateway.php");
	require_once(dirname(__FILE__) . "/../../includes/lib/paytm-donation-viren/encdec_paytm.php");
	require_once(dirname(__FILE__) . "/../../includes/lib/paytm-donation-viren/viren-paytm-gateway.php");

    //load classes init method
    add_action('init', array('PMProGateway_paytm', 'init'));


    /**
     * PMProGateway_gatewayname Class
     *
     * Handles example integration.
     *
     */
    class PMProGateway_paytm extends PMProGateway
    {
        function PMProGateway($gateway = NULL)
        {
            if (!class_exists("paytm"))
                require_once(dirname(__FILE__) . "/../../includes/lib/paytm-donation-viren/viren-paytm-gateway.php");

            //set API connection vars
            paytm::paytm_merchant_id(pmpro_getOption('paytm_merchant_id'));
            paytm::paytm_merchant_key(pmpro_getOption('paytm_merchant_key'));
            paytm::paytm_industry_type_id(pmpro_getOption('paytm_industry_type_id'));
            paytm::paytm_website(pmpro_getOption('paytm_website'));
            paytm::paytm_channel_id(pmpro_getOption('paytm_channel_id'));
            paytm::paytm_mode(pmpro_getOption('paytm_mode'));
            paytm::paytm_amount(pmpro_getOption('paytm_amount'));
            paytm::paytm_returnto(pmpro_getOption('paytm_returnto'));
            paytm::paytm_callback(pmpro_getOption('paytm_callback'));
            paytm::$verifySSL = false;


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
            //make sure paytm                                                          is a gateway option
            add_filter('pmpro_gateways', array('PMProGateway_paytm', 'pmpro_gateways'));

            //add fields to payment settings
            add_filter('pmpro_payment_options', array('PMProGateway_paytm', 'pmpro_payment_options'));
            add_filter('pmpro_payment_option_fields', array('PMProGateway_paytm', 'pmpro_payment_option_fields'), 10, 2);

            //add some fields to edit user page (Updates)
            //following w2 lines are in w2checkout - virendra but are in example
            add_action('pmpro_after_membership_level_profile_fields', array('PMProGateway_paytm', 'user_profile_fields'));
            add_action('profile_update', array('PMProGateway_paytm', 'user_profile_fields_save'));

            //updates cron NOY IN w2CHECKOUT - virendra retained from example
            add_action('pmpro_activation', array('PMProGateway_paytm', 'pmpro_activation'));
            add_action('pmpro_deactivation', array('PMProGateway_paytm', 'pmpro_deactivation'));
            add_action('pmpro_cron_paytm_subscription_updates', array('PMProGateway_paytm', 'pmpro_cron_paytm_subscription_updates'));

            //code to add at checkout if paytm is the current gateway VIRENDRA THIE FOLLOWING LINE IS DIFF IN EXAMPLE AND w2CHECKOUT
            $gateway = pmpro_getOption("gateway");
            if ($gateway == "paytm") {
                //add_action('pmpro_checkout_preheader', array('PMProGateway_paytm', 'pmpro_checkout_preheader'));
                //add_filter('pmpro_checkout_order', array('PMProGateway_paytm', 'pmpro_checkout_order'));
                //add_filter('pmpro_include_billing_address_fields', array('PMProGateway_paytm', 'pmpro_include_billing_address_fields'));
                //add_filter('pmpro_include_cardtype_field', array('PMProGateway_paytm', 'pmpro_include_billing_address_fields'));
                //add_filter('pmpro_include_payment_information_fields', array('PMProGateway_paytm', 'pmpro_include_payment_information_fields'));

                add_filter('pmpro_include_billing_address_fields', '__return_false');
                add_filter('pmpro_include_payment_information_fields', '__return_false');
                add_filter('pmpro_required_billing_fields', '__return_empty_array');
                add_filter('pmpro_checkout_default_submit_button', array('PMProGateway_paytm', 'pmpro_checkout_default_submit_button'));
                add_filter('pmpro_checkout_before_change_membership_level', array('PMProGateway_paytm', 'pmpro_checkout_before_change_membership_level'), 10, 2);
            }
        }

        /**
         * Make sure paytm is in the gateways list
         *
         * @since 1.8
         */
        static function pmpro_gateways($gateways)
        {
            if (empty($gateways['paytm']))
                $gateways['paytm'] = __('paytm', 'pmpro');

            return $gateways;
        }


        /**
         * Get a list of payment options that paytm gateway needs/supports.
         */

        static function getGatewayOptions()
        {
            $options = array(
                'sslseal',
                'nuclear_HTTPS',
                'gateway_environment',
                'currency',
                'paytm_merchant_id',
                'paytm_merchant_key',
                'paytm_website',
                'paytm_industry_type_id',
                'paytm_channel_id',
                'paytm_mode',
                'paytm__amount',
                'paytm_returnto',
                'paytm_callback',
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
            //get paytm options
            $paytm_options = PMProGateway_paytm::getGatewayOptions();

            //merge with others.
            $options = array_merge($paytm_options, $options);

            return $options;
        }


        /*
        * Display fields for paytm options.
         */

        static function pmpro_payment_option_fields($values, $gateway)
        {
            ?>
            <tr class="pmpro_settings_divider gateway gateway_paytm"
                <?php if ($gateway != "paytm") { ?>style="display: none;"<?php } ?>>
                <td colspan="2">
                    <?php _e('paytm Settings', 'pmpro'); ?>
                </td>
            </tr>
            <tr class="gateway gateway_paytm" <?php if ($gateway != "paytm") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="paytm_merchant_id"><?php _e('API MERCHANT ID', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" id="paytm_merchant_id" name="paytm_merchant_id" size="60"
                           value="<?php echo esc_attr($values['paytm_merchant_id']) ?>"/>
                    <br/>
                    <small><?php _e('Merchant id from Paytm'); ?></small>
                </td>
            </tr>
            <tr class="gateway gateway_paytm" <?php if ($gateway != "paytm") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="paytm_merchant_key"><?php _e('MERCHANT KEY', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" id="paytm_merchant_key" name="paytm_merchant_key" size="60"
                           value="<?php echo esc_attr($values['paytm_merchant_key']) ?>"/>
                    <br/>
                    <small><?php _e('Merchant Key from Paytm'); ?></small>
                </td>
            </tr>
            <tr class="gateway gateway_paytm" <?php if ($gateway != "paytm") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="paytm_website"><?php _e('WEBSITE', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" name="paytm_website" size="60" value="<?php echo $values['paytm_website'] ?>"/>
                    <br/>
                    <small><?php _e('Website given by Paytm'); ?></small>
                </td>
            </tr>
            <tr class="gateway gateway_paytm" <?php if ($gateway != "paytm") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="paytm_industry_type_id"><?php _e('INDUSTRY ID', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" name="paytm_industry_type_id" size="60"
                           value="<?php echo $values['paytm_industry_type_id'] ?>"/>
                    <br/>
                    <small><?php _e('Industy Type id provided by Paytm'); ?></small>
                </td>
            </tr>
            <tr class="gateway gateway_paytm" <?php if ($gateway != "paytm") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="paytm_channel_id"><?php _e('CHANNEL ID', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" name="paytm_channel_id" size="60"
                           value="<?php echo $values['paytm_channel_id'] ?>"/>
                    <br/>
                    <small><?php _e('Channel_id by Paytm'); ?></small>
                </td>
            </tr>
            <tr class="gateway gateway_paytm" <?php if ($gateway != "paytm") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="paytm_mode"><?php _e('MODE', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" name="paytm_mode" size="60" value="<?php echo $values['paytm_mode'] ?>"/>
                    <br/>
                    <small><?php _e('Is it Live or Test environment?'); ?></small>
                </td>
            </tr>
            <tr class="gateway gateway_paytm" <?php if ($gateway != "paytm") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="paytm_amount"><?php _e('Default Amount', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" name="paytm_amount" size="60" value="<?php echo $values['paytm_amount'] ?>"/>
                    <br/>
                    <small><?php _e('Default Amount if Any'); ?></small>
                </td>
            </tr>
            <tr class="gateway gateway_paytm" <?php if ($gateway != "paytm") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="paytm_returnto"><?php _e('Return Page', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" name="paytm_returnto" size="60" value="<?php echo $values['paytm_returnto'] ?>"/>
                    <br/>
                    <small><?php _e('Return URL as desired'); ?></small>
                </td>
            </tr>
            <tr class="gateway gateway_paytm" <?php if ($gateway != "paytm") { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="paytm_callback"><?php _e('CallBack URL', 'pmpro'); ?>:</label>
                </th>
                <td>
                    <input type="text" name="paytm_callback" size="100"
                           value="<?php echo $values['paytm_callback'] ?>"/>
                    <br/>
                    <small><?php _e('Specify YES if want to give retrun URL or write NO'); ?></small>
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
                           _e('Check Out with PayTm', 'pmpro');
                       } else {
                           _e('Submit and Confirm', 'pmpro');
                       } ?> &raquo;"/>
			</span>
            <?php
            //don't show the default
            return false;
        }

        /**
         * Instead of change membership levels, send users to PayTM to pay.
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

            do_action("pmpro_before_send_to_paytm", $user_id, $morder);

            $morder->Gateway->pmpro_checkout_order($morder); //sendTopaytm($morder);
        }

        //Send user to Paytm
        function pmpro_checkout_order(&$order) //sendTopaytm(&$order)
        {

            global $wpdb, $pmpro_currency;

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
                    'paytm_returnto' => pmpro_getOption('paytm_returnto')
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


            $current_user = wp_get_current_user();
            $donor_name = $current_user->user_lastname;
            $donor_amount = $order->InitialPayment;
            $donor_email = $current_user->user_email;
            $donor_phone = '';
            $donor_city = '';
            $donor_address = '';
            $donor_country = '';
            $donor_state = '';
            $donor_postal_code = '';
            $order_id = $order->code;

            if ($valid) {
                $table_name = $wpdb->prefix . "paytm_donation";
                $data = array(
                    'order_id' => sanitize_text_field($order_id),
                    'name' => sanitize_text_field($donor_name),
                    'email' => sanitize_text_field($donor_email),
                    'phone' => sanitize_text_field($donor_phone),
                    'address' => sanitize_text_field($donor_address),
                    'city' => sanitize_text_field($donor_city),
                    'country' => sanitize_text_field($donor_country),
                    'state' => sanitize_text_field($donor_state),
                    'zip' => sanitize_text_field($donor_postal_code),
                    'amount' => sanitize_text_field($donor_amount),
                    'payment_status' => 'Pending Payment',
                    'date' => date('Y-m-d H:i:s'),
                );

                $wpdb->insert($table_name, $data);
            }

            $post_params = array(
                'MID' => $paytm_merchant_id,
                'ORDER_ID' => $order_id,
                'WEBSITE' => $paytm_website,
                'CHANNEL_ID' => $paytm_channel_id,
                'INDUSTRY_TYPE_ID' => $paytm_industry_type_id,
                'TXN_AMOUNT' => $paytm_amount,
                'CUST_ID' => $donor_email,
                'EMAIL' => $donor_email,
            );

            $wpdb->query($wpdb->prepare("UPDATE $wpdb->pmpro_membership_orders SET status = 'Aborted' WHERE code = %s", $post_params["ORDER_ID"]));

            if ($paytm_callback == 'YES') {
                $post_params["CALLBACK_URL"] = $paytm_returnto;

                $checkSum = getChecksumFromArray($post_params, $paytm_merchant_key);

                $call = $paytm_returnto;
            } else {

                $post_params["CALLBACK_URL"] = get_site_url() . '/paytm-transaction-status/';

                $checkSum = getChecksumFromArray($post_params, $paytm_merchant_key);

                $call = get_site_url() . '/paytm-transaction-status/';

            }


            if ($paytm_mode == 'LIVE') {
                $paytm_url = "https://secure.paytm.in/oltp-web/processTransaction?orderid=$order_id";
            } else {
                $paytm_url = "https://pguat.paytm.com/oltp-web/processTransaction?orderid=$order_id";
            }


            //Append key paramters to paytm url and turn it into html
            $html = <<<EOF
					
						<center><h1>You are being redirected to PayTm payment dashboard.</br>Please do not refresh this page...</h1></center>
							
							<form method="post" action="$paytm_url" name="f1">
							<table border="1">
								<tbody>
									<input type="hidden" name="MID" value="$paytm_merchant_id">
									<input type="hidden" name="WEBSITE" value="$paytm_website">
									<input type="hidden" name="CHANNEL_ID" value="$paytm_channel_id">
									<input type="hidden" name="ORDER_ID" value="$order_id">
									<input type="hidden" name="INDUSTRY_TYPE_ID" value="$paytm_industry_type_id">									
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
            //redirect to paytm
            echo $html;
            exit;
        }

/**

		 * Use our own payment fields at checkout. (Remove the name attributes.)		
		 * @since 1.8

		static function pmpro_include_payment_information_fields($include)
		{
		}

		/**
		 * Fields shown on edit user page
		 *
		 * @since 1.8

		static function user_profile_fields($user)
		{
		}

		/**
		 * Process fields from the edit user page
		 *
		 * @since 1.8

		static function user_profile_fields_save($user_id)
		{
		}
*/
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