<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1.0">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<!--[if lte IE 9]>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/ie.css" />
<![endif]-->
<?php if ( !isset( $_COOKIE['retina'] ) ) { 
	// this is used to set cookie to detect if screen is retina
	?>
<script type="text/javascript">
var retina = 'retina='+ window.devicePixelRatio +';'+ retina;
document.cookie = retina;
if(document.cookie){
	// document.location.reload(true);
}
</script>
<?php } ?> 
<?php if(ot_get_option('retina_logo')):?>
<style type="text/css" >
	@media only screen and (-webkit-min-device-pixel-ratio: 2),(min-resolution: 192dpi) {
		/* Retina Logo */
		.logo{background:url(<?php echo ot_get_option('retina_logo'); ?>) no-repeat center; display:inline-block !important; background-size:contain;}
		.logo img{ opacity:0; visibility:hidden}
		.logo *{display:inline-block}
	}
</style>
<?php endif;?>
<?php if(ot_get_option('echo_meta_tags')) ct_meta_tags();?>

<?php wp_head();
$body_class = '';
if(ot_get_option('theme_layout',false)){
	$body_class .= 'boxed-mode';
}
?>
</head>

<body <?php body_class($body_class) ?>>
<a name="top" style="height:0; position:absolute; top:0;" id="top-anchor"></a>
<?php if(ot_get_option('loading_effect',2)==1||(ot_get_option('loading_effect',2)==2&&(is_front_page()||is_page_template('page-templates/front-page.php')))){ ?>
<div id="pageloader">   
    <div class="loader-item">
    	<i class="fa fa-refresh fa-spin"></i>
    </div>
</div>
<?php }?>
<div id="body-wrap">
<?php if(ot_get_option('theme_layout',false)){ ?>
<div class="container boxed-container">
<?php }?>
<div id="wrap">
    <header class="dark-div">
    	<?php
		global $global_title;
		if(is_category()){
			$global_title = single_cat_title('',false);
		}elseif(is_tag()){
			$global_title = single_tag_title('',false);
		}elseif(is_tax()){
			$global_title = single_term_title('',false);
		}elseif(is_author()){
			$global_title = __("Author: ",'cactusthemes') . get_the_author();
		}elseif(is_day()){
			$global_title = __("Archives for ",'cactusthemes') . date_i18n(get_option('date_format') ,get_the_time('U'));
		}elseif(is_month()){
			$global_title = __("Archives for ",'cactusthemes') . get_the_date('F, Y');
		}elseif(is_year()){
			$global_title = __("Archives for ",'cactusthemes') . get_the_date('Y');
		}elseif(is_home()){
			if(get_option('page_for_posts')){ $global_title = get_the_title(get_option('page_for_posts'));
			}else{
				$global_title = get_bloginfo('name');
			}
		}elseif(is_404()){
			$global_title = '404!';
		}else{
			global $post;
			if($post)
				$global_title = get_the_title($post->ID);
		}
		get_template_part( 'header', 'navigation' ); // load header-navigation.php 
		
		if(is_single()  && !is_attachment()){
			$playlist_id = get_post_meta(get_the_ID(),'playlist_id',true);
			global $exits_list;
			$exits_list = 0;
			if(is_array($playlist_id) && isset($_GET['list'])){
				if (in_array($_GET['list'], $playlist_id)) {
					$exits_list = 1;
				}
			}elseif($playlist_id!='' && isset($_GET['list'])){
				if ($_GET['list'] == $playlist_id) {
					$exits_list = 1;
				}
			}
			if(tm_is_post_video()){
				if( ($exits_list == 1 && isset($_GET['list']) && $_GET['list']=='') || (!isset($_GET['list'])) ){
					$get_layout = get_post_meta($post->ID,'page_layout',true);
					if($get_layout=='def' || $get_layout==''){$get_layout = ot_get_option('single_layout_video');}
					if($get_layout!='inbox'){
						get_template_part( 'header', 'single-player' );
					}
				}
			}else{
				get_template_part( 'header', 'single' );
			}
		}elseif(is_tax('video-series')&&!is_search()){
			get_template_part( 'header', 'series' );
		}elseif(is_category()&&!is_search()){
			get_template_part( 'header', 'category' );
		}elseif(is_front_page()||is_page_template('page-templates/front-page.php')){
			get_template_part( 'header', 'frontpage' );
		}elseif(is_plugin_active('buddypress/bp-loader.php') && bp_is_current_component('playlist')){
			get_template_part( 'header', 'playlist' );
		}
		global $sidebar_width;
		$sidebar_width = ot_get_option('sidebar_width');
		?>
        <?php
        if($blog_show_meta_grid2 = ot_get_option('blog_show_meta_grid2') && !is_category()){ ?>
        <style>
            .video-listing.style-grid-2 .item-content.hidden,
            .video-listing.style-grid-2 .item-info.hidden{
                display: block !important;
                visibility: visible !important;
            }
		</style>
        <?php
        }
        if($cat_show_meta_grid2 = ot_get_option('cat_show_meta_grid2') && is_category() ){ ?>
        <style>
            .video-listing.style-grid-2 .item-content.hidden,
            .video-listing.style-grid-2 .item-info.hidden{
                display: block !important;
                visibility: visible !important;
            }
		</style>
        <?php
        }?>


    </header>