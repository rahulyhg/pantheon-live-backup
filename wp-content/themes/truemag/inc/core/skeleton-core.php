<?php

/**
 * =============== CACTUSTHEMES.COM ===================
 * ======== Cactusthemes Skeleton Framework ===========
 *          version 0.1 - created 29/4/2013
 * ====================================================
 */
 
require_once 'utility-functions.php';

if( !class_exists( 'Mobile_Detect' ) ) {
require_once locate_template('/inc/mobile-detect.php');
}
require_once locate_template('/inc/classes/class.content-helper.php');
require_once locate_template('/inc/classes/class.content-html.php');

$detect = new Mobile_Detect;
global $_device_, $_device_name_, $_is_retina_;
$_device_ = $detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'pc';
$_device_name_ = $detect->mobileGrade();
$_is_retina_ = $detect->isRetina();

/**
 * Option Tree integration ===========
 */
 /**
 * Optional: set 'ot_show_pages' filter to false.
 * This will hide the settings & documentation pages.
 */
add_filter( 'ot_show_pages', '__return_true' );

/**
 * Optional: set 'ot_show_new_layout' filter to false.
 * This will hide the "New Layout" section on the Theme Options page.
 */
add_filter( 'ot_show_new_layout', '__return_false' );

/**
 * Required: set 'ot_theme_mode' filter to true.
 */
add_filter( 'ot_theme_mode', '__return_true' );

/**
 * Required: include OptionTree Framework.
 */
load_template( trailingslashit( get_template_directory() ) . '/option-tree/ot-loader.php' );
/**
 * Required: include Meta Box Master.
 */
load_template( trailingslashit( get_template_directory() ) . '/inc/meta-box-master/meta-box.php' );
/** 
 * End Option Tree integration ===============================
 * To get options, use this code
 * $test_input = ot_get_option( 'test_input', 'default value');
 * $test_array = ot_get_option( 'test_array', array('value 1','value 2')); or 
 * $test_array = ot_get_option( 'test_array', array());
 */

 /* Support metadata boxes */
require_once locate_template('/inc/meta-boxes.php');
/* Include widgets */
require_once locate_template('/inc/widgets.php');
/* Custom Menu Walker */
require_once locate_template('/inc/custom-menu-walker.php');

require_once locate_template('/inc/plugins/plugin-activation/class-tgm-plugin-activation.php' );


add_action( 'tgmpa_register', 'tm_acplugins' );
function tm_acplugins($plugins) {
	
	global $_theme_required_plugins;
	
    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'domain'            => 'cactusthemes',           // Text domain - likely want to be the same as your theme.
        'default_path'      => '',                           // Default absolute path to pre-packaged plugins
        'parent_menu_slug'  => 'themes.php',         // Default parent menu slug
        'parent_url_slug'   => 'themes.php',         // Default parent URL slug
        'menu'              => 'install-required-plugins',   // Menu slug
        'has_notices'       => true,                         // Show admin notices or not
        'is_automatic'      => false,            // Automatically activate plugins after installation or not
        'message'           => '',               // Message to output right before the plugins table
        'strings'           => array(
            'page_title'                                => __( 'Install Required &amp; Recommended Plugins', 'cactusthemes' ),
            'menu_title'                                => __( 'Install Plugins', 'cactusthemes' ),
            'installing'                                => __( 'Installing Plugin: %s', 'cactusthemes' ), // %1$s = plugin name
            'oops'                                      => __( 'Something went wrong with the plugin API.', 'cactusthemes' ),
            'notice_can_install_required'               => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
            'notice_can_install_recommended'            => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
            'notice_cannot_install'                     => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
            'notice_can_activate_required'              => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
            'notice_can_activate_recommended'           => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
            'notice_cannot_activate'                    => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
            'notice_ask_to_update'                      => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
            'notice_cannot_update'                      => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
            'install_link'                              => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
            'activate_link'                             => _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
            'return'                                    => __( 'Return to Required Plugins Installer', 'cactusthemes' ),
            'plugin_activated'                          => __( 'Plugin activated successfully.', 'cactusthemes' ),
            'complete'                                  => __( 'All plugins installed and activated successfully. %s', 'cactusthemes' ) // %1$s = dashboard link
        )
    );
 
    tgmpa( $_theme_required_plugins, $config);
}

/* Enable oEmbed in Text/HTML Widgets */
add_filter( 'widget_text', array( $wp_embed, 'run_shortcode' ), 8 );
add_filter( 'widget_text', array( $wp_embed, 'autoembed'), 8 );