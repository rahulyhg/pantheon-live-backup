<?php
/*
Plugin Name: Alexa Certify
Plugin URI: http://www.alexa.com/
Description: The official Alexa plugin for WordPress.
Version: 4.0
Author: Alexa Internet
Author URI: http://www.alexa.com/
Text Domain: alexa-internet
*/

if(!class_exists('WP_Alexa_Certify')) {
    
    class WP_Alexa_Certify {                

        public function __construct() {        
            $this->plugin_basename = plugin_basename(__FILE__); 
            $this->admin_page_id = "Alexa-Internet";           
            $this->certify_snippet_oid = "alexacertify_certify";
            $this->certify_atrk_id     = "alexaceritfy_atrk_id";
            $this->certify_atrk_dn     = "alexaceritfy_atrk_dn";
            $this->admin_init();
        }

        /********************
         * Plugin Functions *
         ********************/

        /**
         * Add the metatag and certify snippet
         * actions
         */
        public function run() {
            //Add the alexa certify snippet to the footer of the WP site            
            add_action('login_head', array(&$this, 'alexa_certify_snippet'));
            add_action('wp_head', array(&$this, 'alexa_certify_snippet'));
        }

        public function alexa_certify_snippet() {
            if(!defined('AX_PLUGIN_CERT_SNIPPET')) {
                $certifyCode = get_option($this->certify_snippet_oid);
                $atrkid      = get_option($this->certify_atrk_id);
                $domain      = get_option($this->certify_atrk_dn);
                if ($atrkid && $domain) {
                    $satrkid = htmlspecialchars($atrkid);
                    $sdomain = htmlspecialchars($domain);
                    echo <<<EOT
<!-- Start Alexa Certify Javascript -->
<script type="text/javascript">
_atrk_opts = { atrk_acct:"$satrkid", domain:"$sdomain",dynamic: true};
(function() { var as = document.createElement('script'); as.type = 'text/javascript'; as.async = true; as.src = "https://d31qbv1cthcecs.cloudfront.net/atrk.js"; var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(as, s); })();
</script>
<noscript><img src="https://d5nxst8fruw4z.cloudfront.net/atrk.gif?account=$satrkid" style="display:none" height="1" width="1" alt="" /></noscript>
<!-- End Alexa Certify Javascript -->  
EOT;
                } else if ($certifyCode) {
                    echo $certifyCode . "\n";
                }  
                define('AX_PLUGIN_CERT_SNIPPET', "certify_snippet");
            }
        }
    

        /*******************
         * Admin Setup Functions *
         *******************/

        /**
         * Initialize the admin capabilities
         */
        private function admin_init() {            
            //set up the settings admin page            
            add_action('admin_menu', array(&$this, 'add_settings_page'));   
            //create the link to the settings page
            add_filter("plugin_action_links_".$this->plugin_basename, array(&$this, 'add_settings_action_link'));
        }


        public function add_settings_page() {
            $pageTitle = "Alexa Internet";
            $menuTitle = "Alexa Internet";
            $capability = 'manage_options'; 
            $callback =  array(&$this,"alexa_plugin_settings");    
            add_options_page($pageTitle, $menuTitle, $capability, $this->admin_page_id, $callback);
        }

        /*
         * Modify the $links array to add a 'Settings' link.
         * The 'Settings' link will take users to a page where they can set up their certify code
         * and certify snippets
         */
        public function add_settings_action_link( $links ) {
            $links = isset($links) ? $links : array();
            $links["_ax_settings"] = '<a href="options-general.php?page='.$this->admin_page_id.'">Settings</a>';
            return $links;
        }



        /***************************
         * Settings Page Functions *
         ***************************/        
       
        /*
         * Render the settings page, where users can define their certify snippet
         */
        public function alexa_plugin_settings() {

            //$current_certify_snippet = get_option($this->certify_snippet_oid);
            $current_atrk_id = get_option($this->certify_atrk_id);
            $current_domainn = get_option($this->certify_atrk_dn);
            $errors = array();
            $updated = false;
            if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST["alexacertify_submit"])) {                
                //The user just sent us new data, fetch it, validate it and store it
                $form_certify_atrk_id = isset($_POST["alexacerify_atrk_id"]) 
                    ? $this->_clean_input($_POST["alexacerify_atrk_id"]) : $current_atrk_id;
                $form_certify_domainn = isset($_POST["alexacerify_atrk_dn"]) 
                    ? $this->_clean_input($_POST["alexacerify_atrk_dn"]) : $current_domainn;

                if(strcmp($form_certify_atrk_id, $current_atrk_id) || strcmp($form_certify_domainn, $current_domainn)) {
                    if(check_admin_referer( 'configure-alexa-internet-plugin' ) && $this->_validCertifyRecord($form_certify_atrk_id, $form_certify_domainn)) {
                        update_option($this->certify_atrk_id, $form_certify_atrk_id);
                        update_option($this->certify_atrk_dn, $form_certify_domainn);                                
                        $updated["certify"] = "Your Certify Code has been installed.";
                    } else {
                        $errors["certify"] = "The atrk acct or domain did not match. Please make sure you copy the Alexa Certify Atrk acct or domain exactly as it appears on your Certify page.";    
                    }
                }

                if(empty($errors) && empty($updated)) {
                    $updated["nochange"] = "No changes found.";                    
                } 
            }

            //these values are needed in the admin script
            $this->updated = $updated;            
            $this->errors = $errors;
            //$this->certify_snippet = get_option($this->certify_snippet_oid);
            $this->certify_atrk_id = get_option($this->certify_atrk_id);
            $this->certify_domainn = get_option($this->certify_atrk_dn);
            include('alexacertify_admin.php');
        }


        private function _validCertifySnippet($snippet) {
            $certifyRegex = "<script [^>]*>[^<].+atrk_acct:.{14}.+domain.+<\/script>[^<]*<noscript><img [^>].+d5nxst8fruw4z.cloudfront.net/atrk.gif.+<\/noscript>";
            return empty($snippet) || ereg($certifyRegex, $snippet);
        }

        private function _validCertifyRecord($atrkid, $domain) {
            $av = false;
            $dv = false;
            if ($atrkid && is_string($atrkid) && strlen($atrkid) == 14) $av = true;
            if ($domain && is_string($domain) && strlen($domain) <= 300) $dv = true;
            return $av && $dv;
        }

        private function _clean_input($value) {
            return trim(stripslashes(strip_tags($value)));
        }
    }
}

if(class_exists('WP_Alexa_Certify')) {
    $alexa_certify_plugin = new WP_Alexa_Certify();
    $alexa_certify_plugin->run();
}

?>
