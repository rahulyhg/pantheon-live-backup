// JavaScript Document
(function() {
    tinymce.PluginManager.add('shortcode_smartcontent', function(editor, url) {
		editor.addButton('shortcode_smartcontent', {
			text: '',
			tooltip: 'Smart Content Box',
			icon: 'icon-smart',
			onclick: function() {
				// Open window
				editor.windowManager.open({
					title: 'Smartcontent Box',
					body: [
						{type: 'textbox', name: 'title', label: 'Title'},
						{type: 'listbox', 
							name: 'layout', 
							label: 'Layout', 
							'values': [
								{text: 'Grid', value: 'grid'},
								{text: 'Small Carousel', value: 'small_carousel'},
								{text: 'Medium Carousel', value: 'medium_carousel'},
								{text: 'Medium Carousel with Nav', value: 'medium_carousel_2'},
								{text: 'Single', value: 'single'}
							]
						},
						{type: 'listbox', 
							name: 'condition', 
							label: 'Condition', 
							'values': [
								{text: 'Latest', value: 'latest'},
								{text: 'Most viewed', value: 'most_viewed'},
								{text: 'Most liked', value: 'most_liked'},
								{text: 'Most commented', value: 'most_commented'}
							]
						},
						{type: 'textbox', name: 'id', label: 'Ids'},
						{type: 'textbox', name: 'count', label: 'Number of Slides'},
						{type: 'textbox', name: 'row', label: 'Row'},
						{type: 'listbox', 
							name: 'column', 
							label: 'Column', 
							'values': [
								{text: '2', value: '2'},
								{text: '4', value: '4'},
								{text: '6', value: '6'},
							]
						},
						{type: 'textbox', name: 'url_viewall', label: 'Url-viewall'},
						{type: 'textbox', name: 'label', label: 'Label For Url-viewall'},
						{type: 'textbox', name: 'categories', label: 'Categories'},
						{type: 'textbox', name: 'tag', label: 'Tags'},
						{type: 'listbox', 
							name: 'order', 
							label: 'Order by', 
							'values': [
								{text: 'Descending', value: 'DESC'},
								{text: 'Ascending', value: 'ASC'},
							]
						},
						{type: 'listbox', 
							name: 'show_title', 
							label: 'Show title', 
							'values': [
								{text: 'Yes', value: '1'},
								{text: 'No', value: '0'},
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
						{type: 'listbox', 
							name: 'quick_view', 
							label: 'Enable Quick View Popup', 
							'values': [
								{text: 'Default', value: 'def'},
								{text: 'Yes', value: '1'},
								{text: 'No', value: '0'},
							]
						},
					],
					onsubmit: function(e) {
						// Insert content when the window form is submitted
						editor.insertContent('[scb title="'+e.data.title+'"  layout="'+e.data.layout+'" condition="'+e.data.condition+'" count="'+e.data.count+'" row="'+e.data.row+'" column="'+e.data.column+'" url_viewall="'+e.data.url_viewall+'" label="'+e.data.label+'" id="'+e.data.id+'" order="'+e.data.order+'" show_title="'+e.data.show_title+'" show_rate="'+e.data.show_rate+'" show_excerpt="'+e.data.show_excerpt+'" show_dur="'+e.data.show_dur+'"  show_view="'+e.data.show_view+'" show_com="'+e.data.show_com+'" show_like="'+e.data.show_like+'"  show_aut="'+e.data.show_aut+'"  show_date="'+e.data.show_date+'" 	categories="'+e.data.categories+'" number_excerpt="'+e.data.number_excerpt+'" quick_view="'+e.data.quick_view+'" ]');
					}
				});
			}
		});
	});
})();
