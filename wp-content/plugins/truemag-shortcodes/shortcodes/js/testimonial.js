// JavaScript Document
(function() {
    tinymce.PluginManager.add('shortcode_testimonial', function(editor, url) {
		editor.addButton('shortcode_testimonial', {
			text: '',
			tooltip: 'Testimonials',
			icon: 'icon-testimonial',
			onclick: function() {
				// Open window
				editor.windowManager.open({
					title: 'Testimonial',
					body: [
						{type: 'textbox', name: 'number', label: 'Number of Item'},
					],
					onsubmit: function(e) {
						// Insert content when the window form is submitted
						var uID =  Math.floor((Math.random()*100)+1);
						var number = e.data.number;
						var shortcode = '[testimonial]<br class="nc"/>';
						for(i=0;i<number;i++){
							j=i+1;
								shortcode+= '[testimonial_item  title="Testimonial title ' + j + '" name="Testimonial name '+j+' " position="Testimonial position '+j+' " company="Testimonial company '+j+' "]Content here[/testimonial_item]<br class="nc"/>';
							}
						shortcode+= '[/testimonial]<br class="nc"/>';
						editor.insertContent(shortcode);
					}
				});
			}
		});
	});
})();
