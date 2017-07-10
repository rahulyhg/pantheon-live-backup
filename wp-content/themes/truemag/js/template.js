// JavaScript Document
jQuery(document).ready(function(e) {
	jQuery( 'div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)' ).addClass( 'buttons_added' ).append( '<input type="button" value="+" id="add" class="plus" />' ).prepend( '<input type="button" value="-" id="minus" class="minus" />' );
	jQuery('.buttons_added #minus').click(function(e) {
		var value = parseInt(jQuery(this).next().val()) - 1;
		if(value>0){
			jQuery(this).next().val(value);
		}
    });
	jQuery('.buttons_added #add').click(function(e) {
		var value = parseInt(jQuery(this).prev().val()) + 1;
		jQuery(this).prev().val(value);
    });

	jQuery("[data-toggle=tooltip]").tooltip();
	jQuery(".gptooltip").tooltip();
	jQuery('.qv_tooltip').tooltipster({
                contentAsHTML: true,
				position: 'right',
				interactive: true,
				fixedWidth:250,
				//theme: 'tm-quickview'
            });
	jQuery(window).scroll(function(e){
	   if(jQuery(document).scrollTop()>jQuery(window).height()){
		   jQuery('a#gototop').removeClass('notshow');
	   }else{
		   jQuery('a#gototop').addClass('notshow');
	   }
	});
	//fix body wrap scroll
	jQuery('#body-wrap').scroll(function(e){
	   if(jQuery('#body-wrap').scrollTop()>jQuery(window).height()){
		   jQuery('a#gototop').removeClass('notshow');
	   }else{
		   jQuery('a#gototop').addClass('notshow');
	   }
	});
	// Amazing
    jQuery('.amazing').each(function(){
		var carousel_auto = jQuery(this).data('notauto')?false:true;
		var carousel_auto_timeout = jQuery(this).data('auto_timeout')?jQuery(this).data('auto_timeout'):3000;
		var carousel_auto_duration = jQuery(this).data('auto_duration')?jQuery(this).data('auto_duration'):800;
		$amazingslider = jQuery(this).find('.inner-slides').carouFredSel({
			items               : {visible:1,width:1000},
			responsive  : true,
			direction           : "left",
			scroll : {
				items           : 1,
				easing          : "quadratic",
				duration        : 1000,                         
				pauseOnHover    : false,
				onBefore: function(data){
					jQuery(this).delay(500);
					
					elements = jQuery(data.items.old).find('.element');
					
					for(i = 0; i < elements.length; i++){
						element = elements[(data.scroll.direction == 'next') ? i : (elements.length - 1 - i)];
						jQuery(element).addClass('move-' + ((data.scroll.direction == 'next') ? 'left':'right') + ' move-delay-' + i);
					}
					
					if($bgslider){
						// get index of current item
						index = jQuery(this).triggerHandler("currentPosition");
						//alert(index);
						setTimeout(function(){$bgslider.trigger('slideTo',index)},200);
						
					}
				},
				onAfter: function(data){
					jQuery(data.items.old).find('.element').removeClass('move-left move-right move-delay-0 move-delay-1 move-delay-2');
					css_index = 1;
					$bgslider.trigger("configuration", ["items.height", (jQuery('.slide:nth-child('+css_index+')',this).outerHeight())]);
					jQuery('.amazing .carousel-button a').height(jQuery('.slide:nth-child('+css_index+')',this).outerHeight());
					jQuery('.amazing .carousel-button a').css('line-height',(jQuery('.slide:nth-child('+css_index+')',this).outerHeight())+'px');
				}
			},
			auto 	: {
				play	: carousel_auto,
				timeoutDuration : carousel_auto_timeout,
				duration        : carousel_auto_duration,
				pauseOnHover: "immediate-resume"
			},
			prev:{button:jQuery('.amazing .prev')},
			next:{button:jQuery('.amazing .next')},
			pagination:{container:'.carousel-pagination'}
		});
		
		$bgslider = jQuery(this).find('.bg-slides').carouFredSel({
			items               : {visible:1,height:500,width:1000},
			responsive  : true,
			direction           : "left",
			scroll : {
				items           : 1,
				easing          : "swing",
				duration        : 1500
			},
			align : 'left',
			width :'100%',
			auto : {
				play: false
			}
		});
	});

	jQuery('span#click-more').toggle(function(){
			  jQuery('#top-carousel').removeClass('more-hide');
			  jQuery('span#click-more i').removeClass('fa-angle-down');
			  jQuery('span#click-more i').addClass('fa-angle-up');
	},
	function(){
			  jQuery('#top-carousel').addClass('more-hide');
			  jQuery('span#click-more i').removeClass('fa-angle-up');
			  jQuery('span#click-more i').addClass('fa-angle-down');
	});	
	jQuery('.action-like').click(function(){
			  jQuery(this).addClass('change-color');
			  jQuery('.action-unlike').removeClass('change-color');
	});	
	jQuery('textarea#comment').click(function(){
			  jQuery('.cm-form-info').addClass('cm_show');
			  jQuery('p.form-submit').addClass('form_heig');
	});	
	jQuery('.action-unlike').click(function(){
			  jQuery(this).addClass('change-color');
			  jQuery('.action-like').removeClass('change-color');
	});
	
	//toggle search box
	jQuery(document).mouseup(function (e){
		var container = jQuery(".headline-search");
		if (!container.is(e.target) // if the target of the click isn't the container...
			&& container.has(e.target).length === 0) // ... nor a descendant of the container
		{
			jQuery('.search-toggle').removeClass('toggled');
			jQuery('.headline-search').removeClass('toggled');
			
			jQuery('.searchtext .suggestion',container).hide();
			
			return true;
		}
		return true;
	});
	jQuery('.search-toggle').click(function(){
			  jQuery(this).toggleClass('toggled');
			  jQuery('.headline-search').toggleClass('toggled');
			  return false;
	});
	jQuery(".is-carousel").each(function(){
		var carousel_id = jQuery(this).attr('id');
		var carousel_effect = jQuery(this).data('effect')?jQuery(this).data('effect'):'scroll';
		var carousel_auto = jQuery(this).data('notauto')?false:true;
		var carousel_auto_timeout = jQuery(this).data('auto_timeout')?jQuery(this).data('auto_timeout'):3000;
		var carousel_auto_duration = jQuery(this).data('auto_duration')?jQuery(this).data('auto_duration'):800;
		var carousel_pagi = jQuery(this).data('pagi')?jQuery(this).data('pagi'):false;
		smartboxcarousel = jQuery(this).find(".smart-box-content");
		if(smartboxcarousel.length){
			if(jQuery(this).hasClass('smart-box-style-3-2')){
				smart_visible = {
					min         : 1,
					max         : 3
				}
				if(jQuery(window).width()<=768){
					smart_visible = 1;
				}
				smart_width  = 380;
				smart_onTouch = true;
			}else{ 
				smart_visible = 1;
				smart_width  = 750;
				smart_onTouch = false;
			}
			smcarousel = smartboxcarousel.carouFredSel({
				responsive  : true,
				items       : {
					visible	: smart_visible,
					width       : smart_width,
					height      : "variable"
				},
				circular: true,
				infinite: true,
				width 	: "100%",
				height	: "variable",
				auto 	: false,
				align 	: "left",
				prev	: {	
					button	: "#"+carousel_id+" .prev",
					key		: "left"
				},
				next	: { 
					button	: "#"+carousel_id+" .next",
					key		: "right"
				},
				scroll : {
					items : 1,
					fx : carousel_effect
				},
				swipe       : {
					onTouch : smart_onTouch,
					onMouse : false,
					items	: 1
				},
				pagination 	: '#'+carousel_pagi
			}).imagesLoaded( function() {
				smcarousel.trigger("updateSizes");
			});
		}//if length
		
		featuredboxcarousel = jQuery(this).find(".featured-box-carousel-content");
		if(featuredboxcarousel.length){
			ftcarousel = featuredboxcarousel.carouFredSel({
				responsive  : true,
				items       : {
					width 	: 700,
					visible	: 1
				},
				circular: true,
				infinite: true,
				width 	: "100%",
				height	: "variable",
				auto 	: false,
				align 	: "center",
				scroll : {
					items : 1,
					fx : carousel_effect,
					onBefore: function() {
						var pos = jQuery(this).triggerHandler( 'currentPosition' );
						featured_ID = jQuery(this).data('featured-id');
						cat_ID = jQuery(this).data('id');
						jQuery('#featured-content-box-'+featured_ID+' .item-cat-'+cat_ID).removeClass( 'selected' );
						jQuery('#featured-content-box-'+featured_ID+' .item-cat-'+cat_ID+'-'+pos).addClass( 'selected' );
					}
				},
				swipe       : {
					onTouch : true,
					onMouse : false,
					items	: 1
				},
				pagination 	: '#'+carousel_pagi
			}).imagesLoaded( function() {
				ftcarousel.trigger("updateSizes");
			});
		}//if length
		
		//top carousel
		topcarousel = jQuery(this).find(".carousel-content");
		if(topcarousel.length){
			if(carousel_id=='big-carousel'){
				visible = 3;
				align = "center";
				start = -1;
			}else{
				visible = 0;
				align = false;
				start = 0;
			}
			tcarousel = topcarousel.carouFredSel({
				responsive  : false,
				items       : {
					visible	: function(visibleItems){
						if(visible>0){
							if(visibleItems>=3){
								return 5;
							}else{
								return 3;
							}
						}else{return visibleItems+1;}
					},
					minimum	: 1,
					start : start,
				},
				circular: true,
				infinite: true,
				width 	: "100%",
				auto 	: {
					play	: carousel_auto,
					timeoutDuration : carousel_auto_timeout,
					duration        : carousel_auto_duration,
					pauseOnHover: "immediate-resume"
				},
				align	: align,
				prev	: {	
					button	: "#"+carousel_id+" .prev",
					key		: "left"
				},
				next	: { 
					button	: "#"+carousel_id+" .next",
					key		: "right"
				},
				scroll : {
					items : 1,
					duration : carousel_auto_duration,
					fx : "scroll",
					easing : 'quadratic',
					onBefore : function( data ) {
						jQuery(".video-item").removeClass('current-carousel-item').removeClass('current-carousel-item2');
						var current_item_count=0;
						data.items.visible.each(function(){
							current_item_count++;
							if(current_item_count==2){jQuery(this).addClass( "current-carousel-item2" );}
							jQuery(this).addClass( "current-carousel-item" );
						});
					}
				},
				swipe       : {
					onTouch : false,
					onMouse : false,
				}
			}).imagesLoaded( function() {
				tcarousel.trigger("updateSizes");
				tcarousel.trigger("configuration", {
						items       : {
							visible	: function(visibleItems){
								if(visible>0){
									if(visibleItems>=3){
										return 5;
									}else{
										return 3;
									}
								}else{return visibleItems+1;}
							},
						},
					}
				);
			});
			topcarousel.swipe({
				allowPageScroll : 'vertical',
				excludedElements:"",
				tap:function(event, target) {
					if( event.button == 2 ) {
						return false; 
					}
					tapto = jQuery(target);
					if(tapto.attr('href')){
						window.location = tapto.attr('href');
					}else if(tapto.parent().attr('href')){
						window.location = tapto.parent().attr('href');
					}
					return true;
				},
				swipeStatus:function(event, phase, direction, distance, duration, fingers)
				{
				  //phase : 'start', 'move', 'end', 'cancel'
				  //direction : 'left', 'right', 'up', 'down'
				  //distance : Distance finger is from initial touch point in px
				  //duration : Length of swipe in MS 
				  //fingerCount : the number of fingers used
				  if(phase=='move'){
					  if(direction=='left'||direction=='right'){
						  jQuery(this).css('transform','translateX('+(direction=='left'?'-':'')+distance+'px)');
					  }
				  }
				  if(phase=='end'){
					  item_to_next = distance>520?2:1;
					  direction_to_next = direction=='left'?'next':'prev';
					  if(distance>20){
					  	jQuery(this).trigger(direction_to_next,item_to_next);
					  }
					  jQuery(this).css('transform','translateX(0px)');
				  }
				}
			});
			jQuery(".carousel-content").trigger("currentVisible", function( current_items ) {
				var current_item_count=0;
				current_items.each(function(){
					current_item_count++;
					if(current_item_count==2){jQuery(this).addClass( "current-carousel-item2" );}
					jQuery(this).addClass( "current-carousel-item" );
				});
			});
		}//if length
		//classy carousel
		classycarousel = jQuery(this).find(".classy-carousel-content");
		if(classycarousel.length){
			if(carousel_id=='stage-carousel'){
				cscarousel = classycarousel.carouFredSel({
					responsive  : true,
					items       : {
						visible	: 1,
						minimum	: 1,
						width   : 740,
						height  : "variable"
					},
					circular: true,
					infinite: true,
					width 	: "100%",
					auto 	: {
						play	: carousel_auto,
						timeoutDuration : carousel_auto_timeout,
						duration        : carousel_auto_duration,
						pauseOnHover: "immediate-resume"
					},
					align	: "center",
					scroll : {
						items : 1,
						duration : carousel_auto_duration,
						fx : "scroll",
						easing : 'quadratic',
						onBefore: function() {
							var pos = jQuery(this).triggerHandler( 'currentPosition' );
							jQuery('.classy-carousel-content .video-item').removeClass( 'selected' );
							jQuery('.classy-carousel-content .video-item.item'+pos).addClass( 'selected' );
							var page = Math.floor( pos / 1 );
							jQuery('#control-stage-carousel .classy-carousel-content').trigger( 'slideToPage', page );
						}
					},
					swipe       : {
						onTouch : true,
						onMouse : false,
						items	: 1
					}
				}).imagesLoaded( function() {
					cscarousel.trigger("updateSizes");
				});
			}else{
				if(carousel_id=='control-stage-carousel-horizon'){
					c_height = 116;
					c_direction = 'left';
					c_width = "100%";
				}else{
					if(jQuery(window).width()<768){
						c_height = 198;
					}else{
						c_height = 363;
					}
					c_direction = 'up';
					c_width = "variable";
				}
				ccarousel = classycarousel.carouFredSel({
					responsive  : false,
					items       : {
						visible	: function(visibleItems){
							return visibleItems+1;
						},
						minimum	: 1
					},
					direction: c_direction,
					circular: true,
					infinite: true,
					width 	: c_width,
					height: c_height,
					auto 	: false,
					prev	: {	
						button	: "#"+carousel_id+" .control-up"
					},
					next	: { 
						button	: "#"+carousel_id+" .control-down"
					},
					align	: false,
					scroll : {
						items : 1,
						fx : "scroll",
						duration : carousel_auto_duration,
						onBefore : function( data ) {
							jQuery(".video-item").removeClass('current-carousel-item').removeClass('current-carousel-item2');
							var current_item_count=0;
							data.items.visible.each(function(){
								current_item_count++;
								if(current_item_count==1){jQuery(this).addClass( "current-carousel-item2" );}
								jQuery(this).addClass( "current-carousel-item" );
							});
						}
					},
					swipe       : {
						onTouch : true,
						onMouse : false,
						items	: 1
					}
				}).imagesLoaded( function() {
					ccarousel.trigger("updateSizes");
				});
				jQuery(this).find(".classy-carousel-content").children().each(function(i) {
					jQuery(this).addClass( 'item'+i );
					//for iOS
					jQuery(this).on('touchend',function(){
						jQuery('#stage-carousel .classy-carousel-content').trigger( 'slideTo', [i, 0, true] );
						return false;
					});
					jQuery(this).click(function() {
						jQuery('#stage-carousel .classy-carousel-content').trigger( 'slideTo', [i, 0, true] );
						return false;
					});
				});
			}
			jQuery(".classy-carousel-content").trigger("currentVisible", function( current_items ) {
				var current_item_count=0;
				current_items.each(function(){
					current_item_count++;
					if(current_item_count==1){jQuery(this).addClass( "selected" );}
				});
			});
		}//if length
		
		simplecarousel = jQuery(this).find(".simple-carousel-content");
		if(simplecarousel.length){
			scarousel = simplecarousel.carouFredSel({
				responsive  : true,
				items       : {
					visible	: 1,
					width       : 365,
					height      : "variable"
				},
				circular: true,
				infinite: true,
				width 	: "100%",
				auto 	: {
					play	: carousel_auto,
					timeoutDuration : 2600,
					duration        : 600,
					pauseOnHover	: 'resume'
				},
				align	: 'center',
				pagination  : "#"+carousel_id+" .carousel-pagination",
				scroll : {
					items : 1,
					fx : "scroll",
				},
				swipe       : {
					onTouch : true,
					onMouse : false,
					items	: 1
				}
			}).imagesLoaded( function() {
				scarousel.trigger("updateSizes");
			});
		}//if length
	});//each
	
	//playlist
	jQuery('.playlist-header #control-stage-carousel .video-item').click(function(e) {
		jQuery('.playlist-header iframe').each(function(index, element) {
			jQuery(this).attr('src', jQuery(this).attr('src'));
		});
	});
	
	jQuery(document).on("click",".item-content-toggle i",function(e){
		jQuery(this).parent().parent().find('.item-content').toggleClass('toggled');
		jQuery(this).parent().find('.item-content-gradient').toggleClass('hidden');
		jQuery(this).toggleClass('fa-flip-vertical');
	});
	
	/*jQuery(document).on("click",".off-canvas-toggle",function(e){
        jQuery("#wrap").addClass('mnopen');
		jQuery("body").addClass('mnopen');
		if(jQuery("body").hasClass('old-android')){
			jQuery(window).scrollTop(0);
		}
    });*/
	jQuery(document).on("click",".wrap-overlay",function(e){
		jQuery("#wrap").removeClass('mnopen');
		jQuery("body").removeClass('mnopen');
	});
	jQuery(document).on("click",".canvas-close",function(e){
		jQuery("#wrap").removeClass('mnopen');
		jQuery("body").removeClass('mnopen');
		return false;
	});
	
	/*featured content box*/
	jQuery(".featured-control a").each(function(i) {
		jQuery(this).click(function() {
			featured_ID = jQuery(this).closest('.featured-control').data('featured-id');
			featured_cat_ID = jQuery(this).closest('.featured-control').data('id');
			pos = jQuery(this).data('pos');
			jQuery('#'+featured_cat_ID+featured_ID+' .featured-box-carousel-content').trigger( 'slideTo', [pos, 0, true] );
			return false;
		});
	});
	
	//hammer
	/*
	var hammertime = jQuery("#wrap").hammer({
		dragup: false,
		dragdown: false,
		transform: false,
		rotate: false,
		pinch: false
	});
	hammertime.on("swiperight", function(ev) {
		jQuery("#wrap").addClass('mnopen');
		jQuery("body").addClass('mnopen');
	});
	hammertime.on("dragright", function(ev) {
		if(ev.gesture['deltaX']<=350&&ev.gesture['deltaX']>20){
			jQuery("#wrap").css('margin-left',ev.gesture['deltaX']+'px');
			jQuery("body").addClass('mnopen');
			jQuery("#wrap").addClass('dragging');
		}
	});
	hammertime.on("dragend", function(ev) {
		jQuery("#wrap").removeClass('dragging');
		if(ev.gesture['deltaX']>80){
			//jQuery("#wrap").animate({left: "400px",width:(jQuery(window).width())+'px',marginLeft:0}, 300);
			jQuery("#wrap").css('margin-left',0);
			jQuery("#wrap").addClass('mnopen');
		}else{
			//jQuery("#wrap").animate({left: "0",width: "100%",marginLeft:0}, 300);
			jQuery("#wrap").css('margin-left',0);
			jQuery("#wrap").removeClass('mnopen');
			jQuery("body").removeClass('mnopen');
		}
	});
	
	*/
	if(typeof(off_canvas_enable) != "undefined" && off_canvas_enable){
		var toggle = jQuery(".off-canvas-toggle").hammer({
			drag: false,
			transform: false,
			rotate: false,
			pinch: false
		});
		toggle.on("touch", function(ev) {
			jQuery("#off-canvas a").bind('click', false);
			jQuery("#wrap").toggleClass('mnopen');
			jQuery("body").toggleClass('mnopen');
			if(jQuery("body").hasClass('old-android')){
				jQuery(window).scrollTop(0);
			}
			setTimeout(function(){
				jQuery("#off-canvas a").unbind('click', false);
				jQuery(document).on("click",".canvas-close",function(e){
				  jQuery("#wrap").removeClass('mnopen');
				  jQuery("body").removeClass('mnopen');
				  return false;
				});
			},1100);
		});
		var wrap = jQuery("#wrap").hammer({
			transform: false,
			rotate: false,
			pinch: false,
			stop_browser_behavior: false
		});
		wrap.on("swiperight", function(ev) {
			jQuery("#wrap").addClass('mnopen');
			jQuery("body").addClass('mnopen');
			if(jQuery("body").hasClass('old-android')){
				jQuery(window).scrollTop(0);
			}
		});
		wrap.on("dragright", function(ev) {
			if(ev.gesture['deltaX']>120){
				jQuery("#wrap").addClass('mnopen');
				jQuery("body").addClass('mnopen');
			}
		});
		
		var wrap_overlay = jQuery(".mnopen .wrap-overlay").hammer({
			drag: false,
			transform: false,
			rotate: false,
			pinch: false
		});
		wrap_overlay.on("touch", function(ev) {
			jQuery("#wrap").removeClass('mnopen');
			jQuery("body").removeClass('mnopen');
		});
		wrap_overlay.on("swipeleft", function(ev) {
			jQuery("#wrap").removeClass('mnopen');
			jQuery("body").removeClass('mnopen');
		});
	}
	//top carousel swipe
	var top_carousel_hammer = jQuery('.is-carousel:not(#big-carousel):not(#metro-carousel):not(#control-stage-carousel):not(#control-stage-carousel-horizon) .carousel-content').hammer({
		transform: false,
		rotate: false,
		pinch: false
	});
	top_carousel_hammer.on("swiperight", function(ev) {;
		jQuery('.is-carousel .carousel-content').trigger('prev',1);
	});
	top_carousel_hammer.on("swipeleft", function(ev) {;
		jQuery('.is-carousel .carousel-content').trigger('next',1);
	});
	//list style change
	jQuery('.style-filter a').click(function(e) {
        var list_style = jQuery(this).data('style');
		if(list_style =='style-list-1'){
			jQuery('.qv_tooltip').tooltipster('disable');
		}else{
			jQuery('.qv_tooltip').tooltipster('enable');
		}
		jQuery('.style-filter a').removeClass('current');
		listing_div = jQuery(this).closest('.video-listing');
		jQuery(this).addClass('current');
		jQuery('.video-listing .video-listing-content').fadeOut("fast","swing",function(){
			jQuery(this).fadeIn('fast');
			listing_div.removeClass().addClass('video-listing '+list_style);
		})
    });
	
	// search textbox
	jQuery('#searchform input.ss').keyup(function(){
		parent = jQuery(this).parent().parent();
		
		if(jQuery('.select',parent).length > 0){
			var w = jQuery(".s-chosen-cat",parent).outerWidth();
			//alert(jQuery('#searchform .searchtext').outerWidth());
			
			jQuery('.searchtext .suggestion',parent).css({'width':(jQuery('.searchtext',parent).outerWidth() - w - 3) +'px'});
		}
		
		if(jQuery(this).val() != ''){
			jQuery('#searchform .searchtext i').removeClass('hide');
		} else {
			jQuery('#searchform .searchtext i').addClass('hide');
		}
	});
	
	jQuery('#searchform .searchtext i').click(function(){
		jQuery('#searchform input.ss').val('');
		jQuery(this).addClass('hide');
	});
	
	//smooth scroll anchor
	jQuery('a[href*=#]:not([href=#])').click(function() {
		if(jQuery(this).hasClass('featured-tab') || jQuery(this).hasClass('ui-tabs-anchor') ||  jQuery(this).hasClass('vc-carousel-control')){
			return true;
		}else if(location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') 
			|| location.hostname == this.hostname) {
			var target = jQuery(this.hash);
			target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +']');
			   if (target.length) {
				jQuery('html,body,#body-wrap').animate({
					 scrollTop: target.offset().top
				}, 660);
				return true;
			}
		}
	});
	
	jQuery('.nav-search-box').focusin(function(e) {
		jQuery(this).addClass('focus');
    });
	jQuery('.nav-search-box').focusout(function(e) {
		jQuery(this).removeClass('focus');
    });
	
	jQuery(document).on('click','#light_box',function(){
		var $url = jQuery(this).data('url');
		if($url){
			jQuery.get( jQuery(this).data('url'), function( data ) {
			});
		}
	});
	
});

