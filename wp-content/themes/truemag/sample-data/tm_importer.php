<?php
defined( 'ABSPATH' ) or die( 'You cannot access this script directly' );

add_action( 'admin_init', 'tm_importer' );
function tm_importer() {
	global $wpdb;
	
	$sample_data_folder = get_template_directory() . '/sample-data/';
	
	if ( current_user_can( 'manage_options' ) && isset($_GET['imported'])){
		add_action( 'admin_notices', 'ct_admin_notice_data_imported' );
	}
	if ( current_user_can( 'manage_options' ) && isset( $_GET['import_data'] ) ) {	
		
		if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true);
		
		if ( ! class_exists( 'WP_Importer' ) ) { // if main importer class doesn't exist
			include ABSPATH . 'wp-admin/includes/class-wp-importer.php';
		}

		if ( ! class_exists('WP_Import') ) { // if WP importer doesn't exist
			include get_template_directory() . '/inc/plugins/wordpress-importer/wordpress-importer.php';
		}
		
		$importer = new WP_Import();

		/* Import Menus, Posts, Pages */
		
		$theme_xml = $sample_data_folder . 'truemag.xml.gz';
		
		$importer->fetch_attachments = false; /* Fetch sample images */
		ob_start();
		$importer->import($theme_xml);
		ob_end_clean();
		
		/* Setting Menu Locations */
		$locations = get_theme_mod( 'nav_menu_locations' ); // registered menu locations in theme
		$menus = wp_get_nav_menus(); // registered menus

		if($menus) {
			foreach($menus as $menu) { // assign menus to theme locations
				if( $menu->name == 'Main Navigation' ) {
					$locations['main-navigation'] = $menu->term_id;
				}
				if( $menu->name == 'Footer Navigation' ) {
					$locations['footer-navigation'] = $menu->term_id;
				}
			}
		}

		set_theme_mod( 'nav_menu_locations', $locations ); // set menus to locations

		$sample_data_uri = get_template_directory_uri() . '/sample-data/';
		/* Import Theme Options */
		$theme_options_txt = $sample_data_uri . 'theme-options.txt'; // theme options data file

		$theme_options_txt = wp_remote_get( $theme_options_txt );
		if(is_array($theme_options_txt)){
			$data = unserialize( base64_decode( $theme_options_txt['body'])  );
			tm_import_themeoptions($data);
		}
		
		/* Import Widget Settings */
		$widgets_json = $sample_data_uri . 'widget_data.json'; // widgets data file
		$widgets_json = wp_remote_get( $widgets_json );
		if(is_array($widgets_json)){
			$widget_data = $widgets_json['body'];			
			$import_widgets = tm_import_widgets( $widget_data );
		}
		
		// tm_import_revslider(); // let users do it themselves
		
		tm_set_reading_options();

		// finally redirect to success page
		wp_redirect( admin_url( 'themes.php?page=ot-theme-options&imported=success' ) );
	}
}

function tm_import_themeoptions($data){
	/* get settings array */
	$settings = get_option( 'option_tree_settings' );
		
	/* validate options */
	if ( is_array( $settings ) ) {
	
	  foreach( $settings['settings'] as $setting ) {
	  
		if ( isset( $data[$setting['id']] ) ) {
		  
		  $content = ot_stripslashes( $data[$setting['id']] );
		  
		  $data[$setting['id']] = ot_validate_setting( $content, $setting['type'], $setting['id'] );
		  
		}
	  
	  }
	
	}
	
	/* execute the action hook and pass the theme options to it */
	do_action( 'ot_before_theme_options_save', $data );
  
	/* update the option tree array */
	update_option( 'option_tree', $data );
}

/* Set reading options */
function tm_set_reading_options(){
	$homepage = get_page_by_title( 'Home Page' );
	$posts_page = get_page_by_title( 'Blog' );
	if($homepage->ID && $posts_page->ID) {
		update_option('show_on_front', 'page');
		update_option('page_on_front', $homepage->ID); // Front Page
		update_option('page_for_posts', $posts_page->ID); // Blog Page
		update_option('posts_per_page', 8);
	}
}

