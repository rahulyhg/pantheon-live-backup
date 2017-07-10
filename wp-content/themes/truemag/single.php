<?php 
global $post;
$format = get_post_format();
// Get video layout
// Check the current post is a video post and get template based on the video layout
$exits_list = 0;
$playlist_id = get_post_meta(get_the_ID(),'playlist_id',true);
if(is_array($playlist_id) && isset($_GET['list'])){
	if (in_array($_GET['list'], $playlist_id)) {
		$exits_list = 1;
	}
}elseif($playlist_id!='' && isset($_GET['list'])){
	if ($_GET['list'] == $playlist_id) {
		$exits_list = 1;
	}
}
if( ($exits_list == 1) && isset($_GET['list']) && $_GET['list']!='' && tm_is_post_video()){
	get_template_part('single-inplaylist'); 
	return;
}elseif(tm_is_post_video()) {
	get_template_part('single-video'); 
	return;
}
get_header();
$layout = get_post_meta(get_the_ID(),'single_ly_ct_video', true);
if($layout=='def'){
	$layout = ot_get_option('single_layout_ct_video','right');
}
global $sidebar_width;
if($format!='video'){
$topnav_style = ot_get_option('topnav_style','dark');		
?>
<div class="blog-heading <?php echo $topnav_style=='light'?'heading-light':'' ?>">
    <div class="container">
    	<div class="row">
        	<div class="col-md-10 col-sm-10 col-xs-9">
            	<h1><?php the_title(); ?></h1>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-3">
                <div class="blog-date">
                    <span><?php the_time('d') ?></span>
                    <span><?php the_time('M') ?></span>
                </div>
            </div>
        </div><!--/row-->
    </div>
</div><!--blog-heading-->
<?php } ?>
    <div id="body">
        <div class="container">
            <div class="row">
  				<div id="content" class="<?php echo $layout!='full'?($sidebar_width?'col-md-9':'col-md-8'):'col-md-12' ?><?php echo ($layout == 'left') ? " revert-layout":"";?>" role="main">
                	<?php
					//content
					if($format == 'gallery'){
						$images=&get_children('post_type=attachment&numberposts=-1&post_mime_type=image&post_parent='.get_the_ID());
						if(count($images) > 0){
					?>
						<div class="is-carousel simple-carousel post-gallery" id="post-gallery-<?php the_ID() ?>">
                            <div class="simple-carousel-content carousel-content">
                    	<?php
                        foreach((array)$images as $attachment_id => $attachment){
                            $image = wp_get_attachment_image_src( $attachment_id, 'full' );
                            echo "<img src='".$image[0]."'>";
                        } ?>
                            </div><!--/simple-carousel-->
                            <div class="carousel-pagination"></div>
                        </div><!--/is-carousel-->
                    <?php
						}
					}else{
						$single_show_image = get_post_meta(get_the_ID(),'show_feature_image', true);
						if($single_show_image=='3'){if(function_exists('ot_get_option')){
							$single_show_image = ot_get_option('single_show_image');}
						}
						$single_layout_blog = get_post_meta(get_the_ID(),'page_layout', true);
						if($single_layout_blog=='def'){
							$single_layout_blog = ot_get_option('single_layout_blog');
						}
						if($single_show_image!='1' && $single_layout_blog=='inbox'){
						if(has_post_thumbnail()){
							$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true); ?>
                            <div id="post-thumb"><img src="<?php echo $thumbnail[0] ?>" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>"></div><br />
                        <?php }}
					}
					//content
					if (have_posts()) :
						while (have_posts()) : the_post();
							if(ot_get_option('video_toolbar_for_post',false) && ot_get_option('video_toolbar_position','top')=='top'){
								get_template_part('single','toolbar');
							}
							get_template_part('content','single');
							if(ot_get_option('video_toolbar_for_post',false) && ot_get_option('video_toolbar_position','top')=='bottom'){
								get_template_part('single','toolbar');
							}
						endwhile;
					endif;
					//share
					$social_post= get_post_meta($post->ID,'show_hide_social',true);
					if($social_post=='show'){ //check if show social share
						gp_social_share(get_the_ID());
					}
					if($social_post=='def'){
						if( ot_get_option( 'blog_show_socialsharing', 1)){ //check if show social share
							gp_social_share(get_the_ID());
						}
					}
					//author
					$onoff_author = ot_get_option( 'onoff_author');
					if($onoff_author!='0'){
					?>
						<div class="about-author">
							<div class="author-avatar">
								<?php echo tm_author_avatar(); ?>
							</div>
							<div class="author-info">
								<h5><?php echo __('About The Author','cactusthemes'); ?></h5>
								<?php the_author_posts_link(); ?> - 
								<?php the_author_meta('description'); ?>
							</div>
							<div class="clearfix"></div>
						</div><!--/about-author-->
					<?php } 
				$onoff_postnavi = ot_get_option( 'onoff_postnavi');
				if($onoff_postnavi!='0'){

					?>
					<div class="simple-navigation">
                        <div class="row">
                            <div class="simple-navigation-item col-md-6 col-sm-6 col-xs-6">
                            <?php 
                                $p = get_adjacent_post(true, '', true);
                                if(!empty($p)){ echo '<a href="' . get_permalink($p->ID) . '" title="' . esc_attr($p->post_title) . '" class="maincolor2hover">
                                <i class="fa fa-angle-left pull-left"></i>
                                <div class="simple-navigation-item-content">
                                    <span>'.__('Previous','cactusthemes').'</span>
                                    <h4>' . apply_filters('the_title', $p->post_title) . '</h4>
                                </div>							
                                </a>';} 
                            ?>
                            </div>
                            <div class="simple-navigation-item col-md-6 col-sm-6 col-xs-6">
                            <?php 
                                $n = get_adjacent_post(true, '', false);
                                if(!empty($n)) echo '<a href="' . get_permalink($n->ID) . '" title="' . esc_attr($n->post_title) . '" class="maincolor2hover pull-right">
                                <i class="fa fa-angle-right pull-right"></i>
                                <div class="simple-navigation-item-content">
                                    <span>'.__('Next','cactusthemes').'</span>
                                    <h4>' . apply_filters('the_title', $n->post_title) . '</h4>
                                </div>
                                </a>'; 
                                ?>
                            </div>
                        </div>
                    </div><!--/simple-nav-->
                	<?php }
					comments_template( '', true );
					?>
                </div><!--#content-->
                <?php if($layout != 'full'){
					get_sidebar();
				}?>
            </div><!--/row-->
        </div><!--/container-->
    </div><!--/body-->
<?php get_footer(); ?>