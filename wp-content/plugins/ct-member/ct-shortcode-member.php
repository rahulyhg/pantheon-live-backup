<?php
function parse_member_func($atts, $content){
	$id = isset($atts['id']) ? $atts['id'] : '';
	$animation_class = isset($atts['animation'])?'wpb_'.$atts['animation'].' wpb_animate_when_almost_visible':'';
	wp_reset_postdata();
	
	$args = array(
		'post_type' => 'member',
		'p' => $id,	
		'post_status' => 'publish',	
	);
	

	$html = '
		<div class="row-fluid">
	';
	$the_query = new WP_Query( $args );
	$class_hover = '';
	if($the_query->have_posts()){
		while($the_query->have_posts()){ $the_query->the_post();
			$meta_data = get_post_custom();
			
			$attachment_url  = get_post_meta($id,'mb-hover',true);
			// Finally, run a custom database query to get the attachment ID from the modified attachment URL
			$attachment_id = pn_get_attachment_id_from_url($attachment_url);
			$image_src2 = wp_get_attachment_image_src( $attachment_id,'full','thumbname' );
			$image_src = wp_get_attachment_image_src( get_post_thumbnail_id(),'full','thumbname' );
			$arr_social = array(
				'facebook' => isset($meta_data['facebook'][0]) ? $meta_data['facebook'][0] : '',
				'flickr' => isset($meta_data['flickr'][0]) ? $meta_data['flickr'][0] : '',
				'google-plus' => isset($meta_data['google+'][0]) ? $meta_data['google+'][0] : '',
				'dribbble' => isset($meta_data['dribbble'][0]) ? $meta_data['dribbble'][0] : '',
				'envelope-o' => isset($meta_data['envelope-o'][0]) ? $meta_data['envelope-o'][0] : '',
				'instagram' => isset($meta_data['instagram'][0]) ? $meta_data['instagram'][0] : '',
				'linkedin' => isset($meta_data['linkedIn'][0]) ? $meta_data['linkedIn'][0] : '',
				'pinterest' => isset($meta_data['pinterest'][0]) ? $meta_data['pinterest'][0] : '',
				'rss' => isset($meta_data['rss'][0]) ? $meta_data['rss'][0] : '',
				'twitter' => isset($meta_data['twitter'][0]) ? $meta_data['twitter'][0] : '',
				'youtube' => isset($meta_data['youtube'][0]) ? $meta_data['youtube'][0] : '',
			);
		if(get_post_meta($id,'mb-hover',true)==''){$class_hover='no_ef';}	
		$html .= '
		  <div class="span12 '.$animation_class.'">
			  <div class="member" >
			  	  <div class="img-mb">	
					  <img class="img-thumb  '.$class_hover.' " src="'.$image_src2[0].'" title="'.get_the_title().'" />';
					  if(get_post_meta($id,'mb-hover',true)!=''){
					  $html .='
					  <div class="hover-image"><img src="'.$image_src[0].'" title="'.get_the_title().'" /></div>';}
				$html .='	  
				  </div>
				  <div class="member-info">
						  <h2 class="member-name">'.get_the_title().'</h2>
						  <p>'.$meta_data['position'][0].'</p>
						  <div class="mb-content">'.get_the_excerpt().'</div>
						  <div class="member-social">
							  '.show_social_icon($arr_social).'
						  </div>
				  </div>
			  </div>
		  </div>
		';
		}
	}
	$html .= '
		</div>
	';
	
	
	wp_reset_postdata();
	return $html;

}

add_shortcode( 'member', 'parse_member_func' );

function pn_get_attachment_id_from_url( $attachment_url = '' ) {
 
	global $wpdb;
	$attachment_id = false;
 
	// If there is no url, return.
	if ( '' == $attachment_url )
		return;
 
	// Get the upload directory paths
	$upload_dir_paths = wp_upload_dir();
 
	// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
	if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {
 
		// If this is the URL of an auto-generated thumbnail, get the URL of the original image
		$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
 
		// Remove the upload path base directory from the attachment URL
		$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );
 
		// Finally, run a custom database query to get the attachment ID from the modified attachment URL
		$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
 
	}
 
	return $attachment_id;
}





if(!function_exists('show_social_icon')){
	function show_social_icon($arr_social = array()){
		$html = '';
		if(count($arr_social) > 0){
			foreach($arr_social as $key => $value){
				if(isset($value) && $value != ''){
					if($key=='envelope-o'){$html .= '<a href="mailto:'.$value.'" class="icon-social social-icon maincolor1 bordercolor1 bgcolor1hover fa fa-'.str_replace('_', '-', $key).'"><!-- --></a>';}
					else {
					$html .= '<a href="'.$value.'" class="icon-social social-icon maincolor1 bordercolor1 bgcolor1hover fa fa-'.str_replace('_', '-', $key).'"><!-- --></a>';
					}
				}
			}
		}
		return $html;
	}
}