jQuery(window).load(function(e) {
	jQuery("#pageloader").addClass('done');
	jQuery(".carousel-content").trigger("updateSizes");
	jQuery(".smart-box-content").trigger("updateSizes");
	jQuery(".classy-carousel-content").trigger("updateSizes");
	jQuery(".simple-carousel-content").trigger("updateSizes");
	jQuery(".featured-box-carousel-content").trigger("updateSizes");
	jQuery("#big-carousel .carousel-content").trigger("configuration", {
			items       : {
				visible	: function(visibleItems){					
					if(visibleItems>=3){
						return 5;
					}else{
						return 3;
					}
				},
			},
		}
	);
	if(jQuery(window).width()<=768){
		jQuery("#metro-carousel .carousel-content").trigger("configuration", {
				align	: "left",
				items       : {
					visible	: 1
				},
			}
		);
		jQuery("#metro-carousel .item-head").css('max-width',jQuery(window).width()+'px');
		jQuery("#metro-carousel .carousel-content > .video-item > .qv_tooltip > .item-thumbnail img, #metro-carousel .carousel-content > .video-item > .item-thumbnail img").each(function(index, element) {
			if(jQuery(this).outerWidth() > jQuery(window).width()){
				var move = (jQuery(this).outerWidth() - jQuery(window).width())/2;
            	jQuery(this).attr("style","transform:translate3d(-"+move+"px,0,0); -webkit-transform:translate3d(-"+move+"px,0,0)");
			}
        });
	}
	if ( typeof $amazingslider !== 'undefined' ) {
		css_index = $amazingslider.triggerHandler("currentPosition") + 1;
		$bgslider.trigger("configuration", ["items.height", (jQuery('.amazing .slide:nth-child('+css_index+')').outerHeight())]);
		jQuery('.amazing .carousel-button a').height(jQuery('.amazing .slide:nth-child('+css_index+')').outerHeight());
		jQuery('.amazing .carousel-button a').css('line-height',(jQuery('.amazing .slide:nth-child('+css_index+')').outerHeight())+'px');
	}
});
jQuery(window).resize(function(){
	if(jQuery(window).width()<=768){
		jQuery("#metro-carousel .carousel-content").trigger("configuration", {
				align	: "left",
				items       : {
					visible	: 1
				},
			}
		);
		
		jQuery(".smart-box-style-3-2 .smart-box-content").trigger("configuration", {
				items       : {
					visible	: 1
				}
			}
		);
	} else {
		jQuery(".smart-box-style-3-2 .smart-box-content").trigger("configuration", {
				items       : {
					visible	: {min: 1,max: 3}
				}
			}
		);
	}
	
	jQuery("#metro-carousel .item-head").css('max-width',jQuery(window).width()+'px');
	jQuery("#metro-carousel .carousel-content > .video-item > .qv_tooltip > .item-thumbnail img, #metro-carousel .carousel-content > .video-item > .item-thumbnail img").each(function(index, element) {
		if(jQuery(this).outerWidth() > jQuery(window).width()){
			var move = (jQuery(this).outerWidth() - jQuery(window).width())/2;
			jQuery(this).attr("style","transform:translate3d(-"+move+"px,0,0); -webkit-transform:translate3d(-"+move+"px,0,0)");
		}else{
			jQuery(this).attr("style","");
		}
	});
	
	if ( typeof $amazingslider !== 'undefined' ) {
		css_index = $amazingslider.triggerHandler("currentPosition") + 1;
		$bgslider.trigger("configuration", ["items.height", (jQuery('.amazing .slide:nth-child('+css_index+')').outerHeight())]);
		jQuery('.amazing .carousel-button a').height(jQuery('.amazing .slide:nth-child('+css_index+')').outerHeight());
		jQuery('.amazing .carousel-button a').css('line-height',(jQuery('.amazing .slide:nth-child('+css_index+')').outerHeight())+'px');
	}
});

