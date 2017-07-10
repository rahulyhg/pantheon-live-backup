 <?php while (have_posts()) : the_post(); global $post;?>
<?php
if( !isset($content_width) ){ $content_width = 900; }
$file = get_post_meta($post->ID, 'tm_video_file', true);
global $url;
$url = trim(get_post_meta($post->ID, 'tm_video_url', true));
$code = trim(get_post_meta($post->ID, 'tm_video_code', true));
$multi_link = get_post_meta($post->ID, 'tm_multi_link', true);
global $link_arr;
if(!empty($multi_link)){
	$link_arr = tm_build_multi_link($multi_link, false);
	//check request
	if(isset($_GET['link']) && $_GET['link']!==''){
		$url = $link_arr[$_GET['link']]['url'];
	}
}
$auto_load = ot_get_option('auto_load_next_video');
$auto_load_prev = ot_get_option('auto_load_next_prev');
global $auto_play;
$auto_play= ot_get_option('auto_play_video');
$delay_video= ot_get_option('delay_video');
$delay_video=$delay_video*1000;
if($delay_video==''){$delay_video=1000;}
$detect = new Mobile_Detect;
global $_device_, $_device_name_, $_is_retina_;
$_device_ = $detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'pc';
if($detect->isMobile() || $detect->isTablet()){
	$auto_play=0;
}
$onoff_related_yt= ot_get_option('onoff_related_yt');
$onoff_html5_yt= ot_get_option('onoff_html5_yt');
$using_yt_param = ot_get_option('using_yout_param');
$onoff_info_yt = ot_get_option('onoff_info_yt');
$allow_full_screen = ot_get_option('allow_full_screen');
$allow_networking = ot_get_option('allow_networking');
$remove_annotations = ot_get_option('remove_annotations');
$user_turnoff = ot_get_option('user_turnoff_load_next');
$interactive_videos = ot_get_option('interactive_videos');

$social_locker = get_post_meta($post->ID, 'social_locker', true);
$video_ads_id = get_post_meta($post->ID, 'video_ads_id', true);
$video_ads = ot_get_option('video_ads','off');

$youtube_quality = ot_get_option('youtube_quality','default');

$player_logic = get_post_meta($post->ID, 'player_logic', true);
$player_logic_alt = get_post_meta($post->ID, 'player_logic_alt', true);

$using_jwplayer_param = ot_get_option('using_jwplayer_param');

$video_source = '';
$main_video_url = '';
$player = ot_get_option('single_player_video');	
if((strpos($file, 'youtube.com') !== false)||(strpos($url, 'youtube.com') !== false )) {
	$video_source = 'youtube';
	$main_video_url = Video_Fetcher::extractIDFromURL($url);
}
else if((strpos($file, 'vimeo.com') !== false)||(strpos($url, 'vimeo.com') !== false )) {
	$video_source = 'vimeo';
	$main_video_url = Video_Fetcher::extractIDFromURL($url);
}
else if((strpos($file, site_url()) !== false)||(strpos($url, site_url()) !== false )) {
	$video_source = 'self-hosted';
	$main_video_url = $file;
}
$id_vid = trim(get_post_meta($post->ID, 'tm_video_id', true));
$youtube_start = get_post_meta($post->ID, 'youtube_start', true)*1;
$youtube_end = get_post_meta($post->ID, 'youtube_end', true)*1;

//auto-load
if($file ==''&& $url =='' && $code =='' && $id_vid ==''){
echo '<style type="text/css">
		#player{ display: none}
	 </style>';

}
echo '<input type="hidden" name="main_video_url" value="' . $main_video_url . '"/>
	 <input type="hidden" name="main_video_type" value="' . $video_source . '"/>';
