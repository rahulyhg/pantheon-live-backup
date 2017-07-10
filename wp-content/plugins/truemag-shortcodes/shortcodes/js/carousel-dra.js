// JavaScript Document
(function() {
    // Creates a new plugin class and a custom listbox
    tinymce.create('tinymce.plugins.ct_shortcode_carousel', {
        createControl: function(n, cm) {
            switch (n) {                
                case 'ct_shortcode_carousel':
                var c = cm.createSplitButton('ct_shortcode_carousel', {
                    title : 'Carousel',
                    onclick : function() {
                    }
                });

                c.onRenderMenu.add(function(c, m) {
                    m.onShowMenu.add(function(c,m){
                        jQuery('#menu_'+c.id).height('auto').width('auto');
                        jQuery('#menu_'+c.id+'_co').height('auto').addClass('mceListBoxMenu'); 
                        var $menu = jQuery('#menu_'+c.id+'_co').find('tbody:first');
                        if($menu.data('added')) return;
                        $menu.append('');
                        $menu.append('<div style="padding:0 10px 10px;width:374px">\
						<label>Use slider<br />\
						<select style="width:346px" id="sliderdetail" onchange="sliderchange();" name="sliderdetail" value="flexslider">\
							<option value="flexslider">Flex slider</option>\
							<option value="nivoslider">Nivo slider</option>\
						</select></label><div class="flexslider">\
                        <label>Number of slides<br />\
                        <input type="text" name="itemnum" value="3" onclick="this.select()"  /></label>\
						<label>Interval<br/>\
						<input type="text" name="interval" value="2000" onclick="this.select()"  /></label>\
						<label>Sliding direction<br/>\
						<select name="direction" value="horizontal">\
							<option value="horizontal">Horizontal</option>\
							<option value="vertical">Vertical</option>\
						</select></label>\
						<label>Auto play<br/>\
						<select name="autoplay" value="false">\
							<option value="true">True</option>\
							<option value="false">Flase</option>\
						</select></label>\
						<label>Animation<br/>\
						<select name="animation" value="slide">\
							<option value="slide">Slide</option>\
							<option value="fade">Fade</option>\
						</select></label>\
						<label>Easing effect<br/>\
						<select name="easing" value="swing">\
							<option value="swing">Swing</option>\
							<option value="linear">Linear</option>\
							<option value="jswing">JSwing</option>\
							<option value="easeInQuad">EaseInQuad</option>\
							<option value="easeOutQuad">EaseOutQuad</option>\
							<option value="easeInOutQuad">EaseInOutQuad</option>\
							<option value="easeInCubic">EaseInCubic</option>\
							<option value="easeOutCubic">EaseOutCubic</option>\
							<option value="easeInOutCubic">EaseInOutCubic</option>\
							<option value="easeInQuart">EaseInQuart</option>\
							<option value="easeOutQuart">EaseOutQuart</option>\
							<option value="easeInOutQuart">EaseInOutQuart</option>\
							<option value="easeInQuint">EaseInQuint</option>\
							<option value="easeOutQuint">EaseOutQuint</option>\
							<option value="easeInOutQuint">EaseInOutQuint</option>\
							<option value="easeInSine">EaseInSine</option>\
							<option value="easeOutSine">EaseOutSine</option>\
							<option value="easeInOutSine">EaseInOutSine</option>\
							<option value="easeInExpo">EaseInExpo</option>\
							<option value="easeOutExpo">EaseOutExpo</option>\
							<option value="easeInOutExpo">EaseInOutExpo</option>\
							<option value="easeInCirc">EaseInCirc</option>\
							<option value="easeOutCirc">EaseOutCirc</option>\
							<option value="easeInOutCirc">EaseInOutCirc</option>\
							<option value="easeInElastic">EaseInElastic</option>\
							<option value="easeOutElastic">EaseOutElastic</option>\
							<option value="easeInOutElastic">EaseInOutElastic</option>\
							<option value="easeInBack">EaseInBack</option>\
							<option value="easeOutBack">EaseOutBack</option>\
							<option value="easeInOutBack">EaseInOutBack</option>\
							<option value="easeInBounce">EaseInBounce</option>\
							<option value="easeOutBounce">EaseOutBounce</option>\
							<option value="easeInOutBounce">EaseInOutBounce</option>\
						</select></label>\
						<label>Style<br/>\
						<select name="style" onchange="showitemwidth(this)" value="style-1">\
							<option value="style-1">Style 1 (Big slider)</option>\
							<option value="style-2">Style 2 (Small slider)</option>\
							<option value="style-3">Style 3 (Medium slider)</option>\
							<option value="style-4">Style 4 (Multi-images slider)</option>\
							<option value="style-6">Style 6</option>\
						</select></label>\
						<div class="style1">\
							<label style="margin-bottom:1px;">Top of description<br />\
                        	<input type="text" name="style1top" value="100" onclick="this.select()"  /></label>\
							<label>Left of description<br />\
                        	<input type="text" name="style1left" value="0" onclick="this.select()"  /></label>\
							<label>Width of description<br />\
                        	<input type="text" name="style1width" value="415" onclick="this.select()"  /></label>\
							<label>Height of description<br />\
                        	<input type="text" name="style1height" value="160" onclick="this.select()"  /></label>\
						</div>\
						<div class="style4-itemwidth" style="display:none;">\
						<label>Item width (px - for style 4)<br />\
                        <input type="text" name="itemwidth" value="0" onclick="this.select()"  /></label>\
						<div class="clear"><!-- --></div>\
						<div class="style4-itemmargin" style="display:none;">\
						<label>Item margin (px - for style 4)<br />\
                        <input type="text" name="itemmargin" value="10" onclick="this.select()"  /></label>\
						<div class="clear"><!-- --></div>\
						</div></div>\
						<div class="nivoslider" style="display:none;"><label>Number of Slices (sliding effect)<br />\
                        <input type="text" name="slices" value="15" onclick="this.select()" /></label>\
						<label>Box Cols (Box Effect)<br />\
                        <input type="text" name="boxCols" value="8" onclick="this.select()" /></label>\
						<label>Box Rows (Box Effect)<br />\
                        <input type="text" name="boxRows" value="4" onclick="this.select()" /></label>\
						<label>Animation Speed (milliseconds)<br />\
                        <input type="text" name="animSpeed" value="500" onclick="this.select()" /></label>\
						<label>Pause Time (milliseconds)<br />\
                        <input type="text" name="pauseTime" value="3000" onclick="this.select()" /></label>\
						<label>Show Direction Nav<br />\
                        <select name="directionNav" value="1">\
							<option value="1">Yes</option>\
							<option value="0">No</option>\
						</select></label>\
						<label>Show Control Nav<br />\
                        <select name="controlNav" value="1">\
							<option value="1">True</option>\
							<option value="0">False</option>\
						</select></label>\
						<label>Use Control Nav Thumbs<br />\
                        <select name="controlNavThumbs" value="0">\
							<option value="1">Yes</option>\
							<option value="0">No</option>\
						</select></label>\
						<label>Pause On Hover<br />\
                        <select name="pauseOnHover" value="1">\
							<option value="1">Yes</option>\
							<option value="0">No</option>\
						</select></label>\
						<label>Auto Play<br />\
                        <select name="manualAdvance" value="0">\
							<option value="0">Yes</option>\
							<option value="1">No</option>\
						</select></label>\
						<label>Effect<br />\
                        <select name="effect" value="random">\
							<option value="random">Random</option>\
							<option value="sliceDown">Slice Down</option>\
							<option value="sliceDownLeft">Slice Down Left</option>\
							<option value="sliceUp">Slice Up</option>\
							<option value="sliceUpLeft">Slice Up Left</option>\
							<option value="sliceUpDown">Slice Up Down</option>\
							<option value="sliceUpDownLeft">Slice Up Down Left</option>\
							<option value="fold">Fold</option>\
							<option value="fade">Fade</option>\
							<option value="slideInRight">Slide In Right</option>\
							<option value="slideInLeft">Slide In Left</option>\
							<option value="boxRandom">Box Random</option>\
							<option value="boxRain">Box Rain</option>\
							<option value="boxRainReverse">Box Rain Reverse</option>\
							<option value="boxRainGrow">Box Rain Grow</option>\
							<option value="boxRainGrowReverse">Box Rain Grow Reverse</option>\
						</select></label><div class="clear"><!-- --></div>\
                        </div><div class="clear"><!-- --></div>');

                        jQuery('<input type="button" class="button" value="Insert" />').appendTo($menu)
                                .click(function(){
                         /**
                          * Shortcode markup
                          * -----------------------
                          * [carousel id="MyCarousel" animation="slide" easing="" interval="2000" direction="horizontal"]
						  *		[slide]<img src="slider1.jpg"/><p>some text here</p>[/slide]
						  *		[slide]<img src="slider2.jpg"/><p>some text here</p>[/slide]
						  *		[slide]<img src="slider3.jpg"/><p>some text here</p>[/slide]
						  *		[slide]<img src="slider4.jpg"/><p>some text here</p>[/slide]
						  * [/carousel]
                          *  -----------------------
                          */
                                var uID =  Math.floor((Math.random()*100)+1);
                                var num = $menu.find('input[name=itemnum]').val();
								var easing = $menu.find('select[name=easing]').val();
								var animation = $menu.find('select[name=animation]').val();
								var interval = $menu.find('input[name=interval]').val();
								var direction = $menu.find('select[name=direction]').val();
								var style = $menu.find('select[name=style]').val();
								var itemwidth = $menu.find('input[name=itemwidth]').val();
								var itemmargin = $menu.find('input[name=itemmargin]').val();
								var sliderdetail = $menu.find('select[name=sliderdetail]').val();
								
								var slices = $menu.find('input[name=slices]').val() ;
								var boxCols = $menu.find('input[name=boxCols]').val() ;
								var boxRows = $menu.find('input[name=boxRows]').val() ;
								var animSpeed = $menu.find('input[name=animSpeed]').val();
								var pauseTime = $menu.find('input[name=pauseTime]').val() ;
								var directionNav = $menu.find('select[name=directionNav]').val();
								var controlNav = $menu.find('select[name=controlNav]').val() ;
								var controlNavThumbs = $menu.find('select[name=controlNavThumbs]').val();
								var pauseOnHover = $menu.find('select[name=pauseOnHover]').val() ;
								var manualAdvance = $menu.find('select[name=manualAdvance]').val() ;
								var effect = $menu.find('select[name=effect]').val();
								var autoplay = $menu.find('select[name=autoplay]').val();
								
								var style1top = $menu.find('input[name=style1top]').val();
								var style1left = $menu.find('input[name=style1left]').val();
								var style1width = $menu.find('input[name=style1width]').val();
								var style1height = $menu.find('input[name=style1height]').val();
								
								var nivo = '';
								if(sliderdetail == 'nivoslider'){
									var nivo = ' slices="'+slices+'" boxCols="'+boxCols+'" boxRows="'+boxRows+'" animSpeed="'+animSpeed+'" pauseTime="'+pauseTime+'" directionNav="'+directionNav+'" controlNav="'+controlNav+'" controlNavThumbs="'+controlNavThumbs+'" pauseOnHover="'+pauseOnHover+'" manualAdvance="'+manualAdvance+'" effect="'+effect+'" ';
								}

								var shortcode = '[carousel id="carousel_'+uID+'" animation="' + animation + '" easing="' + easing + '" interval="' + interval + '" direction="' + direction + '" style="'+style+'"  ' + (style=='style-4' ? ('itemwidth="'+itemwidth+'" itemmargin="' + itemmargin + '" ') : '') + nivo + ' sliderdetail="'+sliderdetail+'" ' + (style=='style-1'? ('style1top="'+style1top+' " style1left="'+style1left+'" style1width="'+style1width+'" style1height="'+style1height+'"') : '') + ' autoplay="'+autoplay+'"]<br class="nc"/>';
                                    for(i=0;i<num;i++){
                                        shortcode+= '[ctslide]<br class="nc"/><img src="your-image-' + i +'.jpg" title="Your image title"/>[ctdesc]Image description here[/ctdesc]';
                                        shortcode += '[/ctslide]<br class="nc"/>';
                                    }
                                shortcode+= '[/carousel]<br class="nc"/>';

                                    tinymce.activeEditor.execCommand('mceInsertContent',false,shortcode);
                                    c.hideMenu();
                                }).wrap('<div style="padding: 0 10px 10px"></div>')
                 
                        $menu.data('added',true); 

                    });

                   // XSmall
					m.add({title : 'Carousel', 'class' : 'mceMenuItemTitle'}).setDisabled(1);

                 });
                // Return the new splitbutton instance
                return c;
                
            }
            return null;
        }
    });
    tinymce.PluginManager.add('ct_shortcode_carousel', tinymce.plugins.ct_shortcode_carousel);
})();

function sliderchange(){
	if(jQuery('#sliderdetail').val() == 'nivoslider'){		
		jQuery('.flexslider').css('display', 'none');
		jQuery('.nivoslider').css('display', 'block');
	}else{
		jQuery('.flexslider').css('display', 'block');
		jQuery('.nivoslider').css('display', 'none');		
	}
}

function showitemwidth(obj){
	jQuery('.style1').hide();
	jQuery('.style4-itemwidth').hide();
	jQuery('.style4-itemmargin').hide();
	if(jQuery(obj).val() == 'style-1'){		
		jQuery('.style1').show();
	}else if(jQuery(obj).val() == 'style-4'){		
		jQuery('.style4-itemwidth').show();
		jQuery('.style4-itemmargin').show();
	}
}
