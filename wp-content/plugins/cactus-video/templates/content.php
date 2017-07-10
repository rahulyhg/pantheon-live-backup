<?php
/**
 * @package cactus
 */
global $thumb_url;
global $post_standard_layout;
global $post_gallery_layout;
$tags = ot_get_option('show_tags_single_post');
	global $post;
	$checkSmartListPost = 0;
	if(strpos($post->post_content, '<!--nextpage-->')!=false &&( !isset($_GET['view-all']) || $_GET['view-all']!=1 )){
		$checkSmartListPost = 1;
	};
	$paged = get_query_var('page')?get_query_var('page'):1;
	?>
    <div class="heading-post">                                            
        <!--info-->
        <div class="posted-on">
            <?php show_cat() ?>
            <div class="fix-responsive"></div>
            <?php echo cactus_get_datetime();?>
            <span class="vcard author"> 
                <span class="fn"><?php the_author_posts_link(); ?></span>
            </span>
            <a href="<?php comments_link(); ?>" class="comment cactus-info"><?php echo get_comments_number(get_the_ID());?></a>                                               
        </div><!--info-->
        
        <!--Title-->
        <?php the_title( '<h1 class="h3 title">', tm_post_rating(get_the_ID(), true) . '</h1>' ); ?>
        <!--Title-->
        
    </div>
    <?php
	$csscl ='';
	$more = 1;
	cactus_toolbar($id_curr = get_the_ID(),1, $more, $csscl);?>    
	<div class="body-content " <?php if((isset($_GET['view-all'])) && ($_GET['view-all']==1)) {?>data-scroll-page-viewall="1"<?php }?>>
    	
		<?php 
		if( $checkSmartListPost == 1){
			preg_match('/\<h2(.*)\<\/h2\>/isU',get_the_content(), $h2_tag);
			?>
        	<div class="smart-list-post-wrap">
            	
                
                <h2 class="h3 title-page-post"><span class="post-static-page" <?php if($paged>1){?> data-scroll-page="1"<?php } ?>><?php echo $paged;?></span><span><?php if(isset($h2_tag[0])){echo strip_tags($h2_tag[0]);}?></span></h2>
                
                <?php
				wp_link_pages( 
					array(
						'before' => '<div class="page-links">',
						'after'  => '</div>',
						'nextpagelink'     => __( '<i class="fa fa-angle-right"></i>' ),
						'previouspagelink' => __( '<i class="fa fa-angle-left"></i>' ),
						'next_or_number'   => 'next',
					) 
				);
				?>
            </div>
        <?php    
		};
		if(isset($_GET['view-all']) && $_GET['view-all'] == 1){
			$ct =  $post->post_content = str_replace( "<!--nextpage-->", "<br/>", $post->post_content );
			echo apply_filters('the_content',$ct);
		}else{
			if(isset($h2_tag[0])){
				$content =  preg_replace ('/\<h2(.*)\<\/h2\>/isU', ' ', get_the_content());
				echo apply_filters('the_content',$content);
			}else{
			the_content();} ?>
		<?php
			
			if(strpos($post->post_content, '<!--nextpage-->')!=false){?>
            	<div class="viewallpost-wrap">
                    <div class="cactus-view-all-pages">
                        <span><span></span></span>
                        <a href="<?php echo add_query_arg( array('view-all' => 1), ct_get_curent_url() ); ?>" title=""><span><?php echo __( 'View as one page', 'cactus' )?></span></a>
                        <span><span></span></span>
                    </div>
                </div>
                <?php
			}
		}
		?>
	</div><!-- .entry-content -->
    
    <?php
	$tag_list = get_the_tag_list(' ', ' ');
	if($tags!='off' && str_replace(' ', '', $tag_list) !=''){?>
    <div class="tag-group">
        <span><?php _e('tags:','cactus') ?></span>
        <?php echo $tag_list; ?>
    </div>
    <?php }
		cactus_toolbar($id_curr = get_the_ID(), 1, $show_more=0, $css_class='fix-bottom');
	?> 
    <!--navigation post-->
    <div class="cactus-navigation-post">
    	<?php 
		$next_previous_same = ot_get_option('next_previous_same');
		if($next_previous_same!='all'){
			$p = get_adjacent_post(true, '', true);
			$n = get_adjacent_post(true, '', false);
		}else{
			$p = get_adjacent_post(false, '', true);
			$n = get_adjacent_post(false, '', false);
		}
		if(!empty($p)){ 
		?>
        <div class="prev-post">
            <a href="<?php echo get_permalink($p->ID) ?>" title="<?php echo esc_attr($p->post_title) ?>">
                <span><?php echo __( 'previous', 'cactus' )?></span>
                <?php echo esc_attr($p->post_title) ?>
            </a>
        </div>
        <?php }
		if(!empty($n)){ 
		?>
        <div class="next-post">
            <a href="<?php echo get_permalink($n->ID) ?>" title="<?php echo esc_attr($n->post_title) ?>">
                <span><?php echo __( 'next', 'cactus' )?></span>
                <?php echo esc_attr($n->post_title) ?>
            </a>
        </div>
        <?php }?>
    </div>
    <!--navigation post-->
    <?php 
    $show_related_post = ot_get_option('show_related_post','on');
	if($show_related_post!='off'){
	?>
    <!--Author-->
    <div class="cactus-author-post">
        <div class="cactus-author-pic">
            <div class="img-content">
                <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="avatar">
                <?php echo get_avatar( get_the_author_meta('email'), 110 ); ?>
                </a>
            </div>
        </div>
        <div class="cactus-author-content">
            <div class="author-content">
                <span class="author-name"><?php the_author_meta( 'display_name' ); ?></span>
                <span class="author-body"><?php the_author_meta('description'); ?></span>
                
                <ul class="social-listing list-inline">                	                 
					<?php if(get_the_author_meta( 'facebook')!=''){ ?>
                    <li class="facebook">                                                
                        <a title="Facebook" href="<?php echo get_the_author_meta( 'facebook' ); ?>" target="_blank" rel="nofollow"><i class="fa fa-facebook"></i></a>
                    </li>
                    <?php }?>
                    <?php if(get_the_author_meta( 'twitter' )!=''){ ?>
                    <li class="twitter">                                                
                        <a href="<?php echo get_the_author_meta( 'twitter'); ?>" title="Twitter" rel="nofollow" target="_blank" ><i class="fa fa-twitter"></i></a>
                    </li>
                    <?php }?>
                    <?php if(get_the_author_meta( 'linkedin' )!=''){ ?>
                    <li class="linkedin">                                                
                        <a href="<?php echo get_the_author_meta( 'linkedin'); ?>" title="LinkedIn" rel="nofollow" target="_blank"><i class="fa fa-linkedin"></i></a>
                    </li>
                    <?php }?>
                    <?php if(get_the_author_meta( 'tumblr' )!=''){ ?>
                    <li class="tumblr">                                                
                        <a href="<?php echo get_the_author_meta( 'tumblr'); ?>" title="Tumblr" rel="nofollow" target="_blank"><i class="fa fa-tumblr"></i></a>
                    </li>
                    <?php }?>
                    <?php if(get_the_author_meta( 'google' )!=''){ ?>
                    <li class="google-plus">                                                
                        <a href="<?php echo get_the_author_meta( 'google' ); ?>" title="Google Plus" rel="nofollow" target="_blank"><i class="fa fa-google-plus"></i></a>
                    </li>
                    <?php }?>
                    <?php if(get_the_author_meta( 'pinterest' )!=''){ ?>
                    <li class="pinterest">                                                
                        <a href="<?php echo get_the_author_meta( 'pinterest' ); ?>" title="Pinterest" rel="nofollow" target="_blank"><i class="fa fa-pinterest"></i></a>
                    </li>
                    <?php }?>
                    <?php if(get_the_author_meta( 'author_email' )!=''){ ?>
                    <li class="email">                                                
                        <a href="mailto:<?php echo get_the_author_meta( 'author_email' ); ?>" title="Email"><i class="fa fa-envelope-o"></i></a>
                    </li>
                    <?php }?>
                </ul>
                
            </div>
        </div>
    </div>
    <!--Author-->
    <?php }?>