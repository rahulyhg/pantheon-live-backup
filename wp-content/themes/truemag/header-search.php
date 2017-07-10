<?php
	// Header for single post or page
	$background = ot_get_option('subpage_header');
	$back1=isset($background['background-image'])?$background['background-image']:'';
	$back2=isset($background['background-color'])?$background['background-color']:'';
	if($back1=='' && $back2=='')	{
		$background['background-image'] = get_template_directory_uri().'/images/head.png';
	}

	//$header_height = get_post_meta($post->ID,'header_height',true);
?>
	<style type="text/css">
		#page-header .bg-container{background:<?php echo $background['background-color'];?> url(<?php echo $background['background-image'];?>) <?php echo $background['background-attachment'];?> center 0 <?php echo $background['background-repeat'];?>;}
	</style>
		<div id="page-header">
        	<div class="bg-container">
            	<h1><?php _e('Search results', 'castusthemes');?>&nbsp;</h1>
                <p><?php  _e('Search results for "', 'castusthemes'); echo get_query_var('s');?>"</p>
            </div>
        </div>