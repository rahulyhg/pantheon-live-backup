// JavaScript Document
(function() {
    tinymce.PluginManager.add('shortcode_heading', function(editor, url) {
		editor.addButton('shortcode_heading', {
			text: '',
			tooltip: 'Heading',
			icon: 'icon-heading',
			onclick: function() {
				// Open window
				editor.windowManager.open({
					title: 'Heading',
					body: [
						{type: 'listbox', 
							name: 'style', 
							label: 'Style', 
							'values': [
								{text: 'Classic', value: 'h-classic'},
								{text: 'Modern', value: 'h-modern'}
							]
						},
						{type: 'textbox', name: 'heading', label: 'Heading'},
						{type: 'textbox', name: 'fonsize', label: 'Font size'}
					],
					onsubmit: function(e) {
						var uID =  Math.floor((Math.random()*100)+1);
						// Insert content when the window form is submitted
						editor.insertContent('[heading  style="'+e.data.style+'"  heading="'+e.data.heading+'" fonsize="'+e.data.fonsize+'"]');
					}
				});
			}
		});
	});
})();
