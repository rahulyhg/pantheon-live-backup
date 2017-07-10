<?php
/**
 * PopupsP.
 *
 * @package   PopupsP
 * @author    Damian Logghe <info@timersys.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */

/**
 * Public Class of the plugin
 * @package PopupsP
 * @author  Damian Logghe <info@timersys.com>
 */
class PopupsP {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.8.1';

	/**
	 * Popups to use acrros files
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	const PLUGIN_NAME = 'Popups Premium';


	/**
	 * Database version
	 * @since 1.2
	 * @var string
	 */
	const db_version = '1.0.1';

	/**
	 * Helper functions class
	 * @var Spu_Helper
	 */
	public $helper;

	/**
	 * @TODO - Rename "plugin-name" to the name your your plugin
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'spup';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Plugins settings
	 * @var array
	 */
	protected $spu_settings = array();

	/**
	 * Plugins settings
	 * @var array
	 */
	protected $spu_integrations = array();

	/**
	 * Plugin info accesible everywhere
	 * @var array
	 *
	 * @since  1.0.0
	 */
	var $info;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {


		// vars
		$this->info = array(
			'dir'				=> SPUP_PLUGIN_DIR,
			'url'				=> SPUP_PLUGIN_URL,
			'hook'				=> SPUP_PLUGIN_HOOK,
			'version'			=> self::VERSION,
			'upgrade_version'	=> '1.6.4.3',
			'wpml_lang'	        => defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : '',
		);

		$this->spu_settings = apply_filters('spu/settings_page/opts', get_option( 'spu_settings' ) );
		$this->spu_integrations = apply_filters('spu/settings_page/integrations', get_option( 'spu_integrations' ) );

		// helper funcs
		if( class_exists('Spu_Helper'))
			$this->helper = new Spu_Helper;

		// load dependencies
		$this->loadDependencies();

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Add extra attrs to popups
		add_filter( 'spu/popup/data_attrs', array( $this, 'add_extra_attrs' ), 10, 3 );

		// Add extra Css when needed Eg: wiggle animation
		add_action( 'spu/popup/popup_style', array( $this, 'add_extra_css' ), 10, 3 );

		// Register public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ), 99 );

		// Add optins class to popup box
		add_filter( 'spu/popup/box_class', array( $this, 'add_optin_class_to_box'), 10, 4);
		// Add custom premium styles to box
		add_filter( 'spu/popup/popup_style', array( $this, 'add_custom_style_to_box'), 10, 3);

		// Add placeholder after content in case we need it for after content position
		add_filter( 'the_content', array( $this, 'add_placeholder_to_content' ), apply_filters( 'spu/after_content_priority' , 20 ) );

	#	//FILTERS
		add_filter('spu/get_info', array($this, 'get_info'), 1, 1);
		add_filter( 'spu/check_for_matches', array( $this, 'add_ab_popups_to_matches' ), 10, 2 );

		// Optin ajax
		add_action( 'wp_ajax_spu_optin', array( $this, 'handle_optin_forms' ) );
		add_action( 'wp_ajax_nopriv_spu_optin', array( $this, 'handle_optin_forms' ) );

		// session count
		add_action( 'template_redirect', array( $this, 'sessionCounter' ) );
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		// If there are not popups created let's create a default one
		global $wpdb;

		//check for popups free plugin or throw error
		if( !defined('SPU_PLUGIN_HOOK') || !is_plugin_active( str_replace('trunk','popups',SPU_PLUGIN_HOOK ) ) ){

			wp_die( "Popups free plugin must be installed and active in order to use premium version. Please download it from http://wordpress.org/plugins/popups/ " );

		}
		if(  version_compare( get_option('spu_hits_db_version'), self::db_version, '<' ) ) {

			global $wpdb, $charset_collate;
			$table_name = $wpdb->prefix . 'spu_hits_logs';
			if ( ! empty( $wpdb->charset ) )
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			if ( ! empty( $wpdb->collate ) )
				$charset_collate .= " COLLATE $wpdb->collate";

			$sql_create_table = "CREATE TABLE {$table_name} (
			          hit_id bigint(20) unsigned NOT NULL auto_increment,
			          box_id int(8) unsigned NOT NULL default '0',
			          post_id int(8) unsigned NOT NULL default '0',
			          hit_date datetime NOT NULL default '0000-00-00 00:00:00',
			          hit_type varchar(10) NOT NULL default '',
			          ua varchar(128) NOT NULL default '',
			          referrer varchar(256) NOT NULL default '',
			          PRIMARY KEY  (hit_id),
			          KEY box_id (box_id),
			          KEY hit_date (hit_date),
			          KEY hit_type (hit_type)
			     ) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql_create_table );

