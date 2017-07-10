<?php
	$category = get_category( get_query_var( 'cat' ) );
	$cat_id = $category->cat_ID;
	$style = get_option("cat_layout_$cat_id")?get_option( "cat_layout_$cat_id"):ot_get_option('blog_style','video');
		$cat_header_style = get_option("cat_header_$cat_id")?get_option("cat_header_$cat_id"):ot_get_option('cat_header_style','carousel');
		if($cat_header_style=='carousel' && $style!='blog'){
			$con_top_cat_video = ot_get_option('con_top_cat_video');
			$number_item_cat = ot_get_option('number_item_cat');
			echo do_shortcode('[tm_cat_videos condition="'.$con_top_cat_video.'" count="'.$number_item_cat.'" categories="'.$cat_id.'"]');
	
		}elseif($cat_header_style=='banner'){
			$cat_height = get_option("cat_height_$cat_id")?get_option("cat_height_$cat_id"):'';
			$cat_link = get_option("cat_link_$cat_id")?get_option("cat_link_$cat_id"):'';
			if(function_exists('z_taxonomy_image_url')){ $cat_img = z_taxonomy_image_url();}
			?>
            <div class="category-banner" <?php if($cat_height){ echo 'style="height:'.$cat_height.'px"';} ?>>
            	<?php if($cat_link){ echo '<a href="'.$cat_link.'">';} ?>
                <?php if(isset($cat_img)&&$cat_img){ echo '<img src="'.$cat_img.'" />';} ?>
                <?php if($cat_link){ echo '</a>';} ?>
            </div>
            <?php
		}
	
?>