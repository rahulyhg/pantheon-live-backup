<?php
   /*
   Plugin Name: Truemag - Member
   Plugin URI: http://www.cactusthemes.com
   Description: Truemag - Member post type functions
   Version: 3.2.2
   Author: Cactusthemes
   Author URI: http://www.cactusthemes.com
   License: GPL2
   */
   
if ( ! defined( 'GP_MEMBER_BASE_FILE' ) )
    define( 'GP_MEMBER_BASE_FILE', __FILE__ );
if ( ! defined( 'GP_MEMBER_BASE_DIR' ) )
    define( 'GP_MEMBER_BASE_DIR', dirname( GP_MEMBER_BASE_FILE ) );
if ( ! defined( 'GP_MEMBER_PLUGIN_URL' ) )
    define( 'GP_MEMBER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
include('ct-post-type.php');
include('ct-shortcode-member.php');
