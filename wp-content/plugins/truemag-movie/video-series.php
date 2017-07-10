<?php
add_action( 'init', 'create_series_taxonomies', 0 );
function create_series_taxonomies(){
	if(function_exists('ot_get_option') && ot_get_option('enable_series','on')!='off'){
	$series_label = array(
		'name'              => __( 'Series', 'cactusthemes' ),
		'singular_name'     => __( 'Series', 'cactusthemes' ),
		'search_items'      => __( 'Search','cactusthemes' ),
		'all_items'         => __( 'All Series','cactusthemes' ),
		'parent_item'       => __( 'Parent Series' ,'cactusthemes'),
		'parent_item_colon' => __( 'Parent Series:','cactusthemes' ),
		'edit_item'         => __( 'Edit Series' ,'cactusthemes'),
		'update_item'       => __( 'Update Series','cactusthemes' ),
		'add_new_item'      => __( 'Add New Series' ,'cactusthemes'),
		'new_item_name'     => __( 'New Genre Series' ,'cactusthemes'),
		'menu_name'         => __( 'Series' ),
	);
	$args = array(
		'hierarchical'          => true,
		'labels'                => $series_label,
		'show_admin_column'     => true,
		'rewrite'               => array( 'slug' => ot_get_option('series_slug','video-series') ),
	);
	register_taxonomy('video-series', 'post', $args);
	}
}

function get_post_series($post_id = '', $series_id = '', $ids = '', $count = ''){
	$post_id = $post_id?$post_id:get_the_ID();
	$count = $count?$count:-1;
	$args = array();
	if($ids){
		$args = array(
			'post__in' => explode(',',$ids),
			'posts_per_page' => $count,
			'ignore_sticky_posts' => true,
			'order' => 'ASC'
		);
	}elseif($series_id){
		if(is_numeric($series_id)){
			$series = get_term_by('id', $series_id, 'video-series');
			$series_slug = $series->slug;
		}else{
			$series_slug = $series_id;
		}
		$args = array(
			'post_type' => 'post',
			'posts_per_page' => $count,
			'ignore_sticky_posts' => true,
			'order' => 'ASC',
			'video-series' => $series_slug,
		);
	}else{
		$series = wp_get_post_terms($post_id, 'video-series', array("fields" => "all"));
		$series_slug = $series[0]->slug;
		$args = array(
			'post_type' => 'post',
			'posts_per_page' => $count,
			'ignore_sticky_posts' => true,
			'order' => 'ASC',
			'video-series' => $series_slug,
		);
	}
	
	if(!empty($args)){
		$series_query = new WP_Query( $args );
		$series_title = get_post_meta($post_id,'title_in_series',true)?get_post_meta($post_id,'title_in_series',true):get_the_title();
		if($series_query->have_posts()){
			$style = function_exists('ot_get_option')?ot_get_option('series_single_style','link'):'link';
			if($style == 'select'){
				echo '
				<span class="series-dropdown-title">'.__('SELECT EPISODES: ','cactusthemes').'</span>
				<span class="dropdown series-dropdown">
				<button id="series-dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.esc_html($series_title).' <i class="fa fa-angle-down"></i></button>
			    <ul class="dropdown-menu text-left" aria-labelledby="series-dLabel">';
				while($series_query->have_posts()){
					$series_query->the_post();
					$series_title = get_post_meta(get_the_ID(),'title_in_series',true)?get_post_meta(get_the_ID(),'title_in_series',true):get_the_title();
					?>
					<li><a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>">
					<?php echo get_the_ID()==$post_id?'<i class="fa fa-play"></i> ':'' ?>
					<?php echo esc_html($series_title); ?></a></li>
				<?php
				}
				echo '</ul>
				</span>';
				
			}else{
				echo '<ul class="video-series-list list-inline">';
				echo '<li class="series-title">'.__('EPISODES','cactusthemes').':</li>';
				while($series_query->have_posts()){
					$series_query->the_post();
					$series_title = get_post_meta(get_the_ID(),'title_in_series',true)?get_post_meta(get_the_ID(),'title_in_series',true):get_the_title(); ?>
					<li><a class="btn btn-sm btn-default <?php echo get_the_ID()==$post_id?'series-current':'' ?>" href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>"><i class="fa fa-play"></i> <?php echo esc_html($series_title); ?></a></li>
				<?php
				}
				echo '</ul>';
			}
		}//if have post
		wp_reset_postdata();
	}
}

function parse_video_series($atts, $content){	
	$series = isset($atts['series']) ? $atts['series'] : '';
	$ids = isset($atts['ids']) ? $atts['ids'] : '';
	$count = isset($atts['count']) ? $atts['count'] : '';
	ob_start();
	get_post_series('', $series, $ids, $count);
	$html = ob_get_clean();
	return $html;	
}
add_shortcode( 'movie-series', 'parse_video_series' );

add_action( 'after_setup_theme', 'reg_series' );
function reg_series(){
	if(function_exists('vc_map')){
	vc_map( array(
	   "name" => __("Series","cactusthemes"),
	   "base" => "movie-series",
	   "icon" => "movie-series",
	   "class" => "",
	   "controls" => "full",
	   "category" => __('Content'),
	   "params" => array(
		  array(
			 "type" => "textfield",
			 "holder" => "div",
			 "class" => "",
			 "heading" => __("Series","cactusthemes"),
			 "param_name" => "series",
			 "value" => '',
			 "description" => __('Enter Video Series ID or slug','cactusthemes'),
		  ),
		  array(
			 "type" => "textfield",
			 "holder" => "div",
			 "class" => "",
			 "heading" => __("Post IDs","cactusthemes"),
			 "param_name" => "ids",
			 "value" => '',
			 "description" => __('Enter specify post IDs to display (ex: 6,9,18)','cactusthemes'),
		  ),
		  array(
			 "type" => "textfield",
			 "holder" => "div",
			 "class" => "",
			 "heading" => __("Count","cactusthemes"),
			 "param_name" => "count",
			 "value" => '',
			 "description" => __('Enter number of posts (Leave blank to display all)','cactusthemes')
		  ),
	   )
	));
	}
}

function get_post_series_next($post_id = ''){
	$post_id = $post_id?$post_id:get_the_ID();
	$args = array();
	
	$series = wp_get_post_terms($post_id, 'video-series', array("fields" => "all"));
	$series_slug = $series[0]->slug;
	$args = array(
		'post_type' => 'post',
		'posts_per_page' => -1,
		'ignore_sticky_posts' => true,
		'order' => 'ASC',
		'video-series' => $series_slug,
	);
	$next = array();
	if(!empty($args)){
		$series_query = get_posts( $args );
		$count = 0;
		$current_key = '';
		foreach ( $series_query as $key => $post ) : setup_postdata( $post );
			$count++;
			if($post->ID == $post_id){ $current_key = $count; break;}
		endforeach;
		$current_key = $current_key-1;
		$next[0] = ($series_query[$current_key+1]->ID);
		$next[1] = ($series_query[$current_key-1]->ID);
	}
	return $next;
}

add_action( 'init', 'truemag_register_page_templates' );
/** register page template from plugin **/
function truemag_register_page_templates(){
    if(!class_exists('PageTemplater')){
        require 'classes/cactus_templater.php';
    }
    
    $page_templ = array('truemag-movie/page-templates/series-listing.php' => esc_html__('Video Series Listing', 'cactusthemes'));
    
    $page_templater = PageTemplater::get_instance($page_templ);
}