// Import Widget Settings
// Thanks to http://wordpress.org/plugins/widget-settings-importexport/
function tm_import_widgets($widget_data){
	$json_data = $widget_data;
	$json_data = json_decode( $json_data, true );

	$sidebar_data = $json_data[0];
	$widget_data = $json_data[1];
	
	foreach ( $widget_data as $widget_data_title => $widget_data_value ) {
		$widgets[ $widget_data_title ] = '';
		foreach( $widget_data_value as $widget_data_key => $widget_data_array ) {
			if( is_int( $widget_data_key ) ) {
				$widgets[$widget_data_title][$widget_data_key] = 'on';
			}
		}
	}
	unset($widgets[""]);

	foreach ( $sidebar_data as $title => $sidebar ) {
		$count = count( $sidebar );
		for ( $i = 0; $i < $count; $i++ ) {
			$widget = array( );
			$widget['type'] = trim( substr( $sidebar[$i], 0, strrpos( $sidebar[$i], '-' ) ) );
			$widget['type-index'] = trim( substr( $sidebar[$i], strrpos( $sidebar[$i], '-' ) + 1 ) );
			if ( !isset( $widgets[$widget['type']][$widget['type-index']] ) ) {
				unset( $sidebar_data[$title][$i] );
			}
		}
		$sidebar_data[$title] = array_values( $sidebar_data[$title] );
	}

	foreach ( $widgets as $widget_title => $widget_value ) {
		foreach ( $widget_value as $widget_key => $widget_value ) {
			$widgets[$widget_title][$widget_key] = $widget_data[$widget_title][$widget_key];
		}
	}

	$sidebar_data = array( array_filter( $sidebar_data ), $widgets );

	tm_parse_import_data( $sidebar_data );
}

function tm_parse_import_data( $import_array ) {
	$sidebars_data = $import_array[0];
	$widget_data = $import_array[1];
	$current_sidebars = get_option( 'sidebars_widgets' );
	$new_widgets = array( );
	foreach ( $sidebars_data as $import_sidebar => $import_widgets ) :
		$current_sidebars[$import_sidebar] = array(); // clear current widgets in sidebar
		
		foreach ( $import_widgets as $import_widget ) :
			//if the sidebar exists
			if ( isset( $current_sidebars[$import_sidebar] ) ) :
				
				$title = trim( substr( $import_widget, 0, strrpos( $import_widget, '-' ) ) );
				$index = trim( substr( $import_widget, strrpos( $import_widget, '-' ) + 1 ) );
				$current_widget_data = get_option( 'widget_' . $title );
				$new_widget_name = ct_get_new_widget_name( $title, $index );
				$new_index = trim( substr( $new_widget_name, strrpos( $new_widget_name, '-' ) + 1 ) );

				if ( !empty( $new_widgets[ $title ] ) && is_array( $new_widgets[$title] ) ) {
					while ( array_key_exists( $new_index, $new_widgets[$title] ) ) {
						$new_index++;
					}
				}
				$current_sidebars[$import_sidebar][] = $title . '-' . $new_index;
				if ( array_key_exists( $title, $new_widgets ) ) {
					$new_widgets[$title][$new_index] = $widget_data[$title][$index];
					$multiwidget = $new_widgets[$title]['_multiwidget'];
					unset( $new_widgets[$title]['_multiwidget'] );
					$new_widgets[$title]['_multiwidget'] = $multiwidget;
				} else {
					$current_widget_data[$new_index] = $widget_data[$title][$index];
					$current_multiwidget = $current_widget_data['_multiwidget'];
					$new_multiwidget = isset($widget_data[$title]['_multiwidget']) ? $widget_data[$title]['_multiwidget'] : false;
					$multiwidget = ($current_multiwidget != $new_multiwidget) ? $current_multiwidget : 1;
					unset( $current_widget_data['_multiwidget'] );
					$current_widget_data['_multiwidget'] = $multiwidget;
					$new_widgets[$title] = $current_widget_data;
				}

			endif;
		endforeach;
	endforeach;

	if ( isset( $new_widgets ) && isset( $current_sidebars ) ) {
		update_option( 'sidebars_widgets', $current_sidebars );

		foreach ( $new_widgets as $title => $content )
			update_option( 'widget_' . $title, $content );

		return true;
	}

	return false;
}

function ct_get_new_widget_name( $widget_name, $widget_index ) {
	$current_sidebars = get_option( 'sidebars_widgets' );
	$all_widget_array = array( );
	foreach ( $current_sidebars as $sidebar => $widgets ) {
		if ( !empty( $widgets ) && is_array( $widgets ) && $sidebar != 'wp_inactive_widgets' ) {
			foreach ( $widgets as $widget ) {
				$all_widget_array[] = $widget;
			}
		}
	}
	while ( in_array( $widget_name . '-' . $widget_index, $all_widget_array ) ) {
		$widget_index++;
	}
	$new_widget_name = $widget_name . '-' . $widget_index;
	return $new_widget_name;
}

function ct_admin_notice_data_imported(){
	?>
	<div class="updated">
        <p><?php _e( 'Sample data imported!', 'cactusthemes' ); ?></p>
    </div>
	<?php
}