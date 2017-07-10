// JavaScript Document
(function() {
    tinymce.PluginManager.add('shortcode_tooltip', function(editor, url) {
		editor.addButton('shortcode_tooltip', {
			text: '',
			tooltip: 'Tooltip',
			icon: 'icon-tooltip',
			onclick: function() {
				// Open window
				editor.windowManager.open({
					title: 'Tooltip',
					body: [
						{type: 'textbox', name: 'title', label: 'Title'},
					],
					onsubmit: function(e) {
						// Insert content when the window form is submitted
						 var uID =  Math.floor((Math.random()*100)+1);
						editor.insertContent('[tooltip id="tooltip_'+uID+'" title="'+e.data.title+'"]'+tinymce.activeEditor.selection.getContent()+'[/tooltip]');
					}
				});
			}
		});
	});
})();
