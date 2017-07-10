<?php
/*
  Plugin Name: 00.php
  Author URI: http://getpantheon.com
  Description: Ensure WP Native PHP Sessions plugin is loaded first
*/
if ( isset( $_ENV['PANTHEON_ENVIRONMENT'] ) ) :
   require_once(ABSPATH . '/wp-content/mu-plugins/wp-native-php-sessions/pantheon-sessions.php');
endif;
