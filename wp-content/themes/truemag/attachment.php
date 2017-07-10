<?php
get_header();
$layout = ot_get_option('single_layout_ct_video','right');
global $sidebar_width;
?>
    <div id="body">
        <div class="container">
            <div class="row">
  				<div id="content" class="<?php echo $layout!='full'?($sidebar_width?'col-md-9':'col-md-8'):'col-md-12' ?><?php echo ($layout == 'left') ? " revert-layout":"";?>" role="main">
                	<?php
					//content
					$format = get_post_format();
					if($format == 'gallery'){
						echo '<div id="post-gallery">';
						echo do_shortcode('[gallery]');
						echo '</div>';
					}elseif($format == 'image'){
						echo '<div id="post-thumb">';
						the_post_thumbnail('full');
						echo '</div>';
					}
					//content
					if (have_posts()) :
					while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'image-attachment' ); ?>>
                        <header class="entry-header">
                            <h1 class="light-title"><?php the_title(); ?></h1>
                            <footer class="entry-meta">
                                <?php
                                    $metadata = wp_get_attachment_metadata();
                                    printf( __( '<span class="meta-prep meta-prep-entry-date">Published </span> <span class="entry-date"><time class="entry-date" datetime="%1$s">%2$s</time></span> at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%8$s</a>.', 'twentytwelve' ),
                                        esc_attr( get_the_date( 'c' ) ),
                                        esc_html( date_i18n(get_option('date_format') ,get_the_time('U')) ),
                                        esc_url( wp_get_attachment_url() ),
                                        $metadata['width'],
                                        $metadata['height'],
                                        esc_url( get_permalink( $post->post_parent ) ),
                                        esc_attr( strip_tags( get_the_title( $post->post_parent ) ) ),
                                        get_the_title( $post->post_parent )
                                    );
                                ?>
                                <?php edit_post_link( __( 'Edit', 'twentytwelve' ), '<span class="edit-link">', '</span>' ); ?>
                            </footer><!-- .entry-meta -->
                        </header><!-- .entry-header -->
                        <div class="entry-content">
    
                            <div class="entry-attachment">
                                <div class="attachment">
    <?php
    /**
     * Grab the IDs of all the image attachments in a gallery so we can get the URL of the next adjacent image in a gallery,
     * or the first image (if we're looking at the last image in a gallery), or, in a gallery of one, just the link to that image file
     */
    $attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
    foreach ( $attachments as $k => $attachment ) :
        if ( $attachment->ID == $post->ID )
            break;
    endforeach;
    
    $k++;
    // If there is more than 1 attachment in a gallery
    if ( count( $attachments ) > 1 ) :
        if ( isset( $attachments[ $k ] ) ) :
            // get the URL of the next image attachment
            $next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
        else :
            // or get the URL of the first image attachment
            $next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
        endif;
    else :
        // or, if there's only 1 image, get the URL of the image
        $next_attachment_url = wp_get_attachment_url();
    endif;
    ?>
                                    <a href="<?php echo esc_url( $next_attachment_url ); ?>" title="<?php the_title_attribute(); ?>" rel="attachment"><?php
                                    $attachment_size = apply_filters( 'twentytwelve_attachment_size', array( 960, 960 ) );
                                    echo wp_get_attachment_image( $post->ID, $attachment_size );
                                    ?></a>
    
                                    <?php if ( ! empty( $post->post_excerpt ) ) : ?>
                                    <div class="entry-caption">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    <?php endif; ?>
                                </div><!-- .attachment -->
    
                            </div><!-- .entry-attachment -->
    
                            <div class="entry-description">
                                <?php the_content(); ?>
                                <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'twentytwelve' ), 'after' => '</div>' ) ); ?>
                            </div><!-- .entry-description -->
                        </div><!-- .entry-content -->
                    </article><!-- #post -->
                <?php endwhile;
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
					if(ot_get_option( 'blog_show_authorbio', 1)){?>
						<div class="about-author">
							<div class="author-avatar">
								<?php echo get_avatar( get_the_author_meta('email'), '60' ); ?>
							</div>
							<div class="author-info">
								<h5><?php echo __('About The Author','cactusthemes'); ?></h5>
								<?php the_author(); ?> 
								<?php the_author_meta('description'); ?>
							</div>
							<div class="clearfix"></div>
						</div><!--/about-author-->
					<?php }
					//navigation
					?>
                    <div class="simple-navigation">
                        <div class="row">
                            <div class="simple-navigation-item col-md-6 col-sm-6 col-xs-6">
                                <?php previous_image_link( false, '<i class="fa fa-angle-left pull-left"></i><div class="simple-navigation-item-content"><span>Previous</span><h4>IMAGE</h4></div>' ); ?>
                            </div>
                            <div class="simple-navigation-item col-md-6 col-sm-6 col-xs-6">
                                <?php next_image_link( false, '<i class="fa fa-angle-right pull-right"></i><div class="simple-navigation-item-content"><span>Next</span><h4>IMAGE</h4></div>' ); ?>
                            </div>
                        </div>
                    </div>
                    <?php
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