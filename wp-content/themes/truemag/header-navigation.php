<?php 
	// Navigation part of template
	$topnav_style = ot_get_option('topnav_style','dark');
	$topnav_layout = ot_get_option('topnav_layout');
	$fixed = ot_get_option('topnav_fixed');
	$search_icon = ot_get_option('search_icon');
	$using_yt_param = ot_get_option('using_yout_param');
?>
	<?php tm_display_ads('ad_top_1');?>
		<?php if(ot_get_option('disable_mainmenu') != "1"){ 
		ob_start(); //get top nav html
		?>
        <div id="top-nav" class="<?php echo $topnav_style=='light'?'topnav-light light-div':'topnav-dark'; echo $fixed?' fixed-nav':''; echo ' '.$topnav_layout; ?>">
			<nav class="navbar <?php echo $topnav_style=='dark'?'navbar-inverse':'' ?> navbar-static-top" role="navigation">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle<?php if(ot_get_option('mobile_nav',1)){ echo ' off-canvas-toggle"';}else{ ?>" data-toggle="collapse" data-target=".navbar-collapse"<?php } ?>>
						  <span class="sr-only"><?php _e('Toggle navigation','cactusthemes') ?></span>
						  <i class="fa fa-reorder fa-bars fa-lg"></i>
						</button>
                        <?php if(ot_get_option('logo_image') == ''):?>
						<a class="logo" href="<?php echo home_url(); ?>"><img src="<?php echo get_template_directory_uri() ?>/images/logo.png" alt="logo"></a>
                        <?php else:?>
                        <a class="logo" href="<?php echo get_home_url(); ?>" title="<?php wp_title( '|', true, 'right' ); ?>"><img src="<?php echo ot_get_option('logo_image'); ?>" alt="<?php wp_title( '|', true, 'right' ); ?>"/></a>
						<?php endif;?>
					</div>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="main-menu collapse navbar-collapse">
						<!--<form class="navbar-form navbar-right search-form" role="search">
							<label class="" for="s">Search for:</label>
							<input type="text" placeholder="SEARCH" name="s" id="s" class="form-control">
							<input type="submit" id="searchsubmit" value="Search">
						</form>-->
                        <?php
							$user_show_info = ot_get_option('user_show_info');
						    if ( is_user_logged_in() && $user_show_info=='1') {
							$current_user = wp_get_current_user();
							$link = get_edit_user_link( $current_user->ID );
							?>
                            <div class="user_curent navbar-right">
                            	<ul class="nav navbar-nav navbar-right hidden-xs">
                                    <li class="main-menu-item dropdown">
                                    <?php  
                                    echo '<a class="account_cr" href="#">'.$current_user->user_login; 
                                    echo get_avatar( $current_user->ID, '25' ).'</a>';
                                    ?>
                                    <ul class="dropdown-menu">
                                    	<li><a href="<?php echo $link; ?>"><?php _e('Edit Profile','cactusthemes') ?></a></li>
                                        <li><a href="<?php echo wp_logout_url( get_permalink() ); ?>"><?php _e('Logout','cactusthemes') ?></a></li>
                                    </ul>
                                    </li>
                            	</ul>
                            </div>
                        <?php } ?>
                        <?php
						    if(ot_get_option('user_submit')==1) {
								$text_bt_submit = ot_get_option('text_bt_submit');
								if($text_bt_submit==''){ $text_bt_submit = 'Submit Video';}
								if(ot_get_option('only_user_submit',1)){
									if(is_user_logged_in()){?>
                                    <ul class="nav navbar-nav navbar-right hidden-xs user_submit">
                                        <li class="main-menu-item">
                                            <a class="submit-video" href="#" data-toggle="modal" data-target="#submitModal"><span class="btn btn-xs bgcolor1"><?php _e($text_bt_submit,'cactusthemes'); ?></span></a>
                                        </li>
                                    </ul>
                       			<?php }
								} else{
								?>
                                    <ul class="nav navbar-nav navbar-right hidden-xs user_submit">
                                        <li class="main-menu-item">
                                            <a class="submit-video" href="#" data-toggle="modal" data-target="#submitModal"><span class="btn btn-xs bgcolor1"><?php _e($text_bt_submit,'cactusthemes'); ?></span></a>
                                        </li>
                                    </ul>
                       			<?php
								}
								if($limit_tags = ot_get_option('user_submit_limit_tag')){ ?>
                                <script>
                                jQuery(document).ready(function(e) {
                                    jQuery("form.wpcf7-form").submit(function (e) {
										if(jQuery("input[name=tag].wpcf7-form-control", this).length){
											var submit_tags = jQuery('input[name=tag].wpcf7-form-control').val().split(",");
											if(submit_tags.length > <?php echo $limit_tags ?>){
												if(jQuery('.limit-tag-alert').length==0){
													jQuery('.wpcf7-form-control-wrap.tag').append('<span role="alert" class="wpcf7-not-valid-tip limit-tag-alert"><?php _e('Please enter less than or equal to '.$limit_tags.' tags','cactusthemes') ?>.</span>');
												}
												return false;
											}else{
												return true;
											}
										}
									});
                                });
                                </script>
                                <?php
								}
						} ?>
                        <?php if($topnav_layout && $topnav_layout!='layout-4' && $search_icon!=1){ ?>
						<ul class="nav navbar-nav hidden-xs nav-search-box">
                            <li class="main-menu-item">
                            	<?php if ( is_active_sidebar( 'search_sidebar' ) ) : ?>
                                	<?php dynamic_sidebar( 'search_sidebar' ); ?>
								<?php else: ?>
                                    <form class="<?php echo $topnav_style=='light'?'light-form':''; ?> dark-form" action="<?php echo home_url() ?>">
                                        <div class="input-group">
                                            <input type="text" name="s" class="form-control" placeholder="<?php echo __('Search...','cactusthemes');?>">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default maincolor1 maincolor1hover" type="submit"><i class="fa fa-search"></i></button>
                                            </span>
                                        </div>
                                    </form>
                                <?php endif; ?>
                            </li>
                        </ul>
                        <?php } ?>
						<ul class="nav navbar-nav nav-ul-menu <?php echo $topnav_layout?'':'navbar-right'; ?> hidden-xs">
						<?php
							$megamenu = ot_get_option('megamenu', 'off');
							if($megamenu == 'on' && function_exists('mashmenu_load')){
								mashmenu_load();
							}elseif(has_nav_menu( 'main-navigation' )){
								wp_nav_menu(array(
									'theme_location'  => 'main-navigation',
									'container' => false,
									'items_wrap' => '%3$s',
									'walker'=> new custom_walker_nav_menu()
								));	
							}else{?>
								<li><a href="<?php echo home_url(); ?>/"><?php _e('Home','cactusthemes') ?></a></li>
								<?php wp_list_pages('depth=1&number=5&title_li=' ); ?>
						<?php } ?>
						</ul>
                        <?php if(!ot_get_option('mobile_nav',1)){ //is classic dropdown ?>
                            <!--mobile-->
                            <ul class="nav navbar-nav navbar-right visible-xs classic-dropdown">
                            <?php
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
                                    <li><a href="<?php echo home_url(); ?>/"><?php _e('Home','cactusthemes') ?></a></li>
                                    <?php wp_list_pages('depth=1&number=5&title_li=' ); ?>
                            <?php }
							//user menu
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
                                    <li class="menu-item"><a class="" href="#" data-toggle="modal" data-target="#submitModal"><?php _e($text_bt_submit,'cactusthemes'); ?></a></li>
                       			<?php }
								} else{
								?>
                                    <li class="menu-item"><a class="" href="#" data-toggle="modal" data-target="#submitModal"><?php _e($text_bt_submit,'cactusthemes'); ?></a></li>
                       			<?php
								}
							} ?>
                            </ul>
                        <?php } ?>
					</div><!-- /.navbar-collapse -->
				</div>
			</nav>
		</div><!-- #top-nav -->
		<?php
			$top_nav_html=ob_get_contents();
			ob_end_clean();
			echo $topnav_layout=='layout-3'?'':$top_nav_html;
		}
		$show_top_headline = ot_get_option('show_top_headline');
		$social_account = array(
			'facebook',
			'instagram',
			'envelope',
			'twitter',
			'linkedin',
			'tumblr',
			'google-plus',
			'pinterest',
			'youtube',
			'flickr',
			'vk'
		);
		?>
        <div id="headline" class="<?php echo $topnav_style=='light'?'topnav-light light-div':'topnav-dark'; echo $fixed?' is-fixed-nav':''; echo ' '.$topnav_layout; ?>">
            <div class="container">
                <div class="row">
                	<?php if(is_front_page()||is_page_template('page-templates/front-page.php')){
						$count = 0;
						foreach($social_account as $social){
							if($link = ot_get_option('acc_'.$social,false)){
								$count++;
							}
						}
						if($search_icon==1 && $show_top_headline == 0 && $count == 0 ){
							 echo '<style scoped="scoped">#headline {height:0; overflow: hidden; opacity:0; border: 0;}</style>';
						}
					?>
                    <div class="headline-content col-md-6 col-sm-6 hidden-xs">
                    	<?php if ( is_active_sidebar( 'headline_sidebar' ) ) : ?>
							<?php dynamic_sidebar( 'headline_sidebar' ); ?>
                        <?php else:
								
								$number_item = ot_get_option('number_item_head_show');
								$icon_headline = ot_get_option('icon_headline');
								$title_headline = ot_get_option('title_headline');
								$cat= ot_get_option('cat_head_video');
								$headline_orderby = ot_get_option('headline_orderby','rand');
								if($show_top_headline!=0){
                             		echo do_shortcode('[headline link="yes" icon="'.$icon_headline.'" sortby="'.$headline_orderby.'" cat="'.$cat.'" posttypes="post" number="'.$number_item.'" title="'.$title_headline.'" ]');
								}?>
                        <?php endif; ?>
                    </div>
                    <?php }elseif(is_active_sidebar('pathway_sidebar')){
							echo '<div class="pathway pathway-sidebar col-md-6 hidden-xs">';
							dynamic_sidebar('pathway_sidebar');
							echo '</div>';
						}else{?>
                    <div class="pathway col-md-6 col-sm-6 hidden-xs">
                    	<?php if(function_exists('tm_breadcrumbs')){ tm_breadcrumbs(); } ?>
                    </div>
                    <?php } ?>
                    <div class="social-links col-md-6 col-sm-6">
                    	<div class="pull-right">
                        <?php 
						$file = get_post_meta($post->ID, 'tm_video_file', true);
						$url = trim(get_post_meta($post->ID, 'tm_video_url', true));
						$user_turnoff = ot_get_option('user_turnoff_load_next');
						$auto_load= ot_get_option('auto_load_next_video');
						$replay_state= ot_get_option('replay_state');
						if((strpos($file, 'youtube.com') !== false)&&($using_yt_param !=1) ||(strpos($url, 'youtube.com') !== false )&&($using_yt_param !=1) || (strpos($file, 'vimeo.com') !== false)||(strpos($url, 'vimeo.com') !== false )) { 
						if(is_single()){
						?>
                            <div class="tm-autonext" id="tm-autonext">
                            <?php if($user_turnoff==1){?>
                            <span class="<?php if($replay_state!='on'){?>autonext<?php }?>" id="autonext" >
                                <a href="#" data-toggle="tooltip" title="<?php _e('Auto Next ON','cactusthemes') ?>" class="gptooltip turnoffauto" data-animation="true">
                                    <i class="fa fa-play"></i>
                                </a>
                            </span>
                            <script>
                            jQuery(document).ready(function(e) {
								var className = jQuery('#tm-autonext span#autonext').attr('class');
								if(className==''){
									jQuery('#tm-autonext .tooltip-inner').html('<?php _e('Auto Next OFF','cactusthemes') ?>');
									jQuery('#tm-autonext .gptooltip.turnoffauto').attr('title', '<?php _e('Auto Next OFF','cactusthemes') ?>');
									jQuery('#tm-autonext').toggle(function(){
										  jQuery('#tm-autonext span#autonext').addClass('autonext');
										  jQuery('#tm-autonext .tooltip-inner').html('<?php _e('Auto Next ON','cactusthemes') ?>');
										  jQuery('#tm-autonext .gptooltip.turnoffauto').attr('data-original-title', '<?php _e('Auto Next ON','cactusthemes') ?>');
									},
									function(){
										  jQuery('#tm-autonext span#autonext').removeClass('autonext');
										  jQuery('#tm-autonext .tooltip-inner').html('<?php _e('Auto Next OFF','cactusthemes') ?>');
										  jQuery('#tm-autonext .gptooltip.turnoffauto').attr('data-original-title', '<?php _e('Auto Next OFF','cactusthemes') ?>');
									});	
								}else{
									jQuery('#tm-autonext').toggle(function(){
										  jQuery('#tm-autonext span#autonext').removeClass('autonext');
										  jQuery('#tm-autonext .tooltip-inner').html('<?php _e('Auto Next OFF','cactusthemes') ?>');
										  jQuery('#tm-autonext .gptooltip.turnoffauto').attr('data-original-title', '<?php _e('Auto Next OFF','cactusthemes') ?>');
									},
									function(){
										  jQuery('#tm-autonext span#autonext').addClass('autonext');
										  jQuery('#tm-autonext .tooltip-inner').html('<?php _e('Auto Next ON','cactusthemes') ?>');
										  jQuery('#tm-autonext .gptooltip.turnoffauto').attr('data-original-title', '<?php _e('Auto Next ON','cactusthemes') ?>');
									});	
								}
                            });
                            </script>
                            <?php }?>
                            </div>
                        <?php 
						}
						} ?>
                        
                        <?php if (class_exists('Woocommerce')) {
                            global $woocommerce;
                            $cart_url = $woocommerce->cart->get_cart_url();
                            $checkout_url = $woocommerce->cart->get_checkout_url();
                            if($woocommerce->cart->get_cart_contents_count()){ 
                            ?>
                            <a href="<?php echo $cart_url;?>" class="social-icon shopping maincolor1"><i class="fa fa-shopping-cart"></i><?php echo ' ('.$woocommerce->cart->get_cart_contents_count(),')';?></a>
                        <?php } }?>

                        
                        <?php 
						$social_link_open = ot_get_option('social_link_open');
						foreach($social_account as $social){
							if($link = ot_get_option('acc_'.$social,false)){
								if($social=='envelope'){ ?>
                                	<a class="social-icon<?php echo $topnav_style=='dark'?' maincolor1 bordercolor1hover bgcolor1hover':'' ?>" href="mailto:<?php echo $link ?>" <?php if($social_link_open==1){?>target="_blank" <?php }?>><i class="fa fa-<?php echo $social ?>"></i></a>
                                <?php } else{?>
                        			<a class="social-icon<?php echo $topnav_style=='dark'?' maincolor1 bordercolor1hover bgcolor1hover':'' ?>" href="<?php echo $link ?>" <?php if($social_link_open==1){?>target="_blank" <?php }?>><i class="fa fa-<?php echo $social ?>"></i></a>
                        <?php } } 
						}
						if($custom_acc = ot_get_option('custom_acc')){
							foreach($custom_acc as $a_social){ ?>
                                <a class="social-icon<?php echo $topnav_style=='dark'?' maincolor1 bordercolor1hover bgcolor1hover':'' ?>" href="<?php echo $a_social['link'] ?>" <?php if($social_link_open==1){?>target="_blank" <?php }?>><i class="fa <?php echo $a_social['icon'] ?>"></i></a>
							<?php }
						}
						?>
                        <?php 
						if($search_icon!=1){ ?>
                        <a class="search-toggle social-icon<?php echo $topnav_style=='dark'?' maincolor1 bordercolor1hover bgcolor1hover':''; echo $topnav_layout?' visible-xs':'' ?>" href="#"><i class="fa fa-search"></i></a>
                        <div class="headline-search">
							<?php if ( is_active_sidebar( 'search_sidebar' ) ) : ?>
                                <?php dynamic_sidebar( 'search_sidebar' ); ?>
                            <?php else: ?>
                                <form class="dark-form" action="<?php echo home_url() ?>">
                                    <div class="input-group">
                                        <input type="text" name="s" class="form-control" placeholder="<?php echo __('Search for videos','cactusthemes');?>">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default maincolor1 maincolor1hover" type="submit"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </div><!--/heading-search-->
                        <?php }?>
                        </div>

                    </div>
                </div><!--/row-->
				
				<?php tm_display_ads('ad_top_2');?>
            </div><!--/container-->			
        </div><!--/headline-->
        <?php echo $topnav_layout=='layout-3'?$top_nav_html:''; ?>