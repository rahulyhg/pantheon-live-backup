<?php

/**
 * Class SPU_Newsletter
 * Newsletter provider
 */
class SPU_newsletter implements SPU_Providers{

	protected $api_key;

	/**
	 * @var provider api
	 */
	private $api;

	/**
	 * @var string provider name
	 */
	public $provider = 'newsletter';

	/**
	 * @var array saved integrations
	 */
	private $integrations;

	/**
	 * @var Bool to check if connected
	 */
	private $connected;


	/**
	 * Constructor
	 */
	function __construct( ) {

		$this->integrations = get_option('spu_integrations');
		$this->api_key  =  $this->integrations[$this->provider]['nl_api'] ;

	}

	/**
	 * Pings the Newsletter API to see if we're connected
	 * @return boolean
	 */
	public function is_connected() {

		if( $this->connected !== null ) {
			return $this->connected;
		}
		$this->connected = ( defined('NEWSLETTER_VERSION') && class_exists('Newsletter') );

		return $this->connected;
	}


	/**
	 * Get Newsletter Lists from db
	 *
	 * @param bool $force_renewal
	 *
	 * @return mixed
	 *
	 */
	public function get_lists( $force_renewal = false) {
		$options_profile = get_option('newsletter_profile');

		$lists = array();
		for ($i = 1; $i <= NEWSLETTER_LIST_MAX; $i++) {
			$list = new stdClass();
			$list->id   = $i;
			$list->name = ! empty( $options_profile['list_' . $i] ) ? $options_profile['list_' . $i] : __('List #', 'spup') . $i;
			$lists[] =  $list;
		}
		return $lists;
	}



	/**
	 * The function that actually subscribe user to Newsletter
	 *
	 * @param $lead
	 * @param $box_opts
	 *
	 * @return bool|string
	 */
	public function subscribe($lead, $box_opts ) {

		if( ! class_exists('Newsletter') )
			return __( 'Newsletter plugin is not active');

		$this->api = new Newsletter();
		if( $this->api_key != $this->api->options['api_key'] )
			return __( 'Your API key is wrong, please double check in the Newsletter plugin settings page for the right key', 'spup');

		$data = array(
			'list_' . $box_opts['optin_list'] => 1,
			'email' => $this->api->normalize_email( $lead['email'] ),
		);

		// Setup name if set
		if ( !empty( $lead['name'] ) ) {
			$names = explode( ' ', $lead['name'] );
			if ( isset( $names[0] ) ) {
				$data['name'] = $names[0];
			}
			if ( isset( $names[1] ) ) {
				$data['surname'] = $names[1];
			}
			if ( isset( $names[2] ) ) {
				$data['name'] = $names[0] . ' ' . $names[1];
				$data['surname'] = $names[2];
			}
		}


		$options_feed = get_option('newsletter_feed', array());
		if ( isset( $options_feed['add_new'] ) && $options_feed['add_new'] == 1 )
			$data['feed'] = 1;

		$options_followup = get_option('newsletter_followup', array());
		if ( isset( $options_followup['add_new'] ) && $options_followup['add_new'] == 1 ) {
			$data['followup'] = 1;
			$data['followup_time'] = time() + $options_followup['interval'] * 3600;
		}

		$data['status'] = 'C';

		$response = $this->api->save_user( $data );

		if( ! $response || !isset( $response->id ) ) {
				return __('User already subscribed or another error happened', 'spup');
		}
		return true;
	}
}