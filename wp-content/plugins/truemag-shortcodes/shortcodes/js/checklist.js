// JavaScript Document
(function() {
    tinymce.PluginManager.add('ct_checklist', function(editor, url) {
		editor.addButton('ct_checklist', {
			text: '',
			tooltip: 'Checklist',
			icon: 'icon-checklist',
			onclick: function() {
				// Open window
				editor.windowManager.open({
					title: 'Check list',
					body: [
						{type: 'textbox', name: 'icon', label: 'Icon of checklist'}
					],
					onsubmit: function(e) {
						var uID =  Math.floor((Math.random()*100)+1);
						// Insert content when the window form is submitted
						editor.insertContent('[checklist id="checklist_'+uID+'" icon="'+e.data.icon+'"]<br class="nc"><ul><li>Content here...</li><li>Content here...</li><li>Content here...</li></ul>[/checklist]<br class="nc"/>');
					}
				});
			}
		});
	});
})();
