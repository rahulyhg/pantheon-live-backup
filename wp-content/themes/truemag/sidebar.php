<?php
/**
 * The sidebar containing the main widget area.
 */
global $sidebar_width;
?>
<div id="sidebar" class="<?php echo $sidebar_width?'col-md-3':'col-md-4' ?>">
<?php 
if(is_front_page() && is_active_sidebar('home_sidebar')){
	dynamic_sidebar( 'home_sidebar' );
} else if(is_category()||is_home()&&!is_front_page()){
	$cat_id = get_query_var('cat');
	$style = get_option("cat_layout_$cat_id")?get_option("cat_layout_$cat_id"):ot_get_option('blog_style','video');
	if($style=='video'&&is_active_sidebar('video_listing_sidebar')){
		dynamic_sidebar( 'video_listing_sidebar' );
	}elseif($style=='blog'&&is_active_sidebar('blog_sidebar')){
		dynamic_sidebar( 'blog_sidebar' );
	}elseif(is_active_sidebar('main_sidebar')){
		dynamic_sidebar( 'main_sidebar' );
	}
}elseif(is_plugin_active('buddypress/bp-loader.php') && bp_current_component()){ //buddypress
	if(bp_is_member() && is_active_sidebar('bp_single_member_sidebar')){ //single member
		dynamic_sidebar( 'bp_single_member_sidebar' );
	}elseif(bp_is_group() && is_active_sidebar('bp_single_group_sidebar')){ //single group
		dynamic_sidebar( 'bp_single_group_sidebar' );
	}elseif(bp_is_register_page() && is_active_sidebar('bp_register_sidebar')){ //register
		dynamic_sidebar( 'bp_register_sidebar' );
	}elseif(bp_is_directory()){ //sitewide
		if(bp_is_activity_component() && is_active_sidebar('bp_activity_sidebar')){
			dynamic_sidebar( 'bp_activity_sidebar' ); //activity
		}elseif(bp_is_groups_component() && is_active_sidebar('bp_group_sidebar')){
			dynamic_sidebar( 'bp_group_sidebar' ); //groups
		}elseif(bp_current_component('members') && is_active_sidebar('bp_member_sidebar')){
			dynamic_sidebar( 'bp_member_sidebar' ); //members
		}elseif(is_active_sidebar('bp_sidebar')){
			dynamic_sidebar( 'bp_sidebar' );
		}else{
			dynamic_sidebar( 'main_sidebar' );
		}
	}elseif(is_active_sidebar('bp_sidebar')){
		dynamic_sidebar( 'bp_sidebar' );
	}else{
		dynamic_sidebar( 'main_sidebar' );
	}
}elseif(is_single()&&tm_is_post_video()&&is_active_sidebar('single_video_sidebar')){
	dynamic_sidebar( 'single_video_sidebar' );
}elseif(is_single()&&is_active_sidebar('single_blog_sidebar')){
	dynamic_sidebar( 'single_blog_sidebar' );
}elseif(is_page()&&is_active_sidebar('single_page_sidebar')&&!is_front_page()){
	dynamic_sidebar( 'single_page_sidebar' );
	
}elseif(function_exists('is_woocommerce')&& is_woocommerce()&&!is_shop()){
	dynamic_sidebar( 'single_woo_sidebar' );
	
}elseif(function_exists('is_shop')&&is_shop()){
	dynamic_sidebar( 'shop_sidebar' );
	
}elseif(is_active_sidebar('main_sidebar')){
	dynamic_sidebar( 'main_sidebar' );
}
?>
</div><!--#sidebar-->
