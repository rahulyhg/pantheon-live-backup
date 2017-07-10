// JavaScript Document
(function() {
    tinymce.PluginManager.add('shortcode_padding', function(editor, url) {
		editor.addButton('shortcode_padding', {
			text: '',
			tooltip: 'Margin',
			icon: 'icon-margin',
			onclick: function() {
				// Open window
				editor.windowManager.open({
					title: 'Margin',
					body: [
						{type: 'textbox', name: 'margin_top', label: 'Margin Top'},
						{type: 'textbox', name: 'margin_bottom', label: 'Margin Bottom'},
					],
					onsubmit: function(e) {
						// Insert content when the window form is submitted
						editor.insertContent('[margin   top="'+e.data.margin_top+'" bottom="'+e.data.margin_bottom+'" ]');
					}
				});
			}
		});
	});
})();
