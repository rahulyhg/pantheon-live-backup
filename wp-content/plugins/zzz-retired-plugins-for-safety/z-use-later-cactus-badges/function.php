<?php
add_filter('videopro_loop_item_icon', 'videopro_video_badges_html', 100, 2);
if(!function_exists('videopro_video_badges_html')){	
	function videopro_video_badges_html($html, $id) {
		echo $html.do_shortcode('[cactus_badges id="'.$id.'"]');
	}
}

include_once(ABSPATH.'wp-admin/includes/plugin.php');
if(!is_plugin_active('categories-images/categories-images.php')){
	/* Category custom field */
	add_action( 'cactus_badges_add_form_fields', 'videopro_extra_cactus_badges_fields', 10 );
	add_action ( 'cactus_badges_edit_form_fields', 'videopro_extra_cactus_badges_fields');
	
	function videopro_extra_cactus_badges_fields( $tag ) {    //check for existing featured ID
		$t_id 					= isset($tag->term_id) ? $tag->term_id : '';
		$url_image 			= get_option( "url_image_$t_id")?get_option( "url_image_$t_id"):'';
		?>
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="url-image"><?php _e('Url Image','videopro'); ?></label>
            </th>
			<td>
				<input type="text" name="url-image" id="url-image" value="<?php echo esc_attr($url_image) ?>" />
            </td>
		</tr>
		<?php
	}
	//save extra category extra fields hook
	add_action ( 'edited_cactus_badges', 'videopro_save_extra_cactus_badges_fileds', 10, 2);
	add_action( 'created_cactus_badges', 'videopro_save_extra_cactus_badges_fileds', 10, 2 );
	function videopro_save_extra_cactus_badges_fileds( $term_id ) {
		if ( isset( $_POST[sanitize_key('url-image')] ) ) {
			$url_image = $_POST['url-image'];
			update_option( "url_image_$term_id", $url_image );
		}
	}
}