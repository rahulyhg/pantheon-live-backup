<?php ob_start(); //get toolbar html?>
	<div class="single-inbox">
        <div id="video-toolbar">
        	<div class="container">
                <div class="video-toolbar-inner">
                <?php if(ot_get_option('single_show_meta_view',1)){ 
					if(is_plugin_active('baw-post-views-count/bawpv.php')){
					?>
                    <div class="video-toolbar-item">
                        <div class="wrap-toolbar-item">
                            <div class="maincolor2 toolbar-views-number">
								<?php echo  get_post_meta(get_the_ID(),'_count-views_all',true) ?>
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
					if($show_hide_sharethis!='0'){
					?>
                    <div class="video-toolbar-item share-this">
                        <span class="maincolor2hover">
                        <span class='st_sharethis_large' displayText='ShareThis'></span>
                        <script type="text/javascript">var switchTo5x=false;</script>
                        <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
                        <script type="text/javascript">stLight.options({publisher: "37243fc6-d06b-449d-bdd3-a60613856c42", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>     
                        </span>
                    </div>
                    <?php }
					if(ot_get_option('video_report','on')!='off') { ?>
                    <div class="video-toolbar-item tm-report">
                    	<a class="maincolor2hover" title="<?php echo esc_attr(__('Report','cactusthemes')); ?>" href="#" data-toggle="modal" data-target="#reportModal"><i class="fa fa-flag"></i></a>
                    </div>
                    <?php }?>
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
                    <div class="clearfix"></div>
                </div>
            </div><!--/container-->
        </div><!--/video-toolbar-->
    </div>
		<?php
		$video_toolbar_html = ob_get_clean();
		echo $video_toolbar_html;