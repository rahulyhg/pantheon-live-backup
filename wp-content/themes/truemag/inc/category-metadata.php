<?php
/* Category custom field */
add_action( 'category_add_form_fields', 'tm_extra_category_fields', 10 );
add_action ( 'edit_category_form_fields', 'tm_extra_category_fields');
function tm_extra_category_fields( $tag ) {    //check for existing featured ID
    $t_id = $tag->term_id?$tag->term_id:'';
    $cat_layout = get_option( "cat_layout_$t_id")?get_option( "cat_layout_$t_id"):'';
	$cat_page_layout = get_option( "cat_page_layout_$t_id")?get_option( "cat_page_layout_$t_id"):'';
?>
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="category-layout"><?php _e('Category Listing Layout','cactusthemes'); ?></label>
		</th>
		<td>
            <select name="category-layout" id="category-layout">
                <option value=0 <?php echo $cat_layout==0?'selected="selected"':'' ?> ><?php _e('Default','cactusthemes') ?></option>
                <option value="video" <?php echo $cat_layout=='video'?'selected="selected"':'' ?>><?php _e('Video listing (Grid)','cactusthemes') ?></option>
                <option value="blog" <?php echo $cat_layout=='blog'?'selected="selected"':'' ?>><?php _e('Blog listing','cactusthemes') ?></option>
            </select>
			<p class="description"><?php _e('Choose layout listing for this category page','cactusthemes'); ?></p>
		</td>
	</tr>
    <tr class="form-field">
		<th scope="row" valign="top">
			<label for="category-page-layout"><?php _e('Category Page Layout','cactusthemes'); ?></label>
		</th>
		<td>
            <select name="category-page-layout" id="category-page-layout">
                <option value=0 <?php echo $cat_page_layout==0?'selected="selected"':'' ?> ><?php _e('Default','cactusthemes') ?></option>
                <option value="left" <?php echo $cat_page_layout=='left'?'selected="selected"':'' ?>><?php _e('Left Sidebar','cactusthemes') ?></option>
                <option value="right" <?php echo $cat_page_layout=='right'?'selected="selected"':'' ?>><?php _e('Right Sidebar','cactusthemes') ?></option>
                <option value="full" <?php echo $cat_page_layout=='full'?'selected="selected"':'' ?>><?php _e('Full width','cactusthemes') ?></option>
            </select>
			<p class="description"><?php _e('Choose page layout for this category page','cactusthemes'); ?></p>
		</td>
	</tr>
<?php
if(function_exists('z_taxonomy_image_url')){ //if has category images plugin
	//cat banner
	$cat_header = get_option( "cat_header_$t_id")?get_option( "cat_header_$t_id"):'';
	$cat_height = get_option( "cat_height_$t_id")?get_option( "cat_height_$t_id"):'';
	$cat_link = get_option( "cat_link_$t_id")?get_option( "cat_link_$t_id"):'';
	?>
    <tr class="form-field">
		<th scope="row" valign="top">
			<label for="category-header"><?php _e('Category Header Style','cactusthemes'); ?></label>
		</th>
		<td>
            <select name="category-header" id="category-header">
                <option value=0 <?php echo $cat_header==0?'selected="selected"':'' ?> ><?php _e('Default','cactusthemes') ?></option>
                <option value="carousel" <?php echo $cat_header=='carousel'?'selected="selected"':'' ?>><?php _e('Carousel','cactusthemes') ?></option>
                <option value="banner" <?php echo $cat_header=='banner'?'selected="selected"':'' ?>><?php _e('Banner Image','cactusthemes') ?></option>
                <option value="hide" <?php echo $cat_header=='hide'?'selected="selected"':'' ?>><?php _e('Do not show','cactusthemes') ?></option>
            </select>
			<p class="description"><?php _e('Choose header style for this category page (Need upload image if you use Banner)','cactusthemes'); ?></p>
		</td>
	</tr>
    <tr class="form-field">
		<th scope="row" valign="top">
			<label for="category-height"><?php _e('Category Banner Height','cactusthemes'); ?></label>
		</th>
		<td>
        	<input type="number" name="category-height" id="category-height" value="<?php echo $cat_height ?>" />
			<p class="description"><?php _e('Enter banner height for this category page (in px, ex: 300)','cactusthemes'); ?></p>
		</td>
	</tr>
    <tr class="form-field">
		<th scope="row" valign="top">
			<label for="category-link"><?php _e('Category Banner Link','cactusthemes'); ?></label>
		</th>
		<td>
        	<input type="text" name="category-link" id="category-link" value="<?php echo $cat_link ?>" />
			<p class="description"><?php _e('Enter URL for this category banner','cactusthemes'); ?></p>
		</td>
	</tr>
    <?php
}//if has category images plugin
}
//save extra category extra fields hook
add_action ( 'edited_category', 'tm_save_extra_category_fileds');
add_action( 'created_category', 'tm_save_extra_category_fileds', 10, 2 );
function tm_save_extra_category_fileds( $term_id ) {
    if ( isset( $_POST['category-layout'] ) ) {
        $cat_layout = $_POST['category-layout'];
        update_option( "cat_layout_$term_id", $cat_layout );
    }
	if ( isset( $_POST['category-page-layout'] ) ) {
        $cat_page_layout = $_POST['category-page-layout'];
        update_option( "cat_page_layout_$term_id", $cat_page_layout );
    }
	if ( isset( $_POST['category-header'] ) ) {
        $cat_header = $_POST['category-header'];
        update_option( "cat_header_$term_id", $cat_header );
    }
	if ( isset( $_POST['category-height'] ) ) {
        $cat_height = $_POST['category-height'];
        update_option( "cat_height_$term_id", $cat_height );
    }
	if ( isset( $_POST['category-link'] ) ) {
        $cat_link = $_POST['category-link'];
        update_option( "cat_link_$term_id", $cat_link );
    }
}