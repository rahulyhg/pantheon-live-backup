// JavaScript Document
(function() {
    // Creates a new plugin class and a custom listbox
    tinymce.create('tinymce.plugins.shortcode_carousel', {
        createControl: function(n, cm) {
            switch (n) {                
                case 'shortcode_carousel':
                var c = cm.createSplitButton('shortcode_carousel', {
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
                        $menu.append('<div style="padding:0 10px 10px">\
                       	<label>Name<br />\
						<input type="text" name="name" value="" /></label>\
						<label>Number of Item<br />\
						<i style="font-size:10px;">EX: 3</i><br/>\
						<input type="text" name="number_item" value="" /></label>\
						<label>Animation<br />\
						<select name="animation">\
							<option value="">No</option>\
							<option value="top-to-bottom">Top to bottom</option>\
							<option value="bottom-to-top">Bottom to top</option>\
							<option value="left-to-right">Left to right</option>\
							<option value="right-to-left">Right to left</option>\
							<option value="appear">Appear</option>\
						</select></label>\
                        </div>');
							jQuery('<input type="button" class="button" value="Insert" />').appendTo($menu)
                                .click(function(){
                       
                                var uID =  Math.floor((Math.random()*100)+1);
                                var numberitem = $menu.find('input[name=number_item]').val();
								var name = $menu.find('select[name=name]').val();
								var animation = $menu.find('select[name=animation]').val();
								var shortcode = '[carousel  name="'+ name +'" animation="'+animation+'"]<br class="nc"/>';
								for(i=0;i<numberitem;i++){
									j=i+1;
                                        shortcode+= '[carousel_item  title=" Carousel title ' + j + '" width=""]Content here[/carousel_item]<br class="nc"/>';
                                    }
                                shortcode+= '[/carousel]<br class="nc"/>';
								

                                    tinymce.activeEditor.execCommand('mceInsertContent',false,shortcode);
                                    c.hideMenu();
                                }).wrap('<tr><td><div style="padding: 0 10px 10px"></div></td></tr>')
                 
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
    tinymce.PluginManager.add('shortcode_carousel', tinymce.plugins.shortcode_carousel);
})();
