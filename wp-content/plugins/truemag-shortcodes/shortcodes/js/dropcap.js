// JavaScript Document
(function() {
    tinymce.PluginManager.add('ct_dropcap', function(editor, url) {
		editor.addButton('ct_dropcap', {
			text: '',
			tooltip: 'Dropcap',
			icon: 'icon-dropcap',
			onclick: function() {
				var shortcode = '[dropcap]'+tinymce.activeEditor.selection.getContent()+'[/dropcap]';
				tinymce.activeEditor.execCommand('mceInsertContent',false,shortcode);
				c.hideMenu();
			}
		});
	});
})();