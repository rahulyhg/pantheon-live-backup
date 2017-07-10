// JavaScript Document
(function() {
    // Creates a new plugin class and a custom listbox
    tinymce.create('tinymce.plugins.shortcode_big_font_icon', {
        createControl: function(n, cm) {
            switch (n) {                
                case 'shortcode_big_font_icon':
                var c = cm.createSplitButton('shortcode_big_font_icon', {
                    title : 'Icon',
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
                        $menu.append('<tr><td><div style="padding:10px 10px 10px">\
						<label>Icon in font <a style="display:inline;text-decoration:underline !important;cursor:pointer;" href="http://fortawesome.github.io/Font-Awesome/icons/">Awesome</a><br />\
						<i style="font-size:10px;">EX: icon-mobile-phone</i><br/>\
                        <input type="text" name="icon" value="" /></label>\
                        <label>Icon Effect<br />\
						<select name="effect">\
							<option value="effect-1">Effect 1</option>\
							<option value="effect-2">Effect 2</option>\
							<option value="effect-3">Effect 3</option>\
							<option value="effect-4">Effect 4</option>\
							<option value="effect-5">Effect 5</option>\
							<option value="effect-6">Effect 6</option>\
							<option value="effect-7">Effect 7</option>\
							<option value="effect-8">Effect 8</option>\
							<option value="effect-9">Effect 9</option>\
						</select></label>\
						<label>Icon Size (px)<br />\
                        <input type="text" name="size" value="" /></label>\
						<label>Icon Link<br />\
						<i style="font-size:10px;">Include "http://" before your link</i><br/>\
                        <input type="text" name="link" value="" /></label>\
						<label>Icon color<br />\
						<i style="font-size:10px;">EX: #ffffff</i><br/>\
                        <input type="text" name="text_color" value="" /></label>\
						<label>Background color<br />\
						<i style="font-size:10px;">EX: #ffffff</i><br/>\
                        <input type="text" name="bg_color" value="" /></label>\
                        </div></td></tr>');

                        jQuery('<input type="button" class="button" value="Insert" />').appendTo($menu)
                                .click(function(){
                       
                                var uID =  Math.floor((Math.random()*100)+1);
                                var icon = $menu.find('input[name=icon]').val();								
								var effect = $menu.find('select[name=effect]').val();
								var size = ($menu.find('input[name=size]').val()) ? 'size="'+$menu.find('input[name=size]').val()+'"' : '';
								var links = ($menu.find('input[name=link]').val()) ? 'link="'+$menu.find('input[name=link]').val()+'"' : '';
								var text_color = ($menu.find('input[name=text_color]').val()) ? 'text_color="'+$menu.find('input[name=text_color]').val()+'"' : '';
								var bg_color = ($menu.find('input[name=bg_color]').val()) ? 'bg_color="'+$menu.find('input[name=bg_color]').val()+'"' : '';
								
								var shortcode = '[cticon id="icon_'+uID+'" effect="'+effect+'" '+size+' icon="'+icon+'" '+text_color+' '+bg_color+' '+links+'][/cticon]<br class="nc"/>';

                                    tinymce.activeEditor.execCommand('mceInsertContent',false,shortcode);
                                    c.hideMenu();
                                }).wrap('<tr><td><div style="padding: 0 10px 10px"></div></td></tr>')
                 
                        $menu.data('added',true); 

                    });

                   // XSmall
					m.add({title : 'Icon', 'class' : 'mceMenuItemTitle'}).setDisabled(1);

                 });
                // Return the new splitbig_font_icon instance
                return c;
                
            }
            return null;
        }
    });
    tinymce.PluginManager.add('shortcode_big_font_icon', tinymce.plugins.shortcode_big_font_icon);
})();