//detect android
var ua = navigator.userAgent;
if( ua.indexOf("Android") >= 0 )
{
  var androidversion = parseFloat(ua.slice(ua.indexOf("Android")+8)); 
  if (androidversion < 3.0){
  	jQuery('body').addClass('old-android');
  }
}

/* Change selected text when users select category in Search Form */
function asf_on_change_cat(select_obj){
	jQuery(select_obj).prev().html(jQuery('option:selected',select_obj).text() + '<i class="fa fa-angle-down"></i>');
	// adjust padding-left of textbox
	var w = jQuery(select_obj).prev().outerWidth();
	var search_parent = jQuery(select_obj).parent().parent().parent();

	jQuery('.ss',search_parent).css({'padding-left': w + 10 + 'px'});
	jQuery('.searchtext .suggestion').css({'left':w+'px','width':(jQuery('.searchtext',search_parent).outerWidth() - w - 3) +'px'});	
}

function asf_show_more_tags(obj){
	jQuery(obj).parent().toggleClass('max-height');
}

//side ads
if(typeof(enable_side_ads) != "undefined" && enable_side_ads){
	jQuery(window).load(function(e) {
		bar_height =  jQuery('#wpadminbar').height();
		header_height =  jQuery('header').height();
		fixed_height = jQuery('#top-nav.fixed-nav').height();
		final_top = bar_height + header_height + fixed_height;
		jQuery('.bg-ad').css('top', final_top-jQuery(window).scrollTop());
		jQuery(window).scrollStopped(function(e) {
			if(jQuery(window).scrollTop()<final_top){
				jQuery('.bg-ad').css('top', final_top-jQuery(window).scrollTop());
			}else{
				jQuery('.bg-ad').css('top', bar_height + fixed_height);
			}
		});
		jQuery('#body-wrap').scrollStopped(function(e) {
			if(jQuery('#body-wrap').scrollTop()<final_top){
				jQuery('.bg-ad').css('top', final_top-jQuery('#body-wrap').scrollTop());
			}else{
				jQuery('.bg-ad').css('top', bar_height + fixed_height);
			}
		});
	});
	
	//create event scroll stopped
	jQuery.fn.scrollStopped = function(callback) {           
		jQuery(this).scroll(function(){
			var self = this, $this = jQuery(self);
			if ($this.data('scrollTimeout')) {
			  clearTimeout($this.data('scrollTimeout'));
			}
			$this.data('scrollTimeout', setTimeout(callback,100,self));
		});
	};
}

