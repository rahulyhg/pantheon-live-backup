// JavaScript Document
(function() {
    tinymce.PluginManager.add('shortcode_videoscarousel', function(editor, url) {
		editor.addButton('shortcode_videoscarousel', {
			text: '',
			tooltip: 'Video Carousel',
			icon: 'icon-video-car',
			onclick: function() {
				// Open window
				editor.windowManager.open({
					title: 'Video Carousel',
					body: [
						{type: 'listbox', 
							name: 'condition', 
							label: 'Condition', 
							'values': [
								{text: 'Latest', value: 'latest'},
								{text: 'Most viewed', value: 'most_viewed'},
								{text: 'Most liked', value: 'likes'},
								{text: 'Most commented', value: 'most_commented'},
								{text: 'Title', value: 'title'},
								{text: 'Modified', value: 'modified'},
								{text: 'Random', value: 'random'},
							]
						},
						{type: 'textbox', name: 'ids', label: 'IDs'},
						{type: 'textbox', name: 'count', label: 'Number of Carousel'},
						{type: 'textbox', name: 'categories', label: 'Categories'},
						{type: 'textbox', name: 'tags', label: 'Tags'},
						{type: 'listbox', 
							name: 'order', 
							label: 'Order by',
							'values': [
								{text: 'Descending', value: 'DESC'},
								{text: 'Ascending', value: 'ASC'},
							]
						},
					],
					onsubmit: function(e) {
						// Insert content when the window form is submitted
						editor.insertContent('[video_silder condition="'+e.data.condition+'" ids="'+e.data.ids+'" count="'+e.data.count+'" tags="'+e.data.tags+'" order="'+e.data.order+'" categories="'+e.data.categories+'"]');
					}
				});
			}
		});
	});
})();
