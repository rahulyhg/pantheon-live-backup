// JavaScript Document
jQuery(document).ready(function(e) {
	jQuery(document).on('click','[id^=newcolorpicker]',function(){
		parent_ID = jQuery(this).parent().attr('id');
		if(!jQuery('.'+parent_ID+'-picker').length){
			jQuery(this).parent().append('<div class="'+parent_ID+'-picker new-color-picker"></div>');
		}
		jQuery(document).farbtastic('.new-color-picker').hide();
		jQuery('.'+parent_ID+'-picker').farbtastic(this).show();
	});
	jQuery(document).on('blur','[id^=newcolorpicker]',function(){
		parent_ID = jQuery(this).parent().attr('id');
		jQuery('.'+parent_ID+'-picker').farbtastic(this).hide();
	});
	
	//front page header box
	var page_ct_style_obj = jQuery('#front_page_meta_box2.postbox');
	var page_tpl_obj = jQuery('select[name=page_template]');
	page_tpl_obj.change(function(event) {
		if(jQuery(this).val() == 'page-templates/front-page.php'){
			page_ct_style_obj.show(200);
		}else{
			page_ct_style_obj.hide(200);
		}
	});
	if(jQuery('select#page_template').val()=='page-templates/front-page.php'){
		jQuery('#front_page_meta_box2.postbox').show()
	}else{
		jQuery('#front_page_meta_box2.postbox').hide()
	}
	
	if(jQuery('select#page_template').val()=='page-templates/front-page.php'){
		jQuery('#front_meta_box.postbox').show()
	}else{
		jQuery('#front_meta_box.postbox').hide()
	}
	jQuery('select#page_template').change(function(e) {
        if(jQuery(this).val()=='page-templates/front-page.php'){
			jQuery('#front_meta_box.postbox').show(200)
		}else{
			jQuery('#front_meta_box.postbox').hide(100)
		}
    });
	
	if(jQuery('select#page_template').val()=='page-templates/sitemap-video.php'){
		jQuery('#video_sitemap_meta.postbox').show()
	}else{
		jQuery('#video_sitemap_meta.postbox').hide()
	}
	jQuery('select#page_template').change(function(e) {
        if(jQuery(this).val()=='page-templates/sitemap-video.php'){
			jQuery('#video_sitemap_meta.postbox').show(200)
		}else{
			jQuery('#video_sitemap_meta.postbox').hide(100)
		}
    });
	
});