//buddypress fix
jQuery('div.activity').on( 'click', function(event) {
	var target = jQuery(event.target);
	if ( target.parent().hasClass('show-all') ) {
		target.parent().parent().children('li').removeClass('hidden');
		target.parent().addClass('loading');
		setTimeout( function() {
			target.parent().parent().children('li').fadeIn(200, function() {
				target.parent().remove();
			});
		}, 600 );
		return false;
	}
});

;(function($){
	$(document).ready(function() {
		var scrollBarIndex=[];
		$('.video-listing-content').each(function(index, element) {
			var $this=$(this);
			scrollBarIndex[index] = $this;
			//$.mCustomScrollbar.defaults.scrollButtons.enable=true;
			
			scrollBarIndex[index].mCustomScrollbar({
				theme:"light-2",				
				onInit: function(){
				},			
			});			
			
			$('.control-up', $this.parents('.video-listing')).click(function(){						
				scrollBarIndex[index].mCustomScrollbar('scrollTo','+=180');
			});
			
			$('.control-down', $this.parents('.video-listing')).click(function(){						
				scrollBarIndex[index].mCustomScrollbar('scrollTo','-=180');
			});
			//scrollBarIndex[index].mCustomScrollbar("scrollTo", ($this.find('.cactus-widget-posts-item.active').offset().top - $this.find('.cactus-widget-posts').offset().top)+'px');
			for(var iz=0; iz<=$this.find('.cactus-widget-posts-item').length; iz++) {
				if($this.find('.cactus-widget-posts-item').eq(iz).hasClass('active')){
					if(iz > 0) {
						scrollBarIndex[index].mCustomScrollbar("scrollTo", ($this.find('.cactus-widget-posts-item').eq(iz).outerHeight(true)*(iz)-2)+'px');
					};
					$('.total-video span', $this.parents('.cactus-video-list-content')).text( (iz+1)+'/'+$this.find('.cactus-widget-posts-item').length );
				};
			};		
			$this.parents('.cactus-video-list-content').find('.user-header').click(function(){				
				if($this.parents('.cactus-video-list-content').find('.fix-open-responsive').hasClass('active')) {
					$this.parents('.cactus-video-list-content').find('.fix-open-responsive').removeClass('active');
					$(this).removeClass('active');
				}else{
					$this.parents('.cactus-video-list-content').find('.fix-open-responsive').addClass('active');
					$(this).addClass('active');
				};				
			});				
			//triggerPlayerHashtag($this, index);
		});
		/*Playlist scroll*/
	});
}(jQuery));


