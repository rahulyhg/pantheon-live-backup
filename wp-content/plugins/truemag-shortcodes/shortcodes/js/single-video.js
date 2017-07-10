// JavaScript Document
(function() {
    // Creates a new plugin class and a custom listbox
    tinymce.create('tinymce.plugins.shortcode_single_video', {
        createControl: function(n, cm) {
            switch (n) {                
                case 'shortcode_single_video':
                var c = cm.createSplitButton('shortcode_single_video', {
                    title : 'Single Video',
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
						<label>Post ID <br />\
                        <input type="text" name="postid" value="" /></label>\
                        <label>Show title?<br />\
                        <select name="title">\
							<option value="1">Yes</option>\
							<option value="0">No</option>\
						</select></label>\
						<label>Show author and time?<br />\
                        <select name="author">\
							<option value="1">Yes</option>\
							<option value="0">No</option>\
						</select></label>\
						<label>Show view/like/comment count?<br />\
                        <select name="count">\
							<option value="1">Yes</option>\
							<option value="0">No</option>\
						</select></label>\
						<label>Show excerpt?<br />\
                        <select name="excerpt">\
							<option value="1">Yes</option>\
							<option value="0">No</option>\
						</select></label>\
                        </div></td></tr>');
						  jQuery(document).ready(function() {
							jQuery('#demo').hide();
							jQuery('#picker12').hide();
							jQuery('#color12').click(function(){
								jQuery('#menu_content_content_ct_heading_menu_tbl').css("width","207px");
								jQuery('#picker12').farbtastic('#color12').show();
							});
							jQuery('#color12').focusout(function(){
								jQuery('#menu_content_content_ct_heading_menu_tbl').css("width","auto");
								jQuery('#picker12').farbtastic('#color12').hide();
							});
						  });

                        jQuery('<input type="button" class="button" value="Insert" />').appendTo($menu)
                                .click(function(){
                                var postid = $menu.find('input[name=postid]').val();
								var title = $menu.find('select[name=title]').val();
								var author = $menu.find('select[name=author]').val();
								var count = $menu.find('select[name=count]').val();
								var excerpt = $menu.find('select[name=excerpt]').val();
								
								
								var shortcode = '[singlevideo postid="'+postid+'" title="'+title+'" author="'+author+'" count="'+count+'" excerpt="'+excerpt+'"][/singlevideo]<br class="nc"/>';

                                    tinymce.activeEditor.execCommand('mceInsertContent',false,shortcode);
                                    c.hideMenu();
                                }).wrap('<tr><td><div style="padding: 0 10px 10px"></div></td></tr>')
                 
                        $menu.data('added',true); 

                    });

                   // XSmall
					m.add({title : 'Single Video', 'class' : 'mceMenuItemTitle'}).setDisabled(1);

                 });
                // Return the new splitbig_font_icon instance
                return c;
                
            }
            return null;
        }
    });
    tinymce.PluginManager.add('shortcode_single_video', tinymce.plugins.shortcode_single_video);
})();