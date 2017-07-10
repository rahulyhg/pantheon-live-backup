<?php

if (!defined('ABSPATH')) exit;
global $blogvault;
global $bvNotice;
global $bvPtnAdminPage;
global $bvAppUrl;
$bvNotice = '';
$bvPtnAdminPage = 'ptn-automated-migration';
if (defined('BV_APP_URL')) {
	$bvAppUrl = BV_APP_URL;
} else {
	$bvAppUrl = 'https://migrate.blogvault.net';
}

if (!function_exists('bvAddStyleSheet')) :
	function bvAddStyleSheet() {
		wp_register_style('form-styles', plugins_url('form-styles.css',__FILE__ ));
		wp_enqueue_style('form-styles');
	}
add_action( 'admin_init','bvAddStyleSheet');
endif;

if (!function_exists('bvPtnAdminInitHandler')) :
	function bvPtnAdminInitHandler() {
		global $bvNotice, $blogvault, $bvPtnAdminPage, $bvAppUrl;
		global $sidebars_widgets;
		global $wp_registered_widget_updates;

		if (!current_user_can('activate_plugins'))
			return;

		if (isset($_REQUEST['bvnonce']) && wp_verify_nonce($_REQUEST['bvnonce'], "bvnonce")) {
			if (isset($_REQUEST['blogvaultkey']) && isset($_REQUEST['page']) && $_REQUEST['page'] == $bvPtnAdminPage) {
				if ((strlen($_REQUEST['blogvaultkey']) == 64)) {
					$keys = str_split($_REQUEST['blogvaultkey'], 32);
					$blogvault->updateKeys($keys[0], $keys[1]);
					bvActivateHandler();
					$bvNotice = "<b>Activated!</b> blogVault is now backing up your site.<br/><br/>";
					if (isset($_REQUEST['redirect'])) {
						$location = $_REQUEST['redirect'];
						wp_redirect($bvAppUrl."/migration/".$location);
						exit();
					}
				} else {
					$bvNotice = "<b style='color:red;'>Invalid request!</b> Please try again with a valid key.<br/><br/>";
				}
			}
		}

		if ($blogvault->getOption('bvActivateRedirect') === 'yes') {
			$blogvault->updateOption('bvActivateRedirect', 'no');
			wp_redirect($blogvault->bvAdminUrl($bvPtnAdminPage));
		}
	}
	add_action('admin_init', 'bvPtnAdminInitHandler');
endif;

if (!function_exists('bvPtnAdminMenu')) :
	function bvPtnAdminMenu() {
		global $bvPtnAdminPage;
		add_menu_page('Pantheon Migrate', 'Pantheon Migrate', 'manage_options', $bvPtnAdminPage, 'bvPtnMigrate');
	}
	if (function_exists('is_multisite') && is_multisite()) {
		add_action('network_admin_menu', 'bvPtnAdminMenu');
	} else {
		add_action('admin_menu', 'bvPtnAdminMenu');
	}
endif;

if (!function_exists('bvSettingsLink') ) :
	function bvSettingsLink($links, $file) {
		global $blogvault, $bvPtnAdminPage;
		if ( $file == plugin_basename( dirname(__FILE__).'/blogvault.php' ) ) {
			$links[] = '<a href="'.$blogvault->bvAdminUrl($bvPtnAdminPage).'">'.__( 'Settings' ).'</a>';
		}
		return $links;
	}
	add_filter('plugin_action_links', 'bvSettingsLink', 10, 2);
endif;

if ( !function_exists('bvPtnMigrate') ) :
	function bvPtnMigrate() {
		global $blogvault, $bvNotice, $bvPtnAdminPage, $bvAppUrl;
		$_error = NULL;
		if (array_key_exists('error', $_REQUEST)) {
			$_error = $_REQUEST['error'];
		}
?>
		<div class="logo-container" style="padding: 50px 0px 10px 20px">
			<a href="http://blogvault.net/" style="padding-right: 20px;"><img src="<?php echo plugins_url('logo.png', __FILE__); ?>" /></a>
		</div>

		<div id="wrapper toplevel_page_ptn-automated-migration">
			<form id="ptn_migrate_form" dummy=">" action="<?php echo $bvAppUrl; ?>/home/migrate" style="padding:0 2% 2em 1%;" method="post" name="signup">
				<h1>Migrate Site to Pantheon</h1>
				<p><font size="3">This plugin makes it very easy to migrate your site to Pantheon</font></p>
<?php if ($_error == "email") {
	echo '<div class="error" style="padding-bottom:0.5%;"><p>There is already an account with this email.</p></div>';
} else if ($_error == "blog") {
	echo '<div class="error" style="padding-bottom:0.5%;"><p>Could not create an account. Please contact <a href="http://blogvault.net/contact/">blogVault Support</a><br />
		<font color="red">NOTE: We do not support automated migration of locally hosted sites.</font></p></div>';
} else if (($_error == "custom") && isset($_REQUEST['bvnonce']) && wp_verify_nonce($_REQUEST['bvnonce'], "bvnonce")) {
	echo '<div class="error" style="padding-bottom:0.5%;"><p>'.base64_decode($_REQUEST['message']).'</p></div>';
}
?>
				<input type="hidden" name="bvsrc" value="wpplugin" />
				<input type="hidden" name="migrate" value="pantheon" />
				<input type="hidden" name="type" value="sftp" />
<?php echo $blogvault->siteInfoTags($bvPtnAdminPage); ?>
				<p>No Pantheon site yet? Start by <a href="https://dashboard.pantheon.io/sites/migrate">migrating an existing site</a> on your Pantheon dashboard.</p>
				<div class="row-fluid">
					<div class="span5" style="border-right: 1px solid #EEE; padding-top:1%;">
						<label class="control-label" for="input02">Pantheon Site Name</label>
						<div class="control-group">
							<div class="controls">
								<input type="text" class="input-large" name="newurl">
							</div>
						</div>
						<label class="control-label" for="input01">Machine Token</label>
						<div class="control-group">
							<div class="controls">
								<input type="text" class="input-large" name="machine_token">
							</div>
						</div>
<?php if (array_key_exists('auth_required_source', $_REQUEST)) { ?>
						<div id="source-auth">
							<label class="control-label" for="input02" style="color:red">User <small>(for this site)</small></label>
							<div class="control-group">
								<div class="controls">
									<input type="text" class="input-large" name="httpauth_src_user">
								</div>
							</div>
							<label class="control-label" for="input02" style="color:red">Password <small>(for this site)</small></label>
							<div class="control-group">
								<div class="controls">
									<input type="password" class="input-large" name="httpauth_src_password">
								</div>
							</div>
						</div>
<?php } ?>
<?php if (array_key_exists('auth_required_dest', $_REQUEST)) { ?>
            <label class="control-label" for="input02" style="color:red">Username <small>(for Pantheon Install)</small></label>
            <div class="control-group">
              <div class="controls">
                <input type="text" class="input-large" name="httpauth_dest_user">
              </div>
            </div>
            <label class="control-label" for="input02" style="color:red">Password <small>(for Pantheon Install)</small></label>
            <div class="control-group">
              <div class="controls">
                <input type="password" class="input-large" name="httpauth_dest_password">
              </div>
            </div>
<?php } ?>
					</div>
				</div>
				<p style="font-size: 11px;">By pressing the "Migrate" button, you are agreeing to <a href="https://blogvault.net/tos/">BlogVault's Terms of Service</a></p>
				<input type='submit' value='Migrate'>
			</form>
		</div> <!-- wrapper ends here -->
<?php
	}
endif;