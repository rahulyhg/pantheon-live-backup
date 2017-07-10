<?php
/**
 * @package True Mag
 * @version 4.2.8.8
 */
/*
Plugin Name: Truemag - Playlist
Description: Provide Video-related features for True Mag
Author: Castusthemes
Version: 1.1.2.3
Author URI: http://cactusthemes.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
include('shortcode/cactus-player.php');
if(!class_exists('Cactus_video')){	
/*translate settings*/
$text_translate_playlist_st = __('Playlist settings','cactusthemes').__('Playlist slug','cactusthemes').__('Change single playlist slug. Remember to save the permalink settings again in Settings > Permalinks','cactusthemes').__('Playlists Listing page','cactusthemes').__('Assign Playlists Listing page to a page. Remember to save the permalink settings again in Settings > Permalinks','cactusthemes').__('Single Playlist sidebar','cactusthemes').__('Full Width','cactusthemes').__('Sidebar Right','cactusthemes').__('Sidebar Left','cactusthemes');				

class Cactus_video{
	/* custom template relative url in theme, default is "ct_video" */
	public $template_url;
	public static $woocommerce;
	/* Plugin path */
	public $plugin_path;
	
	/* Main query */
	public $query;
	
	public function __construct() {
		// constructor
		$this->includes();
		$this->register_configuration();
		
		add_action( 'init', array($this,'init'), 0);
		add_action( 'after_setup_theme', array($this,'includes_after'), 0 );
		add_action( 'template_redirect', array($this,'ct_plstop_redirect'), 0);
	}
	function ct_plstop_redirect(){
		if ( is_singular('ct_playlist') ) {
			global $wp_query;
			$page = (int) $wp_query->get('page');
			if ( $page > 1 ) {
		 		 // convert 'page' to 'paged'
		  		$query->set( 'page', 1 );
		  		$query->set( 'paged', $page );
			}
		// prevent redirect
		remove_action( 'template_redirect', 'redirect_canonical' );
	  }
	}
	function ct_video_scripts_styles() {
		global $wp_styles;
		
		/*
		 * Loads our main javascript.
		 */	
		wp_enqueue_script( 'custom',plugins_url('/js/custom.js', __FILE__) , array(), '', true );
		wp_enqueue_script( 'ajax-request',plugins_url('/js/ajax.js', __FILE__) , array( 'jquery' ), '4.4.2' );
		$js_params = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
		global $wp_query, $wp;
		$js_params['query_vars'] = $wp_query->query_vars;
		$js_params['current_url'] =  home_url($wp->request);
		wp_localize_script( 'ajax-request', 'cactus_ajax_paging', $js_params  );
	}
	function admin_video_scripts_styles() {
		global $wp_styles;
			wp_enqueue_style('admin-video-css',plugins_url('/css/admin-css.css', __FILE__));
	}
	function includes_after(){
		include_once('video-functions.php');
	}
	function includes(){
		// custom meta boxes
		if(!function_exists('cmb_init')){
			if(!class_exists('CMB_Meta_Box')){
				include_once('includes/Custom-Meta-Boxes-master/custom-meta-boxes.php');
			}
		}
		if(!class_exists('Options_Page')){
			include_once('includes/options-page/options-page.php');
		}
		//include_once('classes/u-project-query.php');
	}
	
	/* This is called as soon as possible to set up options page for the plugin
	 * after that, $this->get_option($name) can be called to get options.
	 *
	 */
	function register_configuration(){
		global $ct_video_settings;
		$ct_video_settings = new Options_Page('ct_video_settings', array('option_file'=>dirname(__FILE__) . '/options.xml','menu_title'=>__('Cactus PlayList Settings','cactusthemes'),'menu_position'=>null), array('page_title'=>__('Cactus PlayList Setting Page','cactusthemes'),'submit_text'=>__('Save','cactusthemes')));
	}
	
	/* Get main options of the plugin. If there are any sub options page, pass Options Page Id to the second args
	 *
	 *
	 */
	function get_option($option_name, $op_id = ''){
		return $GLOBALS[$op_id != ''?$op_id:'ct_video_settings']->get($option_name);
	}
	
