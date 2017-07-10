// JavaScript Document
(function() {
    tinymce.PluginManager.add('ct_shortcode_compare_table', function(editor, url) {
		editor.addButton('ct_shortcode_compare_table', {
			text: '',
			tooltip: 'Compare Table',
			icon: 'icon-comparetable',
			onclick: function() {
				// Open window
				editor.windowManager.open({
					title: 'Compare Table',
					body: [
						{type: 'textbox', name: 'column', value:'3', label: 'Number of column'},
						{type: 'textbox', name: 'row', value:'5', label: 'Number of row'},
						{type: 'textbox', name: 'color', value:'#', label: 'Background Color highlight', id:'newcolorpicker_pri_hl'},
						{type: 'textbox', name: 'bg_color', value:'#', label: 'Background Color', id:'newcolorpicker_pri_bg'},
					],
					onsubmit: function(e) {
						// Insert content when the window form is submitted
							var uID =  Math.floor((Math.random()*100)+1);
							var column = e.data.column;
							var row = e.data.row;
							var color = e.data.color;
							var bg_color = e.data.bg_color;
							var style = '';
							var column = e.data.row;
							var shortcode = '[comparetable id="comparetable_'+uID+'"] <br class="nc"/>';
								for(i=0;i<column;i++){
									if(style != 'compare-table-2'){
										if(i == 0)
											shortcode+= '[c-column class="tb-left" column="'+column+'" bg_color="'+bg_color+'"]<br class="nc"/>';
										else if(i == 1)
											shortcode+= '[c-column class="tb-center" column="'+column+'" bg_color="'+bg_color+'"]<br class="nc"/>';
										else
											shortcode+= '[c-column  class="tb-right" column="'+column+'" bg_color="'+bg_color+'"]<br class="nc"/>';	
									}else{
										if(i == 0)
											shortcode+= '[c-column class="compare-table-feature" column="'+column+'" bg_color="'+bg_color+'"]<br class="nc"/>';
										else if(i == 1)
											shortcode+= '[c-column class="tb-left" column="'+column+'" bg_color="'+bg_color+'"]<br class="nc"/>';
										else if(i == 2)
											if(column<4)
												shortcode+= '[c-column class="tb-center compare-table-column-2" column="'+column+'" bg_color="'+bg_color+'"]<br class="nc"/>';
											else
												shortcode+= '[c-column class="tb-center" column="'+column+'" bg_color="'+bg_color+'"]<br class="nc"/>';
										else if(i == 3)
											shortcode+= '[c-column class="compare-table-copper" column="'+column+'" bg_color="'+bg_color+'"]<br class="nc"/>';
										else
											shortcode+= '[c-column column="'+column+'" bg_color="'+bg_color+'"]<br class="nc"/>';	
									}
									for(j=0; j<row; j++){
										if(style != 'compare-table-2'){
											if(j==0){
												shortcode+= '[c-row class="row-first" ]Content here….[/c-row]<br class="nc"/>';
												shortcode+= '[c-row]Content here….[/c-row]<br class="nc"/>';
											}
											else if(j==2){
												var colorshortcode = '';
												if(color != 'undefined' && color != null)
													colorshortcode = 'color="' + color + '"';
												shortcode+= '[c-row class="hight-light" '+ colorshortcode +']Content here….[/c-row]<br class="nc"/>';	
											}else
												shortcode+= '[c-row]Content here….[/c-row]<br class="nc"/>';
										}else{
											if(j==0 && i!=0)
												shortcode+= '[c-row class="row-first"][/c-row]<br class="nc"/>';
											else if(j==1)
												if(i==0)
													shortcode+= '[c-row class="hight-light" ][/c-row]<br class="nc"/>';
												else{
													var colorshortcode = '';
													if(color != 'undefined' && color != null)
														colorshortcode = 'color="' + color + '"';		
													shortcode+= '[c-row class="hight-light" '+colorshortcode+']Content here….[/c-row]<br class="nc"/>';	
												}
											else if(j!=0)
												shortcode+= '[c-row]Content here….[/c-row]<br class="nc"/>';
										}
									}
									shortcode += '[/c-column]<br class="nc"/>';
								}
                                shortcode+= '[/comparetable]<br class="nc"/>';

						editor.insertContent(shortcode);
					}
				});
			}
		});
	});
})();
