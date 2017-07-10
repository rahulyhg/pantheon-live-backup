// JavaScript Document
(function() {
    // Creates a new plugin class and a custom listbox
    tinymce.create('tinymce.plugins.shortcode_boxed', {
        createControl: function(n, cm) {
            switch (n) {                
                case 'shortcode_boxed':
                var c = cm.createSplitButton('shortcode_boxed', {
                    title : 'Boxed Icon',
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
						<label>Number of Item<br />\
						<i style="font-size:10px;">EX: 3</i><br/>\
						<input type="text" name="number_item" value="" /></label>\
                       	<label>Style<br />\
						<select name="style">\
							<option value="style-1">Fancy Box</option>\
							<option value="style-2">Solid Box</option>\
							<option value="style-3">Classic Box</option>\
						</select></label>\
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
								var style = $menu.find('select[name=style]').val();
								var animation = $menu.find('select[name=animation]').val();
								var shortcode = '[boxed style='+ style +' animation="'+animation+'"]<br class="nc"/>';
								for(i=0;i<numberitem;i++){
									j=i+1;
                                        shortcode+= '[boxed_item  title="Boxed title ' + j + '" link="Boxed link '+j+' " color_tt="" bg_ttcolor="" bg_color="" icon="icon '+j+'"]Content here[/boxed_item]<br class="nc"/>';
                                    }
                                shortcode+= '[/boxed]<br class="nc"/>';
								

                                    tinymce.activeEditor.execCommand('mceInsertContent',false,shortcode);
                                    c.hideMenu();
                                }).wrap('<tr><td><div style="padding: 0 10px 10px"></div></td></tr>')
                 
                        $menu.data('added',true); 

                    });
                   // XSmall
					m.add({title : 'Boxed Icon', 'class' : 'mceMenuItemTitle'}).setDisabled(1);

                 });
                // Return the new splitbutton instance
                return c;
                
            }
            return null;
        }
    });
    tinymce.PluginManager.add('shortcode_boxed', tinymce.plugins.shortcode_boxed);
})();