/////////////////////////////////////////////////////////////////
/*!
 * imagesLoaded PACKAGED v3.0.4
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */

(function(){"use strict";function e(){}function t(e,t){for(var n=e.length;n--;)if(e[n].listener===t)return n;return-1}function n(e){return function(){return this[e].apply(this,arguments)}}var i=e.prototype;i.getListeners=function(e){var t,n,i=this._getEvents();if("object"==typeof e){t={};for(n in i)i.hasOwnProperty(n)&&e.test(n)&&(t[n]=i[n])}else t=i[e]||(i[e]=[]);return t},i.flattenListeners=function(e){var t,n=[];for(t=0;e.length>t;t+=1)n.push(e[t].listener);return n},i.getListenersAsObject=function(e){var t,n=this.getListeners(e);return n instanceof Array&&(t={},t[e]=n),t||n},i.addListener=function(e,n){var i,r=this.getListenersAsObject(e),o="object"==typeof n;for(i in r)r.hasOwnProperty(i)&&-1===t(r[i],n)&&r[i].push(o?n:{listener:n,once:!1});return this},i.on=n("addListener"),i.addOnceListener=function(e,t){return this.addListener(e,{listener:t,once:!0})},i.once=n("addOnceListener"),i.defineEvent=function(e){return this.getListeners(e),this},i.defineEvents=function(e){for(var t=0;e.length>t;t+=1)this.defineEvent(e[t]);return this},i.removeListener=function(e,n){var i,r,o=this.getListenersAsObject(e);for(r in o)o.hasOwnProperty(r)&&(i=t(o[r],n),-1!==i&&o[r].splice(i,1));return this},i.off=n("removeListener"),i.addListeners=function(e,t){return this.manipulateListeners(!1,e,t)},i.removeListeners=function(e,t){return this.manipulateListeners(!0,e,t)},i.manipulateListeners=function(e,t,n){var i,r,o=e?this.removeListener:this.addListener,s=e?this.removeListeners:this.addListeners;if("object"!=typeof t||t instanceof RegExp)for(i=n.length;i--;)o.call(this,t,n[i]);else for(i in t)t.hasOwnProperty(i)&&(r=t[i])&&("function"==typeof r?o.call(this,i,r):s.call(this,i,r));return this},i.removeEvent=function(e){var t,n=typeof e,i=this._getEvents();if("string"===n)delete i[e];else if("object"===n)for(t in i)i.hasOwnProperty(t)&&e.test(t)&&delete i[t];else delete this._events;return this},i.removeAllListeners=n("removeEvent"),i.emitEvent=function(e,t){var n,i,r,o,s=this.getListenersAsObject(e);for(r in s)if(s.hasOwnProperty(r))for(i=s[r].length;i--;)n=s[r][i],n.once===!0&&this.removeListener(e,n.listener),o=n.listener.apply(this,t||[]),o===this._getOnceReturnValue()&&this.removeListener(e,n.listener);return this},i.trigger=n("emitEvent"),i.emit=function(e){var t=Array.prototype.slice.call(arguments,1);return this.emitEvent(e,t)},i.setOnceReturnValue=function(e){return this._onceReturnValue=e,this},i._getOnceReturnValue=function(){return this.hasOwnProperty("_onceReturnValue")?this._onceReturnValue:!0},i._getEvents=function(){return this._events||(this._events={})},"function"==typeof define&&define.amd?define(function(){return e}):"object"==typeof module&&module.exports?module.exports=e:this.EventEmitter=e}).call(this),function(e){"use strict";var t=document.documentElement,n=function(){};t.addEventListener?n=function(e,t,n){e.addEventListener(t,n,!1)}:t.attachEvent&&(n=function(t,n,i){t[n+i]=i.handleEvent?function(){var t=e.event;t.target=t.target||t.srcElement,i.handleEvent.call(i,t)}:function(){var n=e.event;n.target=n.target||n.srcElement,i.call(t,n)},t.attachEvent("on"+n,t[n+i])});var i=function(){};t.removeEventListener?i=function(e,t,n){e.removeEventListener(t,n,!1)}:t.detachEvent&&(i=function(e,t,n){e.detachEvent("on"+t,e[t+n]);try{delete e[t+n]}catch(i){e[t+n]=void 0}});var r={bind:n,unbind:i};"function"==typeof define&&define.amd?define(r):e.eventie=r}(this),function(e){"use strict";function t(e,t){for(var n in t)e[n]=t[n];return e}function n(e){return"[object Array]"===c.call(e)}function i(e){var t=[];if(n(e))t=e;else if("number"==typeof e.length)for(var i=0,r=e.length;r>i;i++)t.push(e[i]);else t.push(e);return t}function r(e,n){function r(e,n,s){if(!(this instanceof r))return new r(e,n);"string"==typeof e&&(e=document.querySelectorAll(e)),this.elements=i(e),this.options=t({},this.options),"function"==typeof n?s=n:t(this.options,n),s&&this.on("always",s),this.getImages(),o&&(this.jqDeferred=new o.Deferred);var a=this;setTimeout(function(){a.check()})}function c(e){this.img=e}r.prototype=new e,r.prototype.options={},r.prototype.getImages=function(){this.images=[];for(var e=0,t=this.elements.length;t>e;e++){var n=this.elements[e];"IMG"===n.nodeName&&this.addImage(n);for(var i=n.querySelectorAll("img"),r=0,o=i.length;o>r;r++){var s=i[r];this.addImage(s)}}},r.prototype.addImage=function(e){var t=new c(e);this.images.push(t)},r.prototype.check=function(){function e(e,r){return t.options.debug&&a&&s.log("confirm",e,r),t.progress(e),n++,n===i&&t.complete(),!0}var t=this,n=0,i=this.images.length;if(this.hasAnyBroken=!1,!i)return this.complete(),void 0;for(var r=0;i>r;r++){var o=this.images[r];o.on("confirm",e),o.check()}},r.prototype.progress=function(e){this.hasAnyBroken=this.hasAnyBroken||!e.isLoaded;var t=this;setTimeout(function(){t.emit("progress",t,e),t.jqDeferred&&t.jqDeferred.notify(t,e)})},r.prototype.complete=function(){var e=this.hasAnyBroken?"fail":"done";this.isComplete=!0;var t=this;setTimeout(function(){if(t.emit(e,t),t.emit("always",t),t.jqDeferred){var n=t.hasAnyBroken?"reject":"resolve";t.jqDeferred[n](t)}})},o&&(o.fn.imagesLoaded=function(e,t){var n=new r(this,e,t);return n.jqDeferred.promise(o(this))});var f={};return c.prototype=new e,c.prototype.check=function(){var e=f[this.img.src];if(e)return this.useCached(e),void 0;if(f[this.img.src]=this,this.img.complete&&void 0!==this.img.naturalWidth)return this.confirm(0!==this.img.naturalWidth,"naturalWidth"),void 0;var t=this.proxyImage=new Image;n.bind(t,"load",this),n.bind(t,"error",this),t.src=this.img.src},c.prototype.useCached=function(e){if(e.isConfirmed)this.confirm(e.isLoaded,"cached was confirmed");else{var t=this;e.on("confirm",function(e){return t.confirm(e.isLoaded,"cache emitted confirmed"),!0})}},c.prototype.confirm=function(e,t){this.isConfirmed=!0,this.isLoaded=e,this.emit("confirm",this,t)},c.prototype.handleEvent=function(e){var t="on"+e.type;this[t]&&this[t](e)},c.prototype.onload=function(){this.confirm(!0,"onload"),this.unbindProxyEvents()},c.prototype.onerror=function(){this.confirm(!1,"onerror"),this.unbindProxyEvents()},c.prototype.unbindProxyEvents=function(){n.unbind(this.proxyImage,"load",this),n.unbind(this.proxyImage,"error",this)},r}var o=e.jQuery,s=e.console,a=s!==void 0,c=Object.prototype.toString;"function"==typeof define&&define.amd?define(["eventEmitter/EventEmitter","eventie/eventie"],r):e.imagesLoaded=r(e.EventEmitter,e.eventie)}(window);