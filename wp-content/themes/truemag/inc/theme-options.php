<?php
/**
 * Initialize the custom theme options.
 */
add_action( 'admin_init', 'custom_theme_options' );

/**
 * Build the custom settings & update OptionTree.
 */
function custom_theme_options() {
  /**
   * Get a copy of the saved settings array. 
   */
  $saved_settings = get_option( 'option_tree_settings', array() );
  
  /**
   * Custom settings array that will eventually be 
   * passes to the OptionTree Settings API Class.
   */
  $custom_settings = array( 
    'contextual_help' => array( 
      'sidebar'       => ''
    ),
    'sections'        => array( 
      array(
        'id'          => 'general',
        'title'       => '<i class="fa fa-cogs"><!-- --></i>'.__('General','cactusthemes')
      ),
      array(
        'id'          => 'color',
        'title'       => '<i class="fa fa-magic"><!-- --></i>'.__('Colors & Background','cactusthemes')
      ),
      array(
        'id'          => 'fonts',
        'title'       => '<i class="fa fa-font"><!-- --></i>'.__('Fonts Settings','cactusthemes')
      ),
      array(
        'id'          => 'navigation',
        'title'       => '<i class="fa fa-compass"><!-- --></i>'.__('Main Navigation','cactusthemes')
      ),
      array(
        'id'          => 'home',
        'title'       => '<i class="fa fa-home"><!-- --></i>'.__('Front Page','cactusthemes')
      ),
      array(
        'id'          => 'single_video',
        'title'       => '<i class="fa fa-file-text-o"><!-- --></i>'.__('Single Post/Video','cactusthemes')
      ),
      array(
        'id'          => 'single_page',
        'title'       => '<i class="fa fa-file"><!-- --></i>'.__('Single Page','cactusthemes')
      ),
      array(
        'id'          => 'blog',
        'title'       => '<i class="fa fa-pencil-square"><!-- --></i>'.__('Blog','cactusthemes')
      ),
      array(
        'id'          => 'cat_video',
        'title'       => '<i class="fa fa-th-large"><!-- --></i>'.__('Category','cactusthemes')
      ),
	  array(
        'id'          => 'series',
        'title'       => '<i class="fa fa-film"><!-- --></i>'.__('Video Series','cactusthemes')
      ),
      array(
        'id'          => 'headline_video',
        'title'       => '<i class="fa fa-bookmark"><!-- --></i>'.__('Headline Section','cactusthemes')
      ),
      array(
        'id'          => 'search',
        'title'       => '<i class="fa fa-search"><!-- --></i>'.__('Search','cactusthemes')
      ),
      array(
        'id'          => '404',
        'title'       => '<i class="fa fa-exclamation-triangle"><!-- --></i>'.__('404','cactusthemes')
      ),
      array(
        'id'          => 'advertising',
        'title'       => '<i class="fa fa-bullhorn"><!-- --></i>'.__('Advertising','cactusthemes')
      ),
      array(
        'id'          => 'social_account',
        'title'       => '<i class="fa fa-twitter-square"><!-- --></i>'.__('Social Account','cactusthemes')
      ),
      array(
        'id'          => 'social_share',
        'title'       => '<i class="fa fa-share-square"><!-- --></i>'.__('Social Share','cactusthemes')
      ),
	  array(
        'id'          => 'user_submit',
        'title'       => '<i class="fa fa-upload"><!-- --></i>'.__('User Submit Video','cactusthemes')
      ),
	  array(
        'id'          => 'buddypress',
        'title'       => '<i class="fa fa-user"><!-- --></i>'.__('BuddyPress','cactusthemes')
      ),
	  array(
        'id'          => 'bbpress',
        'title'       => '<i class="fa fa-group"><!-- --></i>'.__('BbPress','cactusthemes')
      ),
	  array(
        'id'          => 'youtube_setting',
        'title'       => '<i class="fa fa-youtube"><!-- --></i>'.__('Youtube Settings','cactusthemes')
      ),
	  array(
        'id'          => 'video-ads',
        'title'       => '<i class="fa fa-play"><!-- --></i>'.__('Video Ads','cactusthemes')
      ),
    ),
    'settings'        => array( 
      array(
        'id'          => 'theme_purpose',
        'label'       => __('Theme Purpose: Video / Magazine','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
		  array(
            'value'       => '0',
            'label'       => __('Magazine','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '1',
            'label'       => __('Video','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'theme_layout',
        'label'       => __('Theme Layout','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
		  array(
            'value'       => 0,
            'label'       => __('Full Width','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 1,
            'label'       => __('Boxed','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'flat-style',
        'label'       => __('Flat style mode','cactusthemes'),
        'desc'        => __('Enable if you want your site looks more flat','cactusthemes'),
        'std'         => '',
        'type'        => 'checkbox',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Enable Flat style','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'sidebar_width',
        'label'       => __('Sidebar Width','cactusthemes'),
        'desc'        => __('Select Sidebar Width','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '0',
            'label'       => __('1/3 Page width','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '1',
            'label'       => __('1/4 Page width','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'echo_meta_tags',
        'label'       => __('SEO - Echo Meta Tags','cactusthemes'),
        'desc'        => __('By default, TrueMag generates its own SEO meta tags (for example: Facebook Meta Tags). If you are using another SEO plugin like YOAST or a Facebook plugin, you can turn off this option','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
		  array(
            'value'       => '1',
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'copyright',
        'label'       => __('Copyright Text','cactusthemes'),
        'desc'        => __('Appear in footer','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'right_to_left',
        'label'       => __('RTL mode','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'checkbox',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Enable RTL','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'custom_css',
        'label'       => __('Custom CSS','cactusthemes'),
        'desc'        => __('Enter custom CSS. Ex: <i>.class{ font-size: 13px; }</i>','cactusthemes'),
        'std'         => '',
        'type'        => 'textarea-simple',
        'section'     => 'general',
        'rows'        => '5',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
	  array(
        'id'          => 'google_analytics_code',
        'label'       => __('Custom Code','cactusthemes'),
        'desc'        => __('Enter custom code or JS code here. For example, enter Google Analytics','cactusthemes'),
        'std'         => '',
        'type'        => 'textarea-simple',
        'section'     => 'general',
        'rows'        => '5',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'logo_image',
        'label'       => __('Logo Image','cactusthemes'),
        'desc'        => __('Upload your logo image','cactusthemes'),
        'std'         => '',
        'type'        => 'upload',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'retina_logo',
        'label'       => __('Retina Logo (optional)','cactusthemes'),
        'desc'        => __('Retina logo should be two time bigger than the custom logo. Retina Logo is optional, use this setting if you want to strictly support retina devices.','cactusthemes'),
        'std'         => '',
        'type'        => 'upload',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
	  array(
        'id'          => 'login_logo',
        'label'       => __('Login Logo Image','cactusthemes'),
        'desc'        => __('Upload your Admin Login logo image','cactusthemes'),
        'std'         => '',
        'type'        => 'upload',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),  
      array(
        'id'          => 'quick_view_info',
        'label'       => __('Quick View Info / Quick View Video','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'loading_effect',
        'label'       => __('Pre-Loading Effect','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 0,
            'label'       => __('Disable','cactusthemes'),
            'src'         => ''
          ),
		  array(
            'value'       => 2,
            'label'       => __('Home Page only','cactusthemes'),
            'src'         => ''
          ),
		  array(
            'value'       => 1,
            'label'       => __('Enable','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'user_show_info',
        'label'       => __('Show Signed-In User Info','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 0,
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          ),
		  array(
            'value'       => 1,
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'main_color_1',
        'label'       => __('Main color 1','cactusthemes'),
        'desc'        => __('Used in dark div','cactusthemes'),
        'std'         => '',
        'type'        => 'colorpicker',
        'section'     => 'color',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'main_color_2',
        'label'       => __('Main color 2','cactusthemes'),
        'desc'        => __('Used in light div','cactusthemes'),
        'std'         => '',
        'type'        => 'colorpicker',
        'section'     => 'color',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
	  array(
        'id'          => 'content_color',
        'label'       => __('Content color','cactusthemes'),
        'desc'        => __('Used in almost content','cactusthemes'),
        'std'         => '',
        'type'        => 'colorpicker',
        'section'     => 'color',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
	  array(
        'id'          => 'page_background',
        'label'       => __('Page Background','cactusthemes'),
        'desc'        => __('Whole page background in Fullwidth, and side background in Boxed mode','cactusthemes'),
        'std'         => '',
        'type'        => 'background',
        'section'     => 'color',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'text_font',
        'label'       => __('Main Font','cactusthemes'),
        'desc'        => __('Enter font-family name here. <a href="http://www.google.com/fonts/" target="_blank">Google Fonts</a> are supported. For example, if you choose "Source Code Pro" Google Font with font-weight 400,500,600, enter <i>Source Code Pro:400,500,600</i>','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'fonts',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'text_size',
        'label'       => __('Content Text - Size','cactusthemes'),
        'desc'        => __('Enter font-size for content. Ex: "13px" or "1em"','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'fonts',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'text_weight',
        'label'       => __('Content Text - Weight','cactusthemes'),
        'desc'        => __('Enter font weight for content. For example "200"','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'fonts',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'h1_size',
        'label'       => __('H1 - Size','cactusthemes'),
        'desc'        => __('Enter font size for H1. For example, "18px" or "2em"','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'fonts',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'h1_weight',
        'label'       => __('H1 - Weight','cactusthemes'),
        'desc'        => __('Enter font weight for H1. For example "200"','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'fonts',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'h2_size',
        'label'       => __('H2 - Size','cactusthemes'),
        'desc'        => __('Enter font size for H2. For example "16px" or "1.2em"','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'fonts',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'h2_weight',
        'label'       => __('H2 - Weight','cactusthemes'),
        'desc'        => __('Enter font weight for H2. For example "200"','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'fonts',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'h3_size',
        'label'       => __('H3 - Size','cactusthemes'),
        'desc'        => __('Enter font size for H3. For example "14px" or "1em"','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'fonts',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'h3_weight',
        'label'       => __('H3 - Weight','cactusthemes'),
        'desc'        => __('Enter font weight for H3. For example "200"','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'fonts',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'nav_size',
        'label'       => __('Main Navigation - Size','cactusthemes'),
        'desc'        => __('Enter font size for Navigation. For example, "18px" or "2em"','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'fonts',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'nav_weight',
        'label'       => __('Main Navigation - Weight','cactusthemes'),
        'desc'        => __('Enter font weight for Navigation. For example "200"','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'fonts',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'custom_font',
        'label'       => __('Upload Custom Font','cactusthemes'),
        'desc'        => __('Upload your font, uses this font with name "Custom Font" in above settings','cactusthemes'),
        'std'         => '',
        'type'        => 'upload',
        'section'     => 'fonts',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'topnav_style',
        'label'       => __('Top Navigation Style','cactusthemes'),
        'desc'        => __('Select style for Main menu and Headline','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'dark',
            'label'       => __('Dark','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'light',
            'label'       => __('Light','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'topnav_layout',
        'label'       => __('Top Navigation Layout','cactusthemes'),
        'desc'        => __('Select Layout style for Main menu and Headline','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '',
            'label'       => __('Default','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'layout-2',
            'label'       => __('Layout 2 (Search box on Navigation)','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'layout-3',
            'label'       => __('Layout 3 (Headline on Top)','cactusthemes'),
            'src'         => ''
          ),
		  array(
            'value'       => 'layout-4',
            'label'       => __('Layout 4 (Center Logo)','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'topnav_fixed',
        'label'       => __('Fixed Top Navigation','cactusthemes'),
        'desc'        => __('Select Enable to use Fixed Navigation','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 0,
            'label'       => __('Disable','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 1,
            'label'       => __('Enable','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'topnav_height',
        'label'       => __('Main Navigation Height','cactusthemes'),
        'desc'        => __('Enter custom value (in pixels, for example: 100) to match with your logo size','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'disable_mainmenu',
        'label'       => __('Hide Main Navigation','cactusthemes'),
        'desc'        => __('If you are using another menu plugin (for example MashMenu), maybe you want to hide theme\'s default main navigation','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'no',
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '1',
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'mobile_nav',
        'label'       => __('Mobile Navigation Style','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Off-Canvas','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Classic dropdown','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'megamenu',
        'label'       => __('Enable Megamenu','cactusthemes'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
      ),
      array(
        'id'          => 'mobile_width',
        'label'       => __('Activate Mobile Navigation When Browser Width Is','cactusthemes'),
        'desc'        => __('Enter value (in pixels, for example: 767) at which, menu turns into mobile mode','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'header_home_style',
        'label'       => __('Header Style','cactusthemes'),
        'desc'        => __('Select style header of Home page','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'home',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'carousel',
            'label'       => __('Big Carousel','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'metro',
            'label'       => __('Metro Carousel','cactusthemes'),
            'src'         => ''
          ),
		  array(
            'value'       => 'classy',
            'label'       => __('Classy Slider (Vertical)','cactusthemes'),
            'src'         => ''
          ),
		  array(
            'value'       => 'classy2',
            'label'       => __('Classy Slider 2 (Horizon)','cactusthemes'),
            'src'         => ''
          ),
		  array(
            'value'       => 'classy3',
            'label'       => __('Classy Slider 3 (Horizon & Big Image)','cactusthemes'),
            'src'         => ''
          ),
		  array(
            'value'       => 'amazing',
            'label'       => __('Amazing Slider','cactusthemes'),
            'src'         => ''
          ),
		  array(
            'value'       => 'video_slider',
            'label'       => __('Video Slider','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'sidebar',
            'label'       => __('Sidebar','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'header_home_auto',
        'label'       => __('Auto Play Slider','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'home',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 1,
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 0,
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'header_home_auto_timeout',
        'label'       => __('Auto Play Timeout','cactusthemes'),
        'desc'        => __('Timeout in milisecond','cactusthemes'),
        'std'         => '3000',
        'type'        => 'numeric-slider',
        'section'     => 'home',
        'class'       => '',
		'min_max_step' => '500,10000,500',
        'choices'     => array(),
		'condition' => 'header_home_auto:is(1)'
      ),
	  array(
        'id'          => 'header_home_auto_duration',
        'label'       => __('Auto Play Duration','cactusthemes'),
        'desc'        => __('Transition Duration time in milisecond','cactusthemes'),
        'std'         => '800',
        'type'        => 'numeric-slider',
        'section'     => 'home',
        'class'       => '',
		'min_max_step' => '200,2000,100',
        'choices'     => array(),
		'condition' => 'header_home_auto:is(1)'
      ),
	  array(
        'id'          => 'header_home_use_player',
        'label'       => __('Header Video Item','cactusthemes'),
        'desc'        => __('Display player instead of thumbnail images (Use only for 3 Classy slider styles)','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'home',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 0,
            'label'       => __('Featured Image','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 1,
            'label'       => __('Video Player','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'quick_view_for_slider',
        'label'       => __('Quick View Info / Quick View Video for Metro Carousel, Big Carousel','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'home',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 0,
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 1,
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'header_home_height',
        'label'       => __('Header Height','cactusthemes'),
        'desc'        => __('Use for Big Carousel, Metro Carousel (Ex: 400)','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'home',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
	  array(
        'id'          => 'header_home_hidecat',
        'label'       => __('Hide Category in Items\' Title','cactusthemes'),
        'desc'        => __('Check if you want to hide categories link below title','cactusthemes'),
        'std'         => '',
        'type'        => 'checkbox',
        'section'     => 'home',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 1,
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'maintop_layout',
        'label'       => __('Main Top Sidebar Layout (for Header Style - Sidebar)','cactusthemes'),
        'desc'        => __('Select layout for Main Top Sidebar','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'home',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'full',
            'label'       => __('Full Width','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'boxed',
            'label'       => __('Boxed','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'header_home_bg',
        'label'       => __('Header Background (for Header Style - Classy)','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'background',
        'section'     => 'home',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'header_home_postids',
        'label'       => __('Header - Items IDs','cactusthemes'),
        'desc'        => __('Include post IDs to show on Header of Front Page','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'home',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'header_home_condition',
        'label'       => __('Header - Condition','cactusthemes'),
        'desc'        => __('Select condition to query post on Header of Front Page','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'home',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'latest',
            'label'       => __('Lastest','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'most_viewed',
            'label'       => __('Most Viewed','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'most_comments',
            'label'       => __('Most Comments','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'most_liked',
            'label'       => __('Most liked','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'title',
            'label'       => __('Title','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'modified',
            'label'       => __('Modified','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'random',
            'label'       => __('Random','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'header_time_range',
        'label'       => __('Header Condition - Time Range ','cactusthemes'),
        'desc'        => __('Select condition Time Range to query post on Header of Front Page','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'home',
		'condition'   => 'header_home_condition:not(latest),header_home_condition:not(modified),header_home_condition:not(random),header_home_condition:not(title)',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '',
            'label'       => __('All','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'day',
            'label'       => __('Day','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'week',
            'label'       => __('Week','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'month',
            'label'       => __('Month','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'year',
            'label'       => __('Year','cactusthemes'),
            'src'         => ''
          ),
        ),
      ),
      array(
        'id'          => 'header_home_cat',
        'label'       => __('Header - Category (ID or slug)','cactusthemes'),
        'desc'        => __('Include Category ID, slug to show on Header Home section (Ex: 15,26,37 or foo,bar,jazz)','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'home',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'header_home_tag',
        'label'       => __('Header - Items Tags','cactusthemes'),
        'desc'        => __('Include Tags to show on Header Home section (Ex: foo,bar)','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'home',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'header_home_order',
        'label'       => __('Header - Items Order','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'home',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'desc',
            'label'       => 'DESC',
            'src'         => ''
          ),
          array(
            'value'       => 'asc',
            'label'       => 'ASC',
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'header_home_number',
        'label'       => __('Header - Number of Items','cactusthemes'),
        'desc'        => __('Adjust this value to have best layout that fits screen','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'home',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'show_hide_title',
        'label'       => __('Show/Hide Page Title','cactusthemes'),
        'desc'        => __('Show of Hide Page Title of Front Page','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'home',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'hide',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'front_page_layout',
        'label'       => __('Front page layout','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'home',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Sidebar','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Full Width','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'auto_get_info',
        'label'       => __('Auto Fetch Data','cactusthemes'),
        'desc'        => __('This is an admin feature when adding new video post. Select which data to auto-fetch from video network (support YouTube, Vimeo and Dailymotion) when entering video URL','cactusthemes'),
        'std'         => '',
        'type'        => 'checkbox',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Fetch Video Title','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '2',
            'label'       => __('Fetch Video Description','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '3',
            'label'       => __('Fetch Video Tags (Only work width Vimeo and Dailymotion)','cactusthemes'),
            'src'         => ''
          ),
		  array(
            'value'       => '4',
            'label'       => __('Fetch Video Views','cactusthemes'),
            'src'         => ''
          ),
        ),
      ),
	  array(
        'id'          => 'google_api_key',
        'label'       => __('Google API Key','cactusthemes'),
        'desc'        => __('Fill your Google API key to fetch data from Youtube','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'single_layout_video',
        'label'       => __('Video Player Layout','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'full_width',
            'label'       => __('Full-width','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'inbox',
            'label'       => __('Inbox','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'single_layout_blog',
        'label'       => __('Blog Layout','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'inbox',
            'label'       => __('Inbox','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'full_width',
            'label'       => __('Full-width','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'single_player_video',
        'label'       => __('Player for Video File','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'jwplayer',
            'label'       => 'JWPlayer',
            'src'         => ''
          ),
          array(
            'value'       => 'flowplayer',
            'label'       => 'FlowPlayer ',
            'src'         => ''
          ),
		  array(
            'value'       => 'videojs',
            'label'       => 'Video.js - HTML5 Video Player ',
            'src'         => ''
          ),
		  array(
            'value'       => 'mediaelement',
            'label'       => ' WordPress Native Player: MediaElement',
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'jwplayer_select',
        'label'       => __('JWPlayer version','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
		'condition'   => 'single_player_video:is(jwplayer)',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'jwplayer_6',
            'label'       => 'JWPlayer 6',
            'src'         => ''
          ),
          array(
            'value'       => 'jwplayer_7',
            'label'       => 'JWPlayer 7 ',
            'src'         => ''
          ),
        ),
      ),
	  array(
        'id'          => 'force_videojs',
        'label'       => __('Force Using VideoJS for external videos','cactusthemes'),
        'desc'        => 'YouTube, Vimeo, DailyMotion',
        'std'         => '',
        'type'        => 'on-off',
        'section'     => 'single_video',
        'rows'        => '',
		'condition'   => 'single_player_video:is(videojs)',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
      ),

      array(
        'id'          => 'single_layout_ct_video',
        'label'       => __('Page Layout','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'right',
            'label'       => __('Sidebar Right','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'full',
            'label'       => __('Full Width','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'left',
            'label'       => __('Sidebar Left','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'auto_play_video',
        'label'       => __('Auto Play Video','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'no',
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'auto_load_next_video',
        'label'       => __('Replay','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('All videos and Stop','cactusthemes'),
            'src'         => ''
          ),
		  array(
            'value'       => '2',
            'label'       => __('All videos and First','cactusthemes'),
            'src'         => ''
          ),
		  array(
            'value'       => '3',
            'label'       => __('Current Video','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'no',
            'label'       => __('Off','cactusthemes'),
            'src'         => ''
          )
        ),
      ),

	   array(
        'id'          => 'auto_load_next_prev',
        'label'       => __('Choose Next Video for Auto Load','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'condition'        => 'auto_load_next_video:is(1)',
        'choices'     => array( 
          array(
            'value'       => '0',
            'label'       => __('Newer Video','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '1',
            'label'       => __('Older Video','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'user_turnoff_load_next',
        'label'       => __('Auto Next button for Visitors','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
		'condition'   => 'auto_load_next_video:not(no)',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '0',
            'label'       => __('Disable','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '1',
            'label'       => __('Enable','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'replay_state',
        'label'       => __('Replay Initial State','cactusthemes'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'user_turnoff_load_next:not(0)',
        'operator'    => 'and'
      ),

      array(
        'id'          => 'auto_load_same_cat',
        'label'       => __('Navigate next/previous video by','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Category','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Tag','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'delay_video',
        'label'       => __('Delay','cactusthemes'),
        'desc'        => __('X seconds. Ex: 5','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'video_toolbar_position',
        'label'       => __('Video Toolbar Position','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'top',
            'label'       => __('Top of content','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'bottom',
            'label'       => __('Bottom of content','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'video_toolbar_for_post',
        'label'       => __('Enable Video Toolbar for Standard post','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 0,
            'label'       => __('Disable','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 1,
            'label'       => __('Enable','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'number_of_more',
        'label'       => __('Number of More Videos','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),	  
      array(
        'id'          => 'sort_of_more',
        'label'       => __('More Videos same','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '0',
            'label'       => __('Default','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '1',
            'label'       => __('Categories','cactusthemes'),
            'src'         => ''
          ),
		  array(
            'value'       => '2',
            'label'       => __('Tags','cactusthemes'),
            'src'         => ''
          ),
        ),
      ),
      array(
        'id'          => 'single_show_image',
        'label'       => __('Show Feature Image','cactusthemes'),
        'desc'        => __('Show/hide  Feature Image','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '2',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '1',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  
      array(
        'id'          => 'single_show_meta_view',
        'label'       => __('Show Video Views Count','cactusthemes'),
        'desc'        => __('Show/hide Video Views in single post','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'single_show_meta_comment',
        'label'       => __('Show Comments Count','cactusthemes'),
        'desc'        => __('Show/hide Video Comments Count in single post','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'single_show_meta_like',
        'label'       => __('Show Video Likes Count','cactusthemes'),
        'desc'        => __('Show/hide Video Likes Count in single post','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => 'Show',
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => 'Hide',
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'single_show_meta_author',
        'label'       => __('Show Author Link','cactusthemes'),
        'desc'        => __('Show/hide Author Link in single post','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'single_show_meta_date',
        'label'       => __('Show Video Date','cactusthemes'),
        'desc'        => __('Show/hide Video Date in single post','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'onoff_more_video',
        'label'       => __('Show "More Videos"','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'onoff_related_video',
        'label'       => __('Show "Related Videos"','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'related_video_by',
        'label'       => __('Related Videos By','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '0',
            'label'       => __('Tag','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '1',
            'label'       => __('Category','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'onoff_cat',
        'label'       => __('Show Categories','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'onoff_tag',
        'label'       => __('Show Tags','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'onoff_author',
        'label'       => __('Show About Author','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'showmore_content',
        'label'       => __('Enable Show More Content','cactusthemes'),
        'desc'        => __('Enable show more post content button','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Enable','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Disable','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'onoff_postnavi',
        'label'       => __('Show Posts Navigation','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'video_report',
        'label'       => __('Enable Video Report','cactusthemes'),
        'desc'        => __('Choose to enable Video Report feature','cactusthemes'),
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_video',
        'class'       => '',
      ),
	  array(
        'id'          => 'video_report_form',
        'label'       => __('Video Report Form ID','cactusthemes'),
        'desc'        => __('Enter Contact Form 7 ID for Report Form (Only ID. Ex: 123)','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'single_video',
        'class'       => '',
		'condition'   => 'video_report:is(on)',
      ),
      array(
        'id'          => 'page_layout',
        'label'       => __('Single Page Layout','cactusthemes'),
        'desc'        => __('Choose default single page layout','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'single_page',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'right',
            'label'       => __('Right Sidebar','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'left',
            'label'       => __('Left Sidebar','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'full',
            'label'       => __('Full Width','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'blog_style',
        'label'       => __('Blog Style','cactusthemes'),
        'desc'        => __('Select style for listing','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'blog',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
		  array(
            'value'       => 'blog',
            'label'       => __('Blog listing','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'video',
            'label'       => __('Video listing (Grid)','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'blog_layout',
        'label'       => __('Blog Layout','cactusthemes'),
        'desc'        => __('Select layout for Blog listing page','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'blog',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'right',
            'label'       => __('Right sidebar','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'left',
            'label'       => __('Left sidebar','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'full',
            'label'       => __('Full width','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'pagination_style',
        'label'       => __('Pagination','cactusthemes'),
        'desc'        => __('Select style for Pagination','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'blog',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'page_ajax',
            'label'       => __('Ajax','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'page_navi',
            'label'       => __('WP PageNavi','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'page_def',
            'label'       => __('Default','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'show_image_or_player',
        'label'       => __('Show Feature Image or Video player in blog','cactusthemes'),
        'desc'        => __('Only style Video Listing Grid','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'blog',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Video Player','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Feature Image','cactusthemes'),
            'src'         => ''
          )
        ),
      ),

      array(
        'id'          => 'show_blog_title',
        'label'       => __('Show/Hide Blog Page Title','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'blog',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'show_blog_order',
        'label'       => __('Show/Hide Blog - Ordering Tool','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'blog',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'show_blog_layout',
        'label'       => __('Show/Hide Blog - Layouts Switcher Tool','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'blog',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'default_blog_order',
        'label'       => __('Choose Default Blog Order','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'blog',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '0',
            'label'       => __('Default (Latest)','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'title',
            'label'       => __('By Title','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'view',
            'label'       => __('By Views','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'like',
            'label'       => __('By Likes','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'comment',
            'label'       => __('By Comments','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'default_listing_layout',
        'label'       => __('Choose Default Listing Layout','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'blog',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '0',
            'label'       => __('Default - Small Grid (4 columns)','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'style-grid-2',
            'label'       => __('Big Grid (2 columns)','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'style-list-1',
            'label'       => __('Big detail 1 column','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'blog_show_meta_grid2',
        'label'       => __('Show Metadata in Medium Grid Layout (2 columns)','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'blog',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'blog_show_meta_view',
        'label'       => __('Show Views Count','cactusthemes'),
        'desc'        => __('Show/hide Views in listing page','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'blog',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'blog_show_meta_comment',
        'label'       => __('Show Comments Count','cactusthemes'),
        'desc'        => __('Show/hide Comments Count in listing page','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'blog',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'blog_show_meta_like',
        'label'       => __('Show Likes Count','cactusthemes'),
        'desc'        => __('Show/hide Likes Count in listing page','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'blog',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'blog_show_meta_author',
        'label'       => __('Show Author Name','cactusthemes'),
        'desc'        => __('Show/hide Author in listing page','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'blog',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'blog_show_meta_date',
        'label'       => __('Show Date','cactusthemes'),
        'desc'        => __('Show/hide Date in listing page','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'blog',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'cat_header_style',
        'label'       => __('Category Header Style','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'cat_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'carousel',
            'label'       => __('Carousel','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'banner',
            'label'       => __('Banner Image','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 0,
            'label'       => __('Do not show','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'con_top_cat_video',
        'label'       => __('Top Video Condition','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'cat_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'latest',
            'label'       => __('Latest','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'most_viewed',
            'label'       => __('Most viewed','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'likes',
            'label'       => __('Most liked','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'most_comments',
            'label'       => __('Most comment','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'random',
            'label'       => __('Random','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'number_item_cat',
        'label'       => __('Item Count','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'cat_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
	  array(
        'id'          => 'cat_show_meta_grid2',
        'label'       => __('Show Metadata in Medium Grid Layout (2 columns)','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'cat_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  
	  array(
        'id'          => 'enable_series',
        'label'       => __('Enable Video Series','cactusthemes'),
        'desc'        => __('Choose to enable Video Series Feature','cactusthemes'),
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'series'
      ),
	  array(
        'id'          => 'series_header_style',
        'label'       => __('Series Header Style','cactusthemes'),
        'desc'        => __('Choose Series header style','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'series',
        'condition'   => 'enable_series:is(on)',
        'choices'     => array( 
          array(
            'value'       => 'playlist',
            'label'       => __('Playlist','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'banner',
            'label'       => __('Banner Image','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 0,
            'label'       => __('Do not show','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'series_style',
        'label'       => __('Series Listing Style','cactusthemes'),
        'desc'        => __('Select style for listing','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'series',
		'condition'   => 'enable_series:is(on)',
        'choices'     => array( 
		  array(
            'value'       => 'video',
            'label'       => __('Video listing (Grid)','cactusthemes'),
            'src'         => ''
          ),
		  array(
            'value'       => 'blog',
            'label'       => __('Blog listing','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	   array(
        'id'          => 'series_slug',
        'label'       => __('Video Series Slug','cactusthemes'),
        'desc'        => __('You have to re-save Permalinks Settings after changing this option','cactusthemes'),
        'std'         => '',
		'condition'   => 'enable_series:is(on)',
        'type'        => 'text',
        'section'     => 'series'
      ),
	  array(
        'id'          => 'series_autonext',
        'label'       => __('Single Video Series Auto Next','cactusthemes'),
        'desc'        => __('Choose to enable Auto Next Video in Series (in single video)','cactusthemes'),
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'series'
      ),
	  array(
        'id'          => 'series_single_style',
        'label'       => __('Series Listing Style in Single Video','cactusthemes'),
        'desc'        => __('Choose Series listing style in single video','cactusthemes'),
        'std'         => 'button',
        'type'        => 'select',
        'section'     => 'series',
        'condition'   => 'enable_series:is(on)',
        'choices'     => array( 
          array(
            'value'       => 'link',
            'label'       => __('Links','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'select',
            'label'       => __('Select box','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  
      array(
        'id'          => 'show_top_headline',
        'label'       => __('Show Headline','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'headline_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'hide',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'title_headline',
        'label'       => __('Title','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'headline_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'number_item_head_show',
        'label'       => __('Item Count','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'headline_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'icon_headline',
        'label'       => __('Icon','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'headline_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'cat_head_video',
        'label'       => __('Categories ID/ Slug','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'headline_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
	  array(
        'id'          => 'headline_orderby',
        'label'       => __('Order by','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'headline_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'rand',
            'label'       => __('Random','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'date',
            'label'       => __('Date','cactusthemes'),
            'src'         => ''
          ),
		  array(
            'value'       => 'title',
            'label'       => __('Title','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'headline_order',
        'label'       => __('Order','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'headline_video',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '',
            'label'       => '',
            'src'         => ''
          ),
          array(
            'value'       => 'DESC',
            'label'       => 'DESC',
            'src'         => ''
          ),
		  array(
            'value'       => 'ASC',
            'label'       => 'ASC',
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'search_page',
        'label'       => __('Choose Search Page','cactusthemes'),
        'desc'        => __('Choose a page to be Search page','cactusthemes'),
        'std'         => '',
        'type'        => 'page-select',
        'section'     => 'search',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'show_search_form',
        'label'       => __('Show/Hide search form in result page','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'search',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'search_icon',
        'label'       => __('Search Icon','cactusthemes'),
        'desc'        => __('Enable Search Icon and Search box on Navigation','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'search',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 0,
            'label'       => __('Enable','cactusthemes'),
            'src'         => ''
          ),
		  array(
            'value'       => 1,
            'label'       => __('Disable','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'page404_title',
        'label'       => __('Title page 404','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'text',
        'section'     => '404',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'page404_subtitle',
        'label'       => __('Subtitle page 404','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'text',
        'section'     => '404',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'page404_content',
        'label'       => __('Content page 404','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'textarea',
        'section'     => '404',
        'rows'        => '5',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'adsense_id',
        'label'       => __('Google AdSense Publisher ID','cactusthemes'),
        'desc'        => __('Enter your Google AdSense Publisher ID','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'advertising',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'adsense_slot_ad_top_1',
        'label'       => __('Top Ads 1 - AdSense Ad Slot ID','cactusthemes'),
        'desc'        => __('If you want to display Adsense in Top Ads 1 section, enter Google AdSense Ad Slot ID here. If left empty, "Top Ads 1 - Custom Code" will be used.','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'advertising',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'ad_top_1',
        'label'       => __('Top Ads 1 - Custom Code','cactusthemes'),
        'desc'        => __('Enter custom code for Top Ads 1 position.','cactusthemes'),
        'std'         => '',
        'type'        => 'textarea-simple',
        'section'     => 'advertising',
        'rows'        => '5',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'adsense_slot_ad_top_2',
        'label'       => __('Top Ads 2 - AdSense Ad Slot ID','cactusthemes'),
        'desc'        => __('If you want to display Adsense in Top Ads 2 section, enter Google AdSense Ad Slot ID here. If left empty, "Top Ads 2 - Custom Code" will be used.','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'advertising',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'ad_top_2',
        'label'       => __('Top Ads 2 - Custom Code','cactusthemes'),
        'desc'        => __('Enter custom code for Top Ads 2 position.','cactusthemes'),
        'std'         => '',
        'type'        => 'textarea-simple',
        'section'     => 'advertising',
        'rows'        => '5',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'adsense_slot_ad_body_1',
        'label'       => __('Body Ads 1 - AdSense Ad Slot ID','cactusthemes'),
        'desc'        => __('If you want to display Adsense in Body Ads 1 section, enter Google AdSense Ad Slot ID here. If left empty, "Body Ads 1 - Custom Code" will be used.','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'advertising',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'ad_body_1',
        'label'       => __('Body Ads 1 - Custom Code','cactusthemes'),
        'desc'        => __('Enter custom code for Body Ads 1 position.','cactusthemes'),
        'std'         => '',
        'type'        => 'textarea-simple',
        'section'     => 'advertising',
        'rows'        => '5',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'adsense_slot_ad_body_2',
        'label'       => __('Body Ads 2 - AdSense Ad Slot ID','cactusthemes'),
        'desc'        => __('If you want to display Adsense in Body Ads 2  section, enter Google AdSense Ad Slot ID here. If left empty, "Body Ads 2  - Custom Code" will be used.','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'advertising',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'ad_body_2',
        'label'       => __('Body Ads 2 - Custom Code','cactusthemes'),
        'desc'        => __('Enter custom code for Body Ads 2 position.','cactusthemes'),
        'std'         => '',
        'type'        => 'textarea-simple',
        'section'     => 'advertising',
        'rows'        => '5',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'adsense_slot_ad_foot',
        'label'       => __('Foot Ads - AdSense Ad Slot ID','cactusthemes'),
        'desc'        => __('If you want to display Adsense in Foot Ads  section, enter Google AdSense Ad Slot ID here. If left empty, "Foot Ads - Custom Code" will be used.','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'advertising',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'ad_foot',
        'label'       => __('Foot Ads - Custom Code','cactusthemes'),
        'desc'        => __('Enter custom code for Foot Ads position.','cactusthemes'),
        'std'         => '',
        'type'        => 'textarea-simple',
        'section'     => 'advertising',
        'rows'        => '5',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'adsense_slot_ad_recurring',
        'label'       => __('Recuring Ads - AdSense Ad Slot ID','cactusthemes'),
        'desc'        => __('If you want to display Adsense in Recuring Ads section, enter Google AdSense Ad Slot ID here. If left empty, "Recuring Ads - Custom Code" will be used.','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'advertising',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'ad_recurring',
        'label'       => __('Recuring Ads- Custom Code','cactusthemes'),
        'desc'        => __('Enter custom code for Recuring Ads position. This Ad will appear after each Ajax paginated-page in blog listing, categories or search result pages.','cactusthemes'),
        'std'         => '',
        'type'        => 'textarea-simple',
        'section'     => 'advertising',
        'rows'        => '5',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
	  array(
        'id'          => 'adsense_slot_ad_single_content',
        'label'       => __('Single Content Ads - AdSense Ad Slot ID','cactusthemes'),
        'desc'        => __('If you want to display Adsense in Single Content Ads section, enter Google AdSense Ad Slot ID here. If left empty, "Single Content Ads - Custom Code" will be used.','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'advertising',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'ad_single_content',
        'label'       => __('Single Content Ads - Custom Code','cactusthemes'),
        'desc'        => __('Enter custom code for Single Content Ads position.','cactusthemes'),
        'std'         => '',
        'type'        => 'textarea-simple',
        'section'     => 'advertising',
        'rows'        => '5',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
	  array(
        'id'          => 'adsense_slot_ad_bg_left',
        'label'       => __('Left Side Ads - AdSense Ad Slot ID','cactusthemes'),
        'desc'        => __('If you want to display Adsense in Left Side Ads section, enter Google AdSense Ad Slot ID here. If left empty, "Left Side Ads - Custom Code" will be used.','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'advertising',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'ad_bg_left',
        'label'       => __('Left Side Ads - Custom Code','cactusthemes'),
        'desc'        => __('Enter custom code for Left Side Ads position.','cactusthemes'),
        'std'         => '',
        'type'        => 'textarea-simple',
        'section'     => 'advertising',
        'rows'        => '5',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
	  array(
        'id'          => 'adsense_slot_ad_bg_right',
        'label'       => __('Right Side Ads - AdSense Ad Slot ID','cactusthemes'),
        'desc'        => __('If you want to display Adsense in Right Side Ads section, enter Google AdSense Ad Slot ID here. If left empty, "Right Side Ads - Custom Code" will be used.','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'advertising',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'ad_bg_right',
        'label'       => __('Right Side Ads - Custom Code','cactusthemes'),
        'desc'        => __('Enter custom code for Right Side Ads position.','cactusthemes'),
        'std'         => '',
        'type'        => 'textarea-simple',
        'section'     => 'advertising',
        'rows'        => '5',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
	  array(
        'id'          => 'ad_bg_width',
        'label'       => __('Side Ads Width','cactusthemes'),
        'desc'        => __('Enter your side ads (Left & Right) width (Ex: 100px)','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'advertising',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
	  	  array(
        'id'          => 'social_link_open',
        'label'       => __('Social Links open in','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'social_account',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 0,
            'label'       => __('Curent Tab','cactusthemes'),
            'src'         => ''
          ),
		  array(
            'value'       => 1,
            'label'       => __('New Tab','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'acc_facebook',
        'label'       => 'Facebook',
        'desc'        => __('Enter full link to your account (including http://)','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'social_account',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
	  array(
        'id'          => 'acc_instagram',
        'label'       => 'Instagram',
        'desc'        => __('Enter full link to your account (including http://)','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'social_account',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
	  array(
        'id'          => 'acc_envelope',
        'label'       => 'Email',
        'desc'        => __('Enter full link to your account (including http://)','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'social_account',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'acc_twitter',
        'label'       => 'Twitter',
        'desc'        => __('Enter full link to your account (including http://)','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'social_account',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'acc_linkedin',
        'label'       => 'LinkedIn',
        'desc'        => __('Enter full link to your account (including http://)','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'social_account',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'acc_tumblr',
        'label'       => 'Tumblr',
        'desc'        => __('Enter full link to your account (including http://)','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'social_account',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'acc_google-plus',
        'label'       => 'Google Plus',
        'desc'        => __('Enter full link to your account (including http://)','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'social_account',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'acc_pinterest',
        'label'       => 'Pinterest',
        'desc'        => __('Enter full link to your account (including http://)','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'social_account',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'acc_youtube',
        'label'       => 'Youtube',
        'desc'        => __('Enter full link to your account (including http://)','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'social_account',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'acc_flickr',
        'label'       => 'Flickr',
        'desc'        => __('Enter full link to your account (including http://)','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'social_account',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
	  array(
        'id'          => 'acc_vk',
        'label'       => 'VK',
        'desc'        => __('Enter full link to your account (including http://)','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'social_account',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
	  array(
			'label'       => __('Custom Social Account','cactusthemes'),
			'id'          => 'custom_acc',
			'type'        => 'list-item',
			'class'       => '',
			'section'     => 'social_account',
			'desc'        => __('Add Social Account','cactusthemes'),
			'choices'     => array(),
			'settings'    => array(
				 array(
					'label'       => __('Icon Class','cactusthemes'),
					'id'          => 'icon',
					'type'        => 'text',
					'desc'        => __('Enter Font Awesome class (Ex: fa-facebook)','cactusthemes'),
					'std'         => '',
					'rows'        => '',
					'post_type'   => '',
					'taxonomy'    => ''
				 ),
				 array(
					'label'       => __('URL','cactusthemes'),
					'id'          => 'link',
					'type'        => 'text',
					'desc'        => __('Enter full link to your account (including http://)','cactusthemes'),
					'std'         => '',
					'rows'        => '',
					'post_type'   => '',
					'taxonomy'    => ''
				 ),
			)
	  ),
	  
	  array(
        'id'          => 'play_on_facebook',
        'label'       => __('Share video on facebook:','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'social_share',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 0,
            'label'       => __('Play video on Facebook','cactusthemes'),
            'src'         => ''
          ),
		  array(
            'value'       => 1,
            'label'       => __('Link back to video post URL','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'show_hide_sharethis',
        'label'       => __('Use Share This','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'social_share',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '0',
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '1',
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'sharethis_key',
        'label'       => __('Share This Publisher Key.','cactusthemes'),
        'desc'        => __('Enter your publisher key (http://developer.sharethis.com/member/register)','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'social_share',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
	  array(
        'id'          => 'facebook_app_id',
        'label'       => __('Facebook App ID','cactusthemes'),
        'desc'        => __('Enter your Facebook App ID','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'social_share',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
      array(
        'id'          => 'social_like',
        'label'       => __('Show Facebook Like and Google +1 button?','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'social_share',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'share_facebook',
        'label'       => __('Show Facebook Share','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'social_share',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'share_twitter',
        'label'       => __('Show Twitter Share','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'social_share',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'share_linkedin',
        'label'       => __('Show LinkedIn Share','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'social_share',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'share_tumblr',
        'label'       => __('Show Tumblr Share','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'social_share',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'share_google_plus',
        'label'       => __('Show Google Plus Share','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'social_share',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'share_blogger',
        'label'       => __('Show Blogger Share','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'social_share',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'share_reddit',
        'label'       => __('Show Reddit Share','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'social_share',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'share_vk',
        'label'       => __('Show Vk Share','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'social_share',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'share_pinterest',
        'label'       => __('Show Pinterest Share','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'social_share',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'share_email',
        'label'       => __('Show Email Share','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'social_share',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'user_submit',
        'label'       => __('Enable User Submit Video Feature','cactusthemes'),
        'desc'        => __('When enable this, any Contact form 7 having a field name "video-url" will be saved to post','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'user_submit',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 0,
            'label'       => __('Disable','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 1,
            'label'       => __('Enable','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'only_user_submit',
        'label'       => __('Login required to submit','cactusthemes'),
        'desc'        => __('Select whether only logged in users can submit or not','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'user_submit',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 0,
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 1,
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'text_bt_submit',
        'label'       => __('Text on button submit','cactusthemes'),
        'desc'        => __('Enter text you want to show','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'user_submit',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array(),
      ),
	  array(
        'id'          => 'user_submit_status',
        'label'       => __('Post status for submitted videos','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'user_submit',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'pending',
            'label'       => __('Pending','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'publish',
            'label'       => __('Publish','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'user_submit_format',
        'label'       => __('Post Format for submitted videos','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'user_submit',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'video',
            'label'       => __('Video','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '',
            'label'       => __('Standard','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'user_submit_cat_exclude',
        'label'       => __('Exclude Category from Category checkbox','cactusthemes'),
        'desc'        => __('Enter category ID that you dont want to be display in category checkbox (ex: 1,68,86)','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'user_submit',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array(),
      ),
	  array(
        'id'          => 'user_submit_cat_radio',
        'label'       => __('Categories display as radio buttons','cactusthemes'),
        'desc'        => __('To limit users to choose one category only','cactusthemes'),
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'user_submit',
      ),
	  array(
        'id'          => 'user_submit_fetch',
        'label'       => __('Enable Auto Fetch Data for user\'s submited videos','cactusthemes'),
        'desc'        => __('Auto fill title, description, duration, views... for Youtube, Vimeo video url','cactusthemes'),
        'std'         => '',
        'type'        => 'select',
        'section'     => 'user_submit',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
		  array(
            'value'       => 0,
            'label'       => __('Disable','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 1,
            'label'       => __('Enable','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'user_submit_limit_tag',
        'label'       => __('Limit number of tags that users can enter','cactusthemes'),
        'desc'        => __('0 is unlimited','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'user_submit',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array(),
      ),
	  array(
        'id'          => 'user_submit_notify',
        'label'       => __('Notification','cactusthemes'),
        'desc'        => __('Send notification email to user when post is published','cactusthemes'),
        'std'         => 1,
        'type'        => 'select',
        'section'     => 'user_submit',
        'choices'     => array(
		  array(
            'value'       => 1,
            'label'       => __('Enable','cactusthemes'),
            'src'         => ''
          ),
		  array(
            'value'       => 0,
            'label'       => __('Disable','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'login_page',
        'label'       => __('Choose Login Page','cactusthemes'),
        'desc'        => __('Choose a page to be default login page (Its template should be "Login page")','cactusthemes'),
        'std'         => '',
        'type'        => 'page-select',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
      ),
	  array(
        'id'          => 'login_redirect',
        'label'       => __('Choose Redirect page after login successful','cactusthemes'),
        'desc'        => __('Choose a page to redirect to','cactusthemes'),
        'std'         => '',
        'type'        => 'page-select',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
      ),
	  array(
        'id'          => 'buddypress_layout',
        'label'       => __('Choose BuddyPress pages layout','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'buddypress',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
		'choices'     => array( 
          array(
            'value'       => 'right',
            'label'       => __('Right Sidebar','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'left',
            'label'       => __('Left Sidebar','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'full',
            'label'       => __('Full Width','cactusthemes'),
            'src'         => ''
          )
		)
      ),
	  array(
        'id'          => 'playlist_number',
        'label'       => __('Number of videos in playlist header','cactusthemes'),
        'desc'        => __('Enter nummber of videos to display in header of member playlist page (Enter -1 for all)','cactusthemes'),
        'std'         => '',
        'type'        => 'text',
        'section'     => 'buddypress',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
	  array(
        'id'          => 'bbpress_layout',
        'label'       => __('Choose bbPress pages layout','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'bbpress',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
		'choices'     => array( 
          array(
            'value'       => 'right',
            'label'       => __('Right Sidebar','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'left',
            'label'       => __('Left Sidebar','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'full',
            'label'       => __('Full Width','cactusthemes'),
            'src'         => ''
          )
		)
      ),
	  
	array(
        'id'          => 'using_jwplayer_param',
        'label'       => __('Force Using JWPlayer for YouTube videos','cactusthemes'),
        'desc'        => '',
        'std'         => '0',
        'type'        => 'select',
        'section'     => 'youtube_setting',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '0',
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '1',
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'onoff_related_yt',
        'label'       => __('Related youtube videos','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'youtube_setting',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'youtube_quality',
        'label'       => __('Default YouTube PlayBack Quality','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'youtube_setting',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'default',
            'label'       => __('Default','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'highres',
            'label'       => __('Highres','cactusthemes'),
            'src'         => ''
          ),array(
            'value'       => 'hd1080',
            'label'       => __('HD1080','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'hd720',
            'label'       => __('HD720','cactusthemes'),
            'src'         => ''
          ),
		  array(
            'value'       => 'large',
            'label'       => __('Large','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => 'medium',
            'label'       => __('Medium','cactusthemes'),
            'src'         => ''
          ),
		  array(
            'value'       => 'small',
            'label'       => __('Small','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'onoff_html5_yt',
        'label'       => __('HTML5 (youtube) player','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'youtube_setting',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '0',
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '1',
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          )
        ),
      ),	  
	  array(
        'id'          => 'onoff_info_yt',
        'label'       => __('Show info','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'youtube_setting',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '0',
            'label'       => __('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '1',
            'label'       => __('Hide','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'remove_annotations',
        'label'       => __('Remove YouTube video annotations','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'youtube_setting',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '0',
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '1',
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'using_yout_param',
        'label'       => __('Force using YouTube Embed Code','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'youtube_setting',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '0',
            'label'       => 'No',__('Show','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '1',
            'label'       => 'Yes',__('Show','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'allow_full_screen',
        'label'       => __('Allow Full Screen','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'youtube_setting',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'allow_networking',
        'label'       => __('Allow Networking','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'youtube_setting',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Yes','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('No','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  array(
        'id'          => 'interactive_videos',
        'label'       => __('Interactive Videos','cactusthemes'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'youtube_setting',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => '1',
            'label'       => __('Disable','cactusthemes'),
            'src'         => ''
          ),
          array(
            'value'       => '0',
            'label'       => __('Enable','cactusthemes'),
            'src'         => ''
          )
        ),
      ),
	  //scroll
	  array(
        'id'          => 'nice-scroll',
        'label'       => __('Enable Scroll Effect','cactusthemes'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'general',
        'min_max_step'=> '',
      ),
	  
	  
	  array(
        'id'          => 'video_ads',
        'label'       => __('Enable Video Ads','cactusthemes'),
        'desc'        => __('Turn on Video Ads for all video posts','cactusthemes'),
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'video-ads',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),

    )
  );
  
  /* allow settings to be filtered before saving */
  $custom_settings = apply_filters( 'option_tree_settings_args', $custom_settings );
  
  /* settings are not the same update the DB */
  if ( $saved_settings !== $custom_settings ) {
    update_option( 'option_tree_settings', $custom_settings ); 
  }
}
