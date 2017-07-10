// JavaScript Document
(function() {
    tinymce.PluginManager.add('shortcode_featuredcontent', function(editor, url) {
		editor.addButton('shortcode_featuredcontent', {
			text: '',
			tooltip: 'Featured Content Box',
			icon: 'icon-featured',
			onclick: function() {
				// Open window
				editor.windowManager.open({
					title: 'Featured Content Box',
					body: [
						{type: 'textbox', name: 'title', label: 'Title'},
						{type: 'textbox', name: 'categories', label: 'Categories'},
						{type: 'listbox', 
							name: 'condition', 
							label: 'Condition', 
							'values': [
								{text: 'Latest', value: 'latest'},
								{text: 'Most viewed', value: 'most_viewed'},
								{text: 'Most liked', value: 'likes'},
								{text: 'Most commented', value: 'most_commented'},
								{text: 'Random', value: 'random'},
							]
						},
						
						{type: 'textbox', name: 'count', label: 'Number of posts'},
						{type: 'listbox', 
							name: 'order', 
							label: 'Order',
							'values': [
								{text: 'Descending', value: 'DESC'},
								{text: 'Ascending', value: 'ASC'},
							]
						},
						{type: 'listbox', 
							name: 'show_excerpt', 
							label: 'Show excerpt', 
							'values': [
								{text: 'Yes', value: '1'},
								{text: 'No', value: '0'},
							]
						},
						{type: 'listbox', 
							name: 'show_rate', 
							label: 'Show rate', 
							'values': [
								{text: 'Yes', value: '1'},
								{text: 'No', value: '0'},
							]
						},
						{type: 'listbox', 
							name: 'show_dur', 
							label: 'Show duration', 
							'values': [
								{text: 'Yes', value: '1'},
								{text: 'No', value: '0'},
							]
						},
						{type: 'listbox', 
							name: 'show_view', 
							label: 'Show view', 
							'values': [
								{text: 'Yes', value: '1'},
								{text: 'No', value: '0'},
							]
						},
						{type: 'listbox', 
							name: 'show_com', 
							label: 'Show comment', 
							'values': [
								{text: 'Yes', value: '1'},
								{text: 'No', value: '0'},
							]
						},
						{type: 'listbox', 
							name: 'show_like', 
							label: 'Show like', 
							'values': [
								{text: 'Yes', value: '1'},
								{text: 'No', value: '0'},
							]
						},
						{type: 'listbox', 
							name: 'show_date', 
							label: 'Show date', 
							'values': [
								{text: 'Yes', value: '1'},
								{text: 'No', value: '0'},
							]
						},
						{type: 'listbox', 
							name: 'show_aut', 
							label: 'Show author', 
							'values': [
								{text: 'Yes', value: '1'},
								{text: 'No', value: '0'},
							]
						},
						{type: 'textbox', name: 'number_excerpt', label: 'Number of excerpt to show'},
					],
					onsubmit: function(e) {
						// Insert content when the window form is submitted
						editor.insertContent('[featured-content title="'+e.data.title+'" condition="'+e.data.condition+'" count="'+e.data.count+'" order="'+e.data.order+'" show_excerpt="'+e.data.show_excerpt+'" show_rate="'+e.data.show_rate+'" show_dur="'+e.data.show_dur+'" show_view="'+e.data.show_view+'" show_com="'+e.data.show_com+'" show_like="'+e.data.show_like+'" show_aut="'+e.data.show_aut+'" show_date="'+e.data.show_date+'" number_excerpt="'+e.data.number_excerpt+'" categories="'+e.data.categories+'"]');
					}
				});
			}
		});
	});
})();
