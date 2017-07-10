<?php 
/*
 * Template Name: Video - Sitemap
 */
header('Content-Type: application/xml');
$offset_videosm = get_post_meta(get_the_ID(),'offset_videosm',true);
if($offset_videosm==''  || !is_numeric($offset_videosm)){$offset_videosm = 0;}
$number_videosm = get_post_meta(get_the_ID(),'number_videosm',true);
if($number_videosm=='' || !is_numeric($number_videosm)){ $number_videosm = -1;}
else{$number_videosm = $number_videosm - $offset_videosm;}
$args=array(
	'post_type' => 'post',
	'posts_per_page' => $number_videosm,
    'offset' => $offset_videosm,
	'orderby' => 'date',
	'post_status' => 'publish',
	'tax_query' => array(
		array(                
			'taxonomy' => 'post_format',
			'field' => 'slug',
			'terms' => array(
				'post-format-video'
			),
			'operator' => 'IN'
		)
	)
);
$listing_query = new WP_Query($args);
if ($listing_query->have_posts()) : ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"> 
<?php
while($listing_query->have_posts()){$listing_query->the_post();
$time_video = get_post_meta(get_the_id(),'time_video',true);
$time_video = explode(":",$time_video);
$count = count($time_video);
$i=0;
$total_time = 0;
if($count==3){
	$total_time = ($time_video[0]*60*60) + ($time_video[1]*60) + $time_video[2];
}elseif($count==2){
	$total_time = ($time_video[0]*60) + $time_video[1];
}elseif($count==1){
	$total_time = $time_video[0];
}
$file = get_post_meta($post->ID, 'tm_video_file', true);
$files = !empty($file) ? explode("\n", $file) : array();
?>
   <url> 
     <loc><?php echo get_permalink(get_the_ID());?></loc>
     <video:video>
       <?php if(wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()))!=''){?> 	 
       <video:thumbnail_loc><?php echo esc_url(wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()))); ?></video:thumbnail_loc> 
       <?php }
	   if(get_the_title(get_the_ID())!=''){?>
       <video:title><?php echo esc_html(html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8'));?></video:title>
       <?php }
	   if( has_excerpt()){?>
       <video:description><?php echo esc_html(html_entity_decode(get_the_excerpt(), ENT_QUOTES, 'UTF-8'));?></video:description>
       <?php }
	   if(isset($files[0]) && $files[0]!=''){?>
       <video:content_loc><?php echo esc_url($files[0]);?></video:content_loc>
       <?php }
	   if($total_time!=''){?>
       <video:duration><?php echo esc_attr($total_time);?></video:duration>
       <?php }
	   $view_c = tm_short_number(get_post_meta(get_the_ID(),'_count-views_all',true));
	   if($view_c!='')
	   ?>
       <video:view_count><?php echo $view_c;?></video:view_count>    
       <video:publication_date><?php echo get_the_time('Y-m-d', get_the_ID()); ?></video:publication_date>
       <?php 
	   $posttags = get_the_tags();
	   if ($posttags) {
		  foreach($posttags as $tag) {
	   ?>
       <video:tag><?php echo esc_html(html_entity_decode($tag->name, ENT_QUOTES, 'UTF-8')); ?></video:tag> 
       <?php  }
	   }?>
        <?php 
	   $categories  = get_the_category();
	   if ($categories ) {
		  foreach($categories  as $category) {
		   ?>
		<video:category><?php echo esc_html(html_entity_decode($category->name, ENT_QUOTES, 'UTF-8')); ?></video:category> 
		   <?php 
		   break;
	      }
	   }?>
       <video:uploader info="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) )); ?>"><?php echo esc_html(html_entity_decode(get_the_author_meta( 'display_name' ), ENT_QUOTES, 'UTF-8')); ?></video:uploader>
     </video:video> 
   </url> 
<?php }?>
</urlset>
<?php
endif; wp_reset_postdata(); ?>
