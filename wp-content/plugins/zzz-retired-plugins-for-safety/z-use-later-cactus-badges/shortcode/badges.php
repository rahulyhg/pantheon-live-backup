<?php
function parse_cactus_badges($atts, $content = null){
	$id = isset($atts['id']) && $atts['id']!='' ? $atts['id'] : get_the_ID();
	ob_start();
	$terms = wp_get_post_terms( $id, 'cactus_badges');
	if(!empty($terms)){?>
		<div class="badges-group">
        	<?php 
			foreach ($terms as $term) {
				$tax_img ='';
				if(function_exists('z_taxonomy_image_url')){ $tax_img = z_taxonomy_image_url($term->term_id);}
				if($tax_img==''){ $tax_img = get_option('url_image_' . $term->term_id);}?>
                <div class="badges-item">
                    <img src="<?php echo esc_url($tax_img);?>" alt="<?php echo esc_attr(get_the_title($id))?>">
                </div><?php 
			}?>
		</div>
		<?php
	}
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;
}
add_shortcode( 'cactus_badges', 'parse_cactus_badges' );