	function init(){
		// Variables
		$this->register_taxonomies();
		$this->template_url			= apply_filters( 'ct_video_template_url', 'cactus-video/' );
		add_filter( 'cmb_meta_boxes', array($this,'register_post_type_metadata') );
		add_filter( 'template_include', array( $this, 'template_loader' ) );
		//add_action( 'template_redirect', array($this, 'template_redirect' ) );
		add_action( 'wp_enqueue_scripts', array($this, 'ct_video_scripts_styles') );
		add_action( 'admin_enqueue_scripts', array($this, 'admin_video_scripts_styles') );
	}	
	/**
	 * Get the plugin path.
	 *
	 * @access public
	 * @return string
	 */
	public function plugin_path() {
		if ( $this->plugin_path ) return $this->plugin_path;

		return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
	}
	/**
	 *
	 * Load custom page template for specific pages 
	 *
	 * @return string
	 */
	function template_loader($template){
		$find = array('cactus-video.php');
		$file = '';
		$slug_lis = '';
		$slug_lis =  $this->get_option('playlist-lis-slug');
		if(!is_numeric($slug_lis)){
			$slug_lis = 'playlist';
		}
		if(is_post_type_archive( 'ct_playlist' ) || is_page($slug_lis)){
			$file = 'playlist-listing.php';
			$find[] = $file;
			$find[] = $this->template_url . $file;
		}
		elseif(is_singular('ct_playlist')){
			$file = 'single-playlist.php';
			$find[] = $file;
			$find[] = $this->template_url . $file;
		}
		if ( $file ) {
			$template = locate_template( $find );
			
			if ( ! $template ) $template = $this->plugin_path() . '/templates/' . $file;
		}
		return $template;		
	}
	
	/**
	 * Handle redirects before content is output - hooked into template_redirect so is_page works.
	 *
	 * @access public
	 * @return void
	 */
	function template_redirect(){
		global $ct_video, $wp_query;

		// When default permalinks are enabled, redirect stores page to post type archive url
		if ( ! empty( $_GET['page_id'] ) && get_option( 'permalink_structure' ) == "" && $_GET['page_id'] ==  'video') {
			wp_safe_redirect( get_post_type_archive_link('ct_video') );
			exit;
		}
	}

