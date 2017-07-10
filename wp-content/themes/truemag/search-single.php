<?php
	// Header for single post or page
	$background = ot_get_option('subpage_header');
	//$header_height = get_post_meta($post->ID,'header_height',true);
?>
	<style type="text/css">
		#page-header .bg-container{box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;padding:160px 0 0 40px;color:#FFF;font-weight:bold}
		#page-header .bg-container{<?php if(isset($background['background-image'])){?>background:<?php echo $background['background-color'];?> url(<?php echo $background['background-image'];?>) <?php echo $background['background-attachment'];?> <?php echo $background['background-position'];?> <?php echo $background['background-repeat'];?>;<?php }?>height:400<?php echo $header_height;?>px}
	</style>
		<div id="page-header">
        	<div class="bg-container">
            	<h1><?php _e('Search results', 'castusthemes');?>&nbsp;</h1>
                <p><?php  _e('Search results for "', 'castusthemes'); echo get_query_var('s');?>"</p>
            </div>
        </div>