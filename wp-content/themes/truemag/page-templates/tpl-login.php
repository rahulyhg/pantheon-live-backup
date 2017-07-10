<?php 
/*
 * Template Name: Login Page
 */
if ( is_user_logged_in() ) {
	wp_redirect( home_url() );
}
global $global_page_layout;
$layout = $global_page_layout?$global_page_layout:ot_get_option('page_layout','right');
global $sidebar_width;
global $post;
$topnav_style = ot_get_option('topnav_style','dark');
get_header();
?>
	<div class="blog-heading <?php echo $topnav_style=='light'?'heading-light':'' ?>">
    	<div class="container">
            <h1><?php echo $post->post_title ?></h1>
        </div>
    </div><!--blog-heading-->
    <div id="body">
        <div class="container">
            <div class="row">
  				<div id="content" class="<?php echo $layout!='full'?($sidebar_width?'col-md-9':'col-md-8'):'col-md-12' ?><?php echo ($layout == 'left') ? " revert-layout":"";?>" role="main">
                	<?php if($_GET['login']=='failed'){?>
                      	<div class="alert alert-warning fade in">
                          <button type="button" class="close" data-dismiss="alert">&times;</button>
                          <?php _e('Username or password is wrong.','cactusthemes'); ?>
                          <a class="btn btn-warning reset-pass" rel="popover" data-toggle="popover" data-placement="bottom" title="" data-original-title="Reset Password"><?php _e('Wanna reset password?','cactusthemes'); ?></a>
                        </div>
                        <div id="popover_content_wrapper" class="hidden">
                        <p><?php _e('Enter your username or email to reset your password.','cactusthemes'); ?></p>
                        <form method="post" action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>" class="wp-user-form">
                            <div class="username">
                                <label for="user_login" class="hide"><?php _e('Username or Email','cactusthemes'); ?>: </label>
                                <input type="text" name="user_login" value="" size="20" id="user_login" tabindex="1001" />
                            </div>
                            <div class="login_fields">
                                <input type="submit" name="wp-submit" value="<?php _e('Reset my password','cactusthemes'); ?>" class="user-submit" tabindex="1002" />
                                <input type="hidden" name="reset" value="1" />
                                <input type="hidden" name="redirect_to" value="<?php echo get_permalink(ot_get_option('login_page',1)) ?>?reset=true" />
                                <input type="hidden" name="user-cookie" value="1" />
                            </div>
                        </form>
                        </div>
                        <script>
							jQuery(function(){
								jQuery('[rel=popover]').popover({ 
								html : true, 
								content: function() {
									  return jQuery('#popover_content_wrapper').html();
									}
								});
							});
						  </script>
					  <?php
					  }elseif($_GET['reset']){?>
                      	<div class="alert alert-success">
                          <button type="button" class="close" data-dismiss="alert">&times;</button>
                          <?php _e('<strong>Reset password!</strong> A message will be sent to your email address.','cactusthemes'); ?>
                        </div>
                      <?php }?>
                    <?php 
					$largs = array(
							'echo'           => true,
							'redirect'       => ot_get_option('login_redirect')?get_permalink( ot_get_option('login_redirect') ):site_url(), 
							'form_id'        => 'loginform',
							'label_username' => __( 'Username', 'cactusthemes' ),
							'label_password' => __( 'Password', 'cactusthemes' ),
							'label_remember' => __( 'Remember Me', 'cactusthemes' ),
							'label_log_in'   => __( 'Log In', 'cactusthemes' ),
							'id_username'    => 'user_login',
							'id_password'    => 'user_pass',
							'id_remember'    => 'rememberme',
							'id_submit'      => 'wp-submit',
							'remember'       => true,
							'value_username' => NULL,
							'value_remember' => false
					); ?>
                    <div class="row"><div class="col-md-8">
                    <?php wp_login_form( $largs ); ?>
						<?php if ( function_exists('bp_get_signup_allowed') && bp_get_signup_allowed() ) : ?>
                            <?php printf( __( '<a href="%s" title="Register for a new account">Register</a>', 'buddypress' ), bp_get_signup_page() ); ?>
                        <?php endif; ?>
                    </div></div>
                    <div class="clear"></div>
					<?php
					//content
					if (have_posts()) :
						while (have_posts()) : the_post();
							get_template_part('content','single');
						endwhile;
					endif;
					?>
                    <br /><br /><br />
                </div><!--#content-->
                <?php if($layout != 'full'){
					get_sidebar();
				}?>
            </div><!--/row-->
        </div><!--/container-->
    </div><!--/body-->
<?php get_footer(); ?>