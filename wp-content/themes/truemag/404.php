<?php get_header();
global $global_title;
$global_title = ot_get_option('page404_title',$global_title);
$subtitle = ot_get_option('page404_subtitle','Page not found');
$topnav_style = ot_get_option('topnav_style','dark');
?>
	<div class="blog-heading <?php echo $topnav_style=='light'?'heading-light':'' ?>">
    	<div class="container">
            <h1><?php echo $global_title; ?></h1>
            <?php echo $subtitle?'<span>'.$subtitle.'</span>':'' ?>
        </div>
    </div><!--blog-heading-->
    <div id="body">
        <div class="container">
            <div class="row">
            	<div class="col-md-4"><!----></div>
  				<div id="content" class="content-404 col-md-4" role="main">
                	<h1 class="maincolor2">404</h1>
                    <p><?php echo ot_get_option('page404_content','Please try a search.'); ?></p>
                    <div class="search">
					<?php if ( is_active_sidebar( 'search_sidebar' ) ) : ?>
                        <?php dynamic_sidebar( 'search_sidebar' ); ?>
                    <?php else: ?>
                    	<form class="light-form" action="<?php echo home_url() ?>">
                        	<div class="input-group">
								<input type="text" name="s" class="form-control" placeholder="<?php echo __('Seach for videos','cactusthemes');?>">
								<span class="input-group-btn">
                                	<button class="btn btn-default maincolor1 maincolor1hover" type="button"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </form>
                    <?php endif; ?>
                    </div><!--/search-->
                </div><!--#content-->
                <div class="col-md-4"><!----></div>
            </div><!--/row-->
        </div><!--/container-->
    </div><!--/body-->
<?php get_footer(); ?>