$jwplayer_select = ot_get_option('jwplayer_select');
$force_videojs = ot_get_option('force_videojs');
$single_player_video = ot_get_option('single_player_video');
if($force_videojs=='on' && $single_player_video== 'videojs' && (strpos($url, 'youtube.com') !== false || (strpos($url, 'vimeo.com') !== false ) || strpos($url, 'dailymotion.com') !== false)){
}else{

	if((strpos($file, 'youtube.com') !== false)||(strpos($url, 'youtube.com') !== false )){
		if(($using_yt_param !=1 && $using_jwplayer_param!=1) ||($using_yt_param ==1 && $using_jwplayer_param!=1 && $detect->isTablet())){
			//if ads is on
			if(class_exists('video_ads') && ($video_ads=='on' && $video_ads_id !== '' )):
			?>
			<?php else:
			parse_str( parse_url( $url, PHP_URL_QUERY ), $video_id_yt );
			$pl = preg_match('/list=(PL[a-f0-9]+)/i', $url, $match_pll);
			?>
			<script src="//www.youtube.com/player_api"></script>
				<script>
					// create youtube player
					var player;
					function onYouTubePlayerAPIReady() {
						player = new YT.Player('player-embed', {
						  height: '506',
						  width: '900',
						  videoId: '<?php echo esc_attr($video_id_yt['v']); ?>',
						  <?php if($onoff_related_yt!= '0' || $onoff_html5_yt== '1' || $remove_annotations!= '1' || $onoff_info_yt=='1'){ ?>
						  playerVars : {
							 <?php if($remove_annotations!= '1'){?>
							  iv_load_policy : 3,
							  <?php }
							  if($onoff_related_yt== '1'){?>
							  rel : 0,
							  <?php }
							  if($onoff_html5_yt== '1'){
							  ?>
							  html5 : 1,
							  <?php }
							  if($onoff_info_yt=='1'){
							  ?>
							  showinfo:0,
							  <?php }?>
							  <?php 
							  if($youtube_start!=''){?>
							  start: <?php echo esc_attr($youtube_start);?>,
							  <?php }
							  if($youtube_end!=''){
							  ?>
							  end: <?php echo esc_attr($youtube_end);?>,
							  <?php }?>
							  <?php if(isset($match_pll[1]) && $match_pll!=''){?>
							  listType:'playlist',
							  list: '<?php echo $match_pll[1];?>',
							  <?php }?>
						  },
						  <?php }?>
						  events: {
							'onReady': onPlayerReady,
							<?php if($auto_load=='1' || $auto_load=='2' || $auto_load=='3' || $user_turnoff==1){?>
							'onStateChange': onPlayerStateChange
							<?php } ?>
						  }
						});
					}
					// autoplay video
					function onPlayerReady(event) {
						event.target.setPlaybackQuality('<?php echo $youtube_quality;?>')
						<?php
						if($auto_play=='1'){?>
							event.target.playVideo();
						<?php } ?>
							
					}
					// when video ends
					function onPlayerStateChange(event) {
						if(event.data === 0) {
							setTimeout(function(){
							<?php if($auto_load!='3'){ ?>	
							var link = jQuery('.prev-post a').attr('href');
							<?php if($auto_load_prev){ ?>
								link = jQuery('.next-post a').attr('href');
							<?php } 
							}elseif($auto_load=='3'){?>
								var link = window.location.href;
							<?php }?>
							//alert(link);
							var className = jQuery('#tm-autonext span#autonext').attr('class');
							//alert(className);
							if(className!=''){
							  if(link !=undefined){
								  window.location.href= link;
							  }
							}
							},<?php echo $delay_video ?>);
						}
					}
		
				</script>
		<?php 
		endif;
		}
		if($using_jwplayer_param==1 && class_exists('JWP6_Plugin') && $jwplayer_select !='jwplayer_7'){
		?>
		<script>
			jQuery(document).ready(function() {
				jwplayer("player-embed").setup({
					file: "<?php echo $url ?>",
					width: 900,
					height: 506
				});
			});
			</script>
			<style>
			#player-embed_wrapper{ margin:0 auto}
			</style>
		<?php
		}
	}else if( ($auto_load=='1' || $auto_load=='2' || $auto_load=='3') && ( (strpos($file, 'vimeo.com') !== false ) || (strpos($url, 'vimeo.com') !== false ) || (strpos($code, 'vimeo.com') !== false ) ) ){
		if(class_exists('video_ads') && ($video_ads=='on' && $video_ads_id !== '' )):
		?>
		<?php else:?>
		<script src="<?php echo get_template_directory_uri().'/';?>js/froogaloop2.min.js"></script>
		<script>
			jQuery(document).ready(function() {
				jQuery('iframe').attr('id', 'player_1');
	
				var iframe = jQuery('#player_1')[0],
					player = $f(iframe),
					status = jQuery('.status_videos');
	
				// When the player is ready, add listeners for pause, finish, and playProgress
				player.addEvent('ready', function() {
					status.text('ready');
	
					player.addEvent('pause', onPause);
					<?php if ($auto_load=='1' || $auto_load=='2' || $auto_load=='3' || $user_turnoff==1){?>
					player.addEvent('finish', onFinish);
					<?php }?>
					//player.addEvent('playProgress', onPlayProgress);
				});
	
				// Call the API when a button is pressed
				jQuery(window).load(function() {
					player.api(jQuery(this).text().toLowerCase());
				});
	
				function onPause(id) {
				}
	
				function onFinish(id) {
					setTimeout(function(){
						<?php if($auto_load!='3'){ ?>	
						var link = jQuery('.prev-post a').attr('href');
						<?php if($auto_load_prev){ ?>
							link = jQuery('.next-post a').attr('href');
						<?php } 
							}elseif($auto_load=='3'){?>
							var	link = window.location.href;
							<?php }?>
						var className = jQuery('#tm-autonext span#autonext').attr('class');
						//alert(className);
						if(className!=''){
							if(link !=undefined){
								window.location.href= link;
							}
						}
					},<?php echo $delay_video ?>);
				}
			});
		</script>
	<?php endif; } else if( $auto_load=='1' && (strpos($file, 'dailymotion.com') !== false )||  $auto_load=='1' && (strpos($url, 'dailymotion.com') !== false )){?>
	<script>
		// This code loads the Dailymotion Javascript SDK asynchronously.
		(function() {
			var e = document.createElement('script'); e.async = true;
			e.src = document.location.protocol + '//api.dmcdn.net/all.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(e, s);
		}());
	
		// This function init the player once the SDK is loaded
		window.dmAsyncInit = function()
		{
			// PARAMS is a javascript object containing parameters to pass to the player if any (eg: {autoplay: 1})
			var player = DM.player("player-embed", {video: "<?php echo Video_Fetcher::extractIDFromURL($url); ?>", width: "900", height: "506", params:{<?php if($auto_play=='1'){?>autoplay :1, <?php } if($onoff_info_yt== '1'){?> info:0, <?php } if($onoff_related_yt== '1'){?> related:0 <?php }?>}});
	
			// 4. We can attach some events on the player (using standard DOM events)
			player.addEventListener("ended", function(e)
			{
				setTimeout(function(){
					var link = jQuery('.prev-post a').attr('href');
					<?php if($auto_load_prev){ ?>
						link = jQuery('.next-post a').attr('href');
					<?php } ?>
					var className = jQuery('#tm-autonext span#autonext').attr('class');
					//alert(className);
					if(className!=''){
						if(link !=undefined){
							window.location.href= link;
						}
					}
				},<?php echo $delay_video ?>);
				
			});
		};
	</script>
	<?php }elseif(strpos($url, 'facebook.com') !== false){?>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
	<?php }
}//end if player
wp_reset_postdata();
endwhile;
$background_image_post = get_post_meta($post->ID, 'ct_bg_image', true);
if($background_image_post){
	$ct_bg_attachment = get_post_meta($post->ID, 'ct_bg_attachment', true);
	$ct_bg_repeat = get_post_meta($post->ID, 'ct_bg_repeat', true);
	$ct_bg_position = get_post_meta($post->ID, 'ct_bg_position', true);
	$background_image_post = wp_get_attachment_image_src( $background_image_post, 'full', $icon );
	if($ct_bg_attachment=='fixed'){ ?>
    <style scoped>
		/*@media(min-width:768px){
			#body-wrap{
				position:fixed;
				top:0;
				bottom:0;
				left:0;
				right:0;
			}
			.admin-bar #body-wrap{
				top:32px;
			}
		}
		@media(min-width:768px) and (max-width:782px){
			.admin-bar #body-wrap{
				top:46px;
			}
			.admin-bar #off-canvas{
				top:46px;
			}
		}
		.bg-ad {
			right: 14px;
		}*/
		<?php if(ot_get_option('theme_layout')!=1){ ?>
			/*#body-wrap{
				position:fixed;
				top:0;
				bottom:0;
				left:0;
				right:0;
			}
			.admin-bar #body-wrap{
				top:32px;
			}
			@media(max-width:782px){
				.admin-bar #body-wrap{
					top:46px;
				}
				.admin-bar #off-canvas{
					top:46px;
				}
			}*/
		<?php } ?>
        </style>
	<?php
	}
}
$video_captions = get_post_meta($post->ID, 'caption_info', true);
$files = !empty($file) ? explode("\n", $file) : array();
if( ($player=='jwplayer' && class_exists('JWP6_Plugin') && $video_captions && ($file!='')) || ($player =='' && class_exists('JWP6_Plugin') && $video_captions && ($file!=''))){
	$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full')	
	?>
	<script>
		jQuery(document).ready(function() {
			jwplayer("player-embed").setup({
				playlist: [{
					image: "<?php echo esc_url( $thumb[0] );?>",
					file: "<?php echo trim($files[0]);?>",
					tracks: [
						<?php 
						foreach($video_captions as $capit){?>
							{ 
								file: '<?php echo esc_url($capit['file_language']);?>', 
								label: '<?php echo esc_attr($capit['title'])?>', 
								kind: 'captions' 
							},
						<?php 
						}?>
					],
				}],
				width: 900,
				height: 506
			});
		});
	</script>
	<?php
}?>
		<div id="player" <?php if($background_image_post){echo 'style="background-image:url('.$background_image_post[0].');  background-attachment:'.$ct_bg_attachment.';background-position:'.$ct_bg_position.'; background-repeat:'.$ct_bg_repeat.'"'; }?>>
        	<div class="container">
            	<div class="video-player">
                	<div class="player-content">
                    	<div class="player-content-inner">
                    <?php
						ob_start(); //for social locker
					?>
                    	<div id="player-embed">
                        <?php
							if(($using_jwplayer_param==1 && $jwplayer_select =='jwplayer_7' && (strpos($url, 'youtube.com') !== false)) || ($jwplayer_select =='jwplayer_7' && $file !=='')){
								cactus_jwplayer7();
							}elseif(strpos($url, 'facebook.com') !== false){?>
								<div class="fb-video" data-href="<?php echo $url; ?>" data-width="900"></div>
                                <?php
							}else if($force_videojs=='on' && $single_player_video== 'videojs' && (strpos($url, 'youtube.com') !== false || (strpos($url, 'vimeo.com') !== false ) || strpos($url, 'dailymotion.com') !== false)){
								get_template_part('videojs-player'); 
							}else{
								if((strpos($url, 'wistia.com') !== false )|| (strpos($code, 'wistia.com') !== false ) ){
									$id = substr($url, strrpos($url,'medias/')+7);
									?>
									<div id="wistia_<?php echo $id ?>" class="wistia_embed" style="width:900px;height:506px;" data-video-width="900" data-video-height="506">&nbsp;</div>
									<script charset="ISO-8859-1" src="//fast.wistia.com/assets/external/E-v1.js"></script>
									<script>
									wistiaEmbed = Wistia.embed("<?php echo $id ?>", {
									  version: "v1",
									  videoWidth: 900,
									  videoHeight: 506,
									  volumeControl: true,
									  controlsVisibleOnLoad: true,
									  playerColor: "688AAD",
									  volume: 5
									});
									</script>
									<?php
								}else {
									 if((strpos($file, 'youtube.com') !== false)&&($using_yt_param ==1)||(strpos($url, 'youtube.com') !== false )&&($using_yt_param ==1) && (!$detect->isTablet())){?>
									 <div class="obj-youtube">
										<object width="900" height="506">
										<param name="movie" value="//www.youtube.com/v/<?php echo Video_Fetcher::extractIDFromURL($url); ?><?php if($onoff_related_yt!= '0'){?>&rel=0<?php }if($auto_play=='1'){?>&autoplay=1<?php }if($onoff_info_yt=='1'){?>&showinfo=0<?php }if($remove_annotations!= '1'){?>&iv_load_policy=3<?php }if($onoff_html5_yt== '1'){?>&html5=1<?php }?>&wmode=transparent&autohide=1<?php if($youtube_start!= ''){?>&start=<?php echo esc_attr($youtube_start);}?><?php if($youtube_end!= ''){?>&end=<?php echo esc_attr($youtube_end);}?>&vq=<?php echo $youtube_quality;?>" ></param>
										<param name="allowFullScreen" value="<?php if($allow_full_screen!='0'){?>true<?php }else {?>false<?php }?>"></param>
										<?php if($interactive_videos==0){?>
										<param name="allowScriptAccess" value="samedomain"></param>
										<?php } else {?>
										<param name="allowScriptAccess" value="always"></param>
										<?php }?>
										<param name="wmode" value="transparent"></param>
										<embed src="//www.youtube.com/v/<?php echo Video_Fetcher::extractIDFromURL($url);if($onoff_related_yt!= '0'){?>&rel=0<?php }if($auto_play=='1'){?>&autoplay=1<?php }if($onoff_info_yt=='1'){?>&showinfo=0<?php }if($remove_annotations!= '1'){?>&iv_load_policy=3<?php }if($onoff_html5_yt== '1'){?>&html5=1<?php }?>&autohide=1<?php if($youtube_start!= ''){?>&start=<?php echo esc_attr($youtube_start);}?><?php if($youtube_end!= ''){?>&end=<?php echo esc_attr($youtube_end);}?>&vq=<?php echo $youtube_quality;?>"
										  type="application/x-shockwave-flash"
										  allowfullscreen="<?php if($allow_full_screen!='0'){?>true<?php }else {?>false<?php }?>"
										  <?php if($allow_networking=='0'){ ?>
										  allowNetworking="internal"
										  <?php }?>
										  <?php if($interactive_videos==0){?>
										  allowscriptaccess="samedomain"
										  <?php } else {?>
										  allowscriptaccess="always"
										  <?php }?>
										  wmode="transparent"
										  width="100%" height="100%">
										</embed>
										</object>
										</div>
									 <?php
									 }else {
										 if(($player=='jwplayer' && class_exists('JWP6_Plugin') && $video_captions && ($file!='')) || ($player =='' && class_exists('JWP6_Plugin') && $video_captions && ($file!=''))){
										 }else{
											 if($auto_play=='1'){ $url = (tm_video($post->ID, true));
											 }else { $url = (tm_video($post->ID, false));  }
										 }
									 }
								 }
							}// end if player
							?>
                        </div>
                        <?php
						//social locker
						$player_html=ob_get_contents();
						ob_end_clean();
						
						//for new shortcode
						ob_start();
						if($social_locker || $video_ads=='on' || $video_ads_id !== ''){
							if(class_exists('video_ads') && ($video_ads=='on' && $video_ads_id !== '' )){
								if($video_ads_id=='0'){$video_ads_id='';}
								$player_html = '[advs id="'.$video_ads_id.'"]'.$player_html.'[/advs]';
							}
							if($social_locker){
								$id_text = tm_get_social_locker($social_locker);
								$player_html = '[sociallocker '.$id_text.']'.$player_html.'[/sociallocker]';
							}
							echo do_shortcode($player_html);
						}else{
							echo $player_html;
						}
						
						$player_html_2=ob_get_contents();
						ob_end_clean();
						$player_html_2 = apply_filters('cactus_player_hook',$player_html_2, $url , $id_post = get_the_ID());						
						if(!strpos($player_logic, '[player]')===false){ //have shortcode
							echo do_shortcode(str_replace("[player]",$player_html_2,$player_logic));
						}elseif($player_logic){
							$player_logic="return (" . $player_logic . ");";
							if( eval($player_logic) ){
								echo $player_html_2;
							}elseif($player_logic_alt){
								echo '<div class="player-logic-alt">'.do_shortcode($player_logic_alt).'</div>';
								echo '<style>.player-button,.box-m{display:none !important;}</style>';
							}
						}else{
							echo $player_html_2;
						}
						
						?>
                        <div class="clearfix"></div>
                        </div><!--player-content-inner-->
                    </div><!--player-content-->
                    <!--<div class="player-button">
                       <a href="#" class="prev maincolor1hover bordercolor1hover"><i class="fa fa-chevron-left"></i></a>
                       <a href="#" class="next maincolor1hover bordercolor1hover"><i class="fa fa-chevron-right"></i></a>
                    </div>--><!--/player-button-->
                <?php $auto_load_same_cat= ot_get_option('auto_load_same_cat');
				$series = wp_get_post_terms(get_the_ID(), 'video-series', array("fields" => "all"));
				if( ot_get_option('enable_series','on')!='off' && ot_get_option('series_autonext','on')!='off' && !empty($series) && function_exists('get_post_series_next') ){
					$series_next = get_post_series_next(get_the_ID());
					$next = $series_next[0];
					$previous = $series_next[1];
				?>
                    <div class="player-button">
                    <div class="post-nav">
                    	<?php if($next!=''){?><div class="next-post"><a href="<?php echo get_permalink($next);?>" class="next" ><i class="fa fa-chevron-right"></i></a></div><?php }?>
                        <?php if($previous!=''){?><div class="prev-post"><a href="<?php echo get_permalink($previous);?>" class="prev" ><i class="fa fa-chevron-left"></i></a></div><?php }?>
                    </div>
                    </div>
                <?php
				}elseif($auto_load_same_cat=='1'){
					$pre_link =  get_next_post_link( $format = '%link',  $link = '%title',  $in_same_term = true );
					$npe_link =  get_previous_post_link( $format = '%link', '<i class="fa fa-chevron-right"></i>',  $in_same_term = true );
					if($pre_link=='' && $auto_load=='2'){
						$cat_it = get_the_category(get_the_ID());
						if($cat_it[0]){
							$args_c = array(
								'post_type' => 'post',
								'posts_per_page'   => 1,
								'order' =>'ASC',
								'post_status' => 'publish',
								'cat' => $cat_it[0]->cat_ID,
							 );
							 $c_query = get_posts($args_c);
							 foreach ( $c_query as $key => $post ) : setup_postdata( $post );
								 $first_it = $post->ID;break;
							 endforeach;
							 $previous_f = get_permalink($first_it);
						}
					}
					?>
                    <div class="player-button">
                        <div class="prev-post same-cat">
						<?php if($pre_link !=''){ next_post_link('%link','<i class="fa fa-chevron-left"></i>',TRUE,'') ; }elseif($auto_load=='2'){ ?>
                            <a href="<?php echo $previous_f;?>" ><i class="fa fa-chevron-left"></i></a>
						<?php }?></div>
                        <div class="next-post same-cat"><?php if($npe_link){echo $npe_link;}else{ previous_post_link('%link ','<i class="fa fa-chevron-right"></i>',TRUE,''); }?></div>
                   </div>
                    <?php
				}else
				 if($auto_load_same_cat=='0' || $auto_load_same_cat=='' ){?>
                     <div class="player-button">
                     <?php
					 	$cr_id = get_the_ID();
					 	wp_reset_postdata();
                        $n_tags = "";
                        $posttags = get_the_tags();
                        if ($posttags) {
                        foreach($posttags as $tag) {
                            $n_tags .= ',' . $tag->term_id;
                        }
                        }
                        $n_tags = substr($n_tags, 1);
                     $args = array(
                        'post_type' => 'post',
						'posts_per_page'   => -1,
                        'post_status' => 'publish',
                        'tag__in' => array($n_tags),
                     );
                     $current_key = $next = $previous= '';
                     $tm_query = get_posts($args);
					 $ct= 0;
                     foreach ( $tm_query as $key => $post ) : setup_postdata( $post );
					 	$ct ++;
                        if($post->ID == $cr_id){ $current_key = $ct; break;}
                     endforeach;
					 $current_key = $current_key-1;;
					 $id_pre = ($tm_query[$current_key+1]->ID);
					 $id_nex = ($tm_query[$current_key-1]->ID);
                     if($id_pre!= ''){ $next = get_permalink($tm_query[$current_key+1]->ID); }
                     if($id_nex!= ''){$previous = get_permalink($tm_query[$current_key-1]->ID);}
					 if($previous=='' && $auto_load=='2'){
						 $args_c = array(
							'post_type' => 'post',
							'posts_per_page'   => 1,
							'order' =>'ASC',
							'post_status' => 'publish',
							'tag__in' => array($n_tags),
						 );
						 $c_query = get_posts($args_c);
						 foreach ( $c_query as $key => $post ) : setup_postdata( $post );
							 $first_it = $post->ID;break;
						 endforeach;
						 $previous = get_permalink($first_it);
					 }
					 
                     ?>
                    <div class="post-nav">
                    	<?php if($next!=''){?><div class="next-post"><a href="<?php echo $next;?>" class="next maincolor1hover bordercolor1hover bgcolor-hover" ><i class="fa fa-chevron-right"></i></a></div><?php }?>
                        <?php if($previous!=''){?><div class="prev-post"><a href="<?php echo $previous;?>" class="prev maincolor1hover bordercolor1hover bgcolor-hover" ><i class="fa fa-chevron-left"></i></a></div><?php }?>
                    </div>

                </div>
                <?php  }?>
                </div>
             <?php
			 $onoff_more_video = ot_get_option('onoff_more_video');
			 if($onoff_more_video !='0'){ ?>
             <div class="box-m">
                <span class="box-more  single-full-pl" id="click-more" ><?php echo __('More','cactusthemes'); ?> &nbsp;<i class="fa fa-angle-down"></i></span>
            </div>
			<?php }?>
            </div><!--/container-->
        </div><!--/player-->
		<?php //related carousel
		if($onoff_more_video !='0'){
		wp_reset_postdata();
      	global $post;
		$id_curr = $post->ID;
		if(function_exists('ot_get_option')){$number_of_more = ot_get_option('number_of_more');}
		if($number_of_more=='' || !$number_of_more){$number_of_more=11;}
		global $wp_query;
			 $args = array(
				'posts_per_page' => $number_of_more,
				'post_type' => 'post',
				'post_status' => 'publish',
				'tax_query' => array(
				array(
					'taxonomy' => 'post_format',
					'field' => 'slug',
					'terms' => 'post-format-video',
				))
			 );
			 if(function_exists('ot_get_option')){$sort_of_more = ot_get_option('sort_of_more');}
			 if($sort_of_more=='1'){
				 $categories = get_the_category();
				 $category_id = $categories[0]->cat_ID;
				 if(isset($category_id)){
					$cats = explode(",",$category_id);
					if(is_numeric($cats[0])){
						//$args += array('category__in' => $cats);
						$args['category__in'] = $cats;
					}
				}
			 }
			 if($sort_of_more=='2'){
				 $cr_tags = get_the_tags();
				  if ($cr_tags) {
					  foreach($cr_tags as $tag) {
						  $tag_item .= ',' . $tag->slug;
					  }
				  }
				  $tag_item = substr($tag_item, 1);
				  //print_r($tag_item);
				  $args['tag'] = $tag_item;
			 }
			 $current_key_more = '';
			 $tm_query_more = get_posts($args);
			 //print_r($tm_query);
			 foreach ( $tm_query_more as $key_more => $post ) : setup_postdata( $post );
				if($post->ID == $id_curr){$current_key_more = $key_more;}
			 endforeach;

			 $e_in = $number_of_more/2;
			 if($number_of_more%2!=0){
				$e_in=explode(".",$e_in);
				$e_in = $e_in[1];
			 }
			 $n= $e_in;
		echo  '
			<div id="top-carousel" class="full-more more-hide">
				<div class="container">
					<div class="is-carousel" id="top2" data-notauto=1>
						<div class="carousel-content">';
								?>
                                    <div class="video-item marking_vd">
                                        <div class="item-thumbnail">
                                            <a href="<?php echo get_permalink($id_curr) ?>" title="<?php echo get_the_title($id_curr)?>">
                                            <?php
                                            if(has_post_thumbnail($id_curr)){
                                                $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($id_curr),'thumb_196x126', true);
                                            }else{
                                                $thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
                                            }
                                            ?>
                                            <img src="<?php echo $thumbnail[0] ?>" alt="<?php the_title_attribute($id_curr); ?>" title="<?php the_title_attribute($id_curr); ?>">
                                                <div class="link-overlay fa fa-play"></div>
                                            </a>
                                            <?php echo tm_post_rating($id_curr) ?>
                                            <div class="item-head">
                                                <h3><a href="<?php echo get_permalink($id_curr) ?>" title="<?php echo get_the_title($id_curr)?>"><?php echo get_the_title($id_curr)?></a></h3>
                                            </div>
                                       		 <div class="mark_bg"><?php  echo __('NOW PLAYING','cactusthemes');?></div>

                                        </div>
                                    </div><!--/video-item-->
               				<?php

								$add_cl='';
								$tm_query_more[$current_key_more]->ID;
								for($i=1;$i<=$n;$i++){
								$id_pre_m = ($tm_query_more[$current_key_more+$i]->ID);
								 //if($i==0){$add_cl='marking_vd';}
								if($id_pre_m){
								?>
                                    <div class="video-item <?php //echo $add_cl;?>">
                                        <div class="item-thumbnail">
                                            <a href="<?php echo get_permalink($id_pre_m) ?>" title="<?php echo get_the_title($id_pre_m)?>">
                                            <?php
                                            if(has_post_thumbnail($id_pre_m)){
                                                $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($id_pre_m),'thumb_196x126', true);
                                            }else{
                                                $thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
                                            }
                                            ?>
                                            <img src="<?php echo $thumbnail[0] ?>" alt="<?php the_title_attribute($id_pre_m); ?>" title="<?php the_title_attribute($id_pre_m); ?>">
                                                <div class="link-overlay fa fa-play"></div>
                                            </a>
                                            <?php echo tm_post_rating($id_pre_m) ?>
                                            <div class="item-head">
                                                <h3><a href="<?php echo get_permalink($id_pre_m) ?>" title="<?php echo get_the_title($id_pre_m)?>"><?php echo get_the_title($id_pre_m)?></a></h3>
                                            </div>
                                            <?php if($i==909){?>
                                       		 <div class="mark_bg"><?php  echo __('NOW PLAYING','cactusthemes');?></div>

                                        	<?php }?>
                                        </div>
                                    </div><!--/video-item-->
               				<?php
								}
								$add_cl='';
								}
						for($j=$n;$j>0;$j--){
						$id_nex_m = ($tm_query_more[$current_key_more-$j]->ID);
						if($id_nex_m!=''){
						?>
							<div class="video-item">
								<div class="item-thumbnail">
									<a href="<?php echo get_permalink($id_nex_m) ?>" title="<?php echo get_the_title($id_nex_m)?>">
									<?php
									if(has_post_thumbnail($id_nex_m)){
										$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($id_nex_m),'thumb_196x126', true);
									}else{
										$thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
									}
							?>
									<img src="<?php echo $thumbnail[0] ?>" alt="<?php the_title_attribute($id_nex_m); ?>" title="<?php the_title_attribute($id_nex_m); ?>">
										<div class="link-overlay fa fa-play"></div>
									</a>
									<?php echo tm_post_rating($id_nex_m) ?>
									<div class="item-head">
										<h3><a href="<?php echo get_permalink($id_nex_m) ?>" title="<?php echo get_the_title($id_nex_m)?>"><?php echo get_the_title($id_nex_m)?></a></h3>
									</div>
								</div>
							</div><!--/video-item-->
					<?php
						}
						}
					wp_reset_postdata();
					echo '
						</div><!--/carousel-content-->
						<div class="carousel-button more-videos">
							<a href="#" class="prev maincolor1 bordercolor1 bgcolor1hover"><i class="fa fa-chevron-left"></i></a>
							<a href="#" class="next maincolor1 bordercolor1 bgcolor1hover"><i class="fa fa-chevron-right"></i></a>
						</div><!--/carousel-button-->
					</div><!--/is-carousel-->
				</div><!--/container-->
			</div>';
		}
		?>
        <?php //video series
		$series = wp_get_post_terms(get_the_ID(), 'video-series', array("fields" => "all"));
		if( ot_get_option('enable_series','on')!='off' && !empty($series) && function_exists('get_post_series') ){ ?>
        <div class="video-series-wrap dark-div">
        	<div class="container text-center">
				<?php get_post_series(get_the_ID()); ?>
            </div>
        </div>
		<?php } ?>
        <?php ob_start(); //get toolbar html?>
        <div id="video-toolbar" class="light-div">
        	<div class="container">
                <div class="video-toolbar-inner">
                	<?php if(ot_get_option('single_show_meta_view',1)){
					if(is_plugin_active('baw-post-views-count/bawpv.php')){
					?>
                    <div class="video-toolbar-item">
                        <div class="wrap-toolbar-item">
                            <div class="maincolor2 toolbar-views-number">
							<?php echo  tm_short_number(get_post_meta(get_the_ID(),'_count-views_all',true)) ?>
                            </div>
                            <div class="maincolor2hover toolbar-views-label"><?php echo __('Views','cactusthemes'); ?>
                            <i class="fa fa-eye"></i></div>
                        </div>
                        <span class="middlefix"></span>
                    </div>
                    <?php }}
					if(ot_get_option('single_show_meta_comment',1)){ ?>
                    <div class="video-toolbar-item count-cm">
                        <span class="maincolor2hover"><a href="#comments" class="maincolor2hover"><i class="fa fa-comment"></i>  <?php echo  get_comments_number() ?></a></span>
                    </div>
                    <?php }?>
                    <?php if(function_exists('GetWtiLikePost')){ ?>
                    <div class="video-toolbar-item like-dislike">
                    	<?php if(function_exists('GetWtiLikePost')){ GetWtiLikePost();}?>
                        <!--<span class="maincolor2hover like"><i class="fa fa-thumbs-o-up"></i></span>
                        <span class="maincolor2hover dislike"><i class="fa fa-thumbs-o-down"></i></span>-->
                    </div>
                    <?php }?>
                    <?php if (function_exists('wpfp_link')) { ?>
                    <div class="video-toolbar-item tm-favories">
                    	<?php wpfp_link(); ?>
                    </div>
                    <?php }?>
                    <?php $show_hide_sharethis = ot_get_option('show_hide_sharethis');
					if(ot_get_option('share_facebook')||ot_get_option('share_twitter')||ot_get_option('share_linkedin')||ot_get_option('share_tumblr')||ot_get_option('share_google-plus')||ot_get_option('share_pinterest')||ot_get_option('share_email')||$show_hide_sharethis){
					?>
                    <div class="video-toolbar-item <?php echo $show_hide_sharethis?'':'tm-' ?>share-this collapsed" <?php if($show_hide_sharethis!=1){?> data-toggle="collapse" data-target="#tm-share" <?php }?>>
                        <span class="maincolor2hover">
                        <?php if($show_hide_sharethis == 1){
						$sharethis_key = ot_get_option('sharethis_key');
						?>
                        <span class='st_sharethis_large' displayText='ShareThis'></span>
                        <script type="text/javascript">var switchTo5x=false;</script>
                        <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
                        <script type="text/javascript">stLight.options({publisher: "<?php echo $sharethis_key ?>", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
                        <?php }else{ ?>
                        <i class="ficon-share"></i>
                        <?php }?>
                        </span>
                    </div>
                    <?php
					}
					if(ot_get_option('video_report','on')!='off') { ?>
                    <div class="video-toolbar-item tm-report">
                    	<a class="maincolor2hover" title="<?php echo esc_attr(__('Report','cactusthemes')); ?>" href="#" data-toggle="modal" data-target="#reportModal"><i class="fa fa-flag"></i></a>
                    </div>
                    <?php }
					if(ot_get_option('single_show_meta_like',1)){
					if(function_exists('GetWtiLikePost')){
					?>
                    <div class="video-toolbar-item pull-right col-md-2 video-toolbar-item-like-bar">
                        <div class="wrap-toolbar-item">
						  <?php
						  $main_color_2 = ot_get_option('main_color_2')?ot_get_option('main_color_2'):'#4141a0';
                          $mes= '<style type="text/css">.action-like a:after{ color:'.$main_color_2.' !important}</style>';
                          $mes_un= '<style type="text/css">.action-unlike a:after{ color:'.$main_color_2.'  !important}</style>';
                          if(function_exists('GetWtiVotedMessage')){ $msg = GetWtiVotedMessage(get_the_ID());}
                          if(!$msg){
                              echo '<style type="text/css">
                              .video-toolbar-item.like-dislike .status{display:none !important;}
							  .video-toolbar-item.like-dislike:hover .status{display:none !important;}</style>';
                          }
						  $ip='';
                          if(function_exists('WtiGetRealIpAddress')){$ip = WtiGetRealIpAddress();}
                          $tm_vote = TmAlreadyVoted(get_the_ID(), $ip);
                              // get setting data
                              $is_logged_in = is_user_logged_in();
                              $login_required = get_option('wti_like_post_login_required');
                              if ($login_required && !$is_logged_in) {
                                      echo $mes;
                                      echo $mes_un;
                              } else {
                                  if(function_exists('HasWtiAlreadyVoted')){$has_already_voted = HasWtiAlreadyVoted(get_the_ID(), $ip);}
                                  $voting_period = get_option('wti_like_post_voting_period');
                                  $datetime_now = date('Y-m-d H:i:s');
                                  if ("once" == $voting_period && $has_already_voted) {
                                      // user can vote only once and has already voted.
                                      if($tm_vote>0){echo $mes;}
                                      else if ($tm_vote<0){echo $mes_un;}
                                  } elseif (0 == $voting_period) {
									  if($tm_vote>0){echo $mes;}
                                      else if ($tm_vote<0){echo $mes_un;}
                                  } else {
                                      if (!$has_already_voted) {
                                          // never voted befor so can vote
                                      } else {
                                          // get the last date when the user had voted
                                          if(function_exists('GetWtiLastVotedDate')){$last_voted_date = GetWtiLastVotedDate(get_the_ID(), $ip);}
                                          // get the bext voted date when user can vote
                                          if(function_exists('GetWtiLastVotedDate')){$next_vote_date = GetWtiNextVoteDate($last_voted_date, $voting_period);}
                                          if ($next_vote_date > $datetime_now) {
                                              $revote_duration = (strtotime($next_vote_date) - strtotime($datetime_now)) / (3600 * 24);

                                              if($tm_vote>0){echo $mes;}
                                              else if ($tm_vote<0){echo $mes_un;}
                                          }
                                      }
                                  }
                              }
							$like = $unlike = $fill_cl = $sum = '';
                            if(function_exists('GetWtiLikeCount')){$like = GetWtiLikeCount(get_the_ID());}
                            if(function_exists('GetWtiUnlikeCount')){$unlike = GetWtiUnlikeCount(get_the_ID());}
							$re_like = str_replace('+','',$like);
							$re_unlike = str_replace('-','',$unlike);
							$sum = $re_like + $re_unlike;
							if($sum!=0 && $sum!=''){
								$fill_cl = (($re_like/$sum)*100);
							} else
							if($sum==0){
								$fill_cl = 50;
							}
                            ?>
                            <div class="like-bar"><span style="width:<?php echo $fill_cl ?>%"><!----></span></div>
                            <div class="like-dislike pull-right">
                            	<span class="like"><i class="fa fa-thumbs-o-up"></i>  <?php echo $like ?></span>
                            	<span class="dislike"><i class="fa fa-thumbs-o-down"></i> <?php echo $unlike ?></span>
                            </div>
                        </div>
                    </div>
                    <?php } }?>
                    <?php
						gp_social_share(get_the_ID());
					?>
                    <div class="clearfix"></div>
                    <?php if(!$show_hide_sharethis){?>
                    <div id="tm-share" class="collapse">
                    	<div class="tm-share-inner social-links">
						<?php
						_e('Share this with your friends via:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;','cactusthemes');
						tm_social_share();
						?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="tm-social-res">
                <?php gp_social_share(get_the_ID()); ?>
                </div>
            </div><!--/container-->
        </div><!--/video-toolbar-->
        <?php
		global $video_toolbar_html;
		$video_toolbar_html = ob_get_clean();
		if(ot_get_option('video_toolbar_position','top')=='top'){
			echo $video_toolbar_html;
		}
        wp_reset_postdata();