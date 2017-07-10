// JavaScript Document
(function() {
    tinymce.PluginManager.add('shortcode_button', function(editor, url) {
		editor.addButton('shortcode_button', {
			text: '',
			tooltip: 'Button',
			icon: 'icon-button',
			onclick: function() {
				// Open window
				editor.windowManager.open({
					title: 'Button',
					body: [
						{type: 'textbox', name: 'text', label: 'Button Text'},
						{type: 'textbox', name: 'links', label: 'Button Link'},
						{type: 'textbox', name: 'icon', label: 'Font Icon Awesome'},
						{type: 'listbox', 
							name: 'size', 
							label: 'Button Size', 
							'values': [
								{text: 'Small', value: 'small'},
								{text: 'Big', value: 'big'}
							]
						},
						{type: 'textbox', name: 'bgcolor', label: 'Background Color', value:"#", id: 'newcolorpicker_buttonbg'},
					],
					onsubmit: function(e) {
						var uID =  Math.floor((Math.random()*100)+1);
						// Insert content when the window form is submitted
						editor.insertContent('[ct_button id="button_'+uID+'" size="'+e.data.size+'" link="'+e.data.links+'" icon="'+e.data.icon+'" bg_color="'+e.data.bgcolor+'"]'+e.data.text+'[/ct_button]<br class="nc"/>');
					}
				});
			}
		});
	});
})();
