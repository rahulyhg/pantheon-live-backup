<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 */
?>
    <footer class="dark-div">
		<?php if ( is_404() && is_active_sidebar( 'footer_404_sidebar' ) ) { ?>
    	<div id="bottom">
            <div class="container">
                <div class="row">
					<?php dynamic_sidebar( 'footer_404_sidebar' ); ?>                    
                </div><!--/row-->
            </div><!--/container-->
        </div><!--/bottom-->
		<?php } elseif ( is_active_sidebar( 'footer_sidebar' ) ) { ?>
    	<div id="bottom">
            <div class="container">
                <div class="row">
					<?php dynamic_sidebar( 'footer_sidebar' ); ?>                    
                </div><!--/row-->
            </div><!--/container-->
        </div><!--/bottom-->
		<?php } ?>
		<?php tm_display_ads('ad_foot');?>
					
        <div id="bottom-nav">
        	<div class="container">
                <div class="row">
					<div class="copyright col-md-6"><?php echo ot_get_option('copyright',get_bloginfo('name').' - '.get_bloginfo('description')); ?></div>
					<nav class="col-md-6">
                    	<ul class="bottom-menu list-inline pull-right">
                        	<?php
								if(has_nav_menu( 'footer-navigation' )){
									wp_nav_menu(array(
										'theme_location'  => 'footer-navigation',
										'container' => false,
										'items_wrap' => '%3$s'
									));	
								}?>
                        </ul>
                    </nav>
				</div><!--/row-->
            </div><!--/container-->
        </div>
    </footer>
    <div class="wrap-overlay"></div>
</div><!--wrap-->
<?php if(ot_get_option('mobile_nav',1)){ ?>
<div id="off-canvas">
    <div class="off-canvas-inner">
        <nav class="off-menu">
            <ul>
            <li class="canvas-close"><a href="#"><i class="fa fa-times"></i> <?php _e('Close','cactusthemes'); ?></a></li>
			<?php
				$megamenu = ot_get_option('megamenu', 'off');
				if($megamenu == 'on' && function_exists('mashmenu_load')){
					global $in_mobile_menu;
					$in_mobile_menu = true;
					mashmenu_load();
					$in_mobile_menu = false;
				}elseif(has_nav_menu( 'main-navigation' )){
                    wp_nav_menu(array(
                        'theme_location'  => 'main-navigation',
                        'container' => false,
                        'items_wrap' => '%3$s'
                    ));	
                }else{?>
                    <li><a href="<?php echo home_url(); ?>/"><?php _e('Home','cactusthemes'); ?></a></li>
                    <?php wp_list_pages('title_li=' ); ?>
            <?php } ?>
            <?php
			 	$user_show_info = ot_get_option('user_show_info');
				if ( is_user_logged_in() && $user_show_info =='1') {
				$current_user = wp_get_current_user();
				$link = get_edit_user_link( $current_user->ID );
				?>
                    <li class="menu-item current_us">
                    <?php  
                    echo '<a class="account_cr" href="#">'.$current_user->user_login; 
                    echo get_avatar( $current_user->ID, '25' ).'</a>';
                    ?>
                    <ul class="sub-menu">
                        <li class="menu-item"><a href="<?php echo $link; ?>"><?php _e('Edit Profile','cactusthemes') ?></a></li>
                        <li class="menu-item"><a href="<?php echo wp_logout_url( get_permalink() ); ?>"><?php _e('Logout','cactusthemes') ?></a></li>
                    </ul>
                    </li>
				<?php }?>
                <?php //submit menu
				if(ot_get_option('user_submit',1)) {
					$text_bt_submit = ot_get_option('text_bt_submit');
					if($text_bt_submit==''){ $text_bt_submit = 'Submit Video';}
					if(ot_get_option('only_user_submit',1)){
						if(is_user_logged_in()){?>
						<li class="menu-item"><a class="submit-video" href="#" data-toggle="modal" data-target="#submitModal"><?php _e($text_bt_submit,'cactusthemes'); ?></a></li>
					<?php }
					} else{
					?>
						<li class="menu-item"><a class="submit-video" href="#" data-toggle="modal" data-target="#submitModal"><?php _e($text_bt_submit,'cactusthemes'); ?></a></li>
					<?php 
						
					}
				} ?>
            </ul>
        </nav>
    </div>
</div><!--/off-canvas-->
<script>off_canvas_enable=1;</script>
<?php }?>
<?php if(ot_get_option('theme_layout',false)){ ?>
</div><!--/boxed-container-->
<?php }?>
<div class="bg-ad">
	<div class="container">
    	<div class="bg-ad-left">
			<?php tm_display_ads('ad_bg_left');?>
        </div>
        <div class="bg-ad-right">
			<?php tm_display_ads('ad_bg_right');?>
        </div>
    </div>
</div>
</div><!--/body-wrap-->
<?php
	if(ot_get_option('user_submit',1)) {?>
	<div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel"><?php _e('Submit Video','cactusthemes'); ?></h4>
		  </div>
		  <div class="modal-body">
			<?php dynamic_sidebar( 'user_submit_sidebar' ); ?>
		  </div>
		</div>
	  </div>
	</div>
<?php } ?>
<?php
	if( is_single() && ot_get_option('video_report','on')!='off' ) {?>
	<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel"><?php _e('Report Video','cactusthemes'); ?></h4>
		  </div>
		  <div class="modal-body">
			<?php echo do_shortcode('[contact-form-7 id="'.ot_get_option('video_report_form','').'"]'); ?>
		  </div>
		</div>
	  </div>
	</div>
<?php } ?>
<?php if(!ot_get_option('theme_layout') && (ot_get_option('adsense_slot_ad_bg_left')||ot_get_option('ad_bg_left')||ot_get_option('adsense_slot_ad_bg_right')||ot_get_option('ad_bg_right')) ){ //fullwidth layout ?>
<script>
	enable_side_ads = true;
</script>
<?php } ?>
<a href="#top" id="gototop" class="notshow" title="Go to top"><i class="fa fa-angle-up"></i></a>
<?php echo ot_get_option('google_analytics_code', ''); ?>
<?php wp_footer(); ?>
</body>
</html>