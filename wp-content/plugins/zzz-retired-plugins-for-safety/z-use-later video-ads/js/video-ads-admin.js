var video_ads_type_obj 					= jQuery('#cactus_advs_type');
var video_ads_type 						= jQuery('#cactus_advs_type').val();
var file_advanced_upload 				= jQuery('.rwmb-file_advanced-wrapper');
var advs_video_url 						= jQuery('#advs_video_url').parents('.rwmb-field');
var advs_adsense_code 					= jQuery('#advs_adsense_code').parents('.rwmb-field');
var advs_url 							= jQuery('#advs_url').parents('.rwmb-field');
var advs_target 						= jQuery('#advs_target').parents('.rwmb-field');
var advs_position 						= jQuery('#advs_position').parents('.rwmb-field');

if(video_ads_type == 'image')
{
	file_advanced_upload.show();
	advs_url.show();
	advs_target.show();
	advs_position.show();
	advs_video_url.hide();
	advs_adsense_code.hide();
}
else if(video_ads_type == 'video')
{
	advs_video_url.show();
	advs_url.show();
	file_advanced_upload.hide();
	advs_target.hide();
	advs_position.hide();
	advs_adsense_code.hide();
}
else if(video_ads_type == 'html')
{
	advs_adsense_code.show();
	advs_position.show();
	advs_video_url.hide();
	advs_url.hide();
	file_advanced_upload.hide();
	advs_target.hide();
}
else
{
	advs_adsense_code.hide();
	advs_video_url.hide();
	advs_url.hide();
	file_advanced_upload.hide();
	advs_target.hide();
	advs_position.hide();
}

video_ads_type_obj.change(function(event) {
	if(jQuery(this).val() == 'image')
	{
		file_advanced_upload.show(200);
		advs_url.show(200);
		advs_target.show(200);
		advs_position.show(200);
		advs_video_url.hide(200);
		advs_adsense_code.hide(200);

	}
	else if(jQuery(this).val() == 'video')
	{
		advs_video_url.show(200);
		advs_url.show(200);
		file_advanced_upload.hide(200);
		advs_target.hide(200);
		advs_position.hide(200);
		advs_adsense_code.hide(200);
	}
	else if(jQuery(this).val() == 'html')
	{
		advs_adsense_code.show(200);
		advs_position.show(200);
		advs_video_url.hide(200);
		advs_url.hide(200);
		file_advanced_upload.hide(200);
		advs_target.hide(200);
	}
	else
	{
		advs_adsense_code.hide(200);
		advs_video_url.hide(200);
		advs_url.hide(200);
		file_advanced_upload.hide(200);
		advs_target.hide(200);
		advs_position.hide(200);
	}
});

jQuery(document).ready(function($) {
	var count = 0;
	var post_per_process = 100;
	var save_bulk_id_button = jQuery('#bulk_ads_id_save_button');

	save_bulk_id_button.click(function(event) {
		var list_of_posts 		= jQuery('input[name=list_of_posts]').val();
		var bulk_ads_id_op 		= jQuery('input[name=bulk_ads_id_op]').val();
		var bulk_ads_id 		= jQuery('input[name=bulk_ads_id]').val();
		var ids = list_of_posts.split(',');
		var number_of_posts = ids.length;
		var number_of_process = 1;
		if(post_per_process != 0)
		{
			number_of_process = number_of_posts % post_per_process == 0 ? number_of_posts / post_per_process : parseInt(number_of_posts / post_per_process) + 1;
		}

	    if(ids != '')
	    {
	    	save_bulk_ads_id_op(bulk_ads_id_op, bulk_ads_id);
	        save_bulk_ads_id(count, number_of_process, post_per_process, number_of_posts, ids, bulk_ads_id);
	    }
	});

	function save_bulk_ads_id_op(bulk_ads_id_op, bulk_ads_id)
	{
		var param = {
				action: 'save_bulk_ads_id_op',
				bulk_ads_id_op 	: bulk_ads_id_op,
				bulk_ads_id 	: bulk_ads_id,
			};

			$.ajax({
				type: "post",
				url: cactus.ajaxurl,
				dataType: 'html',
				data: (param),
				success: function(data){
				}
			});
	}

	function save_bulk_ads_id(count, number_of_process, post_per_process, number_of_posts, ids, bulk_ads_id)
	{
	    if(count >= number_of_process)
	    {
	    	$('.error').css('display', 'none');
	        $('.updated').css('display', 'block');
			$('.updated').html('<p>All posts have been updated</p>');
	        return;
	    }
	    else
	    {
	    	var j = 0;
	    	var post_ids = '';
	    	if(count == 0)
	    	{
	    		j = 0;
	    		if(post_per_process > number_of_posts)
	    		{
	    			count_post_ids = number_of_posts;
	    		}
	    		else
	    		{
	    			count_post_ids = post_per_process;
	    		}
	    	}
	    	else
	    	{
	    		j = count * post_per_process;
	    		if(count == number_of_process - 1)
	    		{
	    			if(number_of_posts % post_per_process == 0)
	    			{
	    				count_post_ids = (count+1) * post_per_process;
	    			}
	    			else
	    			{
	    				count_post_ids = (count * post_per_process) + (number_of_posts % post_per_process);
	    			}
	    		}
	    		else
	    		{
	    			count_post_ids = (count+1) * post_per_process;
	    		}
	    	}
	    	for(i = j; i < count_post_ids; i++)
	    	{
	    		post_ids += ids[i] + ',';
	    	}

	        var param = {
				action: 'save_bulk_ads_id',
				post_ids: post_ids,
				bulk_ads_id: bulk_ads_id,
			};

			$.ajax({
				type: "post",
				url: cactus.ajaxurl,
				dataType: 'html',
				data: (param),
				beforeSend: function() {
					$('.updated').css('display', 'none');
					$('.error').css('display', 'block');
					$('.error').html('<p>Saving. Please wait...</p>');
				},
				success: function(data){
					count++;
	                save_bulk_ads_id(count, number_of_process, post_per_process, number_of_posts, ids, bulk_ads_id);
				}
			});
	    }
	}
});
