<?php
/**
 * The sidebar containing the search widget area.
 */
global $sidebar_width;
?>
<div id="sidebar" class="<?php echo $sidebar_width?'col-md-3':'col-md-4' ?>">
<?php if ( is_active_sidebar( 'search_page_sidebar' ) ) { ?>
	<?php dynamic_sidebar( 'search_page_sidebar' ); ?>
<?php }else{
	echo get_dynamic_sidebar('main_sidebar');
} ?>
</div><!--#sidebar-->
