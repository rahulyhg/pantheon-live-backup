<?php
$single_layout_blog = get_post_meta(get_the_ID(),'page_layout', true);
if($single_layout_blog=='def'){
	$single_layout_blog = ot_get_option('single_layout_blog');
}
if($single_layout_blog=='full_width'){
$single_show_image = get_post_meta(get_the_ID(),'show_feature_image', true);
if($single_show_image=='3'){
	// default, so check in global value in Theme Options
	if(function_exists('ot_get_option')){
		$single_show_image = ot_get_option('single_show_image');
	}
}
if($single_show_image!='1' && has_post_thumbnail()){
	// if show featured image and this post has thumbnail
?>  
		<div class="single-full-width" id="player">
        	<div class="container">
            	<div class="video-player">
                	<div class="player-content">
                    	<div id="player-embed">
                        <?php 
                        if(has_post_thumbnail()){
							$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true); ?>
                            <div id="post-thumb"><img src="<?php echo $thumbnail[0] ?>" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>"></div><br />
                        <?php 
						?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                  </div>
               </div>
           </div>
<?php 						
		} 
	}
}
?>