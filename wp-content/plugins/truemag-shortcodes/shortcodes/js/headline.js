// JavaScript Document
(function() {
    tinymce.PluginManager.add('shortcode_headline', function(editor, url) {
		editor.addButton('shortcode_headline', {
			text: '',
			tooltip: 'Headline',
			icon: 'icon-headline',
			onclick: function() {
				// Open window
				editor.windowManager.open({
					title: 'Headline',
					body: [
						{type: 'textbox', name: 'number', label: 'Number of posts'},
						{type: 'textbox', name: 'cat', label: 'Categories ID'},
						{type: 'textbox', name: 'icon', label: 'Font Icon Awesome'},
						{type: 'listbox', 
							name: 'links', 
							label: 'Link', 
							'values': [
								{text: 'Yes', value: 'yes'},
								{text: 'No', value: 'no'}
							]
						},
						{type: 'listbox', 
							name: 'sortby', 
							label: 'Sort by', 
							'values': [
								{text: 'rand', value: 'Random'},
								{text: 'title', value: 'Title'},
								{text: 'date', value: 'Date'}
							]
						},
					],
					onsubmit: function(e) {
						var uID =  Math.floor((Math.random()*100)+1);
						// Insert content when the window form is submitted
						editor.insertContent('[headline  number="'+e.data.number+'" cat="'+e.data.cat+'" icon="'+e.data.icon+'" link="'+e.data.links+'" sortby="'+e.data.sortby+'"]');
					}
				});
			}
		});
	});
})();
