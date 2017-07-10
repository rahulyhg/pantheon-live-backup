// JavaScript Document
(function() {
    tinymce.PluginManager.add('ct_alert', function(editor, url) {
		editor.addButton('ct_alert', {
			text: '',
			tooltip: 'Alert',
			icon: 'icon-alert',
			onclick: function() {
				// Open window
				editor.windowManager.open({
					title: 'Alert',
					body: [
						{type: 'textbox', name: 'content', label: 'Content'},
						{type: 'textbox', name: 'links', label: 'Link'},
						{type: 'listbox', 
							name: 'style', 
							label: 'Style', 
							'values': [
								{text: 'Alert-success', value: 'alert-success'},
								{text: 'Alert-info', value: 'alert-info'},
								{text: 'Alert-warning', value: 'alert-warning'},
								{text: 'Alert-danger', value: 'alert-danger'}
							]
						},
						{type: 'listbox', 
							name: 'dis_alerts', 
							label: 'Dismissable alerts', 
							'values': [
								{text: 'No', value: '0'},
								{text: 'Yes', value: '1'},
							]
						}
					],
					onsubmit: function(e) {
						var uID =  Math.floor((Math.random()*100)+1);
						// Insert content when the window form is submitted
						editor.insertContent('[alert id="alert_'+uID+'" links="'+e.data.links+'" style="'+e.data.style+'"  dis_alerts="'+e.data.dis_alerts+'"]'+e.data.content+'[/alert]<br class="nc"/>');
					}
				});
			}
		});
	});
})();
