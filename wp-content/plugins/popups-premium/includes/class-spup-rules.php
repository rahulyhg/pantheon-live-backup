<?php

/**
 * Class SPUP_Rules
 * Add new rules to core plugin
 */
class SPUP_Rules {
	/**
	* post id used to check rules
	* @var int
	*/
	protected $post_id;
	/**
	* Initialize the plugin by loading admin scripts & styles and adding a
	* settings page and menu.
	*
	* @since     1.0.0
	*/
	public function __construct() {

		global $post;
		// grab session counter
		$this->spu_views    = isset($_SESSION['spu_views']) ? $_SESSION['spu_views'] : '';

		// Add custom rules
		add_filter( 'spu/metaboxes/rule_types' , array( $this, 'add_premium_rules') );

		// Print rules fields
		add_action('spu/rules/print_visited_n_pages_field', array('Spu_Helper', 'print_textfield'), 10, 1);
		add_action('spu/rules/print_converted_field', array('Spu_Helper', 'print_select'), 10, 2);
		add_action('spu/rules/print_local_time_field', array('Spu_Helper', 'print_select'), 10, 2);
		add_action('spu/rules/print_day_field', array('Spu_Helper', 'print_select'), 10, 2);

		// Choices for custom rules
		add_filter( 'spu/rules/rule_values/converted', array( $this, 'converted_field_choices') );
		add_filter( 'spu/rules/rule_values/local_time', array( $this, 'local_time_field_choices') );
		add_filter( 'spu/rules/rule_values/day', array( $this, 'day_field_choices') );

		// Match new rules
		add_filter('spu/rules/rule_match/visited_n_pages', array($this, 'rule_match_visited_n_pages'), 10, 2);
		add_filter('spu/rules/rule_match/converted', array($this, 'rule_match_converted'), 10, 2);
		add_filter('spu/rules/rule_match/local_time', array($this, 'rule_match_local_time'), 10, 2);
		add_filter('spu/rules/rule_match/day', array($this, 'rule_match_day'), 10, 2);

		$this->post_id 	    = isset( $post->ID ) ? $post->ID : '';

		if( defined('DOING_AJAX') ) {
			if ( isset( $_REQUEST['pid'] ) ) {
				$this->post_id = $_REQUEST['pid'];
			}
		}
	}

	/**
	 * Add our premium rules to choices array
	 *
	 * @param $choices
	 *
	 * @return mixed
	 */
	function add_premium_rules( $choices ) {

		$choices [__("Popups", 'popups' )] =  array(
			'converted'		=>	__("Popup converted", 'popups' ),
		);

		$choices[__("User", 'popups' )]['visited_n_pages'] = __("User visited N pages of your site", 'popups' );

		$choices[__("Temporal", 'popups' )]['local_time'] = __("Local time is", 'popups' );
		$choices[__("Temporal", 'popups' )]['day'] = __("Weekday is", 'popups' );

		return $choices;
	}

	/**
	 * Array of hours in a day in 24 hours format
	 * @param $choices
	 *
	 * @return array
	 */
	function local_time_field_choices( $choices ) {
		$times = array();
		for ($h = 0; $h < 24; $h++){
			for ($m = 0; $m < 60 ; $m += 5){
				$time = sprintf('%02d:%02d', $h, $m);
				$times["$time"] = "$time";
			}
		}
		return $times;
	}

	/**
	 * Array of days of the week
	 * @param $choices
	 *
	 * @return array
	 */
	function day_field_choices( $choices ) {
		$days = array(
			__('Sunday'),
			__('Monday'),
			__('Tuesday'),
			__('Wednesday'),
			__('Thursday'),
			__('Friday'),
			__('Saturday'),
		);
		return $days;
	}

	/**
	 * Grab popups IDs for the popup converted select field
	 * @param $choices
	 *
	 * @return array
	 */
	function converted_field_choices( $choices ) {

		$args  = array(
			'numberposts'               => '-1',
			'post_type'                 => 'spucpt',
			'post_status'               => array('publish', 'private', 'draft', 'inherit', 'future'),
			'suppress_filters'          => false,
			'update_post_meta_cache'	=> false,
		);

		$posts = get_posts( apply_filters('spu/rules/spucpt_args', $args ));

		if( $posts)
		{

			foreach($posts as $post)
			{
				$title = apply_filters( 'the_title', $post->post_title, $post->ID );

				// status
				if($post->post_status != "publish")
				{
					$title .= " ($post->post_status)";
				}

				$choices[$post->ID] = $title;

			}
		}
		return $choices;
	}

	/**
	 * Show popup after user visited N pages of our site
	 * @param $match
	 * @param $rule
	 *
	 * @return bool
	 */
	function rule_match_visited_n_pages( $match, $rule ) {

		$views = $this->spu_views;

		if ( $views == $rule['value'] ){
			return  $rule['operator'] == "==" ? true : false;
		}

	}

	/**
	 * Show popup if time matches
	 * @param $match
	 * @param $rule
	 *
	 * @return bool
	 */
	function rule_match_local_time( $match, $rule ) {

		$current_time = DateTime::createFromFormat( '!H:i', date( 'H:i' , current_time( 'timestamp' ) ) );
		$time         = DateTime::createFromFormat( '!H:i', $rule['value'] );

		if( $current_time <= $time && $rule['operator'] == "<" )
			return true;

		if( $current_time >= $time && $rule['operator'] == ">" )
			return true;
		return false;
		
	}

	/**
	 * Show popup if date matches
	 * @param $match
	 * @param $rule
	 *
	 * @return bool
	 */
	function rule_match_day( $match, $rule ) {

		if( date('w', current_time( 'timestamp' ) ) ==  $rule['value'] )
			return  $rule['operator'] == "==" ? true : false;

	}

	/**
	 * Show popup if selected popup already converted
	 * @param $match
	 * @param $rule
	 *
	 * @return bool
	 */
	function rule_match_converted( $match, $rule ) {

		if ( isset( $_COOKIE['spu_box_'.$rule['value']] ) )
			return  $rule['operator'] == "==" ? true : false;

		return $rule['operator'] == "==" ? false : true;
	}
}