	function register_taxonomies(){
		$this->register_ct_video();
	}
	/* Register ct_channel post type and its custom taxonomies */
	function register_ct_video(){
				$labels = array(
			'name'               => __('Playlist', 'cactusthemes'),
			'singular_name'      => __('Playlist', 'cactusthemes'),
			'add_new'            => __('Add New Playlist', 'cactusthemes'),
			'add_new_item'       => __('Add New Playlist', 'cactusthemes'),
			'edit_item'          => __('Edit Playlist', 'cactusthemes'),
			'new_item'           => __('New Playlist', 'cactusthemes'),
			'all_items'          => __('All Playlists', 'cactusthemes'),
			'view_item'          => __('View Playlist', 'cactusthemes'),
			'search_items'       => __('Search Playlists', 'cactusthemes'),
			'not_found'          => __('No Playlist found', 'cactusthemes'),
			'not_found_in_trash' => __('No Playlist found in Trash', 'cactusthemes'),
			'parent_item_colon'  => '',
			'menu_name'          => __('Video Playlist', 'cactusthemes'),
		  );
		$slug_pl =  $this->get_option('playlist-slug');
		if(is_numeric($slug_pl)){ 
			$slug_pl = get_post($slug_pl);
			$slug_pl = $slug_pl->post_name;
		}
		if($slug_pl==''){
			$slug_pl = 'playlist';
		}
		if ( $slug_pl )
			$rewrite =  array( 'slug' => untrailingslashit( $slug_pl ), 'with_front' => false, 'feeds' => true );
		else
			$rewrite = false;

		  $args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => $rewrite,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt')
		  );
		register_post_type( 'ct_playlist', $args );
	}			
			
	/* Register meta box for Store Type 
	 * Wordpress 3.8
	 */
	function ct_video_type_meta_box_cb($post, $box){
		$defaults = array('taxonomy' => 'post_tag');
		if ( !isset($box['args']) || !is_array($box['args']) )
			$args = array();
		else
			$args = $box['args'];
		extract( wp_parse_args($args, $defaults), EXTR_SKIP );
		$tax_name = esc_attr($taxonomy);
		$taxonomy = get_taxonomy($taxonomy);
		$user_can_assign_terms = current_user_can( $taxonomy->cap->assign_terms );
		$comma = _x( ',', 'tag delimiter' );
		?>
		<div class="tagsdiv" id="<?php echo $tax_name; ?>">
			<div class="jaxtag">
			<div class="nojs-tags hide-if-js">
			<p><?php echo $taxonomy->labels->add_or_remove_items; ?></p>
			<textarea name="<?php echo "tax_input[$tax_name]"; ?>" rows="3" cols="20" class="the-tags" id="tax-input-<?php echo $tax_name; ?>" <?php disabled( ! $user_can_assign_terms ); ?>><?php echo str_replace( ',', $comma . ' ', get_terms_to_edit( $post->ID, $tax_name ) ); // textarea_escaped by esc_attr() ?></textarea></div>
			<?php if ( $user_can_assign_terms ) : ?>
			<div class="ajaxtag hide-if-no-js">
				<label class="screen-reader-text" for="new-tag-<?php echo $tax_name; ?>"><?php echo $box['title']; ?></label>
				<div class="taghint"><?php echo $taxonomy->labels->add_new_item; ?></div>
				<p><input type="text" id="new-tag-<?php echo $tax_name; ?>" name="newtag[<?php echo $tax_name; ?>]" class="newtag form-input-tip" size="16" autocomplete="off" value="" />
				<input type="button" class="button tagadd" value="<?php esc_attr_e('Add'); ?>" /></p>
			</div>
			<p class="howto"><?php echo $taxonomy->labels->separate_items_with_commas; ?></p>
			<?php endif; ?>
			</div>
			<div class="tagchecklist"></div>
		</div>
		<?php if ( $user_can_assign_terms ) : ?>
		<p class="hide-if-no-js"><a href="#titlediv" class="tagcloud-link" id="link-<?php echo $tax_name; ?>"><?php echo $taxonomy->labels->choose_from_most_used; ?></a></p>
		<?php endif; ?>
		<?php
	}
	
	/**
	 * Display post categories form fields.
	 *
	 * @since 2.6.0
	 *
	 * @param object $post
	 */
	function ct_video_categories_meta_box_cb( $post, $box ) {
	$defaults = array('taxonomy' => 'category');
	if ( !isset($box['args']) || !is_array($box['args']) )
		$args = array();
	else
		$args = $box['args'];
	extract( wp_parse_args($args, $defaults), EXTR_SKIP );
	$tax = get_taxonomy($taxonomy);

	?>
	<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">
		<ul id="<?php echo $taxonomy; ?>-tabs" class="category-tabs">
			<li class="tabs"><a href="#<?php echo $taxonomy; ?>-all"><?php echo $tax->labels->all_items; ?></a></li>
			<li class="hide-if-no-js"><a href="#<?php echo $taxonomy; ?>-pop"><?php _e( 'Most Used' ); ?></a></li>
		</ul>

		<div id="<?php echo $taxonomy; ?>-pop" class="tabs-panel" style="display: none;">
			<ul id="<?php echo $taxonomy; ?>checklist-pop" class="categorychecklist form-no-clear" >
				<?php $popular_ids = wp_popular_terms_checklist($taxonomy); ?>
			</ul>
		</div>

		<div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
			<?php
            $name = ( $taxonomy == 'category' ) ? 'post_category' : 'tax_input[' . $taxonomy . ']';
            echo "<input type='hidden' name='{$name}[]' value='0' />"; // Allows for an empty term set to be sent. 0 is an invalid Term ID and will be ignored by empty() checks.
            ?>
			<ul id="<?php echo $taxonomy; ?>checklist" data-wp-lists="list:<?php echo $taxonomy?>" class="categorychecklist form-no-clear">
				<?php wp_terms_checklist($post->ID, array( 'taxonomy' => $taxonomy, 'popular_cats' => $popular_ids ) ) ?>
			</ul>
		</div>
	<?php if ( current_user_can($tax->cap->edit_terms) ) : ?>
			<div id="<?php echo $taxonomy; ?>-adder" class="wp-hidden-children">
				<h4>
					<a id="<?php echo $taxonomy; ?>-add-toggle" href="#<?php echo $taxonomy; ?>-add" class="hide-if-no-js">
						<?php
							/* translators: %s: add new taxonomy label */
							printf( __( '+ %s' ), $tax->labels->add_new_item );
						?>
					</a>
				</h4>
				<p id="<?php echo $taxonomy; ?>-add" class="category-add wp-hidden-child">
					<label class="screen-reader-text" for="new<?php echo $taxonomy; ?>"><?php echo $tax->labels->add_new_item; ?></label>
					<input type="text" name="new<?php echo $taxonomy; ?>" id="new<?php echo $taxonomy; ?>" class="form-required form-input-tip" value="<?php echo esc_attr( $tax->labels->new_item_name ); ?>" aria-required="true"/>
					<label class="screen-reader-text" for="new<?php echo $taxonomy; ?>_parent">
						<?php echo $tax->labels->parent_item_colon; ?>
					</label>
					<?php wp_dropdown_categories( array( 'taxonomy' => $taxonomy, 'hide_empty' => 0, 'name' => 'new'.$taxonomy.'_parent', 'orderby' => 'name', 'hierarchical' => 1, 'show_option_none' => '&mdash; ' . $tax->labels->parent_item . ' &mdash;' ) ); ?>
					<input type="button" id="<?php echo $taxonomy; ?>-add-submit" data-wp-lists="add:<?php echo $taxonomy ?>checklist:<?php echo $taxonomy ?>-add" class="button category-add-submit" value="<?php echo esc_attr( $tax->labels->add_new_item ); ?>" />
					<?php wp_nonce_field( 'add-'.$taxonomy, '_ajax_nonce-add-'.$taxonomy, false ); ?>
					<span id="<?php echo $taxonomy; ?>-ajax-response"></span>
				</p>
			</div>
		<?php endif; ?>
	</div>
	<?php

}
	
	function register_post_type_metadata(array $meta_boxes){
		// register aff store metadata
		if(class_exists('cactus_channel')){
			$channel_fields = array(	
					array( 'id' => 'channel_id', 'name' => __('Channel','cactusthemes'), 'type' => 'post_select', 'use_ajax' => true, 'query' => array( 'post_type' => 'ct_channel' ), 'multiple' => true,  'desc' => __('Add this video to a channel','cactusthemes'),  'repeatable' => false),
			);
	
			$meta_boxes[] = array(
				'title' => __('Cactus - Channel','cactusthemes'),
				'pages' => 'post',
				'fields' => $channel_fields,
				'priority' => 'high'
			);	
		}
		// Plays list meta
		$playlist_channel = array(	
				array( 'id' => 'playlist_channel_id', 'name' => __('Channel','cactusthemes'), 'type' => 'post_select', 'use_ajax' => true, 'query' => array( 'post_type' => 'ct_channel' ), 'multiple' => true,  'desc' => __('Add this playlist to a channel','cactusthemes') , 'repeatable' => false ),
		);
		$meta_boxes[] = array(
			'title' => __('Cactus - Channel','cactusthemes'),
			'pages' => 'ct_playlist',
			'fields' => $playlist_channel,
			'priority' => 'high'
		);
		
		$playlist_id = array(	
				array( 'id' => 'playlist_id', 'name' => __('Playlist','cactusthemes'), 'type' => 'post_select', 'use_ajax' => true, 'query' => array( 'post_type' => 'ct_playlist' ), 'multiple' => true,  'desc' => __('Add this video to a playlist','cactusthemes'),  'repeatable' => false),
		);

		$meta_boxes[] = array(
			'title' => __('Cactus - PlayList','cactusthemes'),
			'pages' => 'post',
			'fields' => $playlist_id,
			'priority' => 'high'
		);
		return $meta_boxes;
	}
}


} // class_exists check
if ( ! function_exists( 'ct_video_get_page_id' ) ) {

	/**
	 * Affiliatez page IDs
	 *
	 * retrieve page ids - used for myaccount, edit_address, change_password, shop, cart, checkout, pay, view_order, thanks, terms
	 *
	 * returns -1 if no page is found
	 *
	 * @access public
	 * @param string $page
	 * @return int
	 */
	 /* function ct_video_get_page_id( $page ) {
		  global $affiliatez;
		  $page = apply_filters('affiliatez_get_' . $page . '_page_id', $affiliatez->get_option($page . '-page-id'));
		  return ( $page ) ? $page : -1;
	  }*/
}

/**
 * Init Cactus_video
 */
$GLOBALS['cactus_video'] = new Cactus_video();
?>