<?php
$format = get_post_format();
global $post;
$image_or_playervideo = ot_get_option('show_image_or_player');
if($format=='gallery'){ ?>
	<div class="is-carousel simple-carousel" id="post-gallery-<?php the_ID() ?>">
		<div class="simple-carousel-content carousel-content">
<?php
	$images=&get_children('post_type=attachment&numberposts=5&post_mime_type=image&post_parent='.get_the_ID());
	foreach((array)$images as $attachment_id => $attachment){
		$image = wp_get_attachment_image_src( $attachment_id, 'thumb_365x235' );
		echo "<img src='".$image[0]."'>";
	} ?>
		</div><!--/simple-carousel-->
		<a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
			<div class="link-overlay fa fa-search"></div>
		</a>
		<div class="carousel-pagination"></div>
	</div><!--/is-carousel-->
	<?php
}elseif($format=='video' && $image_or_playervideo!='0' && !is_search()){ //video
	$file = get_post_meta($post->ID, 'tm_video_file', true);
	$url = trim(get_post_meta($post->ID, 'tm_video_url', true));
	$code = trim(get_post_meta($post->ID, 'tm_video_code', true));
	if(strpos($url,'facebook.com') !== false){ ?>
        <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
            <?php the_post_thumbnail( 'thumb_365x235' ) ?>
            <div class="link-overlay fa fa-play"></div>
        </a>
    <?php }else{?>
    <div class="player-embed">
		<?php $url = tm_video($post->ID, false);?>
    </div> 
    <?php }?>
<?php
}elseif($format=='audio'){
	preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $post->post_content, $match);
	foreach($match[0] as $amatch){
		if (strpos($amatch,'soundcloud.com') !== false) {
			echo wp_oembed_get($amatch);
			break;
		}
	}
}else{ //standard
	if(has_post_thumbnail()){ ?>
        <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
            <?php the_post_thumbnail( 'thumb_365x235' ) ?>
            <div class="link-overlay fa fa-search"></div>
        </a>
   	<?php }?>
<?php } ?>