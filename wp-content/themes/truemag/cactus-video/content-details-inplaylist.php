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

$player_logic = get_post_meta($post->ID, 'player_logic', true);
$player_logic_alt = get_post_meta($post->ID, 'player_logic_alt', true);

$video_source = '';

if((strpos($file, 'youtube.com') !== false)||(strpos($url, 'youtube.com') !== false )) {
	$video_source = 'youtube';
}
else if((strpos($file, 'vimeo.com') !== false)||(strpos($url, 'vimeo.com') !== false )) {
	$video_source = 'vimeo';
}

$id_vid = trim(get_post_meta($post->ID, 'tm_video_id', true));
if($file ==''&& $url =='' && $code ==''&& $id_vid ==''){
echo '<style type="text/css">
		#player{ display: none}
	 </style>';	
}
echo '<input type="hidden" name="main_video_url" value="' . Video_Fetcher::extractIDFromURL($url) . '"/>
	 <input type="hidden" name="main_video_type" value="' . $video_source . '"/>';
if($delay_video==''){$delay_video=1000;}
$using_jwplayer_param = ot_get_option('using_jwplayer_param');
//auto-load
$force_videojs = ot_get_option('force_videojs');
$single_player_video = ot_get_option('single_player_video');
endwhile;
$video_captions = get_post_meta($post->ID, 'caption_info', true);
$files = !empty($file) ? explode("\n", $file) : array();
?> 
<div class="single-inbox">
  <!--/player-->
  <?php
  $onoff_more_video = ot_get_option('onoff_more_video');
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
			<div id="top-carousel" class="inbox-more more-hide">
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
		//video series   
        $series = wp_get_post_terms(get_the_ID(), 'video-series', array("fields" => "all"));
		if( ot_get_option('enable_series','on')!='off' && !empty($series) && function_exists('get_post_series') ){ ?>
        <div class="video-series-wrap">
        	<div class="text-center">
				<?php get_post_series(get_the_ID()); ?>
            </div>
        </div>
		<?php } ?>                 
        <?php 
        if (have_posts()) :
            while (have_posts()) : the_post();?>
            <div class="box-title">
            	<div class="title-info">
                    <h1 class="light-title"><?php the_title(); ?></h1>
                    <?php if(is_single()){ ?>
                    <div class="item-info">
						<?php if(ot_get_option('single_show_meta_author',1)){the_author_posts_link();} ?>
                        <?php if(ot_get_option('single_show_meta_date',1)){ ?>
                        <span class="item-date"><?php the_time(get_option('date_format')); ?> <?php the_time(get_option('time_format')); ?></span>
                        <?php }?>
                    </div>
                </div>
                <?php 
				 
				 if($onoff_more_video !='0'){ ?> 
                <div class="box-m">
                	<span class="box-more" id="click-more" ><?php echo __('More videos','cactusthemes'); ?> <i class="fa fa-angle-down"></i></span>
                </div>
                <?php }?>
            </div>
        <?php ob_start(); //get toolbar html?>
        <div id="video-toolbar">
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
                            <div class="maincolor2hover toolbar-views-label"><?php echo __('Views  ','cactusthemes'); ?><i class="fa fa-eye"></i></div>
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
                    <div class="video-toolbar-item <?php echo $show_hide_sharethis?'':'tm-' ?>share-this collapsed" <?php if($show_hide_sharethis!=1){?>data-toggle="collapse" data-target="#tm-share" <?php }?>>
                        <span class="maincolor2hover">
                        <?php if($show_hide_sharethis==1){
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
                    <div class="video-toolbar-item pull-right col-md-3 video-toolbar-item-like-bar">
                        <div class="wrap-toolbar-item">
                        <?php 
						  $main_color_2 = ot_get_option('main_color_2')?ot_get_option('main_color_2'):'#4141a0';
                          $mes= '<style type="text/css">.action-like a:after{ color:'.$main_color_2.' !important}</style>';
                          $mes_un= '<style type="text/css">.action-unlike a:after{ color:'.$main_color_2.' !important}</style>';
                          if(function_exists('GetWtiVotedMessage')){$msg = GetWtiVotedMessage(get_the_ID());}
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
                            	<span class="dislike"><i class="fa fa-thumbs-o-down"></i>  <?php echo $unlike ?></span>
                            </div>
                        </div>
                    </div>
                    <?php } }?>
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
            </div><!--/container-->
        </div><!--/video-toolbar-->
		<?php
		$video_toolbar_html = ob_get_clean();
		if(ot_get_option('video_toolbar_position','top')=='top'){
			echo $video_toolbar_html;
		}
       // $social_post= get_post_meta($post->ID,'show_hide_social',true);
       /// if($social_post=='show'){ //check if show social share
            gp_social_share(get_the_ID());
       //}
//        if($social_post=='def'){
//            if( ot_get_option( 'blog_show_socialsharing', 1)){ //check if show social share
//                gp_social_share(get_the_ID());
//            }
//        }
        ?>
        <?php tm_display_ads('ad_single_content');?>        
        <div class="<?php echo is_single()?'item-content':'content-single'; ?>">
        	<?php
			if(!empty($multi_link)){
				tm_build_multi_link($multi_link, true);
			}
			the_content(); ?>
            <div class="clearfix"></div>
            <?php if(is_single()){ ?>
            <div class="item-tax-list">
            	<?php 
				$onoff_tag = ot_get_option('onoff_tag');
				$onoff_cat = ot_get_option('onoff_cat');
				if($onoff_cat !='0'){
				 ?>
                <div><strong><?php _e('Category:', 'cactusthemes'); ?> </strong><?php the_category(', '); ?></div>
                <?php }
				if($onoff_tag !='0'){
				?>
                <div><?php the_tags('<strong>'.__('Tags:', 'cactusthemes').' </strong>', ', ', ''); ?></div>
                <?php }?>
            </div>
            <?php 
				if(ot_get_option('video_toolbar_position','top')=='bottom'){
					echo '<br>'.$video_toolbar_html;
				}
			} ?>
    	</div><!--/item-content-->
        <?php }endwhile;
        endif;
		?>
</div>
