	(function($){
		var minusDefault = 240;
		if($('.single-inbox').length > 0)
		{
			minusDefault = 0;
			plusDefault = 0;
		}
		else
		{
			if($(window).width() < 1218){minusDefault = 0;}else{minusDefault = 240}
			plusDefault = 0;
		}

		var checkWidth = $('.video-player').width() - minusDefault;
		var checkHeight = (checkWidth / 16 * 9) + plusDefault;

    	$(window).resize(function() {
    		if($('.single-inbox').length > 0)
    		{
	    		setTimeout(function(){
	    			minusDefault = 0;
					checkWidth = $('.video-player').width() - minusDefault;
					checkHeight = (checkWidth / 16 * 9) + plusDefault;
					$('.cactus-video-list .cactus-video-item').css({"width": checkWidth + "px" , "height": checkHeight + "px"});
					$('.cactus-video-list .cactus-video-item .cactus-video-content').css({"width": checkWidth + "px" , "height": checkHeight + "px"});
					$('.cactus-video-list .cactus-video-item .cactus-video-ads iframe').css({"width": checkWidth + "px" , "height": checkHeight + "px"});
					$('.cactus-video-list .cactus-video-item .cactus-video-ads .wp-video-shortcode').css({"width": checkWidth + "px" , "height": checkHeight + "px"});
	    		},400)
    		}
    		else
    		{
    			setTimeout(function(){
	    			if($(window).width() < 1218){minusDefault = 0;}else{minusDefault = 240}
					checkWidth = $('.video-player').width() - minusDefault;
					checkHeight = checkWidth / 16 * 9;
					$('.cactus-video-list .cactus-video-item').css({"width": checkWidth + "px" , "height": checkHeight + "px"});
					$('.cactus-video-list .cactus-video-item .cactus-video-content').css({"width": checkWidth + "px" , "height": checkHeight + "px"});
					$('.cactus-video-list .cactus-video-item .cactus-video-ads iframe').css({"width": checkWidth + "px" , "height": checkHeight + "px"});
					$('.cactus-video-list .cactus-video-item .cactus-video-ads .wp-video-shortcode').css({"width": checkWidth + "px" , "height": checkHeight + "px"});
	    		},400)
    		}
    	});

		$(document).ready(function() {

		/*Video Youtube Iframe*/
		var tag = document.createElement('script');
		tag.src = "https://www.youtube.com/iframe_api";
		var firstScriptTag = document.getElementsByTagName('script')[0];
		firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
		/*Video Youtube Iframe*/

		var cactusAllVideoList='cactus-video-list';
		var cactusVideoItem='cactus-video-item';
		var cactusVideoDetails='cactus-video-details';
		var cactusVideoContent='cactus-video-content';
		var cactusVideoAds='cactus-video-ads';

		var $global_this='';
		var click_count = [];
		var flag = [];
		var flag_vimeo = [];
		var flag_ads_vimeo = [];

		//youtube variable
		var cactus_player=[];
		var cactus_player_Ads=[];
		var cactus_player_Ads1=[];

		//vimeo variable

		var cactus_vimeo_player=[];
		var cactus_vimeo_player_Ads=[];

		var cactus_main_vimeo=[];
		var cactus_main_vimeo_player=[];

		var cactus_ads_vimeo_obj=[];
		var cactus_ads_vimeo_player=[];


		//html5 variable
		var cactus_html5_player=[];
		var cactus_html5_player_Ads=[];



		window.onYouTubeIframeAPIReady = function () {	//onYouTubeIframeAPIReady

			$('.'+cactusAllVideoList).find('.'+cactusVideoItem).each(function(index, element) {
				var $this=$(this);
				$global_this=$(this);

				var divVideoId=cactusVideoItem+'-'+index;
				var AdsVideoId=cactusVideoAds+'-'+index;

				$this.find('.'+cactusVideoDetails).find('.'+cactusVideoContent).attr('id', divVideoId);

				var adsID					= $this.attr("data-ads-id");
				var videoWidth				= $this.attr("data-width");
				var videoHeight				= $this.attr("data-height");
				var videoSource				= $this.attr("data-source");
				var videoLink 				= $this.attr("data-link");
				var videoAdsType 			= $this.attr("data-ads-type");
				var videoAds 				= $this.attr("data-ads");
				var videoAdsSource 			= $this.attr("data-ads-source");
				var playbackAdsID			= $this.attr("playback-data-ads-id");
				var playbackVideoAdsType 	= $this.attr("playback-data-ads-type");
				var playbackVideoAds 		= $this.attr("playback-data-ads");
				var playbackVideoAdsSource 	= $this.attr("playback-data-ads-source");
				var playbackDataLinkRedirect= $this.attr("playback-data-link-redirect");
				var videoAutoPlay 			= $this.attr("data-autoplay");
				var videoDataTimeHideAds 	= parseInt($this.attr("data-time-hide-ads"));
				var closeButtonName 		= $this.attr("data-close-button-name");
            	var videoDataLinkRedirect	= $this.attr("data-link-redirect");
				var adsDataTimePlayAgain 	= parseInt($this.attr("ads-play-again-after"));
				var fullBannerDataTimePlayAgain 	= parseInt($this.attr("full-banner-play-again-after"));
				var topBottomDataTimePlayAgain 	= parseInt($this.attr("top-bottom-banner-play-again-after"));
            	var closeButtonPosition		= $this.attr("close-button-position");
            	var isMobileOrTablet		= $this.attr("is-mobile-or-tablet");
            	var autoLoadNextVideo		= $this.attr("auto-next-video");
            	var autoLoadNextVideoOptions= $this.attr("auto-next-video-options");

            	var enableBrand				= $this.attr("enable-brand");
            	var brandLogo				= $this.attr("brand-logo");
            	var brandText				= $this.attr("brand-text");
            	var brandPosition			= $this.attr("brand-position");
            	var brandColor				= $this.attr("brand-color");
            	var brandOpacity			= $this.attr("brand-opacity");


            	var adsImagePosition		= $this.attr("ads-image-position");
            	var playbackAdsImagePosition= $this.attr("playback-ads-image-position");

            	var youtubeQuality			= $this.attr("youtube_quality");

            	var videoDataTimePlayAgain 	= adsDataTimePlayAgain;

            	var isClickCloseButtonFirstTime = true;
            	var isVimeoPlayback 			= false;

            	if(videoAdsType == 'image')
            	{
            		//full banner
            		if(adsImagePosition == 1 || adsImagePosition == '')
            		{
            			videoDataTimePlayAgain = fullBannerDataTimePlayAgain;
            		}
            		//top and bottom banner
            		else
            		{
            			videoDataTimePlayAgain = topBottomDataTimePlayAgain;
            		}
            	}
            	else if(videoAdsType == 'adsense')
            	{
            		videoDataTimePlayAgain = topBottomDataTimePlayAgain;
            	}

            	if(videoLink == '@data-link')
            		videoLink = $('input[name=main_video_url]').val();

            	if(videoSource == '@data-source')
            		videoSource = $('input[name=main_video_type]').val();


				$this.css({"width": checkWidth + "px" , "height": checkHeight + "px"});

				//setup branch
				if(enableBrand == 'yes')
				{
					if(brandLogo != '' && brandLogo != undefined)
						$this.find('.'+cactusVideoDetails).append('<div id="brand-'+index+'"><img src="' + brandLogo + '"/></div>');
					else
					{
						$this.find('.'+cactusVideoDetails).append('<div id="brand-'+index+'">' + brandText + '</div>');
						$this.find('#brand-'+index).css({opacity: brandOpacity, color: brandColor});
					}

					if(brandPosition == 'top-right') {
						$this.find('#brand-'+index).css({top: '0',right: '0'});
					}
					else if(brandPosition == 'top-left') {
						$this.find('#brand-'+index).css({top: '0',left: '0'});
					}
					else if(brandPosition == 'bottom-right') {
						$this.find('#brand-'+index).css({bottom: '0',right: '0'});
					}
					else if(brandPosition == 'bottom-left') {
						$this.find('#brand-'+index).css({bottom: '0',left: '0'});
					}
					else {
						$this.find('#brand-'+index).css({top: '0',right: '0'});
					}
				}

				/*Video Youtube Iframe*/

				if(videoSource == 'youtube')
				{
					function onPlayerReady(event) {

						function StartVideoNow(){event.target.playVideo();};

						event.target.setPlaybackQuality(youtubeQuality);

						$this.find('.'+cactusVideoAds).css("visibility","hidden");

						var videoDurationAds=0;
						var videoPlayCurrentTime=0;

						if(videoAutoPlay=="1")
						{
							if(videoAds!='' && videoAds!=null && videoAdsType!='')
							{
								$this.find('.'+cactusVideoAds).css("visibility","visible");
								var divVideoAdsId=cactusVideoAds+'-'+index;

								if(videoAdsType=='video')
								{

									if(videoAdsSource == 'youtube')
									{
										close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition);

										$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
											//ajax track click to first ads here
											ajax_track(adsID, 0, false, true);
										});

										function onPlayerReady_auto(event) {
											$this.find('.'+cactusVideoAds).find('.hide-pause').css({"opacity":"0", "cursor":"auto"});
											$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});
										};

										function onPlayerStateChange_auto(event) {
											$this.find('.'+cactusVideoAds).find('.hide-pause').css({"opacity":"0", "cursor":"auto"});
											$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});

											// when youtube ads finish
											if(event.data === 0) {

												videoPlayFullTime=cactus_player_Ads[index].getDuration();
												//ajax track close here when finish ads first time
												ajax_track(adsID, videoPlayFullTime, false, false);

												$this.find('.'+cactusVideoAds).css({"display":"none"});
												cactus_player_Ads[index].stopVideo();
												StartVideoNow();
											};

											var youtubeAdsInterval = null;

											function adsPlayCurrentTime_func() {
												videoPlayCurrentTime=cactus_player_Ads[index].getCurrentTime();
												if(parseInt(videoPlayCurrentTime) >= videoDataTimeHideAds) {

													$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);

													if(isClickCloseButtonFirstTime == true)
													{
														$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').off('.closeYoutubeAds').on('click.closeYoutubeAds', function(){

															//ajax track close here
															ajax_track(adsID, videoPlayCurrentTime, true, false);

															if(youtubeAdsInterval!=null) {clearInterval(youtubeAdsInterval);}
															$this.find('.'+cactusVideoAds).css({"display":"none"});
															cactus_player_Ads[index].stopVideo();
															StartVideoNow();
															isClickCloseButtonFirstTime = false;
														});
													}


												}
											};
											youtubeAdsInterval = setInterval(adsPlayCurrentTime_func,500);
										};

										cactus_player_Ads[index] = new YT.Player(divVideoAdsId, {
											width: checkWidth,
											height: checkHeight,
											videoId: videoAds,
											playerVars: {
												controls: 0,
												showinfo: 0,
												enablejsapi:1,
												autoplay:1,
												disablekb:1,
											},
											events: {
												'onReady': onPlayerReady_auto,
												'onStateChange': onPlayerStateChange_auto
											}
										});
									}
									else if(videoAdsSource == 'vimeo')
									{
										if(flag_vimeo[index] == true)
										{
											flag_vimeo[index] = true;
										}
										else
										{
											flag_vimeo[index] = false;
										}

										$this.find('.'+cactusVideoAds).html('<iframe id="player-vimeo-' + index + '" src="https://player.vimeo.com/video/' + videoAds + '?api=1&player_id=player-vimeo-' + index + '&autoplay=1" width="' + checkWidth + '" height="' + checkHeight + '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');

										close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition, videoAdsSource);

										$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
											//ajax track click to first ads here
											ajax_track(adsID, 0, false, true);
										});

										$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});

									    // var iframe = $('#player-vimeo-' + index)[0];
									    // cactus_player_Ads[] = $f(iframe);

									    cactus_vimeo_player[index] = $('#player-vimeo-' + index)[0];
										cactus_vimeo_player_Ads[index] =$f(cactus_vimeo_player[index]);
									    // var status = $('.vimeo_status');

								        function onPause(id) {
								            // status.text('paused');
								        }

								        function onFinish(id) {
								            // status.text('finished');
								            
								            ajax_track(playbackVideoAdsID, vimeoAdsDuration, false, false);
								            $this.find('.'+cactusVideoAds).css({"display":"none"});
								            StartVideoNow();
								        }


								        function onPlayProgress(data, id) {
								            // status.text(data.seconds + 's played');
								            vimeoAdsDuration = data.duration;

								            if(data.seconds > videoDataTimeHideAds && flag_vimeo[index] == false)
								            {
								            	$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);

								            	//console.log(data.seconds);
								            	currentTimeVideoCheck = data.seconds;

								            	
								            	$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').off('.closeVimeoAds').on('click.closeVimeoAds', function(){
							            			//ajax track close here
													ajax_track(playbackVideoAdsID, currentTimeVideoCheck, true, false);

							            			$this.find('.'+cactusVideoAds).css({"display":"none"});
							            			cactus_vimeo_player_Ads[index].api('pause');
							            			StartVideoNow();
					            					flag_vimeo[index] = true;
								            	});
												
								            }
								        }

								        // When the cactus_vimeo_player_Ads[index] is ready, add listeners for pause, finish, and playProgress
								        cactus_vimeo_player_Ads[index].addEvent('ready', function() {
								            // status.text('ready');
								            cactus_vimeo_player_Ads[index].addEvent('pause', onPause);
								            cactus_vimeo_player_Ads[index].addEvent('finish', onFinish);
								            var currentTimeVideoCheck = 0;
								            var vimeoAdsDuration = 0;
								            if(isVimeoPlayback == true){
								            	playbackVideoAdsID 	= playbackVideoAdsSource != '' && playbackVideoAdsSource == 'vimeo' ? playbackAdsID : adsID;
								            }
								            else
								            {
								            	playbackVideoAdsID = adsID;
								            }
								            
								            cactus_vimeo_player_Ads[index].addEvent('playProgress', onPlayProgress);
								        });
									}
									else if(videoAdsSource == 'self-hosted')
									{
										$this.find('.'+cactusVideoAds).html('<video id="player-html5-' + index + '" class="wp-video-shortcode" autoplay="true" preload="auto" controls="controls" style="width:100%"><source src="' + videoAds + '" type="video/mp4"></video><div>');

										cactus_html5_player_Ads[index] = $this.find('.'+cactusVideoAds).find('.wp-video-shortcode');

										close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition);

										$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
											//ajax track click to first ads here
											ajax_track(adsID, 0, false, true);
										});

										$this.find('.'+cactusVideoAds).find('.hide-pause').css({"opacity":"0", "cursor":"auto"});
										$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});

										// when youtube html 5 ads finish
										cactus_html5_player_Ads[index].get(0).onended = function(e) {
											// alert('end video');
											videoPlayFullTime=cactus_html5_player_Ads[index].get(0).duration;
											//ajax track close here when finish ads first time
											ajax_track(adsID, videoPlayFullTime, false, false);

											$this.find('.'+cactusVideoAds).css({"display":"none"});
											StartVideoNow();
										}

									    var html5AdsInterval = null;

										function adsPlayCurrentTime_func() {
												videoHtml5PlayCurrentTime=cactus_html5_player_Ads[index].get(0).currentTime;
												cactus_html5_player_Ads[index].get(0).addEventListener("timeupdate",function() {
													if(videoHtml5PlayCurrentTime >= videoDataTimeHideAds)
													{
														$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);

														if(isClickCloseButtonFirstTime == true)
														{
															$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').off('.closeYoutubeAds').on('click.closeYoutubeAds', function(){

																//ajax track close here
																ajax_track(adsID, videoPlayCurrentTime, true, false);

																if(html5AdsInterval!=null) {clearInterval(html5AdsInterval);}
																$this.find('.'+cactusVideoAds).css({"display":"none"});
																cactus_html5_player_Ads[index].get(0).pause();
																StartVideoNow();
																isClickCloseButtonFirstTime = false;
															});
														}
													}
												});
											};
											html5AdsInterval = setInterval(adsPlayCurrentTime_func,500)
									}
								}
								else if(videoAdsType=='image')
								{
									//ads images

									// Hidden ads images
									$this.find('.'+cactusVideoAds).css("visibility","hidden");

									//full size
									if(adsImagePosition == '1' || adsImagePosition == 'undefined' || adsImagePosition == '')
									{
										$this.find('.'+cactusVideoAds).css("display","none");
										// prepare ads images
										$this.find('.'+cactusVideoAds).html('<a href="' + videoDataLinkRedirect + '" target="_blank"><img src="' + videoAds + '"/></a>');
										$this.find('.'+cactusVideoAds + ' img').css({"width":"100%", "height":"100%"});

										close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition);

										$this.find('.'+cactusVideoAds).find('a img').off('.clickToAds').on('click.clickToAds', function(){
											//ajax track click to full ads here
											ajax_track(adsID, 0, false, true);
										});

										$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);


						            	$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').click(function(){
						            			//ajax track close full ads here
												ajax_track(adsID, 0, true, false);
						            			$this.find('.'+cactusVideoAds).css({"display":"none"});
						            			StartVideoNow();
						            	});
									}
									else
									{
										// top banner
										if(adsImagePosition == '2')
										{
											// prepare ads images
											$this.find('.'+cactusVideoAds).css({"pointer-events":"none", 'z-index':'10'});
											$('<img src="'+videoAds+'">').load(function() {
												$this.find('.'+cactusVideoAds).html('<div class="banner-img"><a href="' + videoDataLinkRedirect + '" target="_blank"><img class="main-img" src="' + videoAds + '"/></div></a>');
												var playerHeight 	= checkHeight;
												var playerWidth 	= checkWidth;
												var imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
												var imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

												var dscFromBottomPlayertoImg = playerHeight - imgHeight;
												var dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

												$this.find('.'+cactusVideoAds + ' .banner-img').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
												$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });

												$(window).resize(function() {
									    			setTimeout(function(){
									    				playerWidth 	= $('.cactus-video-content').width();
									    				playerHeight 	= (checkWidth / 16 * 9);
														imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
														imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();
														dscFromBottomPlayertoImg = playerHeight - imgHeight;
														dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;
														$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
										    		},400)
												});



												$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds').on('click.clickToAds', function(){
													//ajax track click to first top ads here
													ajax_track(adsID, 0, false, true);
												});


												$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').click(function() {
							            			if(playbackVideoAds != '' && playbackVideoAdsType == 'image' && playbackAdsImagePosition == '2')
							            			{
							            				$this.find('.'+cactusVideoAds).css({"visibility":"hidden"});
							            				$this.find('.'+cactusVideoAds + ' .banner-img img').attr("src", playbackVideoAds);
							            				$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds');
							            				$this.find('.'+cactusVideoAds + ' .banner-img img').removeClass("main-img");
							            				$this.find('.'+cactusVideoAds + ' .banner-img img').addClass("second-img");

							            				$this.find('.'+cactusVideoAds + ' .banner-img a').attr("href", playbackDataLinkRedirect);
							            				playerWidth 	= $('.cactus-video-content').width();
									    				playerHeight 	= (playerWidth / 16 * 9);
							            				imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
							            				imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

							            				dscFromBottomPlayertoImg = playerHeight - imgHeight;
							            				dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

							            				$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
							            			}
							            			else
							            			{
							            				$this.find('.'+cactusVideoAds).css({"display":"none"});
							            			}
							            			if(click_count[index] != '1'){
							            				ajax_track(adsID, 0, true, false);	
							            			}

							            			click_count[index] = '1';
							            		});
											});

											// $this.find('.'+cactusVideoAds + ' img').css({"width":"100%", "height":"auto"});


										}
										//bottom banner
										else if(adsImagePosition == '3')
										{
											// prepare ads images
											$this.find('.'+cactusVideoAds).css({"height":"auto", "top": "0", "pointer-events" : "none", 'z-index':'10'});
											$('<img src="'+videoAds+'">').load(function() {
												$this.find('.'+cactusVideoAds).html('<div class="banner-img"><a href="' + videoDataLinkRedirect + '" target="_blank"><img class="main-img" src="' + videoAds + '"/></div></a>');

		            							var playerHeight 	= checkHeight;
		            							var playerWidth 	= checkWidth;
		            							var imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
		            							var imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

		            							var dscFromBottomPlayertoImg = playerHeight - imgHeight;
		            							var dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

		            							$this.find('.'+cactusVideoAds + ' .banner-img').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
		            							$this.find('.'+cactusVideoAds + ' .banner-img').css({'padding-bottom': '40px', 'top':'auto'});
		            							$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top': '10px', 'right': dscFromRightPlayertoImg + 10 + 'px' });

		            							$(window).resize(function() {
		            				    			setTimeout(function(){
		            				    				playerWidth 	= $('.cactus-video-content').width();
		            				    				playerHeight 	= (checkWidth / 16 * 9);
		            									imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
		            									imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();
		            									dscFromBottomPlayertoImg = playerHeight - imgHeight;
		            									dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;
		            									if(playerWidth < 600)
		            									{
		            										$this.find('.'+cactusVideoAds + ' .banner-img').css({'top':'0'});
		            										$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top':'auto', 'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
		            									}
		            									else
		            									{
		            										$this.find('.'+cactusVideoAds + ' .banner-img').css({'top':'auto'});
		            										$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top': '10px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
		            									}
		            					    		},400)
		            							});

												$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds').on('click.clickToAds', function(){
													//ajax track click to first top ads here
													ajax_track(adsID, 0, false, true);
												});


		            							$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').click(function() {
		            		            			if(playbackVideoAds != '' && playbackVideoAdsType == 'image' && playbackAdsImagePosition == '3')
							            			{
							            				$this.find('.'+cactusVideoAds).css({"visibility":"hidden"});
							            				$this.find('.'+cactusVideoAds + ' .banner-img img').attr("src", playbackVideoAds);
							            				$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds');
							            				$this.find('.'+cactusVideoAds + ' .banner-img img').removeClass("main-img");
							            				$this.find('.'+cactusVideoAds + ' .banner-img img').addClass("second-img");
							            				$this.find('.'+cactusVideoAds + ' .banner-img a').attr("href", playbackDataLinkRedirect);
							            				playerWidth 	= $('.cactus-video-content').width();
									    				playerHeight 	= (playerWidth / 16 * 9);
							            				imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
							            				imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

							            				dscFromBottomPlayertoImg = playerHeight - imgHeight;
							            				dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

							            				$this.find('.'+cactusVideoAds + ' .banner-img').css({'padding-bottom': '40px', 'top':'auto'});
				            							$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top': '10px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
							            			}
							            			else
							            			{
							            				$this.find('.'+cactusVideoAds).css({"display":"none"});
							            			}
							            			if(click_count[index] != '1'){
							            				ajax_track(adsID, 0, true, false);	
							            			}
							            			click_count[index] = '1';
		            		            		});
											});
										}
									}

					            	//play youtube video
					            	StartVideoNow();

								}
								else if(videoAdsType=='adsense')
								{
									if(adsImagePosition == '1' || adsImagePosition == '') 
									{
										adsImagePosition = '2';
									}
									// Hidden ads images
									$this.find('.'+cactusVideoAds).css("display","none");

									// top banner
									if(adsImagePosition == '2')
									{
										$this.find('.'+cactusVideoAds).css({"height":"0", "pointer-events":"none", 'z-index':'10'});
										$this.find('.'+cactusVideoAds).html('<div class="adsense-block">' + videoAds + '</div>');
										$this.find('.'+cactusVideoAds + ' .adsense-block').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
										$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').css({'bottom': '10px', 'right': '10px'});
										$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').click(function() {
					            			$this.find('.'+cactusVideoAds).css({"display":"none"});
					            			click_count[index] = '1';
					            		});
									}
									//bottom banner
									else if(adsImagePosition == '3')
									{
										$this.find('.'+cactusVideoAds).css({"height":"auto", "top": "auto", "pointer-events" : "none", 'z-index':'10'});
					            		$this.find('.'+cactusVideoAds).html('<div class="adsense-block">' + videoAds + '</div>');


										$this.find('.'+cactusVideoAds + ' .adsense-block').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
										$this.find('.'+cactusVideoAds + ' .adsense-block').css({"padding-bottom" : "40px"});
										$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').css({'top': '10px', 'right': '10px'});
										$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').click(function() {
					            			$this.find('.'+cactusVideoAds).css({"display":"none"});
					            			click_count[index] = '1';
					            		});

									}


									//play youtube video
					            	StartVideoNow();
								}
							}
							else
							{
								//event.target.playVideo();
								StartVideoNow();
							}
						}
						else
						{
							if(videoAds!='' && videoAds!=null && videoAdsType!='')
							{
								$this.find('.'+cactusVideoAds).css("visibility","visible");
								var divVideoAdsId=cactusVideoAds+'-'+index;
								if(videoAdsType=='video')
								{
									if(videoAdsSource == 'youtube')
									{
										close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition, videoAdsSource);

										$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
											//ajax track click to first ads here
											ajax_track(adsID, 0, false, true);
										});

										$this.find('.'+cactusVideoAds).find(".hide-pause.button-start").click(function(){

											var $thisads=$(this);

											function onPlayerReady_nauto(event) {
												$thisads.css({"opacity":"0"});
												$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});
											};

											function onPlayerStateChange_nauto(event) {
												if(event.data === 0) {
													videoPlayFullTime=cactus_player_Ads[index].getDuration();
													//ajax track close here when finish ads first time
													ajax_track(adsID, videoPlayFullTime, false, false);

													$this.find('.'+cactusVideoAds).css({"display":"none"});
													cactus_player_Ads[index].stopVideo();
													StartVideoNow();
												};

												
												var youtubeAdsInterval = null;
												function adsPlayCurrentTime_func() {
													videoPlayCurrentTime=cactus_player_Ads[index].getCurrentTime();
													if(parseInt(videoPlayCurrentTime) >= videoDataTimeHideAds) {
														clearInterval(adsPlayCurrentTime_func);
														$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);

														if(isClickCloseButtonFirstTime == true)
														{
															$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').off('.closeYoutubeAds').on('click.closeYoutubeAds', function(){

																//ajax track close here
																ajax_track(adsID, videoPlayCurrentTime, true, false);

																if(youtubeAdsInterval!=null) {clearInterval(youtubeAdsInterval);}

																$this.find('.'+cactusVideoAds).css({"display":"none"});
																cactus_player_Ads[index].stopVideo();
																StartVideoNow();
																isClickCloseButtonFirstTime = false;
															});
														}

													};
												}
												youtubeAdsInterval = setInterval(adsPlayCurrentTime_func,500)
											};

											cactus_player_Ads[index] = new YT.Player(divVideoAdsId, {
												width: checkWidth,
												height: checkHeight,
												videoId: videoAds,
												playerVars: {
													controls: 0,
													showinfo: 0,
													enablejsapi:1,
													autoplay:1,
													disablekb:1,
												},
												events: {
													'onReady': onPlayerReady_nauto,
													'onStateChange': onPlayerStateChange_nauto
												}
											});

										});
									}
									else if(videoAdsSource == 'vimeo')
									{
										if(flag_vimeo[index] == true)
										{
											flag_vimeo[index] = true;
										}
										else
										{
											flag_vimeo[index] = false;
										}

										mask_button($this, cactusVideoAds, videoAdsSource, videoSource);

										$this.find('.'+cactusVideoAds).find(".hide-pause.button-start").click(function() {

											$this.find('.'+cactusVideoAds).html('<iframe id="player-vimeo-' + index + '" src="https://player.vimeo.com/video/' + videoAds + '?api=1&player_id=player-vimeo-' + index + '" width="' + checkWidth + '" height="' + checkHeight + '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');

											close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition, videoAdsSource);

											$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
												//ajax track click to first ads here
												ajax_track(adsID, 0, false, true);
											});

											$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});

										    cactus_vimeo_player[index] = $('#player-vimeo-' + index)[0];
											cactus_vimeo_player_Ads[index] =$f(cactus_vimeo_player[index]);
										    // var status = $('.vimeo_status');

									        function onPause(id) {
									            // status.text('paused');
									        }

									        function onFinish(id) {
									            // status.text('finished');
									            ajax_track(playbackVideoAdsID, vimeoAdsDuration, false, false);
									            $this.find('.'+cactusVideoAds).css({"display":"none"});
									            StartVideoNow();
									        }

									        function onPlayProgress(data, id) {
								            // status.text(data.seconds + 's played');
								            vimeoAdsDuration = data.duration;

								            if(data.seconds > videoDataTimeHideAds && flag_vimeo[index] == false)
								            {
								            	$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);

								            	//console.log(data.seconds);
								            	currentTimeVideoCheck = data.seconds;

								            	
								            	$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').off('.closeVimeoAds').on('click.closeVimeoAds', function(){
							            			//ajax track close here
													ajax_track(playbackVideoAdsID, currentTimeVideoCheck, true, false);

							            			$this.find('.'+cactusVideoAds).css({"display":"none"});
							            			cactus_vimeo_player_Ads[index].api('pause');
							            			StartVideoNow();
					            					flag_vimeo[index] = true;
								            	});
												
								            }
								        }

									        // When the cactus_vimeo_player_Ads[index] is ready, add listeners for pause, finish, and playProgress
									        cactus_vimeo_player_Ads[index].addEvent('ready', function() {
									            // status.text('ready');
									            cactus_vimeo_player_Ads[index].addEvent('pause', onPause);
									            cactus_vimeo_player_Ads[index].addEvent('finish', onFinish);
									            var currentTimeVideoCheck = 0;
									            var vimeoAdsDuration = 0;
									            if(isVimeoPlayback == true){
									            	playbackVideoAdsID 	= playbackVideoAdsSource != '' && playbackVideoAdsSource == 'vimeo' ? playbackAdsID : adsID;
									            }
									            else
									            {
									            	playbackVideoAdsID = adsID;
									            }
									            cactus_vimeo_player_Ads[index].addEvent('playProgress', onPlayProgress);
									            cactus_vimeo_player_Ads[index].api('play');
									        });
								        });
									}
									else if(videoAdsSource == 'self-hosted')
									{
										mask_button($this, cactusVideoAds, videoAdsSource, videoSource);
										$this.find('.'+cactusVideoAds).find(".hide-pause.button-start").click(function() {
											$this.find('.'+cactusVideoAds).html('<video id="player-html5-' + index + '" class="wp-video-shortcode" preload="auto" controls="controls" style="width:100%"><source src="' + videoAds + '" type="video/mp4"></video><div>');

											cactus_html5_player_Ads[index] = $this.find('.'+cactusVideoAds).find('.wp-video-shortcode');
											cactus_html5_player_Ads[index].get(0).play();

											close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition);

											$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
												//ajax track click to first ads here
												ajax_track(adsID, 0, false, true);
											});

											$this.find('.'+cactusVideoAds).find('.hide-pause').css({"opacity":"0", "cursor":"auto"});
											$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});

											// when youtube html 5 ads finish
											cactus_html5_player_Ads[index].get(0).onended = function(e) {
												// alert('end video');
												videoPlayFullTime=cactus_html5_player_Ads[index].get(0).duration;
												//ajax track close here when finish ads first time
												ajax_track(adsID, videoPlayFullTime, false, false);

												$this.find('.'+cactusVideoAds).css({"display":"none"});
												StartVideoNow();
											}

										    var html5AdsInterval = null;

											function adsPlayCurrentTime_func() {
													videoHtml5PlayCurrentTime=cactus_html5_player_Ads[index].get(0).currentTime;
													cactus_html5_player_Ads[index].get(0).addEventListener("timeupdate",function() {
														if(videoHtml5PlayCurrentTime >= videoDataTimeHideAds)
														{
															$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);

															if(isClickCloseButtonFirstTime == true)
															{
																$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').off('.closeYoutubeAds').on('click.closeYoutubeAds', function(){

																	//ajax track close here
																	ajax_track(adsID, videoPlayCurrentTime, true, false);

																	if(html5AdsInterval!=null) {clearInterval(html5AdsInterval);}
																	$this.find('.'+cactusVideoAds).css({"display":"none"});
																	cactus_html5_player_Ads[index].get(0).pause();
																	StartVideoNow();
																	isClickCloseButtonFirstTime = false;
																});
															}
														}
													});
												};
												html5AdsInterval = setInterval(adsPlayCurrentTime_func,500)
										});
									}
								}
								else if(videoAdsType=='image')
								{
									//ads images

									// Hidden ads images
									$this.find('.'+cactusVideoAds).css("visibility","hidden");

									//full size
									if(adsImagePosition == '1' || adsImagePosition == 'undefined' || adsImagePosition == '')
									{
										$this.find('.'+cactusVideoAds).css("display","none");
										// prepare ads images
										$this.find('.'+cactusVideoAds).html('<a href="' + videoDataLinkRedirect + '" target="_blank"><img src="' + videoAds + '"/></a>');
										$this.find('.'+cactusVideoAds + ' img').css({"width":"100%", "height":"100%"});

										close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition);

										$this.find('.'+cactusVideoAds).find('a img').off('.clickToAds').on('click.clickToAds', function(){
											//ajax track click to full ads here
											ajax_track(adsID, 0, false, true);
										});

										$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);


						            	$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').click(function(){
						            		//ajax track close full ads here
												ajax_track(adsID, 0, true, false);
						            			$this.find('.'+cactusVideoAds).css({"display":"none"});
						            			StartVideoNow();
						            	});
									}

									else
									{
										// top banner
										if(adsImagePosition == '2')
										{
											// prepare ads images
											$this.find('.'+cactusVideoAds).css({"pointer-events":"none", 'z-index':'10'});
											$('<img src="'+videoAds+'">').load(function() {
												$this.find('.'+cactusVideoAds).html('<div class="banner-img"><a href="' + videoDataLinkRedirect + '" target="_blank"><img class="main-img" src="' + videoAds + '"/></div></a>');
												var playerHeight 	= checkHeight;
												var playerWidth 	= checkWidth;
												var imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
												var imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

												var dscFromBottomPlayertoImg = playerHeight - imgHeight;
												var dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

												$this.find('.'+cactusVideoAds + ' .banner-img').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
												$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });

												$(window).resize(function() {
									    			setTimeout(function(){
									    				playerWidth 	= $('.cactus-video-content').width();
									    				playerHeight 	= (playerWidth / 16 * 9);
														imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
														imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();
														dscFromBottomPlayertoImg = playerHeight - imgHeight;
														dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;
														$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
										    		},400)
												});

												$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds').on('click.clickToAds', function(){
													//ajax track click to first top ads here
													ajax_track(adsID, 0, false, true);
												});


												$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').click(function() {
							            			if(playbackVideoAds != '' && playbackVideoAdsType == 'image' && playbackAdsImagePosition == '2')
							            			{
							            				$this.find('.'+cactusVideoAds).css({"visibility":"hidden"});
							            				$this.find('.'+cactusVideoAds + ' .banner-img img').attr("src", playbackVideoAds);
							            				$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds');
							            				$this.find('.'+cactusVideoAds + ' .banner-img img').removeClass("main-img");
							            				$this.find('.'+cactusVideoAds + ' .banner-img img').addClass("second-img");
							            				$this.find('.'+cactusVideoAds + ' .banner-img a').attr("href", playbackDataLinkRedirect);
							            				playerWidth 	= $('.cactus-video-content').width();
									    				playerHeight 	= (playerWidth / 16 * 9);
							            				imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
							            				imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

							            				dscFromBottomPlayertoImg = playerHeight - imgHeight;
							            				dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

							            				$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
							            			}
							            			else
							            			{
							            				$this.find('.'+cactusVideoAds).css({"display":"none"});
							            			}
							            			if(click_count[index] != '1'){
							            				ajax_track(adsID, 0, true, false);	
							            			}
							            			click_count[index] = '1';
							            		});
											});

											// $this.find('.'+cactusVideoAds + ' img').css({"width":"100%", "height":"auto"});


										}
										//bottom banner
										else if(adsImagePosition == '3')
										{
											// prepare ads images
											$this.find('.'+cactusVideoAds).css({"height":"auto", "top": "0", "pointer-events" : "none", 'z-index':'10'});
											$('<img src="'+videoAds+'">').load(function() {
												$this.find('.'+cactusVideoAds).html('<div class="banner-img"><a href="' + videoDataLinkRedirect + '" target="_blank"><img class="main-img" src="' + videoAds + '"/></div></a>');

		            							var playerHeight 	= checkHeight;
		            							var playerWidth 	= checkWidth;
		            							var imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
		            							var imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

		            							var dscFromBottomPlayertoImg = playerHeight - imgHeight;
		            							var dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

		            							$this.find('.'+cactusVideoAds + ' .banner-img').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
		            							$this.find('.'+cactusVideoAds + ' .banner-img').css({'padding-bottom': '40px', 'top':'auto'});
		            							$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top': '10px', 'right': dscFromRightPlayertoImg + 10 + 'px' });

		            							$(window).resize(function() {
		            				    			setTimeout(function(){
		            				    				playerWidth 	= $('.cactus-video-content').width();
		            				    				playerHeight 	= (checkWidth / 16 * 9);
		            									imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
		            									imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();
		            									dscFromBottomPlayertoImg = playerHeight - imgHeight;
		            									dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;
		            									if(playerWidth < 600)
		            									{
		            										$this.find('.'+cactusVideoAds + ' .banner-img').css({'top':'0'});
		            										$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top':'auto', 'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
		            									}
		            									else
		            									{
		            										$this.find('.'+cactusVideoAds + ' .banner-img').css({'top':'auto'});
		            										$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top': '10px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
		            									}
		            					    		},400)
		            							});

												$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds').on('click.clickToAds', function(){
													//ajax track click to first top ads here
													ajax_track(adsID, 0, false, true);
												});


		            							$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').click(function() {
		            		            			if(playbackVideoAds != '' && playbackVideoAdsType == 'image' && playbackAdsImagePosition == '3')
							            			{
							            				$this.find('.'+cactusVideoAds).css({"visibility":"hidden"});
							            				$this.find('.'+cactusVideoAds + ' .banner-img img').attr("src", playbackVideoAds);
							            				$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds');
							            				$this.find('.'+cactusVideoAds + ' .banner-img img').removeClass("main-img");
							            				$this.find('.'+cactusVideoAds + ' .banner-img img').addClass("second-img");
							            				$this.find('.'+cactusVideoAds + ' .banner-img a').attr("href", playbackDataLinkRedirect);
							            				playerWidth 	= $('.cactus-video-content').width();
									    				playerHeight 	= (playerWidth / 16 * 9);
							            				imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
							            				imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

							            				dscFromBottomPlayertoImg = playerHeight - imgHeight;
							            				dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

							            				$this.find('.'+cactusVideoAds + ' .banner-img').css({'padding-bottom': '40px', 'top':'auto'});
				            							$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top': '10px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
							            			}
							            			else
							            			{
							            				$this.find('.'+cactusVideoAds).css({"display":"none"});
							            			}
							            			if(click_count[index] != '1'){
							            				ajax_track(adsID, 0, true, false);	
							            			}
							            			click_count[index] = '1';
		            		            		});
											});
										}

									}
								}
								else if(videoAdsType=='adsense')
								{
									// Hidden ads images
									$this.find('.'+cactusVideoAds).css("display","none");

									if(adsImagePosition == '1' || adsImagePosition == '') 
									{
										adsImagePosition = '2';
									}
									// Hidden ads images
									$this.find('.'+cactusVideoAds).css("display","none");

									// top banner
									if(adsImagePosition == '2')
									{
										$this.find('.'+cactusVideoAds).css({"height":"0", "pointer-events":"none", 'z-index':'10'});
										$this.find('.'+cactusVideoAds).html('<div class="adsense-block">' + videoAds + '</div>');
										$this.find('.'+cactusVideoAds + ' .adsense-block').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
										$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').css({'bottom': '10px', 'right': '10px'});
										$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').click(function() {
					            			$this.find('.'+cactusVideoAds).css({"display":"none"});
					            			click_count[index] = '1';
					            		});
									}
									//bottom banner
									else if(adsImagePosition == '3')
									{
										$this.find('.'+cactusVideoAds).css({"height":"auto", "top": "auto", "pointer-events" : "none", 'z-index':'10'});
					            		$this.find('.'+cactusVideoAds).html('<div class="adsense-block">' + videoAds + '</div>');


										$this.find('.'+cactusVideoAds + ' .adsense-block').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
										$this.find('.'+cactusVideoAds + ' .adsense-block').css({"padding-bottom" : "40px"});
										$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').css({'top': '10px', 'right': '10px'});
										$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').click(function() {
					            			$this.find('.'+cactusVideoAds).css({"display":"none"});
					            			click_count[index] = '1';
					            		});

									}
								}
							}
							else
							{
								cactus_player[index].stopVideo();
							}
						}
					};



					var done = false;
					function onPlayerStateChange(event) {

				        // when video ends
						if(event.data === 0) {
							setTimeout(function(){
								if(autoLoadNextVideo != 3)
								{
									var link = $('.prev-post a').attr('href');
									if(autoLoadNextVideoOptions == 1)
									{
										link = jQuery('.next-post a').attr('href');
									}
								}
								else if(autoLoadNextVideo == 3)
								{
									var link = window.location.href;
								}
								var className = $('#tm-autonext span#autonext').attr('class');
								//alert(className);
								if(className!=''){
								  if(link !=undefined){
									  window.location.href= link;
								  }
								}
							},1000);
						}


						function videoPlayCurrentTime_func() {
							videoPlayCurrentTime=cactus_player[index].getCurrentTime();

							if(flag[index] == true)
							{
								flag[index] = true;
							}
							else
							{
								flag[index] = false;
							}
							if(parseInt(videoPlayCurrentTime) > 0 && (adsImagePosition == '2' || adsImagePosition == '3') && click_count[index] != 1)
							{
								$global_this.find('.'+cactusVideoAds).css({"visibility":"visible", "display":"block"});
								click_count[index] = 2;
							}
							if(parseInt(videoPlayCurrentTime) >= videoDataTimePlayAgain && flag[index] == false) {
								clearInterval(videoPlayCurrentTime_func);


								//display ads and play it
								$global_this.find('.'+cactusVideoAds).css({"visibility":"visible", "display":"block"});
								//pause main video
								cactus_player[index].pauseVideo();

								if(videoAdsType == 'video')
								{
									$this.find('.'+cactusVideoAds).find('.linkads').attr("href", playbackDataLinkRedirect);	
									if(videoAdsSource == 'youtube')
									{
										playbackVideoAdsURL = playbackVideoAdsSource != '' && playbackVideoAdsSource == 'youtube' ? playbackVideoAds : videoAds;
										playbackVideoAdsID 	= playbackVideoAdsSource != '' && playbackVideoAdsSource == 'youtube' ? playbackAdsID : adsID;

										cactus_player_Ads[index].destroy();

										$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){

											//ajax track click to second ads here
											ajax_track(playbackVideoAdsID, 0, false, true);

										});

										function onPlayerReady_auto1(event) {

											if(screenfull.isFullscreen == false)
											{
												cactus_player_Ads[index].seekTo(0, true);
												cactus_player_Ads[index].playVideo();
											}
											else
											{
												cactus_player_Ads[index].stopVideo();
												$this.find('.'+cactusVideoAds).css({"display":"none"});
												cactus_player[index].playVideo();
											}
										};

										function onPlayerStateChange_auto1(event) {

											// $this.find('.'+cactusVideoAds).find('#close-'+AdsVideoId+'').click(function(){
											if(isClickCloseButtonFirstTime == false)
											{
												$this.find('.'+cactusVideoAds).find('#close-'+AdsVideoId+'').off('.closeYoutubeAds').on('click.closeYoutubeAds', function(){
													
													ajax_track(playbackVideoAdsID, videoPlayCurrentTime, true, false);

													$this.find('.'+cactusVideoAds).css({"display":"none"});
													cactus_player_Ads[index].stopVideo();
													cactus_player[index].playVideo();
												});
											}

											if(event.data === 0) {

												videoPlayFullTime=cactus_player_Ads[index].getDuration();

												//ajax track close here when finish ads second time
												ajax_track(playbackVideoAdsID, videoPlayFullTime, false, false);

												$this.find('.'+cactusVideoAds).css({"display":"none"});
												cactus_player_Ads[index].stopVideo();
												cactus_player[index].playVideo();
											};

										};


										cactus_player_Ads[index] = new YT.Player(AdsVideoId, {
												width: checkWidth,
												height: checkHeight,
												videoId: playbackVideoAdsURL,
												playerVars: {
													controls: 0,
													showinfo: 0,
													enablejsapi:1,
													autoplay:1,
													disablekb:1,
												},
												events: {
													'onReady': onPlayerReady_auto1,
													'onStateChange': onPlayerStateChange_auto1
												}
											});
									}
									else if(videoAdsSource == 'vimeo')
									{
										playbackVideoAdsURL = playbackVideoAdsSource != '' && playbackVideoAdsSource == 'vimeo' ? playbackVideoAds : videoAds;
										playbackVideoAdsID 	= playbackVideoAdsSource != '' && playbackVideoAdsSource == 'vimeo' ? playbackAdsID : adsID;
										$global_this.find('.'+cactusVideoAds+' iframe#player-vimeo-' + index).attr('src', 'https://player.vimeo.com/video/' + playbackVideoAdsURL + '?api=1&player_id=player-vimeo-' + index + '&autoplay=1');

										$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
											//ajax track click to first ads here
											ajax_track(playbackVideoAdsID, 0, false, true);
										});

										if(screenfull.isFullscreen == false)
										{
											cactus_vimeo_player_Ads[index].api("seekTo", 0);
											cactus_vimeo_player_Ads[index].api("play");
										}
										else
										{
											$global_this.find('.'+cactusVideoAds+' iframe#player-vimeo-' + index).remove();
											$this.find('.'+cactusVideoAds).css({"display":"none"});
											cactus_player[index].playVideo();
										}

										isVimeoPlayback = true;


									}
									else if(videoAdsSource == 'self-hosted')
									{
										playbackVideoAdsURL = playbackVideoAdsSource != '' && playbackVideoAdsSource == 'self-hosted' ? playbackVideoAds : videoAds;
										playbackVideoAdsID 	= playbackVideoAdsSource != '' && playbackVideoAdsSource == 'self-hosted' ? playbackAdsID : adsID;

										$this.find('.'+cactusVideoAds+' video#player-html5-' + index).attr('src', playbackVideoAdsURL);		
										$this.find('.'+cactusVideoAds).find('.linkads').attr("href", playbackDataLinkRedirect);										

										cactus_html5_player_Ads[index] = $this.find('.'+cactusVideoAds).find('.wp-video-shortcode');

										if(screenfull.isFullscreen == false)
										{
											cactus_html5_player_Ads[index].get(0).play();
										}
										else
										{
											cactus_html5_player_Ads[index].get(0).pause();
											$this.find('.'+cactusVideoAds).css({"display":"none"});
											cactus_player[index].playVideo();
										}

										$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
											//ajax track click to first ads here
											ajax_track(playbackVideoAdsID, 0, false, true);
										});

										//cactus_html5_player_Ads[index].get(0).play();
									}
								}
								else if(videoAdsType == '')
								{
									$global_this.find('.'+cactusVideoAds).css({"visibility":"hidden", "display":"none"});
									cactus_player[index].playVideo();
								}
								else if(videoAdsType == 'adsense')
								{
									if(adsImagePosition == '1')
										$global_this.find('.'+cactusVideoAds).css({"visibility":"hidden", "display":"none"});
									cactus_player[index].playVideo();
								}
								else
								{
									//ads image
									if(adsImagePosition == '2' || adsImagePosition == '3')
									{
										cactus_player[index].playVideo();
										playbackVideoAdsID 	= playbackVideoAds != '' && playbackVideoAdsType == 'image' && (playbackAdsImagePosition == '2' || playbackAdsImagePosition == '3' )  ? playbackAdsID : adsID;

										$this.find('.'+cactusVideoAds).find('.banner-img a img.second-img').off('.clickToSecondAds').on('click.clickToSecondAds', function(){
											//ajax track click to second top ads here
											ajax_track(playbackVideoAdsID, 0, false, true);
										});

										$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').click(function() {
											ajax_track(playbackVideoAdsID, 0, true, false);
										});
									}
								}

								flag[index] = true;
							}
						};
						setInterval(videoPlayCurrentTime_func,500);
					};

					function stopVideo() {
						cactus_player[index].stopVideo();
					};

					cactus_player[index] = new YT.Player(divVideoId, {
						width: checkWidth,
						height: checkHeight,
						videoId: videoLink,
						playerVars: {
							//controls: 0,
							//showinfo: 0,
							enablejsapi:1,
							html5:1,
						},
						events: {
							'onReady': onPlayerReady,
							'onStateChange': onPlayerStateChange
						}
					});


				}
				else if(videoSource == 'vimeo')
				{
					if(flag_vimeo[index] == true)
					{
						flag_vimeo[index] = true;
					}
					else
					{
						flag_vimeo[index] = false;
					}

					$this.find('.'+cactusVideoDetails).find('.'+cactusVideoContent).html('<iframe id="player-vimeo-' + index + '" src="https://player.vimeo.com/video/' + videoLink + '?api=1&player_id=player-vimeo-' + index + '" width="' + checkWidth + '" height="' + checkHeight + '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');

					cactus_main_vimeo[index] = $('#player-vimeo-' + index)[0];
					cactus_main_vimeo_player[index] =$f(cactus_main_vimeo[index]);
					// var status = $('.vimeo_status');

			        function onPause(id) {
			            // status.text('paused');
			        }

			        function onFinish(id) {
			            // status.text('finished');
			            $this.find('.'+cactusVideoAds).css({"display":"none"});
			            setTimeout(function(){
							if(autoLoadNextVideo != 3)
							{
								var link = $('.prev-post a').attr('href');
								if(autoLoadNextVideoOptions == 1)
								{
									link = jQuery('.next-post a').attr('href');
								}
							}
							else if(autoLoadNextVideo == 3)
							{
								var link = window.location.href;
							}
							var className = $('#tm-autonext span#autonext').attr('class');
							//alert(className);
							if(className!=''){
							  if(link !=undefined){
								  window.location.href= link;
							  }
							}
						},1000);
			        }

			        function onPlayProgress(data, id) {
			            // status.text(data.seconds + 's played');

			            if(data.seconds > 0 && (adsImagePosition == '2' || adsImagePosition == '3') && click_count[index] != 1)
						{
							$global_this.find('.'+cactusVideoAds).css({"visibility":"visible", "display":"block"});
						}

			            if(data.seconds > videoDataTimePlayAgain && flag_vimeo[index] == false)
			            {
							$global_this.find('.'+cactusVideoAds).css({"visibility":"visible", "display":"block"});

							//pause main video
							cactus_main_vimeo_player[index].api('pause');

							if(videoAdsType == 'video')
							{
								if(videoAdsSource == 'youtube')
								{
									$this.find('.'+cactusVideoAds).find('.linkads').attr("href", playbackDataLinkRedirect);	
									playbackVideoAdsURL = playbackVideoAdsSource != '' && playbackVideoAdsSource == 'youtube' ? playbackVideoAds : videoAds;
									playbackVideoAdsID 	= playbackVideoAdsSource != '' && playbackVideoAdsSource == 'youtube' ? playbackAdsID : adsID;

									cactus_player_Ads[index].destroy();

									$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){

										//ajax track click to second ads here
										ajax_track(playbackVideoAdsID, 0, false, true);

									});

									function onPlayerReady_auto1(event) {
										if(screenfull.isFullscreen == false)
										{
											cactus_player_Ads[index].seekTo(0, true);
											cactus_player_Ads[index].playVideo();
										}
										else
										{
											cactus_player_Ads[index].stopVideo();
											$this.find('.'+cactusVideoAds).css({"display":"none"});
											cactus_main_vimeo_player[index].api("play");
										}
									};

									function onPlayerStateChange_auto1(event) {

										if(isClickCloseButtonFirstTime == false)
										{
											$this.find('.'+cactusVideoAds).find('#close-'+AdsVideoId+'').off('.closeYoutubeAds').on('click.closeYoutubeAds', function(){
												
												ajax_track(playbackVideoAdsID, videoPlayCurrentTime, true, false);

												$this.find('.'+cactusVideoAds).css({"display":"none"});
												cactus_player_Ads[index].stopVideo();
												cactus_main_vimeo_player[index].api("play");
											});
										}

										if(event.data === 0) {

											videoPlayFullTime=cactus_player_Ads[index].getDuration();

											//ajax track close here when finish ads second time
											ajax_track(playbackVideoAdsID, videoPlayFullTime, false, false);

											$this.find('.'+cactusVideoAds).css({"display":"none"});
											cactus_player_Ads[index].stopVideo();
											cactus_main_vimeo_player[index].api("play");
										};

									};

									cactus_player_Ads[index] = new YT.Player(AdsVideoId, {
										width: checkWidth,
										height: checkHeight,
										videoId: playbackVideoAdsURL,
										playerVars: {
											controls: 0,
											showinfo: 0,
											enablejsapi:1,
											autoplay:1,
											disablekb:1,
										},
										events: {
											'onReady': onPlayerReady_auto1,
											'onStateChange': onPlayerStateChange_auto1
										}
									});
								}
								else if(videoAdsSource == 'vimeo')
								{
									$this.find('.'+cactusVideoAds).find('.linkads').attr("href", playbackDataLinkRedirect);
									playbackVideoAdsURL = playbackVideoAdsSource != '' && playbackVideoAdsSource == 'vimeo' ? playbackVideoAds : videoAds;
									playbackVideoAdsID 	= playbackVideoAdsSource != '' && playbackVideoAdsSource == 'vimeo' ? playbackAdsID : adsID;
									$global_this.find('.'+cactusVideoAds+' iframe#ads-vimeo-player' + index).attr('src', 'https://player.vimeo.com/video/' + playbackVideoAdsURL + '?api=1&player_id=player-vimeo-' + index + '&autoplay=1');

									$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
										//ajax track click to first ads here
										ajax_track(playbackVideoAdsID, 0, false, true);
									});

									if(screenfull.isFullscreen == false)
									{
										cactus_ads_vimeo_player[index].api("seekTo", 0);
										cactus_ads_vimeo_player[index].api("play");
									}
									else
									{
										$global_this.find('.'+cactusVideoAds+' iframe#ads-vimeo-player' + index).remove();
										$this.find('.'+cactusVideoAds).css({"display":"none"});
										cactus_main_vimeo_player[index].api("play");
									}

									isVimeoPlayback = true;
								}
								else if(videoAdsSource == 'self-hosted')
								{
									playbackVideoAdsURL = playbackVideoAdsSource != '' && playbackVideoAdsSource == 'self-hosted' ? playbackVideoAds : videoAds;
									playbackVideoAdsID 	= playbackVideoAdsSource != '' && playbackVideoAdsSource == 'self-hosted' ? playbackAdsID : adsID;

									$this.find('.'+cactusVideoAds+' video#player-html5-' + index).attr('src', playbackVideoAdsURL);
									$this.find('.'+cactusVideoAds).find('.linkads').attr("href", playbackDataLinkRedirect);							

									cactus_html5_player_Ads[index] = $this.find('.'+cactusVideoAds).find('.wp-video-shortcode');

									if(screenfull.isFullscreen == false)
									{
										cactus_html5_player_Ads[index].get(0).play();
									}
									else
									{
										cactus_html5_player_Ads[index].get(0).pause();
										$this.find('.'+cactusVideoAds).css({"display":"none"});
										cactus_main_vimeo_player[index].api("play");
									}

									$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
										//ajax track click to first ads here
										ajax_track(playbackVideoAdsID, 0, false, true);
									});
								}
							}
							else if(videoAdsType == '')
							{
								$global_this.find('.'+cactusVideoAds).css({"visibility":"hidden", "display":"none"});
								cactus_main_vimeo_player[index].api('play');
							}
							else if(videoAdsType == 'adsense')
							{
								if(adsImagePosition == '1')
									$global_this.find('.'+cactusVideoAds).css({"visibility":"hidden", "display":"none"});
								cactus_main_vimeo_player[index].api('play');
							}
							else
							{
								if(adsImagePosition == '2' || adsImagePosition == '3')
								{
									cactus_main_vimeo_player[index].api('play');
									playbackVideoAdsID 	= playbackVideoAds != '' && playbackVideoAdsType == 'image' && (playbackAdsImagePosition == '2' || playbackAdsImagePosition == '3' )  ? playbackAdsID : adsID;

									$this.find('.'+cactusVideoAds).find('.banner-img a img.second-img').off('.clickToSecondAds').on('click.clickToSecondAds', function(){
										//ajax track click to second top ads here
										ajax_track(playbackVideoAdsID, 0, false, true);
									});

									$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').click(function() {
										ajax_track(playbackVideoAdsID, 0, true, false);
									});
								}
							}

							flag_vimeo[index] = true;
			            }
			        }

			        // When the cactus_vimeo_player_Ads[index] is ready, add listeners for pause, finish, and playProgress
			        cactus_main_vimeo_player[index].addEvent('ready', function() {
			            cactus_main_vimeo_player[index].addEvent('pause', onPause);
			            cactus_main_vimeo_player[index].addEvent('finish', onFinish);
			            cactus_main_vimeo_player[index].addEvent('playProgress', onPlayProgress);
			            if(videoAdsType == '')
			            {
							$global_this.find('.'+cactusVideoAds).css({"visibility":"hidden", "display":"none"});
							cactus_main_vimeo_player[index].api('play');
						}
			        });


					if(videoAutoPlay == "1")
					{
						if(videoAds!='' && videoAds!=null && videoAdsType!='')
						{
							$this.find('.'+cactusVideoAds).css("visibility","visible");
								var divVideoAdsId=cactusVideoAds+'-'+index;
								if(videoAdsType=='video')
								{
									if(videoAdsSource == 'youtube')
									{
										close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition);

										$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
											//ajax track click to first ads here
											ajax_track(adsID, 0, false, true);
										});

										function onPlayerReady_auto(event) {
											$this.find('.'+cactusVideoAds).find('.hide-pause').css({"opacity":"0", "cursor":"auto"});
											$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});
										};

										function onPlayerStateChange_auto(event) {
											$this.find('.'+cactusVideoAds).find('.hide-pause').css({"opacity":"0", "cursor":"auto"});
											$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});

											if(event.data === 0) {
												videoPlayFullTime=cactus_player_Ads[index].getDuration();
												//ajax track close here when finish ads first time
												ajax_track(adsID, videoPlayFullTime, false, false);

												$this.find('.'+cactusVideoAds).css({"display":"none"});
												cactus_player_Ads[index].stopVideo();
												cactus_main_vimeo_player[index].api("play");
											};

											var youtubeAdsInterval = null;

											function adsPlayCurrentTime_func() {
												videoPlayCurrentTime=cactus_player_Ads[index].getCurrentTime();
												if(parseInt(videoPlayCurrentTime) >= videoDataTimeHideAds) {

													$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);

													if(isClickCloseButtonFirstTime == true)
													{
														$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').off('.closeYoutubeAds').on('click.closeYoutubeAds', function(){
																//ajax track close here
																ajax_track(adsID, videoPlayCurrentTime, true, false);

																if(youtubeAdsInterval!=null) {clearInterval(youtubeAdsInterval);}

																$this.find('.'+cactusVideoAds).css({"display":"none"});
																cactus_player_Ads[index].stopVideo();
																cactus_main_vimeo_player[index].api("play");
																isClickCloseButtonFirstTime = false;
														});
													}

												}
											};
											youtubeAdsInterval = setInterval(adsPlayCurrentTime_func,500);
										};

										cactus_player_Ads[index] = new YT.Player(divVideoAdsId, {
											width: checkWidth,
											height: checkHeight,
											videoId: videoAds,
											playerVars: {
												controls: 0,
												showinfo: 0,
												enablejsapi:1,
												autoplay:1,
												disablekb:1,
											},
											events: {
												'onReady': onPlayerReady_auto,
												'onStateChange': onPlayerStateChange_auto
											}
										});
									}
									else if(videoAdsSource == 'vimeo')
									{
										if(flag_ads_vimeo[index] == true)
										{
											flag_ads_vimeo[index] = true;
										}
										else
										{
											flag_ads_vimeo[index] = false;
										}



										$this.find('.'+cactusVideoAds).html('<iframe id="ads-vimeo-player'+index+'" src="https://player.vimeo.com/video/' + videoAds + '?api=1&player_id=ads-vimeo-player'+index+'&autoplay=1" width="' + checkWidth + '" height="' + checkHeight + '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
										close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition, videoAdsSource);

										$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
											//ajax track click to first ads here
											ajax_track(adsID, 0, false, true);
										});

										$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});

										cactus_ads_vimeo_obj[index] = $('#ads-vimeo-player' + index)[0];
									    cactus_ads_vimeo_player[index]  = $f(cactus_ads_vimeo_obj[index]);
									    // var status = $('.status');

									    function onPauseAds(id) {
									        // status.text('paused');
									    }

									    function onFinishAds(id) {
									        // status.text('finished');
									        ajax_track(playbackVideoAdsID, vimeoAdsDuration, false, false);
									        $this.find('.'+cactusVideoAds).css({"display":"none"});
							            	cactus_main_vimeo_player[index].api('play');
									    }

									    function onPlayProgressAds(data, id) {

									    	vimeoAdsDuration = data.duration;

									        if(data.seconds > videoDataTimeHideAds && flag_ads_vimeo[index] == false)
								            {

								            	$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);

								            	currentTimeVideoCheck = data.seconds;

								            	$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').off('.closeVimeoAds').on('click.closeVimeoAds', function(){

		            			            			//ajax track close here
		            									ajax_track(playbackVideoAdsID, currentTimeVideoCheck, true, false);

								            			$this.find('.'+cactusVideoAds).css({"display":"none"});
								            			cactus_ads_vimeo_player[index].api('pause');
								            			cactus_main_vimeo_player[index].api('play');
				            							flag_ads_vimeo[index] = true;
								            	});
								            }
									    }

									    // When the cactus_ads_vimeo_player[index]  is ready, add listeners for pause, finish, and playProgress
									    cactus_ads_vimeo_player[index].addEvent('ready', function() {
									        // status.text('ready');
									        cactus_ads_vimeo_player[index].addEvent('pause', onPauseAds);
									        cactus_ads_vimeo_player[index].addEvent('finish', onFinishAds);

									        var currentTimeVideoCheck = 0;
								            var vimeoAdsDuration = 0;
								            if(isVimeoPlayback == true){
								            	playbackVideoAdsID 	= playbackVideoAdsSource != '' && playbackVideoAdsSource == 'vimeo' ? playbackAdsID : adsID;
								            }
								            else
								            {
								            	playbackVideoAdsID = adsID;
								            }

									        cactus_ads_vimeo_player[index].addEvent('playProgress', onPlayProgressAds);
									    });
									}
									else if(videoAdsSource == 'self-hosted')
									{
										$this.find('.'+cactusVideoAds).html('<video id="player-html5-' + index + '" class="wp-video-shortcode" autoplay="true" preload="auto" controls="controls" style="width:100%"><source src="' + videoAds + '" type="video/mp4"></video><div>');

										cactus_html5_player_Ads[index] = $this.find('.'+cactusVideoAds).find('.wp-video-shortcode');

										close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition);

										$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
											//ajax track click to first ads here
											ajax_track(adsID, 0, false, true);
										});

										$this.find('.'+cactusVideoAds).find('.hide-pause').css({"opacity":"0", "cursor":"auto"});
										$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});

										// when youtube html 5 ads finish
										cactus_html5_player_Ads[index].get(0).onended = function(e) {
											// alert('end video');
											videoPlayFullTime=cactus_html5_player_Ads[index].get(0).duration;
											//ajax track close here when finish ads first time
											ajax_track(adsID, videoPlayFullTime, false, false);

											$this.find('.'+cactusVideoAds).css({"display":"none"});
											cactus_main_vimeo_player[index].api("play");
										}

									    var html5AdsInterval = null;

										function adsPlayCurrentTime_func() {
												videoHtml5PlayCurrentTime=cactus_html5_player_Ads[index].get(0).currentTime;
												cactus_html5_player_Ads[index].get(0).addEventListener("timeupdate",function() {
													if(videoHtml5PlayCurrentTime >= videoDataTimeHideAds)
													{
														$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);

														if(isClickCloseButtonFirstTime == true)
														{
															$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').off('.closeYoutubeAds').on('click.closeYoutubeAds', function(){

																//ajax track close here
																ajax_track(adsID, videoHtml5PlayCurrentTime, true, false);

																if(html5AdsInterval!=null) {clearInterval(html5AdsInterval);}
																$this.find('.'+cactusVideoAds).css({"display":"none"});
																cactus_html5_player_Ads[index].get(0).pause();
																cactus_main_vimeo_player[index].api("play");
																isClickCloseButtonFirstTime = false;
															});
														}
													}
												});
											};
										html5AdsInterval = setInterval(adsPlayCurrentTime_func,500)
									}
								}
								else
								{
									if(videoAdsType=='image')
									//ads images
									{
										// Hidden ads images
										$this.find('.'+cactusVideoAds).css("visibility","hidden");

										//full size
										if(adsImagePosition == '1' || adsImagePosition == 'undefined' || adsImagePosition == '')
										{
											$this.find('.'+cactusVideoAds).css("display","none");
											// prepare ads images
											$this.find('.'+cactusVideoAds).html('<a href="' + videoDataLinkRedirect + '" target="_blank"><img src="' + videoAds + '"/></a>');
											$this.find('.'+cactusVideoAds + ' img').css({"width":"100%", "height":"100%"});

											close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition);

											$this.find('.'+cactusVideoAds).find('a img').off('.clickToAds').on('click.clickToAds', function(){
												//ajax track click to full ads here
												ajax_track(adsID, 0, false, true);
											});

											$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);


							            	$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').click(function(){
							            			ajax_track(adsID, 0, true, false);
							            			$this.find('.'+cactusVideoAds).css({"display":"none"});
							            			cactus_main_vimeo_player[index].api("play");
							            	});
										}
										else
										{
											// top banner
											if(adsImagePosition == '2')
											{
												// prepare ads images
												$this.find('.'+cactusVideoAds).css({"pointer-events":"none", 'z-index':'10'});
												$('<img src="'+videoAds+'">').load(function() {
													$this.find('.'+cactusVideoAds).html('<div class="banner-img"><a href="' + videoDataLinkRedirect + '" target="_blank"><img class="main-img" src="' + videoAds + '"/></div></a>');
													var playerHeight 	= checkHeight;
													var playerWidth 	= checkWidth;
													var imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
													var imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

													var dscFromBottomPlayertoImg = playerHeight - imgHeight;
													var dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

													$this.find('.'+cactusVideoAds + ' .banner-img').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
													$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });

													$(window).resize(function() {
										    			setTimeout(function(){
										    				playerWidth 	= $('.cactus-video-content').width();
										    				playerHeight 	= (checkWidth / 16 * 9);
															imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
															imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();
															dscFromBottomPlayertoImg = playerHeight - imgHeight;
															dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;
															$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
											    		},400)
													});

													$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds').on('click.clickToAds', function(){
														//ajax track click to first top ads here
														ajax_track(adsID, 0, false, true);
													});

													$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').click(function() {
								            			if(playbackVideoAds != '' && playbackVideoAdsType == 'image' && playbackAdsImagePosition == '2')
								            			{
								            				$this.find('.'+cactusVideoAds).css({"visibility":"hidden"});
								            				$this.find('.'+cactusVideoAds + ' .banner-img img').attr("src", playbackVideoAds);
								            				$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds');
								            				$this.find('.'+cactusVideoAds + ' .banner-img img').removeClass("main-img");
								            				$this.find('.'+cactusVideoAds + ' .banner-img img').addClass("second-img");
								            				$this.find('.'+cactusVideoAds + ' .banner-img a').attr("href", playbackDataLinkRedirect);
								            				playerWidth 	= $('.cactus-video-content').width();
										    				playerHeight 	= (playerWidth / 16 * 9);
								            				imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
								            				imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

								            				dscFromBottomPlayertoImg = playerHeight - imgHeight;
								            				dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

								            				$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
								            			}
								            			else
								            			{
								            				$this.find('.'+cactusVideoAds).css({"display":"none"});
								            			}
								            			if(click_count[index] != '1'){
								            				ajax_track(adsID, 0, true, false);	
								            			}
								            			click_count[index] = '1';
								            		});
												});

												// $this.find('.'+cactusVideoAds + ' img').css({"width":"100%", "height":"auto"});


											}
											//bottom banner
											else if(adsImagePosition == '3')
											{
												// prepare ads images
												$this.find('.'+cactusVideoAds).css({"height":"auto", "top": "0", "pointer-events" : "none", 'z-index':'10'});
												$('<img src="'+videoAds+'">').load(function() {
													$this.find('.'+cactusVideoAds).html('<div class="banner-img"><a href="' + videoDataLinkRedirect + '" target="_blank"><img class="main-img" src="' + videoAds + '"/></div></a>');

			            							var playerHeight 	= checkHeight;
			            							var playerWidth 	= checkWidth;
			            							var imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
			            							var imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

			            							var dscFromBottomPlayertoImg = playerHeight - imgHeight;
			            							var dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

			            							$this.find('.'+cactusVideoAds + ' .banner-img').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
			            							$this.find('.'+cactusVideoAds + ' .banner-img').css({'padding-bottom': '40px', 'top':'auto'});
			            							$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top': '10px', 'right': dscFromRightPlayertoImg + 10 + 'px' });

			            							$(window).resize(function() {
			            				    			setTimeout(function(){
			            				    				playerWidth 	= $('.cactus-video-content').width();
			            				    				playerHeight 	= (checkWidth / 16 * 9);
			            									imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
			            									imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();
			            									dscFromBottomPlayertoImg = playerHeight - imgHeight;
			            									dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;
			            									if(playerWidth < 600)
			            									{
			            										$this.find('.'+cactusVideoAds + ' .banner-img').css({'top':'0'});
			            										$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top':'auto', 'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
			            									}
			            									else
			            									{
			            										$this.find('.'+cactusVideoAds + ' .banner-img').css({'top':'auto'});
			            										$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top': '10px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
			            									}
			            					    		},400)
			            							});

													$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds').on('click.clickToAds', function(){
														//ajax track click to first top ads here
														ajax_track(adsID, 0, false, true);
													});


			            							$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').click(function() {
			            		            			if(playbackVideoAds != '' && playbackVideoAdsType == 'image' && playbackAdsImagePosition == '3')
								            			{
								            				$this.find('.'+cactusVideoAds).css({"visibility":"hidden"});
								            				$this.find('.'+cactusVideoAds + ' .banner-img img').attr("src", playbackVideoAds);
								            				$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds');
								            				$this.find('.'+cactusVideoAds + ' .banner-img img').removeClass("main-img");
								            				$this.find('.'+cactusVideoAds + ' .banner-img img').addClass("second-img");
								            				$this.find('.'+cactusVideoAds + ' .banner-img a').attr("href", playbackDataLinkRedirect);
								            				playerWidth 	= $('.cactus-video-content').width();
										    				playerHeight 	= (playerWidth / 16 * 9);
								            				imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
								            				imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

								            				dscFromBottomPlayertoImg = playerHeight - imgHeight;
								            				dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

								            				$this.find('.'+cactusVideoAds + ' .banner-img').css({'padding-bottom': '40px', 'top':'auto'});
					            							$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top': '10px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
								            			}
								            			else
								            			{
								            				$this.find('.'+cactusVideoAds).css({"display":"none"});
								            			}
								            			if(click_count[index] != '1'){
								            				ajax_track(adsID, 0, true, false);	
								            			}
								            			click_count[index] = '1';
			            		            		});
												});
											}
										}
									}
									else if(videoAdsType=='adsense')
									{
										if(adsImagePosition == '1' || adsImagePosition == '') 
										{
											adsImagePosition = '2';
										}
										// Hidden ads images
										$this.find('.'+cactusVideoAds).css("display","none");

										// top banner
										if(adsImagePosition == '2')
										{
											$this.find('.'+cactusVideoAds).css({"height":"0", "pointer-events":"none", 'z-index':'10'});
											$this.find('.'+cactusVideoAds).html('<div class="adsense-block">' + videoAds + '</div>');
											$this.find('.'+cactusVideoAds + ' .adsense-block').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
											$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').css({'bottom': '10px', 'right': '10px'});
											$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').click(function() {
						            			$this.find('.'+cactusVideoAds).css({"display":"none"});
						            			click_count[index] = '1';
						            		});
										}
										//bottom banner
										else if(adsImagePosition == '3')
										{
											$this.find('.'+cactusVideoAds).css({"height":"auto", "top": "auto", "pointer-events" : "none", 'z-index':'10'});
						            		$this.find('.'+cactusVideoAds).html('<div class="adsense-block">' + videoAds + '</div>');


											$this.find('.'+cactusVideoAds + ' .adsense-block').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
											$this.find('.'+cactusVideoAds + ' .adsense-block').css({"padding-bottom" : "40px"});
											$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').css({'top': '10px', 'right': '10px'});
											$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').click(function() {
						            			$this.find('.'+cactusVideoAds).css({"display":"none"});
						            			click_count[index] = '1';
						            		});

										}
									}


					            	//play vimeo video
					            	cactus_main_vimeo_player[index].addEvent('ready', function() {
					            	    cactus_main_vimeo_player[index].api('play');
					            		cactus_main_vimeo_player[index].addEvent('pause', onPause);
							            cactus_main_vimeo_player[index].addEvent('finish', onFinish);
							            cactus_main_vimeo_player[index].addEvent('playProgress', onPlayProgress);
					            	});
								}
						}
						else
						{
							//play video
							cactus_main_vimeo_player[index].api("play");
						}
					}
					else
					{
						if(videoAds!='' && videoAds!=null && videoAdsType!='')
						{
							$this.find('.'+cactusVideoAds).css("visibility","hidden");
								var divVideoAdsId=cactusVideoAds+'-'+index;
								if(videoAdsType=='video')
								{
									if(videoAdsSource == 'youtube')
									{
										mask_button($this, cactusVideoAds, videoAdsSource, videoSource);
										close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition);

										$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
											//ajax track click to first ads here
											ajax_track(adsID, 0, false, true);
										});

										$this.find('.'+cactusVideoAds).find(".hide-pause.button-start").click(function() {

											$this.find('.'+cactusVideoAds).css("visibility","visible");

											function onPlayerReady_nauto(event) {
												$this.find('.'+cactusVideoAds).find('.hide-pause').css({"opacity":"0", "cursor":"auto"});
												$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});
											};

											function onPlayerStateChange_nauto(event) {
												$this.find('.'+cactusVideoAds).find('.hide-pause').css({"opacity":"0", "cursor":"auto"});
												$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});

												if(event.data === 0) {
													videoPlayFullTime=cactus_player_Ads[index].getDuration();
													//ajax track close here when finish ads first time
													ajax_track(adsID, videoPlayFullTime, false, false);

													$this.find('.'+cactusVideoAds).css({"display":"none"});
													cactus_player_Ads[index].stopVideo();
													cactus_main_vimeo_player[index].api("play");
												};

												var youtubeAdsInterval = null;
												function adsPlayCurrentTime_func() {
													videoPlayCurrentTime=cactus_player_Ads[index].getCurrentTime();
													if(parseInt(videoPlayCurrentTime) >= videoDataTimeHideAds) {
														clearInterval(adsPlayCurrentTime_func);
														$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);

														if(isClickCloseButtonFirstTime == true)
														{
															$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').off('.closeYoutubeAds').on('click.closeYoutubeAds', function(){

																	//ajax track close here
																	ajax_track(adsID, videoPlayCurrentTime, true, false);

																	if(youtubeAdsInterval!=null) {clearInterval(youtubeAdsInterval);}

																	$this.find('.'+cactusVideoAds).css({"display":"none"});
																	cactus_player_Ads[index].stopVideo();
																	cactus_main_vimeo_player[index].api("play");
																	isClickCloseButtonFirstTime = false;
															});
														}

													}
												};
												youtubeAdsInterval = setInterval(adsPlayCurrentTime_func,500);
											};

											cactus_player_Ads[index] = new YT.Player(divVideoAdsId, {
												width: checkWidth,
												height: checkHeight,
												videoId: videoAds,
												playerVars: {
													controls: 0,
													showinfo: 0,
													enablejsapi:1,
													autoplay:1,
													disablekb:1,
												},
												events: {
													'onReady': onPlayerReady_nauto,
													'onStateChange': onPlayerStateChange_nauto
												}
											});
										});
									}
									else if(videoAdsSource == 'vimeo')
									{
										if(flag_ads_vimeo[index] == true)
										{
											flag_ads_vimeo[index] = true;
										}
										else
										{
											flag_ads_vimeo[index] = false;
										}

										mask_button($this, cactusVideoAds, videoAdsSource, videoSource);



										$this.find('.'+cactusVideoAds).find(".hide-pause.button-start").click(function() {

											$this.find('.'+cactusVideoAds).html('<iframe id="ads-vimeo-player'+index+'" src="https://player.vimeo.com/video/' + videoAds + '?api=1&player_id=ads-vimeo-player'+index+'&autoplay=1" width="' + checkWidth + '" height="' + checkHeight + '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');

											close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition, videoAdsSource);

											$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
												//ajax track click to first ads here
												ajax_track(adsID, 0, false, true);
											});

											$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});

											$this.find('.'+cactusVideoAds).css("visibility","visible");

											cactus_ads_vimeo_obj[index] = $('#ads-vimeo-player' + index)[0];
										    cactus_ads_vimeo_player[index]  = $f(cactus_ads_vimeo_obj[index]);
										    // var status = $('.status');

										    function onPauseAds(id) {
										        // status.text('paused');
										    }

										    function onFinishAds(id) {
										        // status.text('finished');
									            ajax_track(playbackVideoAdsID, vimeoAdsDuration, false, false);
										        $this.find('.'+cactusVideoAds).css({"display":"none"});
								            	cactus_main_vimeo_player[index].api('play');
										    }

										    function onPlayProgressAds(data, id) {
										        // status.text(data.seconds + 's played');

										        vimeoAdsDuration = data.duration;
										        if(data.seconds > videoDataTimeHideAds && flag_ads_vimeo[index] == false)
									            {
									            	$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);

									            	currentTimeVideoCheck = data.seconds;

									            	$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').off('.closeVimeoAds').on('click.closeVimeoAds', function(){

			            			            			//ajax track close here
			            									ajax_track(playbackVideoAdsID, currentTimeVideoCheck, true, false);

									            			$this.find('.'+cactusVideoAds).css({"display":"none"});
									            			cactus_ads_vimeo_player[index].api('pause');
									            			cactus_main_vimeo_player[index].api('play');
					            							flag_ads_vimeo[index] = true;
									            	});
									            }
										    }

										    // When the cactus_ads_vimeo_player[index]  is ready, add listeners for pause, finish, and playProgress
										    cactus_ads_vimeo_player[index] .addEvent('ready', function() {
										        // status.text('ready');
										        cactus_ads_vimeo_player[index] .addEvent('pause', onPauseAds);
										        cactus_ads_vimeo_player[index] .addEvent('finish', onFinishAds);
										        var currentTimeVideoCheck = 0;
									            var vimeoAdsDuration = 0;
									            if(isVimeoPlayback == true){
									            	playbackVideoAdsID 	= playbackVideoAdsSource != '' && playbackVideoAdsSource == 'vimeo' ? playbackAdsID : adsID;
									            }
									            else
									            {
									            	playbackVideoAdsID = adsID;
									            }
										        cactus_ads_vimeo_player[index] .addEvent('playProgress', onPlayProgressAds);
										    });
									    });
									}
									else if(videoAdsSource == 'self-hosted')
									{
										$this.find('.'+cactusVideoAds).css("visibility","visible");
										mask_button($this, cactusVideoAds, videoAdsSource, videoSource);
										$this.find('.'+cactusVideoAds).find(".hide-pause.button-start").click(function() {
											$this.find('.'+cactusVideoAds).html('<video id="player-html5-' + index + '" class="wp-video-shortcode" autoplay="true" preload="auto" controls="controls" style="width:100%"><source src="' + videoAds + '" type="video/mp4"></video><div>');

											cactus_html5_player_Ads[index] = $this.find('.'+cactusVideoAds).find('.wp-video-shortcode');

											cactus_html5_player_Ads[index].get(0).play();

											close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition);

											$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
												//ajax track click to first ads here
												ajax_track(adsID, 0, false, true);
											});

											$this.find('.'+cactusVideoAds).find('.hide-pause').css({"opacity":"0", "cursor":"auto"});
											$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});

											// when youtube html 5 ads finish
											cactus_html5_player_Ads[index].get(0).onended = function(e) {
												// alert('end video');
												videoPlayFullTime=cactus_html5_player_Ads[index].get(0).duration;
												//ajax track close here when finish ads first time
												ajax_track(adsID, videoPlayFullTime, false, false);

												$this.find('.'+cactusVideoAds).css({"display":"none"});
												cactus_main_vimeo_player[index].api("play");
											}

										    var html5AdsInterval = null;

											function adsPlayCurrentTime_func() {
													videoHtml5PlayCurrentTime=cactus_html5_player_Ads[index].get(0).currentTime;
													cactus_html5_player_Ads[index].get(0).addEventListener("timeupdate",function() {
														if(videoHtml5PlayCurrentTime >= videoDataTimeHideAds)
														{
															$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);

															if(isClickCloseButtonFirstTime == true)
															{
																$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').off('.closeYoutubeAds').on('click.closeYoutubeAds', function(){

																	//ajax track close here
																	ajax_track(adsID, videoHtml5PlayCurrentTime, true, false);

																	if(html5AdsInterval!=null) {clearInterval(html5AdsInterval);}
																	$this.find('.'+cactusVideoAds).css({"display":"none"});
																	cactus_html5_player_Ads[index].get(0).pause();
																	cactus_main_vimeo_player[index].api("play");
																	isClickCloseButtonFirstTime = false;
																});
															}
														}
													});
												};
											html5AdsInterval = setInterval(adsPlayCurrentTime_func,500)
										});
									}
								}
								else
								{
								 	if(videoAdsType=='image')
								 	{
										//ads images

										// Hidden ads images
										$this.find('.'+cactusVideoAds).css("visible","hidden");

										//full size
										if(adsImagePosition == '1' || adsImagePosition == 'undefined' || adsImagePosition == '')
										{
											$this.find('.'+cactusVideoAds).css("display","none");
											// prepare ads images
											$this.find('.'+cactusVideoAds).html('<a href="' + videoDataLinkRedirect + '" target="_blank"><img src="' + videoAds + '"/></a>');
											$this.find('.'+cactusVideoAds + ' img').css({"width":"100%", "height":"100%"});

											$this.find('.'+cactusVideoAds).find('a img').off('.clickToAds').on('click.clickToAds', function(){
												//ajax track click to full ads here
												ajax_track(adsID, 0, false, true);
											});

											close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition);
											$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);


							            	$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').click(function(){
		            			            		//ajax track close full ads here
	            									ajax_track(adsID, 0, true, false);
							            			$this.find('.'+cactusVideoAds).css({"display":"none"});
							            			cactus_main_vimeo_player[index].api("play");
							            	});
										}
										else
										{
											// top banner
											if(adsImagePosition == '2')
											{
												// prepare ads images
												$this.find('.'+cactusVideoAds).css({"pointer-events":"none", 'z-index':'10'});
												$('<img src="'+videoAds+'">').load(function() {
													$this.find('.'+cactusVideoAds).html('<div class="banner-img"><a href="' + videoDataLinkRedirect + '" target="_blank"><img class="main-img" src="' + videoAds + '"/></div></a>');
													var playerHeight 	= checkHeight;
													var playerWidth 	= checkWidth;
													var imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
													var imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

													var dscFromBottomPlayertoImg = playerHeight - imgHeight;
													var dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

													$this.find('.'+cactusVideoAds + ' .banner-img').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
													$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });

													$(window).resize(function() {
										    			setTimeout(function(){
										    				playerWidth 	= $('.cactus-video-content').width();
										    				playerHeight 	= (checkWidth / 16 * 9);
															imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
															imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();
															dscFromBottomPlayertoImg = playerHeight - imgHeight;
															dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;
															$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
											    		},400)
													});

													$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds').on('click.clickToAds', function(){
														//ajax track click to first top ads here
														ajax_track(adsID, 0, false, true);
													});


													$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').click(function() {
								            			if(playbackVideoAds != '' && playbackVideoAdsType == 'image' && playbackAdsImagePosition == '2')
								            			{
								            				$this.find('.'+cactusVideoAds).css({"visibility":"hidden"});
								            				$this.find('.'+cactusVideoAds + ' .banner-img img').attr("src", playbackVideoAds);
								            				$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds');
								            				$this.find('.'+cactusVideoAds + ' .banner-img img').removeClass("main-img");
								            				$this.find('.'+cactusVideoAds + ' .banner-img img').addClass("second-img");
								            				$this.find('.'+cactusVideoAds + ' .banner-img a').attr("href", playbackDataLinkRedirect);
								            				playerWidth 	= $('.cactus-video-content').width();
										    				playerHeight 	= (playerWidth / 16 * 9);
								            				imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
								            				imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

								            				dscFromBottomPlayertoImg = playerHeight - imgHeight;
								            				dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

								            				$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
								            			}
								            			else
								            			{
								            				$this.find('.'+cactusVideoAds).css({"display":"none"});
								            			}
								            			if(click_count[index] != '1'){
								            				ajax_track(adsID, 0, true, false);	
								            			}
								            			click_count[index] = '1';
								            		});
												});

												// $this.find('.'+cactusVideoAds + ' img').css({"width":"100%", "height":"auto"});


											}
											//bottom banner
											else if(adsImagePosition == '3')
											{
												// prepare ads images
												$this.find('.'+cactusVideoAds).css({"height":"auto", "top": "0", "pointer-events" : "none", 'z-index':'10'});
												$('<img src="'+videoAds+'">').load(function() {
													$this.find('.'+cactusVideoAds).html('<div class="banner-img"><a href="' + videoDataLinkRedirect + '" target="_blank"><img class="main-img" src="' + videoAds + '"/></div></a>');

			            							var playerHeight 	= checkHeight;
			            							var playerWidth 	= checkWidth;
			            							var imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
			            							var imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

			            							var dscFromBottomPlayertoImg = playerHeight - imgHeight;
			            							var dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

			            							$this.find('.'+cactusVideoAds + ' .banner-img').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
			            							$this.find('.'+cactusVideoAds + ' .banner-img').css({'padding-bottom': '40px', 'top':'auto'});
			            							$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top': '10px', 'right': dscFromRightPlayertoImg + 10 + 'px' });

			            							$(window).resize(function() {
			            				    			setTimeout(function(){
			            				    				playerWidth 	= $('.cactus-video-content').width();
			            				    				playerHeight 	= (checkWidth / 16 * 9);
			            									imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
			            									imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();
			            									dscFromBottomPlayertoImg = playerHeight - imgHeight;
			            									dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;
			            									if(playerWidth < 600)
			            									{
			            										$this.find('.'+cactusVideoAds + ' .banner-img').css({'top':'0'});
			            										$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top':'auto', 'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
			            									}
			            									else
			            									{
			            										$this.find('.'+cactusVideoAds + ' .banner-img').css({'top':'auto'});
			            										$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top': '10px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
			            									}
			            					    		},400)
			            							});

													$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds').on('click.clickToAds', function(){
														//ajax track click to first top ads here
														ajax_track(adsID, 0, false, true);
													});


			            							$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').click(function() {
			            		            			if(playbackVideoAds != '' && playbackVideoAdsType == 'image' && playbackAdsImagePosition == '3')
								            			{
								            				$this.find('.'+cactusVideoAds).css({"visibility":"hidden"});
								            				$this.find('.'+cactusVideoAds + ' .banner-img img').attr("src", playbackVideoAds);
								            				$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds');
								            				$this.find('.'+cactusVideoAds + ' .banner-img img').removeClass("main-img");
								            				$this.find('.'+cactusVideoAds + ' .banner-img img').addClass("second-img");
								            				$this.find('.'+cactusVideoAds + ' .banner-img a').attr("href", playbackDataLinkRedirect);
								            				playerWidth 	= $('.cactus-video-content').width();
										    				playerHeight 	= (playerWidth / 16 * 9);
								            				imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
								            				imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

								            				dscFromBottomPlayertoImg = playerHeight - imgHeight;
								            				dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

								            				$this.find('.'+cactusVideoAds + ' .banner-img').css({'padding-bottom': '40px', 'top':'auto'});
					            							$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top': '10px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
								            			}
								            			else
								            			{
								            				$this.find('.'+cactusVideoAds).css({"display":"none"});
								            			}
								            			if(click_count[index] != '1'){
								            				ajax_track(adsID, 0, true, false);	
								            			}
								            			click_count[index] = '1';
			            		            		});
												});
											}
										}
								 	}
								 	else if( videoAdsType == 'adsense')
									{
										if(adsImagePosition == '1' || adsImagePosition == '') 
										{
											adsImagePosition = '2';
										}
										// Hidden ads images
										$this.find('.'+cactusVideoAds).css("display","none");

										// top banner
										if(adsImagePosition == '2')
										{
											$this.find('.'+cactusVideoAds).css("height","0");
											$this.find('.'+cactusVideoAds).html('<div class="adsense-block">' + videoAds + '</div>');
											$this.find('.'+cactusVideoAds + ' .adsense-block').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
											$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').css({'bottom': '10px', 'right': '10px'});
											$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').click(function() {
						            			$this.find('.'+cactusVideoAds).css({"display":"none"});
						            			click_count[index] = '1';
						            		});
										}
										//bottom banner
										else if(adsImagePosition == '3')
										{
											$this.find('.'+cactusVideoAds).css({"height":"auto", "top": "auto", "pointer-events" : "none"});
						            		$this.find('.'+cactusVideoAds).html('<div class="adsense-block">' + videoAds + '</div>');


											$this.find('.'+cactusVideoAds + ' .adsense-block').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
											$this.find('.'+cactusVideoAds + ' .adsense-block').css({"padding-bottom" : "40px"});
											$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').css({'top': '10px', 'right': '10px'});
											$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').click(function() {
						            			$this.find('.'+cactusVideoAds).css({"display":"none"});
						            			click_count[index] = '1';
						            		});

										}
									}
								}
						}
						else
						{
							//play video
							cactus_main_vimeo_player[index].api("play");
						}
					}
				}

				else if(videoSource == 'self-hosted')
				{
					var videoDurationAds=0;
					var videoPlayCurrentTime=0;

					if(videoAutoPlay == "1")
					{
						if(videoAds!='' && videoAds!=null && videoAdsType!='')
						{
							$this.find('.'+cactusVideoAds).css("visibility","visible");
							var divVideoAdsId=cactusVideoAds+'-'+index;

							$this.find('.'+cactusVideoDetails).find('.'+cactusVideoContent).html('<video class="wp-video-shortcode" autoplay="true" preload="auto" controls="controls" style="width:100%;"><source src="' + videoLink + '" type="video/mp4"></video><div>');

							cactus_player[index] = $this.find('.'+cactusVideoDetails).find('.wp-video-shortcode');
							
							cactus_player[index].get(0).pause();

							if(videoAdsType=='video')
							{

								if(videoAdsSource == 'youtube')
								{
									close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition);

									$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
										//ajax track click to first ads here
										ajax_track(adsID, 0, false, true);
									});

									function onPlayerReady_auto_self_hosted(event) {
										$this.find('.'+cactusVideoAds).find('.hide-pause').css({"opacity":"0", "cursor":"auto"});
										$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});
									};

									function onPlayerStateChange_auto_self_hosted(event) {
										$this.find('.'+cactusVideoAds).find('.hide-pause').css({"opacity":"0", "cursor":"auto"});
										$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});

										// when youtube ads finish
										if(event.data === 0) {

											videoPlayFullTime=cactus_player_Ads[index].getDuration();
											//ajax track close here when finish ads first time
											ajax_track(adsID, videoPlayFullTime, false, false);

											$this.find('.'+cactusVideoAds).css({"display":"none"});
											cactus_player_Ads[index].stopVideo();
											cactus_player[index].get(0).play();
										};

										var youtubeAdsInterval = null;

										function adsPlayCurrentTime_func() {
											videoPlayCurrentTime=cactus_player_Ads[index].getCurrentTime();
											if(parseInt(videoPlayCurrentTime) >= videoDataTimeHideAds) {

												$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);

												if(isClickCloseButtonFirstTime == true)
												{
													$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').off('.closeYoutubeAds').on('click.closeYoutubeAds', function(){

														//ajax track close here
														ajax_track(adsID, videoPlayCurrentTime, true, false);

														if(youtubeAdsInterval!=null) {clearInterval(youtubeAdsInterval);}
														$this.find('.'+cactusVideoAds).css({"display":"none"});
														cactus_player_Ads[index].stopVideo();
														cactus_player[index].get(0).play();
														isClickCloseButtonFirstTime = false;
													});
												}


											}
										};
										youtubeAdsInterval = setInterval(adsPlayCurrentTime_func,500);
									};

									cactus_player_Ads[index] = new YT.Player(divVideoAdsId, {
										width: checkWidth,
										height: checkHeight,
										videoId: videoAds,
										playerVars: {
											controls: 0,
											showinfo: 0,
											enablejsapi:1,
											autoplay:1,
											disablekb:1,
										},
										events: {
											'onReady': onPlayerReady_auto_self_hosted,
											'onStateChange': onPlayerStateChange_auto_self_hosted
										}
									});
								}
								else if(videoAdsSource == 'vimeo')
								{
									if(flag_vimeo[index] == true)
									{
										flag_vimeo[index] = true;
									}
									else
									{
										flag_vimeo[index] = false;
									}

									$this.find('.'+cactusVideoAds).html('<iframe id="player-vimeo-' + index + '" src="https://player.vimeo.com/video/' + videoAds + '?api=1&player_id=player-vimeo-' + index + '&autoplay=1" width="' + checkWidth + '" height="' + checkHeight + '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');

									close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition, videoAdsSource);

									$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
										//ajax track click to first ads here
										ajax_track(adsID, 0, false, true);
									});

									$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});

								    // var iframe = $('#player-vimeo-' + index)[0];
								    // cactus_player_Ads[] = $f(iframe);

								    cactus_vimeo_player[index] = $('#player-vimeo-' + index)[0];
									cactus_vimeo_player_Ads[index] =$f(cactus_vimeo_player[index]);
								    // var status = $('.vimeo_status');

							        function onPause_self_hosted(id) {
							            // status.text('paused');
							        }

							        function onFinish_self_hosted(id) {
							            // status.text('finished');
							            
							            ajax_track(playbackVideoAdsID, vimeoAdsDuration, false, false);
							            $this.find('.'+cactusVideoAds).css({"display":"none"});
							            cactus_player[index].get(0).play();
							        }


							        function onPlayProgress_self_hosted(data, id) {
							            // status.text(data.seconds + 's played');
							            vimeoAdsDuration = data.duration;

							            if(data.seconds > videoDataTimeHideAds && flag_vimeo[index] == false)
							            {
							            	$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);

							            	//console.log(data.seconds);
							            	currentTimeVideoCheck = data.seconds;

							            	
							            	$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').off('.closeVimeoAds').on('click.closeVimeoAds', function(){
						            			//ajax track close here
												ajax_track(playbackVideoAdsID, currentTimeVideoCheck, true, false);

						            			$this.find('.'+cactusVideoAds).css({"display":"none"});
						            			cactus_vimeo_player_Ads[index].api('pause');
						            			cactus_player[index].get(0).play();
				            					flag_vimeo[index] = true;
							            	});
											
							            }
							        }

							        // When the cactus_vimeo_player_Ads[index] is ready, add listeners for pause, finish, and playProgress
							        cactus_vimeo_player_Ads[index].addEvent('ready', function() {
							            // status.text('ready');
							            cactus_vimeo_player_Ads[index].addEvent('pause', onPause_self_hosted);
							            cactus_vimeo_player_Ads[index].addEvent('finish', onFinish_self_hosted);
							            var currentTimeVideoCheck = 0;
							            var vimeoAdsDuration = 0;
							            if(isVimeoPlayback == true){
							            	playbackVideoAdsID 	= playbackVideoAdsSource != '' && playbackVideoAdsSource == 'vimeo' ? playbackAdsID : adsID;
							            }
							            else
							            {
							            	playbackVideoAdsID = adsID;
							            }
							            
							            cactus_vimeo_player_Ads[index].addEvent('playProgress', onPlayProgress_self_hosted);
							        });
								}
								else if(videoAdsSource == 'self-hosted')
								{
									$this.find('.'+cactusVideoAds).html('<video id="player-html5-' + index + '" class="wp-video-shortcode" autoplay="true" preload="auto" controls="controls" style="width:100%; height: 100%;"><source src="' + videoAds + '" type="video/mp4"></video><div>');

									cactus_html5_player_Ads[index] = $this.find('.'+cactusVideoAds).find('.wp-video-shortcode');

									close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition);

									$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
										//ajax track click to first ads here
										ajax_track(adsID, 0, false, true);
									});

									$this.find('.'+cactusVideoAds).find('.hide-pause').css({"opacity":"0", "cursor":"auto"});
									$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});

									// when youtube html 5 ads finish
									cactus_html5_player_Ads[index].get(0).onended = function(e) {
										videoPlayFullTime=cactus_html5_player_Ads[index].get(0).duration;
										//ajax track close here when finish ads first time
										ajax_track(adsID, videoPlayFullTime, false, false);

										$this.find('.'+cactusVideoAds).css({"display":"none"});
										cactus_player[index].get(0).play();
									}

								    var html5AdsInterval = null;

									function adsPlayCurrentTime_func_self_hosted() {
											videoHtml5PlayCurrentTime=cactus_html5_player_Ads[index].get(0).currentTime;
											cactus_html5_player_Ads[index].get(0).addEventListener("timeupdate",function() {
												if(videoHtml5PlayCurrentTime >= videoDataTimeHideAds)
												{
													$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);

													if(isClickCloseButtonFirstTime == true)
													{
														$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').off('.closeYoutubeAds').on('click.closeYoutubeAds', function(){

															//ajax track close here
															ajax_track(adsID, videoHtml5PlayCurrentTime, true, false);

															if(html5AdsInterval!=null) {clearInterval(html5AdsInterval);}
															$this.find('.'+cactusVideoAds).css({"display":"none"});
															cactus_html5_player_Ads[index].get(0).pause();
															cactus_player[index].get(0).play();
															isClickCloseButtonFirstTime = false;
														});
													}
												}
											});
										};
									html5AdsInterval = setInterval(adsPlayCurrentTime_func_self_hosted,500)
								}
							}
							else if(videoAdsType=='image')
							{
								//ads images

								// Hidden ads images
								$this.find('.'+cactusVideoAds).css("visibility","hidden");

								//full size
								if(adsImagePosition == '1' || adsImagePosition == 'undefined' || adsImagePosition == '')
								{
									$this.find('.'+cactusVideoAds).css("display","none");
									// prepare ads images
									$this.find('.'+cactusVideoAds).html('<a href="' + videoDataLinkRedirect + '" target="_blank"><img src="' + videoAds + '"/></a>');
									$this.find('.'+cactusVideoAds + ' img').css({"width":"100%", "height":"100%"});

									close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition);

									$this.find('.'+cactusVideoAds).find('a img').off('.clickToAds').on('click.clickToAds', function(){
										//ajax track click to full ads here
										ajax_track(adsID, 0, false, true);
									});

									$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);


					            	$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').click(function(){
					            			//ajax track close full ads here
											ajax_track(adsID, 0, true, false);
					            			$this.find('.'+cactusVideoAds).css({"display":"none"});
					            			cactus_player[index].get(0).play();
					            	});
								}
								else
								{
									// top banner
									if(adsImagePosition == '2')
									{
										// prepare ads images
										$this.find('.'+cactusVideoAds).css({"pointer-events":"none", 'z-index':'10'});
										$('<img src="'+videoAds+'">').load(function() {
											$this.find('.'+cactusVideoAds).html('<div class="banner-img"><a href="' + videoDataLinkRedirect + '" target="_blank"><img class="main-img" src="' + videoAds + '"/></div></a>');
											var playerHeight 	= checkHeight;
											var playerWidth 	= checkWidth;
											var imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
											var imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

											var dscFromBottomPlayertoImg = playerHeight - imgHeight;
											var dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

											$this.find('.'+cactusVideoAds + ' .banner-img').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
											$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });

											$(window).resize(function() {
								    			setTimeout(function(){
								    				playerWidth 	= $('.cactus-video-content').width();
								    				playerHeight 	= (checkWidth / 16 * 9);
													imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
													imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();
													dscFromBottomPlayertoImg = playerHeight - imgHeight;
													dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;
													$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
									    		},400)
											});



											$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds').on('click.clickToAds', function(){
												//ajax track click to first top ads here
												ajax_track(adsID, 0, false, true);
											});


											$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').click(function() {
						            			if(playbackVideoAds != '' && playbackVideoAdsType == 'image' && playbackAdsImagePosition == '2')
						            			{
						            				$this.find('.'+cactusVideoAds).css({"visibility":"hidden"});
						            				$this.find('.'+cactusVideoAds + ' .banner-img img').attr("src", playbackVideoAds);
						            				$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds');
						            				$this.find('.'+cactusVideoAds + ' .banner-img img').removeClass("main-img");
						            				$this.find('.'+cactusVideoAds + ' .banner-img img').addClass("second-img");

						            				$this.find('.'+cactusVideoAds + ' .banner-img a').attr("href", playbackDataLinkRedirect);
						            				playerWidth 	= $('.cactus-video-content').width();
								    				playerHeight 	= (playerWidth / 16 * 9);
						            				imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
						            				imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

						            				dscFromBottomPlayertoImg = playerHeight - imgHeight;
						            				dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

						            				$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
						            			}
						            			else
						            			{
						            				$this.find('.'+cactusVideoAds).css({"display":"none"});
						            			}
						            			if(click_count[index] != '1'){
						            				ajax_track(adsID, 0, true, false);	
						            			}

						            			click_count[index] = '1';
						            		});
										});

										// $this.find('.'+cactusVideoAds + ' img').css({"width":"100%", "height":"auto"});


									}
									//bottom banner
									else if(adsImagePosition == '3')
									{
										// prepare ads images
										$this.find('.'+cactusVideoAds).css({"height":"auto", "top": "0", "pointer-events" : "none", 'z-index':'10'});
										$('<img src="'+videoAds+'">').load(function() {
											$this.find('.'+cactusVideoAds).html('<div class="banner-img"><a href="' + videoDataLinkRedirect + '" target="_blank"><img class="main-img" src="' + videoAds + '"/></div></a>');

	            							var playerHeight 	= checkHeight;
	            							var playerWidth 	= checkWidth;
	            							var imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
	            							var imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

	            							var dscFromBottomPlayertoImg = playerHeight - imgHeight;
	            							var dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

	            							$this.find('.'+cactusVideoAds + ' .banner-img').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
	            							$this.find('.'+cactusVideoAds + ' .banner-img').css({'padding-bottom': '40px', 'top':'auto'});
	            							$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top': '10px', 'right': dscFromRightPlayertoImg + 10 + 'px' });

	            							$(window).resize(function() {
	            				    			setTimeout(function(){
	            				    				playerWidth 	= $('.cactus-video-content').width();
	            				    				playerHeight 	= (checkWidth / 16 * 9);
	            									imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
	            									imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();
	            									dscFromBottomPlayertoImg = playerHeight - imgHeight;
	            									dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;
	            									if(playerWidth < 600)
	            									{
	            										$this.find('.'+cactusVideoAds + ' .banner-img').css({'top':'0'});
	            										$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top':'auto', 'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
	            									}
	            									else
	            									{
	            										$this.find('.'+cactusVideoAds + ' .banner-img').css({'top':'auto'});
	            										$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top': '10px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
	            									}
	            					    		},400)
	            							});

											$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds').on('click.clickToAds', function(){
												//ajax track click to first top ads here
												ajax_track(adsID, 0, false, true);
											});


	            							$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').click(function() {
	            		            			if(playbackVideoAds != '' && playbackVideoAdsType == 'image' && playbackAdsImagePosition == '3')
						            			{
						            				$this.find('.'+cactusVideoAds).css({"visibility":"hidden"});
						            				$this.find('.'+cactusVideoAds + ' .banner-img img').attr("src", playbackVideoAds);
						            				$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds');
						            				$this.find('.'+cactusVideoAds + ' .banner-img img').removeClass("main-img");
						            				$this.find('.'+cactusVideoAds + ' .banner-img img').addClass("second-img");
						            				$this.find('.'+cactusVideoAds + ' .banner-img a').attr("href", playbackDataLinkRedirect);
						            				playerWidth 	= $('.cactus-video-content').width();
								    				playerHeight 	= (playerWidth / 16 * 9);
						            				imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
						            				imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

						            				dscFromBottomPlayertoImg = playerHeight - imgHeight;
						            				dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

						            				$this.find('.'+cactusVideoAds + ' .banner-img').css({'padding-bottom': '40px', 'top':'auto'});
			            							$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top': '10px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
						            			}
						            			else
						            			{
						            				$this.find('.'+cactusVideoAds).css({"display":"none"});
						            			}
						            			if(click_count[index] != '1'){
						            				ajax_track(adsID, 0, true, false);	
						            			}
						            			click_count[index] = '1';
	            		            		});
										});
									}
								}

				            	//play html5 video
				            	cactus_player[index].get(0).play();

							}
							else if(videoAdsType=='adsense')
							{
								if(adsImagePosition == '1' || adsImagePosition == '') 
								{
									adsImagePosition = '2';
								}
								// Hidden ads images
								$this.find('.'+cactusVideoAds).css("display","none");

								// top banner
								if(adsImagePosition == '2')
								{
									$this.find('.'+cactusVideoAds).css({"height":"0", "pointer-events":"none", 'z-index':'10'});
									$this.find('.'+cactusVideoAds).html('<div class="adsense-block">' + videoAds + '</div>');
									$this.find('.'+cactusVideoAds + ' .adsense-block').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
									$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').css({'bottom': '10px', 'right': '10px'});
									$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').click(function() {
				            			$this.find('.'+cactusVideoAds).css({"display":"none"});
				            			click_count[index] = '1';
				            		});
								}
								//bottom banner
								else if(adsImagePosition == '3')
								{
									$this.find('.'+cactusVideoAds).css({"height":"auto", "top": "auto", "pointer-events" : "none", 'z-index':'10'});
				            		$this.find('.'+cactusVideoAds).html('<div class="adsense-block">' + videoAds + '</div>');


									$this.find('.'+cactusVideoAds + ' .adsense-block').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
									$this.find('.'+cactusVideoAds + ' .adsense-block').css({"padding-bottom" : "40px"});
									$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').css({'top': '10px', 'right': '10px'});
									$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').click(function() {
				            			$this.find('.'+cactusVideoAds).css({"display":"none"});
				            			click_count[index] = '1';
				            		});

								}


								//play youtube video
				            	cactus_player[index].get(0).play();
							}
						}							
					}
					else
					{
						if(videoAds!='' && videoAds!=null && videoAdsType!='')
						{
							$this.find('.'+cactusVideoAds).css("visibility","visible");
							var divVideoAdsId=cactusVideoAds+'-'+index;
							$this.find('.'+cactusVideoDetails).find('.'+cactusVideoContent).html('<video class="wp-video-shortcode" preload="auto" controls="controls"  style="width:100%;" 	><source src="' + videoLink + '" type="video/mp4"></video><div>');

							cactus_player[index] = $this.find('.'+cactusVideoDetails).find('.wp-video-shortcode');

							cactus_player[index].get(0).pause();
							

							if(videoAdsType=='video')
							{
								if(videoAdsSource == 'youtube')
								{
									close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition, videoAdsSource);

									$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
										//ajax track click to first ads here
										ajax_track(adsID, 0, false, true);
									});

									$this.find('.'+cactusVideoAds).find(".hide-pause.button-start").click(function(){

										var $thisads=$(this);

										function onPlayerReady_nauto(event) {
											$thisads.css({"opacity":"0"});
											$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});
										};

										function onPlayerStateChange_nauto(event) {
											if(event.data === 0) {
												videoPlayFullTime=cactus_player_Ads[index].getDuration();
												//ajax track close here when finish ads first time
												ajax_track(adsID, videoPlayFullTime, false, false);

												$this.find('.'+cactusVideoAds).css({"display":"none"});
												cactus_player_Ads[index].stopVideo();
												cactus_player[index].get(0).play();
											};

											
											var youtubeAdsInterval = null;
											function adsPlayCurrentTime_func() {
												videoPlayCurrentTime=cactus_player_Ads[index].getCurrentTime();
												if(parseInt(videoPlayCurrentTime) >= videoDataTimeHideAds) {
													clearInterval(adsPlayCurrentTime_func);
													$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);

													if(isClickCloseButtonFirstTime == true)
													{
														$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').off('.closeYoutubeAds').on('click.closeYoutubeAds', function(){

															//ajax track close here
															ajax_track(adsID, videoPlayCurrentTime, true, false);

															if(youtubeAdsInterval!=null) {clearInterval(youtubeAdsInterval);}

															$this.find('.'+cactusVideoAds).css({"display":"none"});
															cactus_player_Ads[index].stopVideo();
															cactus_player[index].get(0).play();
															isClickCloseButtonFirstTime = false;
														});
													}

												};
											}
											youtubeAdsInterval = setInterval(adsPlayCurrentTime_func,500)
										};

										cactus_player_Ads[index] = new YT.Player(divVideoAdsId, {
											width: checkWidth,
											height: checkHeight,
											videoId: videoAds,
											playerVars: {
												controls: 0,
												showinfo: 0,
												enablejsapi:1,
												autoplay:1,
												disablekb:1,
											},
											events: {
												'onReady': onPlayerReady_nauto,
												'onStateChange': onPlayerStateChange_nauto
											}
										});

									});
								}
								else if(videoAdsSource == 'vimeo')
								{
									if(flag_vimeo[index] == true)
									{
										flag_vimeo[index] = true;
									}
									else
									{
										flag_vimeo[index] = false;
									}

									mask_button($this, cactusVideoAds, videoAdsSource, videoSource);

									$this.find('.'+cactusVideoAds).find(".hide-pause.button-start").click(function() {

										$this.find('.'+cactusVideoAds).html('<iframe id="player-vimeo-' + index + '" src="https://player.vimeo.com/video/' + videoAds + '?api=1&player_id=player-vimeo-' + index + '" width="' + checkWidth + '" height="' + checkHeight + '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');

										close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition, videoAdsSource);

										$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
											//ajax track click to first ads here
											ajax_track(adsID, 0, false, true);
										});

										$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});

									    cactus_vimeo_player[index] = $('#player-vimeo-' + index)[0];
										cactus_vimeo_player_Ads[index] =$f(cactus_vimeo_player[index]);
									    // var status = $('.vimeo_status');

								        function onPause(id) {
								            // status.text('paused');
								        }

								        function onFinish(id) {
								            // status.text('finished');
								            ajax_track(playbackVideoAdsID, vimeoAdsDuration, false, false);
								            $this.find('.'+cactusVideoAds).css({"display":"none"});
								            cactus_player[index].get(0).pause();
								        }

								        function onPlayProgress(data, id) {
							            // status.text(data.seconds + 's played');
							            vimeoAdsDuration = data.duration;

							            if(data.seconds > videoDataTimeHideAds && flag_vimeo[index] == false)
							            {
							            	$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);

							            	//console.log(data.seconds);
							            	currentTimeVideoCheck = data.seconds;

							            	
							            	$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').off('.closeVimeoAds').on('click.closeVimeoAds', function(){
						            			//ajax track close here
												ajax_track(playbackVideoAdsID, currentTimeVideoCheck, true, false);

						            			$this.find('.'+cactusVideoAds).css({"display":"none"});
						            			cactus_vimeo_player_Ads[index].api('pause');
						            			cactus_player[index].get(0).pause();
				            					flag_vimeo[index] = true;
							            	});
											
							            }
							        }

								        // When the cactus_vimeo_player_Ads[index] is ready, add listeners for pause, finish, and playProgress
								        cactus_vimeo_player_Ads[index].addEvent('ready', function() {
								            // status.text('ready');
								            cactus_vimeo_player_Ads[index].addEvent('pause', onPause);
								            cactus_vimeo_player_Ads[index].addEvent('finish', onFinish);
								            var currentTimeVideoCheck = 0;
								            var vimeoAdsDuration = 0;
								            if(isVimeoPlayback == true){
								            	playbackVideoAdsID 	= playbackVideoAdsSource != '' && playbackVideoAdsSource == 'vimeo' ? playbackAdsID : adsID;
								            }
								            else
								            {
								            	playbackVideoAdsID = adsID;
								            }
								            cactus_vimeo_player_Ads[index].addEvent('playProgress', onPlayProgress);
								            cactus_vimeo_player_Ads[index].api('play');
								        });
							        });
								}
								else if(videoAdsSource == 'self-hosted')
								{
									// $this.find('.'+cactusVideoAds).css("visibility","visible");
									mask_button($this, cactusVideoAds, videoAdsSource, videoSource);
									$this.find('.'+cactusVideoAds).find(".hide-pause.button-start").click(function() {
										$this.find('.'+cactusVideoAds).html('<video id="player-html5-' + index + '" class="wp-video-shortcode" autoplay="true" preload="auto" controls="controls" style="width:100%"><source src="' + videoAds + '" type="video/mp4"></video><div>');

									cactus_html5_player_Ads[index] = $this.find('.'+cactusVideoAds).find('.wp-video-shortcode');

									cactus_html5_player_Ads[index].get(0).play();

									close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition);

									$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
										//ajax track click to first ads here
										ajax_track(adsID, 0, false, true);
									});

									$this.find('.'+cactusVideoAds).find('.hide-pause').css({"opacity":"0", "cursor":"auto"});
									$this.find('.'+cactusVideoAds).find('.linkads').css({"opacity":"1", "visibility":"visible"});

									// when youtube html 5 ads finish
									cactus_html5_player_Ads[index].get(0).onended = function(e) {
										videoPlayFullTime=cactus_html5_player_Ads[index].get(0).duration;
										//ajax track close here when finish ads first time
										ajax_track(adsID, videoPlayFullTime, false, false);

										$this.find('.'+cactusVideoAds).css({"display":"none"});
										cactus_player[index].get(0).play();
									}

								    var html5AdsInterval = null;

									function adsPlayCurrentTime_func_self_hosted() {
											videoHtml5PlayCurrentTime=cactus_html5_player_Ads[index].get(0).currentTime;
											cactus_html5_player_Ads[index].get(0).addEventListener("timeupdate",function() {
												if(videoHtml5PlayCurrentTime >= videoDataTimeHideAds)
												{
													$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);

													if(isClickCloseButtonFirstTime == true)
													{
														$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').off('.closeYoutubeAds').on('click.closeYoutubeAds', function(){

															//ajax track close here
															ajax_track(adsID, videoHtml5PlayCurrentTime, true, false);

															if(html5AdsInterval!=null) {clearInterval(html5AdsInterval);}
															$this.find('.'+cactusVideoAds).css({"display":"none"});
															cactus_html5_player_Ads[index].get(0).pause();
															cactus_player[index].get(0).play();
															isClickCloseButtonFirstTime = false;
														});
													}
												}
											});
										};
									html5AdsInterval = setInterval(adsPlayCurrentTime_func_self_hosted,500)
									});
								}
							}
							else if(videoAdsType=='image')
							{
								//ads images

								// Hidden ads images
								$this.find('.'+cactusVideoAds).css("visibility","hidden");

								//full size
								if(adsImagePosition == '1' || adsImagePosition == 'undefined' || adsImagePosition == '')
								{
									$this.find('.'+cactusVideoAds).css("display","none");
									// prepare ads images
									$this.find('.'+cactusVideoAds).html('<a href="' + videoDataLinkRedirect + '" target="_blank"><img src="' + videoAds + '"/></a>');
									$this.find('.'+cactusVideoAds + ' img').css({"width":"100%", "height":"100%"});

									close_button($this, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition);

									$this.find('.'+cactusVideoAds).find('a img').off('.clickToAds').on('click.clickToAds', function(){
										//ajax track click to full ads here
										ajax_track(adsID, 0, false, true);
									});

									$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').css({"visibility":"visible", "opacity":"1"}).text(closeButtonName);


					            	$this.find('.'+cactusVideoAds).find('#close-'+divVideoAdsId+'').click(function(){
					            		//ajax track close full ads here
											ajax_track(adsID, 0, true, false);
					            			$this.find('.'+cactusVideoAds).css({"display":"none"});
					            			cactus_player[index].get(0).pause();
					            	});
								}

								else
								{
									// top banner
									if(adsImagePosition == '2')
									{
										// prepare ads images
										$this.find('.'+cactusVideoAds).css({"pointer-events":"none", 'z-index':'10'});
										$('<img src="'+videoAds+'">').load(function() {
											$this.find('.'+cactusVideoAds).html('<div class="banner-img"><a href="' + videoDataLinkRedirect + '" target="_blank"><img class="main-img" src="' + videoAds + '"/></div></a>');
											var playerHeight 	= checkHeight;
											var playerWidth 	= checkWidth;
											var imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
											var imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

											var dscFromBottomPlayertoImg = playerHeight - imgHeight;
											var dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

											$this.find('.'+cactusVideoAds + ' .banner-img').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
											$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });

											$(window).resize(function() {
								    			setTimeout(function(){
								    				playerWidth 	= $('.cactus-video-content').width();
								    				playerHeight 	= (playerWidth / 16 * 9);
													imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
													imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();
													dscFromBottomPlayertoImg = playerHeight - imgHeight;
													dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;
													$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
									    		},400)
											});

											$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds').on('click.clickToAds', function(){
												//ajax track click to first top ads here
												ajax_track(adsID, 0, false, true);
											});


											$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').click(function() {
						            			if(playbackVideoAds != '' && playbackVideoAdsType == 'image' && playbackAdsImagePosition == '2')
						            			{
						            				$this.find('.'+cactusVideoAds).css({"visibility":"hidden"});
						            				$this.find('.'+cactusVideoAds + ' .banner-img img').attr("src", playbackVideoAds);
						            				$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds');
						            				$this.find('.'+cactusVideoAds + ' .banner-img img').removeClass("main-img");
						            				$this.find('.'+cactusVideoAds + ' .banner-img img').addClass("second-img");
						            				$this.find('.'+cactusVideoAds + ' .banner-img a').attr("href", playbackDataLinkRedirect);
						            				playerWidth 	= $('.cactus-video-content').width();
								    				playerHeight 	= (playerWidth / 16 * 9);
						            				imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
						            				imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

						            				dscFromBottomPlayertoImg = playerHeight - imgHeight;
						            				dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

						            				$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
						            			}
						            			else
						            			{
						            				$this.find('.'+cactusVideoAds).css({"display":"none"});
						            			}
						            			if(click_count[index] != '1'){
						            				ajax_track(adsID, 0, true, false);	
						            			}
						            			click_count[index] = '1';
						            		});
										});

										// $this.find('.'+cactusVideoAds + ' img').css({"width":"100%", "height":"auto"});


									}
									//bottom banner
									else if(adsImagePosition == '3')
									{
										// prepare ads images
										$this.find('.'+cactusVideoAds).css({"height":"auto", "top": "0", "pointer-events" : "none", 'z-index':'10'});
										$('<img src="'+videoAds+'">').load(function() {
											$this.find('.'+cactusVideoAds).html('<div class="banner-img"><a href="' + videoDataLinkRedirect + '" target="_blank"><img class="main-img" src="' + videoAds + '"/></div></a>');

	            							var playerHeight 	= checkHeight;
	            							var playerWidth 	= checkWidth;
	            							var imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
	            							var imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

	            							var dscFromBottomPlayertoImg = playerHeight - imgHeight;
	            							var dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

	            							$this.find('.'+cactusVideoAds + ' .banner-img').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
	            							$this.find('.'+cactusVideoAds + ' .banner-img').css({'padding-bottom': '40px', 'top':'auto'});
	            							$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top': '10px', 'right': dscFromRightPlayertoImg + 10 + 'px' });

	            							$(window).resize(function() {
	            				    			setTimeout(function(){
	            				    				playerWidth 	= $('.cactus-video-content').width();
	            				    				playerHeight 	= (checkWidth / 16 * 9);
	            									imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
	            									imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();
	            									dscFromBottomPlayertoImg = playerHeight - imgHeight;
	            									dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;
	            									if(playerWidth < 600)
	            									{
	            										$this.find('.'+cactusVideoAds + ' .banner-img').css({'top':'0'});
	            										$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top':'auto', 'bottom': dscFromBottomPlayertoImg + 10 + 'px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
	            									}
	            									else
	            									{
	            										$this.find('.'+cactusVideoAds + ' .banner-img').css({'top':'auto'});
	            										$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top': '10px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
	            									}
	            					    		},400)
	            							});

											$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds').on('click.clickToAds', function(){
												//ajax track click to first top ads here
												ajax_track(adsID, 0, false, true);
											});


	            							$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').click(function() {
	            		            			if(playbackVideoAds != '' && playbackVideoAdsType == 'image' && playbackAdsImagePosition == '3')
						            			{
						            				$this.find('.'+cactusVideoAds).css({"visibility":"hidden"});
						            				$this.find('.'+cactusVideoAds + ' .banner-img img').attr("src", playbackVideoAds);
						            				$this.find('.'+cactusVideoAds).find('.banner-img a img.main-img').off('.clickToAds');
						            				$this.find('.'+cactusVideoAds + ' .banner-img img').removeClass("main-img");
						            				$this.find('.'+cactusVideoAds + ' .banner-img img').addClass("second-img");
						            				$this.find('.'+cactusVideoAds + ' .banner-img a').attr("href", playbackDataLinkRedirect);
						            				playerWidth 	= $('.cactus-video-content').width();
								    				playerHeight 	= (playerWidth / 16 * 9);
						            				imgHeight 		= $this.find('.'+cactusVideoAds + ' .banner-img img').height();
						            				imgWidth 		= $this.find('.'+cactusVideoAds + ' .banner-img img').width();

						            				dscFromBottomPlayertoImg = playerHeight - imgHeight;
						            				dscFromRightPlayertoImg = (playerWidth - imgWidth) / 2;

						            				$this.find('.'+cactusVideoAds + ' .banner-img').css({'padding-bottom': '40px', 'top':'auto'});
			            							$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').css({'top': '10px', 'right': dscFromRightPlayertoImg + 10 + 'px' });
						            			}
						            			else
						            			{
						            				$this.find('.'+cactusVideoAds).css({"display":"none"});
						            			}
						            			if(click_count[index] != '1'){
						            				ajax_track(adsID, 0, true, false);	
						            			}
						            			click_count[index] = '1';
	            		            		});
										});
									}

								}
							}
							else if(videoAdsType=='adsense')
							{
								// Hidden ads images
								$this.find('.'+cactusVideoAds).css("display","none");

								if(adsImagePosition == '1' || adsImagePosition == '') 
								{
									adsImagePosition = '2';
								}
								// Hidden ads images
								$this.find('.'+cactusVideoAds).css("display","none");

								// top banner
								if(adsImagePosition == '2')
								{
									$this.find('.'+cactusVideoAds).css({"height":"0", "pointer-events":"none", 'z-index':'10'});
									$this.find('.'+cactusVideoAds).html('<div class="adsense-block">' + videoAds + '</div>');
									$this.find('.'+cactusVideoAds + ' .adsense-block').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
									$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').css({'bottom': '10px', 'right': '10px'});
									$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').click(function() {
				            			$this.find('.'+cactusVideoAds).css({"display":"none"});
				            			click_count[index] = '1';
				            		});
								}
								//bottom banner
								else if(adsImagePosition == '3')
								{
									$this.find('.'+cactusVideoAds).css({"height":"auto", "top": "auto", "pointer-events" : "none", 'z-index':'10'});
				            		$this.find('.'+cactusVideoAds).html('<div class="adsense-block">' + videoAds + '</div>');


									$this.find('.'+cactusVideoAds + ' .adsense-block').append('<span class="close-banner-button"><i class="fa fa-times"></i></span>');
									$this.find('.'+cactusVideoAds + ' .adsense-block').css({"padding-bottom" : "40px"});
									$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').css({'top': '10px', 'right': '10px'});
									$this.find('.'+cactusVideoAds + ' .adsense-block .close-banner-button').click(function() {
				            			$this.find('.'+cactusVideoAds).css({"display":"none"});
				            			click_count[index] = '1';
				            		});

								}
							}
						}
						else
						{
							cactus_player[index].get(0).pause();
						}
					}

					cactus_player[index].get(0).addEventListener("timeupdate",function() {
						
						function videoPlayCurrentTime_func() {
							videoPlayCurrentTime=cactus_player[index].get(0).currentTime;

							if(flag[index] == true)
							{
								flag[index] = true;
							}
							else
							{
								flag[index] = false;
							}
							if(parseInt(videoPlayCurrentTime) > 0 && (adsImagePosition == '2' || adsImagePosition == '3') && click_count[index] != 1)
							{
								$global_this.find('.'+cactusVideoAds).css({"visibility":"visible", "display":"block"});
								click_count[index] = 2;
							}
							if(parseInt(videoPlayCurrentTime) >= videoDataTimePlayAgain && flag[index] == false) {
								clearInterval(videoPlayCurrentTime_func);


								//display ads and play it
								$global_this.find('.'+cactusVideoAds).css({"visibility":"visible", "display":"block"});
								//pause main video
								cactus_player[index].get(0).pause();

								if(isClickCloseButtonFirstTime == false)
								{
									$this.find('.'+cactusVideoAds).find('#close-'+AdsVideoId+'').off('.closeYoutubeAds').on('click.closeYoutubeAds', function(){
										
										ajax_track(playbackVideoAdsID, videoPlayCurrentTime, true, false);

										$this.find('.'+cactusVideoAds).css({"display":"none"});
										cactus_html5_player_Ads[index].get(0).pause();
										cactus_player[index].get(0).play();
									});
								}

								if(videoAdsType == 'video')
								{
									$this.find('.'+cactusVideoAds).find('.linkads').attr("href", playbackDataLinkRedirect);	
									if(videoAdsSource == 'youtube')
									{
										playbackVideoAdsURL = playbackVideoAdsSource != '' && playbackVideoAdsSource == 'youtube' ? playbackVideoAds : videoAds;
										playbackVideoAdsID 	= playbackVideoAdsSource != '' && playbackVideoAdsSource == 'youtube' ? playbackAdsID : adsID;

										cactus_player_Ads[index].destroy();

										$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){

											//ajax track click to second ads here
											ajax_track(playbackVideoAdsID, 0, false, true);

										});

										function onPlayerReady_auto1(event) {

											if(screenfull.isFullscreen == false)
											{
												cactus_player_Ads[index].seekTo(0, true);
												cactus_player_Ads[index].playVideo();
											}
											else
											{
												cactus_player_Ads[index].stopVideo();
												$this.find('.'+cactusVideoAds).css({"display":"none"});
												cactus_player[index].get(0).play();
											}
										};

										function onPlayerStateChange_auto1(event) {

											// $this.find('.'+cactusVideoAds).find('#close-'+AdsVideoId+'').click(function(){
											if(isClickCloseButtonFirstTime == false)
											{
												$this.find('.'+cactusVideoAds).find('#close-'+AdsVideoId+'').off('.closeYoutubeAds').on('click.closeYoutubeAds', function(){
													
													ajax_track(playbackVideoAdsID, videoPlayCurrentTime, true, false);

													$this.find('.'+cactusVideoAds).css({"display":"none"});
													cactus_player_Ads[index].stopVideo();
													cactus_player[index].get(0).play();
												});
											}

											if(event.data === 0) {

												videoPlayFullTime=cactus_player_Ads[index].getDuration();

												//ajax track close here when finish ads second time
												ajax_track(playbackVideoAdsID, videoPlayFullTime, false, false);

												$this.find('.'+cactusVideoAds).css({"display":"none"});
												cactus_player_Ads[index].stopVideo();
												cactus_player[index].get(0).play();
											};

										};


										cactus_player_Ads[index] = new YT.Player(AdsVideoId, {
												width: checkWidth,
												height: checkHeight,
												videoId: playbackVideoAdsURL,
												playerVars: {
													controls: 0,
													showinfo: 0,
													enablejsapi:1,
													autoplay:1,
													disablekb:1,
												},
												events: {
													'onReady': onPlayerReady_auto1,
													'onStateChange': onPlayerStateChange_auto1
												}
											});
									}
									else if(videoAdsSource == 'vimeo')
									{
										playbackVideoAdsURL = playbackVideoAdsSource != '' && playbackVideoAdsSource == 'vimeo' ? playbackVideoAds : videoAds;
										playbackVideoAdsID 	= playbackVideoAdsSource != '' && playbackVideoAdsSource == 'vimeo' ? playbackAdsID : adsID;
										$global_this.find('.'+cactusVideoAds+' iframe#player-vimeo-' + index).attr('src', 'https://player.vimeo.com/video/' + playbackVideoAdsURL + '?api=1&player_id=player-vimeo-' + index + '&autoplay=1');

										$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
											//ajax track click to first ads here
											ajax_track(playbackVideoAdsID, 0, false, true);
										});

										if(screenfull.isFullscreen == false)
										{
											cactus_vimeo_player_Ads[index].api("seekTo", 0);
											cactus_vimeo_player_Ads[index].api("play");
										}
										else
										{
											$global_this.find('.'+cactusVideoAds+' iframe#player-vimeo-' + index).remove();
											$this.find('.'+cactusVideoAds).css({"display":"none"});
											cactus_player[index].get(0).play();
										}

										isVimeoPlayback = true;


									}
									else if(videoAdsSource == 'self-hosted')
									{
										playbackVideoAdsURL = playbackVideoAdsSource != '' && playbackVideoAdsSource == 'self-hosted' ? playbackVideoAds : videoAds;
										playbackVideoAdsID 	= playbackVideoAdsSource != '' && playbackVideoAdsSource == 'self-hosted' ? playbackAdsID : adsID;

										$this.find('.'+cactusVideoAds+' video#player-html5-' + index).attr('src', playbackVideoAdsURL);
										$this.find('.'+cactusVideoAds).find('.linkads').attr("href", playbackDataLinkRedirect);							

										cactus_html5_player_Ads[index] = $this.find('.'+cactusVideoAds).find('.wp-video-shortcode');

										if(screenfull.isFullscreen == false)
										{
											cactus_html5_player_Ads[index].get(0).play();
										}
										else
										{
											cactus_html5_player_Ads[index].get(0).pause();
											$this.find('.'+cactusVideoAds).css({"display":"none"});
											cactus_player[index].get(0).play();
										}

										$this.find('.'+cactusVideoAds).find('.linkads').off('.clickToAds').on('click.clickToAds', function(){
											//ajax track click to first ads here
											ajax_track(playbackVideoAdsID, 0, false, true);
										});
									}
								}
								else if(videoAdsType == '')
								{
									$global_this.find('.'+cactusVideoAds).css({"visibility":"hidden", "display":"none"});
									cactus_player[index].get(0).play();
								}
								else if(videoAdsType == 'adsense')
								{
									if(adsImagePosition == '1')
										$global_this.find('.'+cactusVideoAds).css({"visibility":"hidden", "display":"none"});
									cactus_player[index].get(0).play();
								}
								else
								{
									//ads image
									if(adsImagePosition == '2' || adsImagePosition == '3')
									{
										cactus_player[index].get(0).play();
										playbackVideoAdsID 	= playbackVideoAds != '' && playbackVideoAdsType == 'image' && (playbackAdsImagePosition == '2' || playbackAdsImagePosition == '3' )  ? playbackAdsID : adsID;

										$this.find('.'+cactusVideoAds).find('.banner-img a img.second-img').off('.clickToSecondAds').on('click.clickToSecondAds', function(){
											//ajax track click to second top ads here
											ajax_track(playbackVideoAdsID, 0, false, true);
										});

										$this.find('.'+cactusVideoAds + ' .banner-img .close-banner-button').click(function() {
											ajax_track(playbackVideoAdsID, 0, true, false);
										});
									}
								}

								flag[index] = true;
							}
						};
						setInterval(videoPlayCurrentTime_func,500);
					});
					
					cactus_player[index].get(0).onended = function(e) {
						$this.find('.'+cactusVideoAds).css({"display":"none"});
			            setTimeout(function(){
							if(autoLoadNextVideo != 3)
							{
								var link = $('.prev-post a').attr('href');
								if(autoLoadNextVideoOptions == 1)
								{
									link = jQuery('.next-post a').attr('href');
								}
							}
							else if(autoLoadNextVideo == 3)
							{
								var link = window.location.href;
							}
							var className = $('#tm-autonext span#autonext').attr('class');
							//alert(className);
							if(className!=''){
							  if(link !=undefined){
								  window.location.href= link;
							  }
							}
						},1000);
			        }
				}

				/*Video Youtube Iframe*/

			});

		};//onYouTubeIframeAPIReady


		// $('.'+cactusAllVideoList).find('.'+cactusVideoItem+'[data-source="vimeo"]').each(function(index, element) {

		// });



    });

	function close_button(elements, divVideoAdsId, cactusVideoAds, videoDataLinkRedirect, closeButtonPosition, videoAdsSource)
	{
		var $this=elements;

		if(videoAdsSource == 'youtube')
			$this.find('.'+cactusVideoAds).append('<div id="close-'+divVideoAdsId+'"></div><a class="linkads" href="'+videoDataLinkRedirect+'" target="_blank"></a><div class="hide-pause button-start"></div><div id="'+divVideoAdsId+'"></div>');
		else
		{
			$this.find('.'+cactusVideoAds).append('<div id="close-'+divVideoAdsId+'"></div><a class="linkads" href="'+videoDataLinkRedirect+'" target="_blank"></a></div><div id="'+divVideoAdsId+'"></div>');
		}

		//set up close position
		if(closeButtonPosition == 'right') {
			$this.find('#close-'+divVideoAdsId).css({"right":"0"});
		}
		else {
			$this.find('#close-'+divVideoAdsId).css({"left":"0"});
		}
	}

	function mask_button(elements, cactusVideoAds, videoAdsSource, videoSource)
	{
		var $this=elements;
		if(videoSource == 'youtube') {
			$this.find('.'+cactusVideoAds).append('<div class="hide-pause button-start"></div>');
		}
		else if(videoSource == 'vimeo') {
			$this.find('.'+cactusVideoAds).append('<div class="hide-pause button-start non-icon"></div>');
		}
		else if(videoSource == 'self-hosted') {
			$this.find('.'+cactusVideoAds).append('<div class="hide-pause button-start non-icon"></div>');
		}
	}

	function ajax_track(ads_id, videoAdsCurrentTime, clickCloseButton, clickToAds)
	{
		$.ajax(
		{
		    type:   'post',
		    cache: 	false,
		    url:    cactus.ajaxurl,
		    data:   {
		        'ads_id'   				: ads_id,
		        'videoAdsCurrentTime'   : videoAdsCurrentTime,
		        'clickCloseButton'		: clickCloseButton,
		        'clickToAds'			: clickToAds,
		        'action'				:'cactus_track_time_when_click_close'
		    },
		    success: function(data){}
		});
	}

}(jQuery));