<?php 
get_header();
$default_style = ot_get_option('default_listing_layout');
if($default_style =='style-list-1'){?>
	<style>
		.tooltipster-base{ display:none !important}
	</style>
<?php }
$layout = ot_get_option('blog_layout','right');
global $sidebar_width;
global $global_title;
if(is_category()){
	$cat_id = get_query_var('cat');
	$style = get_option( "cat_layout_$cat_id")?get_option( "cat_layout_$cat_id"):ot_get_option('blog_style','video');
	$layout = get_option( "cat_page_layout_$cat_id")?get_option( "cat_page_layout_$cat_id"):ot_get_option('blog_layout','right');
	$subtitle=category_description( $cat_id );
}else{
	$style = ot_get_option('blog_style','video');
	if(get_option('page_for_posts')&&is_home()){
		$subtitle = get_post_field('post_content',get_option('page_for_posts'));
	}else{
		$subtitle = '';
	}
}
$topnav_style = ot_get_option('topnav_style','dark');
if($style=='blog'&&(ot_get_option('show_blog_title',1)||!is_home())&&!is_author()){
	?>
	<div class="blog-heading  <?php echo $topnav_style=='light'?'heading-light':'' ?>">
    	<div class="container">
            <h1><?php echo $global_title; ?></h1>
            <?php echo $subtitle?'<span>'.$subtitle.'</span>':'' ?>
        </div>
    </div><!--blog-heading-->
<?php }elseif(is_author()){
	$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
	?>
	<div class="blog-heading  <?php echo $topnav_style=='light'?'heading-light':'' ?> author-heading">
    	<div class="container">
            <div class="header-about-author">
                <div class="author-avatar">
					<?php echo tm_author_avatar(false,125); ?>
                </div>
                <div class="author-info row">
                	<div class="col-md-7 col-sm-8">
                        <h1><?php the_author_meta('display_name',$author->ID); ?></h1>
                        <span><?php the_author_meta('description',$author->ID); ?></span>
                    </div>
                    <div class="col-md-5 col-sm-4">
                    	<div class="author-social pull-right">
						<?php if($website = get_the_author_meta('user_url',$author->ID)){?>
							<a rel="nofollow" href="<?php echo $website ?>" title="Website"><i class="fa fa-2x fa-link"></i></a>
						<?php } ?>
                        <?php if($twitter = get_the_author_meta('twitter',$author->ID)){ ?>
                            <a rel="nofollow" href="<?php echo $twitter ?>" title="Twitter"><i class="fa fa-2x fa-twitter"></i></a>
                        <?php }
						if($facebook = get_the_author_meta('facebook',$author->ID)){ ?>
                            <a rel="nofollow" href="<?php echo $facebook ?>" title="Facebook"><i class="fa fa-2x fa-facebook"></i></a>
                        <?php }
						if($flickr = get_the_author_meta('flickr',$author->ID)){ ?>
                            <a rel="nofollow" href="<?php echo $flickr ?>" title="Flickr"><i class="fa fa-2x fa-flickr"></i></a>
                        <?php }
						if($google = get_the_author_meta('google',$author->ID)){ ?>
                            <a rel="nofollow" href="<?php echo $google ?>" title="Google Plus"><i class="fa fa-2x fa-google-plus"></i></a>
                        <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div><!--/about-author-->
        </div>
    </div><!--blog-heading-->
    
<?php }?>
    <div id="body">
        <div class="container">
            <div class="row">
				<?php $pagination = ot_get_option('pagination_style','page_def');?>
  				<div id="content" class="<?php echo $layout!='full'?($sidebar_width?'col-md-9':'col-md-8'):'col-md-12' ?><?php echo ($layout == 'left') ? " revert-layout":"";?>" role="main">
					<?php tm_display_ads('ad_body_1');?>
                	<?php if (have_posts()) : ?>
						<section class="<?php echo $style=='blog'?'blog-listing':'video-listing'; echo ot_get_option('default_listing_layout')?' '.ot_get_option('default_listing_layout'):''; ?>">
                        	<?php if($style=='video'){ ?>
                            <div class="video-listing-head">
                            	<?php 
								if(is_home()){
									if(ot_get_option('show_blog_title','1')){?>
										<h2 class="light-title"><?php global $global_title; echo $global_title ?></h2>
									<?php }
								}elseif(!is_author()){ ?>
								<h2 class="light-title"><?php global $global_title; echo $global_title ?></h2>
								 <?php }?>
                                <?php get_template_part('loop-filter'); ?>
                            </div>
                            <?php }?>
                            <div class="<?php echo $style=='blog'?'blog-listing-content':'video-listing-content' ?> <?php if($pagination=='page_ajax'||$pagination==''){ echo 'tm_load_ajax';} ?>  ">
								<?php
								if($style=='blog'){
									get_template_part('loop-blog');
								}else{
									get_template_part('loop-item');
								}
								?>
                            </div><!--/video-listing-content(blog-listing-content)-->
                            <div class="clearfix"></div>
						<?php if( $pagination=='page_navi' && function_exists( 'wp_pagenavi' )){
							wp_pagenavi();
						}else if($pagination=='page_def'){
							cactusthemes_content_nav('paging');
						}?>
                        </section>
					<?php endif; wp_reset_query(); ?>
					<?php tm_display_ads('ad_body_2');?>
                </div><!--#content-->
                <?php if($layout != 'full'){ get_sidebar(); } ?>
            </div><!--/row-->
        </div><!--/container-->
    </div><!--/body-->
<?php get_footer(); ?>