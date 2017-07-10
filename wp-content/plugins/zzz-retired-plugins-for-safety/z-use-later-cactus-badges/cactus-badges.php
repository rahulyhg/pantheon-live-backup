<?php

/*
Plugin Name: Cactus Badges
Description: Badges Features for CactusThemes's themes
Author: Cactusthemes
Version: 1.0
Author URI: http://cactusthemes.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(!function_exists('ct_badges_get_plugin_url')){
	function ct_badges_get_plugin_url(){
		return plugin_dir_path(__FILE__);
	}
}
if(!class_exists('Cactus_badges')){	
	class Cactus_badges{
		public function __construct() {
			// constructor
			$this->includes();			
			add_action( 'init', array($this,'init'), 0);
			add_action( 'after_setup_theme', array($this,'includes_after'), 0 );
		}
		function template_loader($template){
			$find = array('cactus-badges.php');
			if(is_tax( 'cactus_badges' )){
				wp_redirect( get_template_part( '404' ) ); exit;
			}
			return $template;
		}
		function enqueue_scripts() {}
		
		function enqueue_styles(){
		}
		
		function enqueue_admin_styles() {
		}
		function includes_after(){
		}
		function includes(){
			include ct_badges_get_plugin_url().'function.php';
			include ct_badges_get_plugin_url().'shortcode/badges.php';
		}
		
		function init(){
			$this->register_badges_taxonomies();
			add_filter( 'template_include', array( $this, 'template_loader' ) );
			// Variables
			add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts') );
			add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
			add_action( 'admin_enqueue_scripts', array($this, 'enqueue_admin_styles') );
		}
		
		function register_badges_taxonomies(){
			$badges_label = array(
				'name'              => esc_html__( 'Badges', 'videopro' ),
				'singular_name'     => esc_html__( 'Badges', 'videopro' ),
				'search_items'      => esc_html__( 'Search','videopro' ),
				'all_items'         => esc_html__( 'All Badges','videopro' ),
				'parent_item'       => esc_html__( 'Parent Badges' ,'videopro'),
				'parent_item_colon' => esc_html__( 'Parent Badges:','videopro' ),
				'edit_item'         => esc_html__( 'Edit Badge' ,'videopro'),
				'update_item'       => esc_html__( 'Update Badge','videopro' ),
				'add_new_item'      => esc_html__( 'Add New Badge' ,'videopro'),
				'menu_name'         => esc_html__( 'Badges' ),
			);			
			$args = array(
				'hierarchical'          => true,
				'labels'                => $badges_label,
				'show_admin_column'     => true,
				'rewrite'               => false,
			);
			
			register_taxonomy('cactus_badges', 'post', $args);
		}
	}
}

$Cactus_badges = new Cactus_badges();
