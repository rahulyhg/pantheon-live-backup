<?php

// template part used in loop get_terms.
$cat_img = '';
if(function_exists('z_taxonomy_image_url')){ $cat_img = z_taxonomy_image_url($term->term_id);}
$video_series_release                = get_option('video_series_release_' . $term->term_id);
$video_series_creator                = get_option('video_series_creator_' . $term->term_id);
$video_series_stars                = get_option('video_series_stars_' . $term->term_id);
$des = term_description( $term->term_id, 'video-series' ) ;
?>
<article class="cactus-post-item hentry">
                                                
	<div class="entry-content">                                        
		
		<!--picture (remove)-->
		<div class="picture has-tooltip">
			<div class="picture-content">
            	<?php if($cat_img!=''){?>
				<a href="<?php echo get_term_link($term);?>" title="<?php echo esc_attr($term->name);?>">
					<img src="<?php echo esc_url($cat_img);?>" alt="<?php echo esc_attr($term->name);?>">                                                       
				</a>  
				<?php }?>
			</div>                              
		</div><!--picture-->
		
		<div class="content">
																		
			<!--Title (no title remove)-->
			<h3 class="cactus-post-title entry-title h4"> 
				<a href="<?php echo get_term_link($term);?>" title="<?php echo $term->name;?>"><?php echo $term->name;?></a> 
			</h3><!--Title-->
			
			<div class="posted-on metadata-font">
				<div class="videos cactus-info"><span><?php echo $term->count > 1 ? sprintf(esc_html__('%d VIDEOS', 'cactusthemes'), $term->count) : esc_html__('1 VIDEO', 'cactusthemes');?></span></div>
			</div>
			
		</div>
		
	</div>
	
</article>