<?php

/**
 * Class SPU_Activecampaign
 * Activecampaign provider
 */
class SPU_activecampaign implements SPU_Providers{

	/**
	 * @var provider api
	 */
	private $api;

	/**
	 * @var string provider name
	 */
	public $provider = 'activecampaign';

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

		$this->api = new ActiveCampaign( $this->integrations[$this->provider]['ac_url'], $this->integrations[$this->provider]['ac_api'] );

	}

	/**
	 * Pings the MailChimp API to see if we're connected
	 * @return boolean
	 */
	public function is_connected() {

		if( $this->connected !== null ) {
			return $this->connected;
		}

		$this->connected = false;
		if ((int)$this->api->credentials_test() ) {
				$this->connected = true;
		} else {
			SPU_Errors::display_error( 'Activecampaign error: Access denied: Invalid credentials (URL and/or API key)' );
		}

		return $this->connected;
	}


	/**
	 * Get Activecampaign Lists from cache or renew
	 *
	 * @param bool $force_renewal
	 *
	 * @return mixed
	 *
	 */
	public function get_lists( $force_renewal = false) {
		$lists = array();

		$cached_lists = get_transient( 'spu_ac_lists' );

		// if empty try older one
		if( empty($cached_lists) ) {
			$cached_lists = get_transient( 'spu_ac_lists_fallback' );
		}

		if ( true === $force_renewal || false === $cached_lists || empty( $cached_lists ) ) {
			$lists = $this->api->api('list/list_' , array('ids'=> 'all') );

			$lists = $this->normalize_lists( $lists );
			set_transient( 'spu_ac_lists', $lists, ( 24 * 3600 ) ); // 1 day
			set_transient( 'spu_ac_lists_fallback', $lists, ( 24 * 3600 * 7 ) ); // 1 week
		} else {
			if( !empty($cached_lists) )
				$lists = $cached_lists;
		}
		return $lists;
	}



	/**
	 * The function that actually subscribe user to MailChimp
	 *
	 * @param $lead
	 * @param $box_opts
	 *
	 * @return bool|string
	 */
	public function subscribe($lead, $box_opts ) {

		$list_id = $box_opts['optin_list'] ;
		$data = array(
			'p[{$list_id}]' => $list_id,
			'email' => $lead['email'],
			"status[{$list_id}]" => 1,
		);

		// Setup name if set
		if ( !empty( $lead['name'] ) ) {
			$names = explode( ' ', $lead['name'] );
			if ( isset( $names[0] ) ) {
				$data['first_name'] = $names[0];
			}
			if ( isset( $names[1] ) ) {
				$data['last_name'] = $names[1];
			}
			if ( isset( $names[2] ) ) {
				$data['first_name'] = $names[0] . ' ' . $names[1];
				$data['last_name'] = $names[2];
			}
		}

		$contact_sync = $this->api->api("contact/sync", $data);
		if (!(int)$contact_sync->success) {
			// request failed
			return $contact_sync->error;

		}
		return true;
	}

	/**
	 * Grab lists from response object and return an array
	 * @param $lists
	 *
	 * @return array
	 */
	private function normalize_lists( $lists ) {
		$normalized = array();
		if( !(int)$lists->result_code )
			return $normalized;

		foreach ( $lists as $l ) {
			if( isset( $l->id ) )
				$normalized[] = $l;
		}

		return $normalized;
	}
}