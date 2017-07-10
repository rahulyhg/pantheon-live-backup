// JavaScript Document
(function() {
    tinymce.PluginManager.add('shortcode_member', function(editor, url) {
		editor.addButton('shortcode_member', {
			text: '',
			tooltip: 'Member',
			icon: 'icon-member',
			onclick: function() {
				// Open window
				editor.windowManager.open({
					title: 'Member',
					body: [
						{type: 'textbox', name: 'member_id', label: 'Member ID'},
					],
					onsubmit: function(e) {
						// Insert content when the window form is submitted
						editor.insertContent('[member  id="'+e.data.member_id+'"]');
					}
				});
			}
		});
	});
})();