			update_option( 'spu_hits_db_version', self::db_version );
		}

		// Upgrade mailchimp integrations
		$current_version = get_option('spup_version');
		if(  empty($current_version) ) {
			$integrations = get_option('spu_integrations');
			$new_integrations = array(
				'mailchimp'     => array('mc_api'),
				'aweber'        => array('aweber_auth', 'access_token','access_token_secret' )
			);

			if( !empty( $integrations['mc_api'] ) )
				$new_integrations['mailchimp']['mc_api'] = $integrations['mc_api'];
			update_option('spu_integrations', $new_integrations );
		}

		update_option('spup_version', self::VERSION);
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Add extra data-attr to the popups
	 *
	 * @param $data_attrs
	 * @param array $opts popup selected options
	 *
	 * @param $box
	 *
	 * @return string
	 */
	function add_extra_attrs( $data_attrs, $opts, $box ) {

		//trigger value
		if( ( 'trigger-click' == $opts['trigger'] || 'visible' == $opts['trigger'] ) && !empty( $opts['trigger_value'] ) ) {
			$data_attrs .= 'data-trigger-value="' . esc_attr( $opts['trigger_value'] ) .'" ';
		}

		// auto close
		if( !empty( $opts['autoclose'] ) )
			$data_attrs .= 'data-auto-close="' . esc_attr( absint( $opts['autoclose'] ) ) .'" ';

		// close button
		if( !empty( $opts['disable_close'] ) )
			$data_attrs .= 'data-disable-close="' . esc_attr( absint( $opts['disable_close'] ) ) .'" ';

		// advanced close
		if( !empty( $opts['disable_advanced_close'] ) )
			$data_attrs .= 'data-advanced-close="' . esc_attr( absint( $opts['disable_advanced_close'] ) ) .'" ';

		// google analytics
		if( !empty( $this->spu_settings['ua_code']) ) {
			$data_attrs .= 'data-ua-code="' . esc_attr(  $this->spu_settings['ua_code'] )  .'" ';
			if( !empty( $opts['event_cat'] ) )
				$data_attrs .= 'data-event-cat="' . esc_attr(  $opts['event_cat'] )  .'" ';
			if( !empty( $opts['event_c_action'] ) )
				$data_attrs .= 'data-converion-action="' . esc_attr(  $opts['event_c_action'] )  .'" ';
			if( !empty( $opts['event_i_action'] ) )
				$data_attrs .= 'data-impression-action="' . esc_attr(  $opts['event_i_action'] )  .'" ';
			if( !empty( $opts['event_label'] ) )
				$data_attrs .= 'data-event-label="' . esc_attr(  $opts['event_label'] )  .'" ';
		}

		// popup conversion rules
		if( $rules = $this->check_rules( get_post_meta( $box->ID, 'spu_rules', true ) ) )
			$data_attrs .= 'data-converted="' . esc_attr( implode(',', array_keys( $rules[0] ) ) )  .'" data-not_converted="' . esc_attr( implode(',', array_keys( $rules[1] ) ) )  .'" ';

		// ab testing
		$ab_parent = get_post_meta( $box->ID ,'spu_ab_parent', true);
		$ab_group  = $ab_parent ? $ab_parent : get_post_meta( $box->ID ,'spu_ab_group', true);
		$data_attrs .= 'data-ab-group="' . esc_attr( $ab_group ).'" ';

		return  $data_attrs;
	}

	/**
	 * Check for rules of converted popups
	 * @param array $rules
	 *
	 * @return bool
	 */
	private function check_rules( $rules = array() ) {

		if( empty( $rules ) )
			return false;
		// we only want to pass rules to popup js if only one group exist and we cannot check the other group entirely on js
		if( count( $rules ) != 1 )
			return false;
		$converted = $not_converted = array();
		foreach( $rules as $group_id => $group ) {

			if( is_array($group) ) {

				foreach( $group as $rule_id => $rule ) {
					if( $rule['param'] == 'converted' ){
						if( $rule['operator'] == "==" ) {
							$converted[$rule['value']] = true;
						} else {
							$not_converted[$rule['value']] = true;
						}
					}

				}
			}

		}
		return array( $converted, $not_converted );
	}

	/**
	 * Added extra style block before the popup in case is needed
	 * @param object $box  popup object
	 * @param array $opts array of popup options
	 * @param array $css  array of css option of current popup
	 */
	function add_extra_css( $box, $opts, $css) {

		//wiggle animation
		if ( 'wiggle' == $opts['animation'] ) {
			echo '@-webkit-keyframes swing{20%{-webkit-transform:rotate(15deg);transform:rotate(15deg)}40%{-webkit-transform:rotate(-10deg);transform:rotate(-10deg)}60%{-webkit-transform:rotate(5deg);transform:rotate(5deg)}80%{-webkit-transform:rotate(-5deg);transform:rotate(-5deg)}100%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}}@keyframes swing{20%{-webkit-transform:rotate(15deg);-ms-transform:rotate(15deg);transform:rotate(15deg)}40%{-webkit-transform:rotate(-10deg);-ms-transform:rotate(-10deg);transform:rotate(-10deg)}60%{-webkit-transform:rotate(5deg);-ms-transform:rotate(5deg);transform:rotate(5deg)}80%{-webkit-transform:rotate(-5deg);-ms-transform:rotate(-5deg);transform:rotate(-5deg)}100%{-webkit-transform:rotate(0deg);-ms-transform:rotate(0deg);transform:rotate(0deg)}}#spu-' . $box->ID . '.spu-animate {-webkit-animation-duration:1s;animation-duration:1s;-webkit-transform-origin:top center;-ms-transform-origin:top center;transform-origin:top center;-webkit-animation-name:swing;animation-name:swing}';
		}
		//tada animation
		if( 'tada' == $opts['animation']) {
			echo '#spu-'.$box->ID.'.spu-animate{animation:sputada linear 1s;animation-iteration-count:1;transform-origin:;-webkit-animation:sputada linear 1s;-webkit-animation-iteration-count:1;-webkit-transform-origin:;-moz-animation:sputada linear 1s;-moz-animation-iteration-count:1;-moz-transform-origin:;-o-animation:sputada linear 1s;-o-animation-iteration-count:1;-o-transform-origin:;-ms-animation:sputada linear 1s;-ms-animation-iteration-count:1;-ms-transform-origin:}@keyframes sputada{0%{opacity:1;transform:rotate(0deg) scaleX(1) scaleY(1)}10%,20%{transform:rotate(-3deg) scaleX(0.8) scaleY(0.8)}30%{transform:rotate(3deg) scaleX(1.2) scaleY(1.2)}40%{transform:rotate(-3deg) scaleX(1.2) scaleY(1.2)}50%{transform:rotate(3deg) scaleX(1.2) scaleY(1.2)}60%{transform:rotate(-3deg) scaleX(1.2) scaleY(1.2)}70%{transform:rotate(3deg) scaleX(1.2) scaleY(1.2)}80%{transform:rotate(-3deg) scaleX(1.2) scaleY(1.2)}90%{transform:rotate(3deg) scaleX(1.2) scaleY(1.2)}100%{opacity:1;transform:rotate(0deg) scaleX(1.2) scaleY(1.2)}}@-moz-keyframes sputada{0%{opacity:1;-moz-transform:rotate(0deg) scaleX(1) scaleY(1)}10%,20%{-moz-transform:rotate(-3deg) scaleX(0.8) scaleY(0.8)}30%{-moz-transform:rotate(3deg) scaleX(1.2) scaleY(1.2)}40%{-moz-transform:rotate(-3deg) scaleX(1.2) scaleY(1.2)}50%{-moz-transform:rotate(3deg) scaleX(1.2) scaleY(1.2)}60%{-moz-transform:rotate(-3deg) scaleX(1.2) scaleY(1.2)}70%{-moz-transform:rotate(3deg) scaleX(1.2) scaleY(1.2)}80%{-moz-transform:rotate(-3deg) scaleX(1.2) scaleY(1.2)}90%{-moz-transform:rotate(3deg) scaleX(1.2) scaleY(1.2)}100%{opacity:1;-moz-transform:rotate(0deg) scaleX(1.2) scaleY(1.2)}}@-webkit-keyframes sputada{0%{opacity:1;-webkit-transform:rotate(0deg) scaleX(1) scaleY(1)}10%,20%{-webkit-transform:rotate(-3deg) scaleX(0.8) scaleY(0.8)}30%{-webkit-transform:rotate(3deg) scaleX(1.2) scaleY(1.2)}40%{-webkit-transform:rotate(-3deg) scaleX(1.2) scaleY(1.2)}50%{-webkit-transform:rotate(3deg) scaleX(1.2) scaleY(1.2)}60%{-webkit-transform:rotate(-3deg) scaleX(1.2) scaleY(1.2)}70%{-webkit-transform:rotate(3deg) scaleX(1.2) scaleY(1.2)}80%{-webkit-transform:rotate(-3deg) scaleX(1.2) scaleY(1.2)}90%{-webkit-transform:rotate(3deg) scaleX(1.2) scaleY(1.2)}100%{opacity:1;-webkit-transform:rotate(0deg) scaleX(1.2) scaleY(1.2)}}@-o-keyframes sputada{0%{opacity:1;-o-transform:rotate(0deg) scaleX(1) scaleY(1)}10%,20%{-o-transform:rotate(-3deg) scaleX(0.8) scaleY(0.8)}30%{-o-transform:rotate(3deg) scaleX(1.2) scaleY(1.2)}40%{-o-transform:rotate(-3deg) scaleX(1.2) scaleY(1.2)}50%{-o-transform:rotate(3deg) scaleX(1.2) scaleY(1.2)}60%{-o-transform:rotate(-3deg) scaleX(1.2) scaleY(1.2)}70%{-o-transform:rotate(3deg) scaleX(1.2) scaleY(1.2)}80%{-o-transform:rotate(-3deg) scaleX(1.2) scaleY(1.2)}90%{-o-transform:rotate(3deg) scaleX(1.2) scaleY(1.2)}100%{opacity:1;-o-transform:rotate(0deg) scaleX(1.2) scaleY(1.2)}}@-ms-keyframes sputada{0%{opacity:1;-ms-transform:rotate(0deg) scaleX(1) scaleY(1)}10%,20%{-ms-transform:rotate(-3deg) scaleX(0.8) scaleY(0.8)}30%{-ms-transform:rotate(3deg) scaleX(1.2) scaleY(1.2)}40%{-ms-transform:rotate(-3deg) scaleX(1.2) scaleY(1.2)}50%{-ms-transform:rotate(3deg) scaleX(1.2) scaleY(1.2)}60%{-ms-transform:rotate(-3deg) scaleX(1.2) scaleY(1.2)}70%{-ms-transform:rotate(3deg) scaleX(1.2) scaleY(1.2)}80%{-ms-transform:rotate(-3deg) scaleX(1.2) scaleY(1.2)}90%{-ms-transform:rotate(3deg) scaleX(1.2) scaleY(1.2)}100%{opacity:1;-ms-transform:rotate(0deg) scaleX(1.2) scaleY(1.2)}}';
		}
		//shake animation
		if( 'shake' == $opts['animation']) {
			echo '#spu-'.$box->ID.'.spu-animate{animation:spushake linear 1s;animation-iteration-count:1;transform-origin:;-webkit-animation:spushake linear 1s;-webkit-animation-iteration-count:1;-webkit-transform-origin:;-moz-animation:spushake linear 1s;-moz-animation-iteration-count:1;-moz-transform-origin:;-o-animation:spushake linear 1s;-o-animation-iteration-count:1;-o-transform-origin:;-ms-animation:spushake linear 1s;-ms-animation-iteration-count:1;-ms-transform-origin:}@keyframes spushake{0%{margin-left:0;opacity:1;transform:rotate(0deg) scaleX(1) scaleY(1)}10%{margin-left:-10px}20%{margin-left:10px}30%{margin-left:-10px}40%{margin-left:10px}50%{margin-left:-10px}60%{margin-left:10px}70%{margin-left:-10px}80%{margin-left:10px}90%{margin-left:-10px}100%{margin-left:0;opacity:1;transform:rotate(0deg) scaleX(1) scaleY(1)}}@-moz-keyframes spushake{0%{margin-left:0;opacity:1;-moz-transform:rotate(0deg) scaleX(1) scaleY(1)}10%{margin-left:-10px}20%{margin-left:10px}30%{margin-left:-10px}40%{margin-left:10px}50%{margin-left:-10px}60%{margin-left:10px}70%{margin-left:-10px}80%{margin-left:10px}90%{margin-left:-10px}100%{margin-left:0;opacity:1;-moz-transform:rotate(0deg) scaleX(1) scaleY(1)}}@-webkit-keyframes spushake{0%{margin-left:0;opacity:1;-webkit-transform:rotate(0deg) scaleX(1) scaleY(1)}10%{margin-left:-10px}20%{margin-left:10px}30%{margin-left:-10px}40%{margin-left:10px}50%{margin-left:-10px}60%{margin-left:10px}70%{margin-left:-10px}80%{margin-left:10px}90%{margin-left:-10px}100%{margin-left:0;opacity:1;-webkit-transform:rotate(0deg) scaleX(1) scaleY(1)}}@-o-keyframes spushake{0%{margin-left:0;opacity:1;-o-transform:rotate(0deg) scaleX(1) scaleY(1)}10%{margin-left:-10px}20%{margin-left:10px}30%{margin-left:-10px}40%{margin-left:10px}50%{margin-left:-10px}60%{margin-left:10px}70%{margin-left:-10px}80%{margin-left:10px}90%{margin-left:-10px}100%{margin-left:0;opacity:1;-o-transform:rotate(0deg) scaleX(1) scaleY(1)}}@-ms-keyframes spushake{0%{margin-left:0;opacity:1;-ms-transform:rotate(0deg) scaleX(1) scaleY(1)}10%{margin-left:-10px}20%{margin-left:10px}30%{margin-left:-10px}40%{margin-left:10px}50%{margin-left:-10px}60%{margin-left:10px}70%{margin-left:-10px}80%{margin-left:10px}90%{margin-left:-10px}100%{margin-left:0;opacity:1;-ms-transform:rotate(0deg) scaleX(1) scaleY(1)}}';
		}
		//wobble animation
		if( 'wobble' == $opts['animation']) {
			echo '#spu-'.$box->ID.'.spu-animate{animation:spuwobble linear 1s;animation-iteration-count:1;transform-origin:50% 50%;-webkit-animation:spuwobble linear 1s;-webkit-animation-iteration-count:1;-webkit-transform-origin:50% 50%;-moz-animation:spuwobble linear 1s;-moz-animation-iteration-count:1;-moz-transform-origin:50% 50%;-o-animation:spuwobble linear 1s;-o-animation-iteration-count:1;-o-transform-origin:50% 50%;-ms-animation:spuwobble linear 1s;-ms-animation-iteration-count:1;-ms-transform-origin:}@keyframes spuwobble{0%{opacity:1;transform:rotate(0deg) scaleX(1) scaleY(1)}15%{margin-left:-25px;transform:rotate(-5deg)}30%{margin-left:20px;transform:rotate(3deg)}45%{margin-left:-15px;transform:rotate(-3deg)}60%{margin-left:10px;transform:rotate(2deg)}75%{margin-left:-5px;transform:rotate(-1deg)}100%{opacity:1;transform:rotate(0deg) scaleX(1) scaleY(1)}}@-moz-keyframes spuwobble{0%{opacity:1;-moz-transform:rotate(0deg) scaleX(1) scaleY(1)}15%{margin-left:-25px;-moz-transform:rotate(-5deg)}30%{margin-left:20px;-moz-transform:rotate(3deg)}45%{margin-left:-15px;-moz-transform:rotate(-3deg)}60%{margin-left:10px;-moz-transform:rotate(2deg)}75%{margin-left:-5px;-moz-transform:rotate(-1deg)}100%{opacity:1;-moz-transform:rotate(0deg) scaleX(1) scaleY(1)}}@-webkit-keyframes spuwobble{0%{opacity:1;-webkit-transform:rotate(0deg) scaleX(1) scaleY(1)}15%{margin-left:-25px;-webkit-transform:rotate(-5deg)}30%{margin-left:20px;-webkit-transform:rotate(3deg)}45%{margin-left:-15px;-webkit-transform:rotate(-3deg)}60%{margin-left:10px;-webkit-transform:rotate(2deg)}75%{margin-left:-5px;-webkit-transform:rotate(-1deg)}100%{opacity:1;-webkit-transform:rotate(0deg) scaleX(1) scaleY(1)}}@-o-keyframes spuwobble{0%{opacity:1;-o-transform:rotate(0deg) scaleX(1) scaleY(1)}15%{margin-left:-25px;-o-transform:rotate(-5deg)}30%{margin-left:20px;-o-transform:rotate(3deg)}45%{margin-left:-15px;-o-transform:rotate(-3deg)}60%{margin-left:10px;-o-transform:rotate(2deg)}75%{margin-left:-5px;-o-transform:rotate(-1deg)}100%{opacity:1;-o-transform:rotate(0deg) scaleX(1) scaleY(1)}}@-ms-keyframes spuwobble{0%{opacity:1;-ms-transform:rotate(0deg) scaleX(1) scaleY(1)}15%{margin-left:-25px;-ms-transform:rotate(-5deg)}30%{margin-left:20px;-ms-transform:rotate(3deg)}45%{margin-left:-15px;-ms-transform:rotate(-3deg)}60%{margin-left:10px;-ms-transform:rotate(2deg)}75%{margin-left:-5px;-ms-transform:rotate(-1deg)}100%{opacity:1;-ms-transform:rotate(0deg) scaleX(1) scaleY(1)}}';
		}
		//ratatein animation
		if( 'rotate-in' == $opts['animation']) {
			echo '#spu-'.$box->ID.'.spu-animate{animation:spurotatein linear .7s;animation-iteration-count:1;transform-origin:;-webkit-animation:spurotatein linear .7s;-webkit-animation-iteration-count:1;-webkit-transform-origin:;-moz-animation:spurotatein linear .7s;-moz-animation-iteration-count:1;-moz-transform-origin:;-o-animation:spurotatein linear .7s;-o-animation-iteration-count:1;-o-transform-origin:;-ms-animation:spurotatein linear .7s;-ms-animation-iteration-count:1;-ms-transform-origin:}@keyframes spurotatein{0%{opacity:0;transform:rotate(-200deg) scaleX(1) scaleY(1)}100%{opacity:1;transform:rotate(0deg) scaleX(1) scaleY(1)}}@-moz-keyframes spurotatein{0%{opacity:0;-moz-transform:rotate(-200deg) scaleX(1) scaleY(1)}100%{opacity:1;-moz-transform:rotate(0deg) scaleX(1) scaleY(1)}}@-webkit-keyframes spurotatein{0%{opacity:0;-webkit-transform:rotate(-200deg) scaleX(1) scaleY(1)}100%{opacity:1;-webkit-transform:rotate(0deg) scaleX(1) scaleY(1)}}@-o-keyframes spurotatein{0%{opacity:0;-o-transform:rotate(-200deg) scaleX(1) scaleY(1)}100%{opacity:1;-o-transform:rotate(0deg) scaleX(1) scaleY(1)}}@-ms-keyframes spurotatein{0%{opacity:0;-ms-transform:rotate(-200deg) scaleX(1) scaleY(1)}100%{opacity:1;-ms-transform:rotate(0deg) scaleX(1) scaleY(1)}}';
		}
		// sppedy left
		if( 'speedy-left' == $opts['animation']) {
			echo '#spu-'.$box->ID.'.spu-animate{animation:spuspeedyleft ease-out .8s;animation-iteration-count:1;transform-origin:;animation-fill-mode:forwards;-webkit-animation:spuspeedyleft ease-out .8s;-webkit-animation-iteration-count:1;-webkit-transform-origin:;-webkit-animation-fill-mode:forwards;-moz-animation:spuspeedyleft ease-out .8s;-moz-animation-iteration-count:1;-moz-transform-origin:;-moz-animation-fill-mode:forwards;-o-animation:spuspeedyleft ease-out .8s;-o-animation-iteration-count:1;-o-transform-origin:;-o-animation-fill-mode:forwards;-ms-animation:spuspeedyleft ease-out .8s;-ms-animation-iteration-count:1;-ms-transform-origin:;-ms-animation-fill-mode:forwards}@keyframes spuspeedyleft{0%{margin-left:-600px;opacity:0;transform:rotate(0deg) scaleX(1) scaleY(1) skewX(-30deg)}60%{margin-left:-40px;opacity:1;transform:skewX(30deg)}80%{margin-left:0;transform:skewX(-15deg)}100%{margin-left:0;opacity:1;transform:rotate(0deg) scaleX(1) scaleY(1) skewX(0deg)}}@-moz-keyframes spuspeedyleft{0%{margin-left:-600px;opacity:0;-moz-transform:rotate(0deg) scaleX(1) scaleY(1) skewX(-30deg)}60%{margin-left:-40px;opacity:1;-moz-transform:skewX(30deg)}80%{margin-left:0;-moz-transform:skewX(-15deg)}100%{margin-left:0;opacity:1;-moz-transform:rotate(0deg) scaleX(1) scaleY(1) skewX(0deg)}}@-webkit-keyframes spuspeedyleft{0%{margin-left:-600px;opacity:0;-webkit-transform:rotate(0deg) scaleX(1) scaleY(1) skewX(-30deg)}60%{margin-left:-40px;opacity:1;-webkit-transform:skewX(30deg)}80%{margin-left:0;-webkit-transform:skewX(-15deg)}100%{margin-left:0;opacity:1;-webkit-transform:rotate(0deg) scaleX(1) scaleY(1) skewX(0deg)}}@-o-keyframes spuspeedyleft{0%{margin-left:-600px;opacity:0;-o-transform:rotate(0deg) scaleX(1) scaleY(1) skewX(-30deg)}60%{margin-left:-40px;opacity:1;-o-transform:skewX(30deg)}80%{margin-left:0;-o-transform:skewX(-15deg)}100%{margin-left:0;opacity:1;-o-transform:rotate(0deg) scaleX(1) scaleY(1) skewX(0deg)}}@-ms-keyframes spuspeedyleft{0%{margin-left:-600px;opacity:0;-ms-transform:rotate(0deg) scaleX(1) scaleY(1) skewX(-30deg)}60%{margin-left:-40px;opacity:1;-ms-transform:skewX(30deg)}80%{margin-left:0;-ms-transform:skewX(-15deg)}100%{margin-left:0;opacity:1;-ms-transform:rotate(0deg) scaleX(1) scaleY(1) skewX(0deg)}}';
		}
		// sppedy right
		if( 'speedy-right' == $opts['animation']) {
			echo '#spu-'.$box->ID.'.spu-animate{animation:spuspeedyright ease-out .8s;animation-iteration-count:1;transform-origin:;animation-fill-mode:forwards;-webkit-animation:spuspeedyright ease-out .8s;-webkit-animation-iteration-count:1;-webkit-transform-origin:;-webkit-animation-fill-mode:forwards;-moz-animation:spuspeedyright ease-out .8s;-moz-animation-iteration-count:1;-moz-transform-origin:;-moz-animation-fill-mode:forwards;-o-animation:spuspeedyright ease-out .8s;-o-animation-iteration-count:1;-o-transform-origin:;-o-animation-fill-mode:forwards;-ms-animation:spuspeedyright ease-out .8s;-ms-animation-iteration-count:1;-ms-transform-origin:;-ms-animation-fill-mode:forwards}@keyframes spuspeedyright{0%{margin-left:600px;opacity:0;transform:rotate(0deg) scaleX(1) scaleY(1) skewX(-30deg)}60%{margin-left:-40px;opacity:1;transform:skewX(30deg)}80%{margin-left:0;transform:skewX(-15deg)}100%{margin-left:0;opacity:1;transform:rotate(0deg) scaleX(1) scaleY(1) skewX(0deg)}}@-moz-keyframes spuspeedyright{0%{margin-left:600px;opacity:0;-moz-transform:rotate(0deg) scaleX(1) scaleY(1) skewX(-30deg)}60%{margin-left:-40px;opacity:1;-moz-transform:skewX(30deg)}80%{margin-left:0;-moz-transform:skewX(-15deg)}100%{margin-left:0;opacity:1;-moz-transform:rotate(0deg) scaleX(1) scaleY(1) skewX(0deg)}}@-webkit-keyframes spuspeedyright{0%{margin-left:600px;opacity:0;-webkit-transform:rotate(0deg) scaleX(1) scaleY(1) skewX(-30deg)}60%{margin-left:-40px;opacity:1;-webkit-transform:skewX(30deg)}80%{margin-left:0;-webkit-transform:skewX(-15deg)}100%{margin-left:0;opacity:1;-webkit-transform:rotate(0deg) scaleX(1) scaleY(1) skewX(0deg)}}@-o-keyframes spuspeedyright{0%{margin-left:600px;opacity:0;-o-transform:rotate(0deg) scaleX(1) scaleY(1) skewX(-30deg)}60%{margin-left:-40px;opacity:1;-o-transform:skewX(30deg)}80%{margin-left:0;-o-transform:skewX(-15deg)}100%{margin-left:0;opacity:1;-o-transform:rotate(0deg) scaleX(1) scaleY(1) skewX(0deg)}}@-ms-keyframes spuspeedyright{0%{margin-left:600px;opacity:0;-ms-transform:rotate(0deg) scaleX(1) scaleY(1) skewX(-30deg)}60%{margin-left:-40px;opacity:1;-ms-transform:skewX(30deg)}80%{margin-left:0;-ms-transform:skewX(-15deg)}100%{margin-left:0;opacity:1;-ms-transform:rotate(0deg) scaleX(1) scaleY(1) skewX(0deg)}}';
		}
		//hinge animation
		if( 'hinge' == $opts['animation']) {
			echo '#spu-'.$box->ID.'.spu-animate{animation:spuhinge ease 1s;animation-iteration-count:1;transform-origin:0 0;animation-fill-mode:forwards;-webkit-animation:spuhinge ease 1s;-webkit-animation-iteration-count:1;-webkit-transform-origin:0 0;-webkit-animation-fill-mode:forwards;-moz-animation:spuhinge ease 1s;-moz-animation-iteration-count:1;-moz-transform-origin:0 0;-moz-animation-fill-mode:forwards;-o-animation:spuhinge ease 1s;-o-animation-iteration-count:1;-o-transform-origin:0 0;-o-animation-fill-mode:forwards;-ms-animation:spuhinge ease 1s;-ms-animation-iteration-count:1;-ms-transform-origin:0 0;-ms-animation-fill-mode:forwards}@keyframes spuhinge{0%{opacity:1;transform:rotate(0deg) scaleX(1) scaleY(1)}20%{transform:rotate(60deg)}40%{transform:rotate(40deg)}60%{transform:rotate(54deg)}80%{transform:rotate(42deg)}100%{opacity:1;transform:rotate(0deg) scaleX(1) scaleY(1)}}@-moz-keyframes spuhinge{0%{opacity:1;-moz-transform:rotate(0deg) scaleX(1) scaleY(1)}20%{-moz-transform:rotate(60deg)}40%{-moz-transform:rotate(40deg)}60%{-moz-transform:rotate(54deg)}80%{-moz-transform:rotate(42deg)}100%{opacity:1;-moz-transform:rotate(0deg) scaleX(1) scaleY(1)}}@-webkit-keyframes spuhinge{0%{opacity:1;-webkit-transform:rotate(0deg) scaleX(1) scaleY(1)}20%{-webkit-transform:rotate(60deg)}40%{-webkit-transform:rotate(40deg)}60%{-webkit-transform:rotate(54deg)}80%{-webkit-transform:rotate(42deg)}100%{opacity:1;-webkit-transform:rotate(0deg) scaleX(1) scaleY(1)}}@-o-keyframes spuhinge{0%{opacity:1;-o-transform:rotate(0deg) scaleX(1) scaleY(1)}20%{-o-transform:rotate(60deg)}40%{-o-transform:rotate(40deg)}60%{-o-transform:rotate(54deg)}80%{-o-transform:rotate(42deg)}100%{opacity:1;-o-transform:rotate(0deg) scaleX(1) scaleY(1)}}@-ms-keyframes spuhinge{0%{opacity:1;-ms-transform:rotate(0deg) scaleX(1) scaleY(1)}20%{-ms-transform:rotate(60deg)}40%{-ms-transform:rotate(40deg)}60%{-ms-transform:rotate(54deg)}80%{-ms-transform:rotate(42deg)}100%{opacity:1;-ms-transform:rotate(0deg) scaleX(1) scaleY(1)}}';
		}
		// full screnn mode
		if( 'full-screen' == $opts['css']['position']) {
			echo '#spu-'. $box->ID.' { background-color: transparent !important;}';
			echo '#spu-bg-'. $box->ID.' { background-color: '. esc_attr($css['background_color']) .';}';
		}
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function register_scripts() {


		$opts = $this->spu_settings;
		$handle = 'spup-public';

		$js_url = plugins_url( 'assets/js/min/public-min.js', __FILE__ );
		if( defined( 'SPU_DEBUG_MODE' ) || !empty( $opts['debug'] ) ) {
			$js_url = plugins_url( 'assets/js/public.js', __FILE__ );
			$handle = 'spup-public-debug';
		}
		//remove free version js
		wp_dequeue_script( str_replace( 'spup', 'spu', $handle ));

		//load new css
		wp_register_style( 'spup-public-css', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );



		wp_enqueue_style('spup-public-css');
		wp_enqueue_script( $handle, $js_url, '', self::VERSION, true );
		wp_localize_script( $handle, 'spuvar', array(
			'is_admin' 						=> current_user_can( apply_filters( 'spu/capabilities/testmode', 'administrator' ) ),
			'l18n'			=> array(
				'wait'		=> __( "Please wait", 'spup' ),
				'seconds' 	=> __( "seconds ", 'spup'),
				'name_error' 	=> __( "Please enter a valid name", 'spup'),
				'email_error' 	=> __( "Please enter a valid email", 'spup'),
			),
			'disable_style' 				=> esc_attr( isset( $opts['shortcodes_style'] ) ? $opts['shortcodes_style'] : '' ),
			'safe_mode' 					=> esc_attr( isset( $opts['safe'] ) ? $opts['safe'] : '' ),
			'ajax_mode'						=> esc_attr( isset( $opts['ajax_mode'] ) ? $opts['ajax_mode'] : '' ),
			'site_url'						=> site_url('/'),
			'ajax_mode_url'					=> site_url('/?spu_action=spu_load&lang='.$this->info['wpml_lang']),
			'ajax_url'      				=> admin_url( 'admin-ajax.php'),
			'pid'						    => get_queried_object_id(),
			'is_front_page'				    => is_front_page(),
			'is_category'				    => is_category(),
			'is_archive'				    => is_archive(),
			'is_search'				        => is_search(),
			'seconds_confirmation_close'	=> apply_filters( 'spu/spuvar/seconds_confirmation_close', 5 ),
			'dsampling'                     => esc_attr( isset( $opts['dsampling'] ) ? $opts['dsampling'] : '' ),
			'dsamplingrate'                 => esc_attr( isset( $opts['dsamplingrate'] ) ? $opts['dsamplingrate'] : '' ),
			'disable_stats'                 => defined('SPUP_DISABLE_STATS')
		) );
		wp_localize_script( $handle, 'spuvar_social', $GLOBALS['spuvar_social']);
	}



	/**
	 * Returns plugin info
	 * @param  string $i info name
	 * @return mixed one all or none
	 */
	function get_info( $i )
	{
		// vars
		$return = false;


		// specific
		if( isset($this->info[ $i ]) )
		{
			$return = $this->info[ $i ];
		}


		// all
		if( $i == 'all' )
		{
			$return = $this->info;
		}


		// return
		return $return;
	}


	public function handle_optin_forms(){
		// Honeypot
		if( !empty($_POST['email']) || !empty($_POST['web']) ){
			echo json_encode(array( 'error' => 'honeypot'));
			die();
		}


		$box_id = $_POST['box_id'];

		$box_opts = apply_filters('spu/metaboxes/get_box_options', $this->helper->get_box_options( $box_id ), $box_id );

		// If not box opts or wrong id
		if( empty ( $box_opts ) ){
			echo json_encode(array( 'error' => 'nobox'));
			die();
		}

		$sender_class = 'SPU_'. $box_opts['optin'];

		// Create provider class
		$sender = new $sender_class();

		// Map lead
		$lead = array(
			'email' => $_POST['spu-email'],
			'name'  => !empty($_POST['spu-name']) ? $_POST['spu-name'] : '',
		);

		$result = $sender->subscribe($lead, $box_opts);

		if( $result === true ) {

			if( !empty($box_opts['optin_redirect']) ) {
				echo json_encode( array( 'redirect' => $box_opts['optin_redirect'], 'lead' => $lead ) );
				die();
			}

			if( !empty($box_opts['optin_success']) ){
				echo json_encode( array( 'success_msg' => do_shortcode( $box_opts['optin_success'] ) ) );
				die();
			}

			echo json_encode( array( 'success' => 'true') );
		} else {
			echo json_encode(array( 'error' => $result));
		}
		die();
	}

	/**
	 * If we have optins we need to add the proper class to the box in free plugin
	 *
	 * @param $box_class existing box class
	 * @param $opts popup cpt settings
	 * @param $css current css taken from opts
	 * @param $box popup cpt
	 * @since 1.5
	 * @return string
	 */
	function add_optin_class_to_box( $box_class, $opts, $css, $box ) {
		// Optin popup ?
		if( !empty( $opts['optin'] ) ) {
			$box_class  .= ' spu-optin';
			if( !empty( $opts['optin_theme'] ) )
				$box_class  .= ' spu-theme-'.$opts['optin_theme'];
			if( isset( $opts['optin_display_name'] ) && $opts['optin_display_name'] == '1' )
				$box_class  .= ' with-spu-name';
		}
		return $box_class;
	}

	/**
	 * Action executed in the style block of popup view
	 * @param $box
	 * @param $opts
	 * @param $css
	 * @since 1.5
	 */
	function add_custom_style_to_box( $box, $opts, $css ) {
		// this is not needed as styles are added inline when popup is edit.
		// I will leave in case is needed later for other styles
		/*?>
		#spu-<?php echo $box->ID; ?> button.spu-submit {
			background-color : <?php echo $css['button_bg'];?>;
			border-color :<?php echo $css['button_bg'];?>;
			color : <?php echo $css['button_color'];?>;
		}
		<?php*/
	}


	/**
	 * Count how many times a user visits our pages
	 * I use template_redirect to match only frontend
	 * Used on n_visited_pages rule
	 */
	function sessionCounter() {

		if( defined( 'DOING_AJAX') || apply_filters('spu/disable_sessions',false ) )
			return;

		if( isset( $_SESSION['spu_views'] ) ) {
			$_SESSION['spu_views'] = $_SESSION['spu_views'] + 1;
		} else {
			$_SESSION['spu_views'] = 1;
		}
	}

	/**
	 * Load files
	 */
	private function loadDependencies() {
		if( ! apply_filters('spu/disable_sessions',false ) ) {
			if( session_id() == '' )
				session_start();
		}
		require_once( SPUP_PLUGIN_DIR . 'includes/class-spup-rules.php' );
		$rules = new SPUP_Rules();
	}

	/**
	 * On after content position mode we need a placeholder to replace with our actual popup
	 *
	 * @param $content
	 *
	 * @return string
	 */
	function add_placeholder_to_content( $content ) {
		// only on single pages
		if( ! is_single() && ! is_singular() )
			return $content;
		$content .= '<div class="spu-placeholder" style="display:none"></div>';
		return $content;
	}

	/**
	 * Check every popup to see if they are child of AB test and add them to the spu_matches
	 *
	 * @param $spu_matches Array of ids that will be displayed
	 * @param $spu_ids Array of all popups ids
	 *
	 * @return array
	 */
	function add_ab_popups_to_matches( $spu_matches, $spu_ids ) {
		if( empty( $spu_ids ) )
			return $spu_matches;

		$ab_popups = array();

		foreach ( $spu_ids as $spu ) {
			if( !empty( $spu->spu_ab_parent ) && in_array( $spu->spu_ab_parent, $spu_matches ) ) {
				$spu_matches[$spu->ID] = $spu->ID;
				$ab_popups[$spu->spu_ab_parent][] = $spu->ID;
			}
		}
		if( !empty( $ab_popups ) ) {


			foreach ($ab_popups as $ab_parent => $childs ) {
				// how many varitions exists ?
				$total = count( $childs ) + 1; // we add one for the parent
				// flip the coin
				$coin = rand( 1, $total );
				// we leave the parent and unset the childs
				if( $coin == 1 ) {
					if( !empty( $childs ) ) {

						foreach ($childs as $id) {

							unset( $spu_matches[$id]);
						}
					}
				} else { // the winner is one of the childs
					//so unset parent
				 	unset( $spu_matches[$ab_parent]);
				 	// unset other childs
					if( !empty( $childs ) ) {
						// grab the winner
						$winner_child = array_slice( $childs, $coin - 2, 1);
						$winner = array_pop($winner_child);

						// unset all
						foreach ($childs as $id) {
							unset( $spu_matches[$id]);
						}
						// add the winner back
						$spu_matches[$winner] = $winner;
					}
				}
			}
		}

		return $spu_matches;
	}
}
