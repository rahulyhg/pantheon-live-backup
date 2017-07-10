<?php 

class PageTemplater {

		/**
         * A Unique Identifier
         */
		 protected $plugin_slug;

        /**
         * A reference to an instance of this class.
         */
        private static $instance;

        /**
         * The array of templates that this plugin tracks.
         */
        protected $templates;


        /**
         * Returns an instance of this class. 
		 *
		 * @params
				$templates - array - Array of template names
						array{
							'template-path' => 'template name'
						}
				
				'template-path' must contain plugin's base dir
         */
        public static function get_instance($templates) {
                if( null == self::$instance ) {
                        self::$instance = new PageTemplater($templates);
                }  else {
					$obj = self::$instance;
					
					$obj->register_page_templates($templates);
				}

                return self::$instance;
			
        } 

        public function register_page_templates($templates){
			$this->templates = !isset($this->templates) ? $templates : array_merge($this->templates, $templates);


			// Add a filter to the attributes metabox to inject template into the cache.
			add_filter(
				'page_attributes_dropdown_pages_args',
				 array( $this, 'register_templates' ) 
			);


			// Add a filter to the save post to inject out template into the page cache
			add_filter(
				'wp_insert_post_data', 
				array( $this, 'register_templates' ) 
			);


			// Add a filter to the template include to determine if the page has our 
			// template assigned and return it's path
			add_filter(
				'template_include', 
				array( $this, 'view_template') 
			);
		}

        /**
         * Initializes the plugin by setting filters and administration functions.
         */
        private function __construct($templates) {
                $this->register_page_templates($templates);
        }  


        /**
         * Adds our template to the pages cache in order to trick WordPress
         * into thinking the template file exists where it doens't really exist.
         *
         */

        public function register_templates( $atts ) {

                // Create the key used for the themes cache
                $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

                // Retrieve the cache list. 
				// If it doesn't exist, or it's empty prepare an array
                $templates = wp_get_theme()->get_page_templates();
                if ( empty( $templates ) ) {
                        $templates = array();
                } 

                // New cache, therefore remove the old one
                wp_cache_delete( $cache_key , 'themes');

                // Now add our template to the list of templates by merging our templates
                // with the existing templates array from the cache.
                $templates = array_merge( $templates, $this->templates );

                // Add the modified cache to allow WordPress to pick it up for listing
                // available templates
                wp_cache_add( $cache_key, $templates, 'themes', 1800 );

                return $atts;

        } 

        /**
         * Checks if the template is assigned to the page
         */
        public function view_template( $template ) {

                global $post;

                if (!isset($this->templates[get_post_meta( 
					$post->ID, '_wp_page_template', true 
				)] ) ) {
					
                        return $template;
						
                } 

                $file = plugin_dir_path(__FILE__). '../../' . get_post_meta( 
					$post->ID, '_wp_page_template', true 
				);

                // Just to be safe, we check if the file exist first
                if( file_exists( $file ) ) {
                        return $file;
                } 
				else { echo $file; }

                return $template;

        } 


}