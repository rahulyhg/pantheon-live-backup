<?php
/**
 * The Template for displaying all single video posts with standard layout.
 *
 * @package deTube
 * @subpackage Template
 * @since deTbue 1.1
 */

get_header(); 

$info_toggle = (int)get_option('dp_info_toggle');
?>
<div class="bg-container single-post-body"> 
    <div class="body-top-color"><!----></div>
    <div class="background-color"><!----></div> 
    <div class="container">
		<div class="container-pad">

            <div id="main"><div class="wrap cf">
                
                <div class="entry-header cf">
                <div class="inner cf">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <?php if(function_exists('tm_like_post')){tm_like_post($post_id);} ?>
                </div><!-- end .entry-header>.inner -->
                </div><!-- end .entry-header -->
                
                <div id="content" role="main">
                    <?php while (have_posts()) : the_post(); global $post;?>
                    
                    <div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
                    
                    <div id="video">
                        <div class="screen fluid-width-video-wrapper">
                        	<div id="player">
                            <?php $url = (tm_video($post->ID, get_option('dp_single_video_autoplay'))); ?> 
                            </div>
                            <?php $tern_wp_youtube_video = get_post_meta($post->ID, '_tern_wp_youtube_video', true);
							if ( ! isset( $content_width ) ) $content_width = 900;
							$file = get_post_meta($post->ID, 'tm_video_file', true);
							$url = trim(get_post_meta($post->ID, 'tm_video_url', true));
							$code = trim(get_post_meta($post->ID, 'tm_video_code', true));
							function getYouTubeIdFromURL($url)
							{
							  $url_string = parse_url($url, PHP_URL_QUERY);
							  parse_str($url_string, $args);
							  return isset($args['v']) ? $args['v'] : false;
							}
							$auto_load= ot_get_option('auto_load_next_vieo');
							//auto-load
							if($auto_load=='1'){
							if((strpos($file, 'youtube.com') !== false )|| (strpos($url, 'youtube.com') !== false )|| (strpos($code, 'youtube.com') !== false ) ){
							 ?>
                            <script src="http://www.youtube.com/player_api"></script>
							<script>
                                // create youtube player
                                var player;
                                function onYouTubePlayerAPIReady() {
                                    player = new YT.Player('player', {
                                      height: '506',
                                      width: '900',
                                      videoId: '<?php echo getYouTubeIdFromURL($url); ?>',
                                      events: {
                                        'onReady': onPlayerReady,
                                        'onStateChange': onPlayerStateChange
                                      }
                                    });
                                }
                        
                                // autoplay video
                                function onPlayerReady(event) {
                                    event.target.playVideo();
                                }
                        
                                // when video ends
                                function onPlayerStateChange(event) {        
                                    if(event.data === 0) {          
 										var link = jQuery('.next-post a').attr('href');
										if(link !=undefined){
											window.location.href= link;
										}
                                    }
                                }
                            </script>
                            <?php }else
							if((strpos($file, 'vimeo.com') !== false )|| (strpos($url, 'vimeo.com') !== false )|| (strpos($code, 'vimeo.com') !== false ) ){
							
							?>
                            <script src="http://a.vimeocdn.com/js/froogaloop2.min.js"></script>
                            <script>
								jQuery(document).ready(function() {	
									jQuery('iframe').attr('id', 'player_1');
						
									var iframe = jQuery('#player_1')[0],
										player = $f(iframe),
										status = jQuery('.status');
									
									// When the player is ready, add listeners for pause, finish, and playProgress
									player.addEvent('ready', function() {
										status.text('ready');
										
										player.addEvent('pause', onPause);
										player.addEvent('finish', onFinish);
										player.addEvent('playProgress', onPlayProgress);
									});
									
									// Call the API when a button is pressed
									jQuery(window).load(function() {
										player.api(jQuery(this).text().toLowerCase());
									});
									
									function onPause(id) {
									}
									
									function onFinish(id) {
										var link = jQuery('.next-post a').attr('href');
										if(link !=undefined){
											window.location.href= link;
										}
									}
									
								});
        								
                            </script>
                            <?php } else{?>
							 <script type="text/javascript">
                                jwplayer("player_1").setup({
                                        width: 465,
                                        height: 300,
                                        events:{
                                        onComplete: function() {
                                            alert("hi!");
                                            }
                                        }
                                });
                            </script> 
                            <?php }}
							//end auto
							?>
                            </div><!-- end .screen -->
                    </div><!-- end #video-->                    
                    <div id="details" class="section-box">
                        <div class="section-content">
                        <div id="info"<?php if(!empty($info_toggle)) echo ' class="" data-less-height="'.$info_toggle.'"'; ?>>
                            <p class="entry-meta">
                                <span class="author"><?php _e('Added by', 'dp'); ?> <?php the_author_posts_link(); ?></span>
                                <span class="time"><?php _e('on', 'dp'); ?> <?php the_date(); ?></span>
                                
                                <?php edit_post_link(__('Edit', 'dp'), ' <span class="sep">/</span> '); ?>
                            </p>
            
                            <div class="entry-content rich-content">
                                <?php  the_content(); ?>
                                <?php wp_link_pages(array('before' => '<p class="entry-nav pag-nav"><span>'.__('Pages:', 'dp').'</span> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
                            </div><!-- end .entry-content -->
                        
                            <div id="extras">
                                <h4><?php _e('Category:', 'dp'); ?></h4> <?php the_category(', '); ?>
                                <?php the_tags('<h4>'.__('Tags:', 'dp').'</h4>', ', ', ''); ?>
                            </div>
                        </div><!-- end #info -->
                        </div><!-- end .section-content -->
                        </div><!--end #deatils-->
                    
                    </div><!-- end #post-<?php the_ID(); ?> -->
                    <?php //comments_template('', true); ?>
                            <?php $auto_load_same_cat= ot_get_option('auto_load_same_cat');
				if($auto_load_same_cat=='1'){?>
                <div class="post-nav">
                    <div class="prev-post"><?php previous_post_link('&laquo; %link','%title',TRUE,'') ; ?></div>
                    <div class="next-post"><?php next_post_link('%link &raquo;','%title',TRUE,''); ?></div>
           		<?php }else if($auto_load_same_cat=='0'){?>
                 <div class="post-nav">
                 <?php 
				    $test = "";
					$posttags = get_the_tags();
					if ($posttags) {
					foreach($posttags as $tag) {
						$test .= ',' . $tag->slug; 
					}
					}
					$test = substr($test, 1); 
				 $args = array(
					'post_type' => 'post',
					'post_status' => 'publish',
					'tag' => $test,
				 );
				 $current_key = '';
				 $tm_query = get_posts($args);
				 //print_r($tm_query);
				 foreach ( $tm_query as $key => $post ) : setup_postdata( $post );
				 	if($post->ID == get_the_ID()){$current_key = $key;}
				 endforeach;
				 $next = get_permalink($tm_query[$current_key+1]->ID); 
				 $previous = get_permalink($tm_query[$current_key-1]->ID);
                 ?>
				<div class="post-nav">
                    <?php if($next!=''){?><div class="prev-post"><a href="<?php echo $next;?>" >Next post: </a><span></span></div><?php }?>
                    <?php if($previous!=''){?><div class="next-post"><a href="<?php echo $previous;?>" >Previous post: </a><span></span></div><?php }?>
                </div>
				 
                </div>
                <?php wp_reset_postdata(); }else {?>
                 <div class="post-nav">
                    <?php previous_post_link('<div class="prev-post">Next post: <span>%link</span></div>'); ?>
                    <?php next_post_link('<div class="next-post">Previous post: <span>%link</span></div>'); ?>
                </div>

                    <?php  } endwhile; ?>
                </div><!-- end #content -->
                <?php get_sidebar(); ?>
            
            </div></div><!-- end #main -->
</div>
</div>
</div>	
<?php get_footer(); ?>