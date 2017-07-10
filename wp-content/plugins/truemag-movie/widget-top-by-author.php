<?php

class Top_By_Author_Widget extends WP_Widget {	

	function __construct() {
    	$widget_ops = array(
			'classname'   => 'advanced_by_author_videos_widget', 
			'description' => __('Top videos by author','cactusthemes')
		);
    	parent::__construct('advanced_by_author_videos', __('TM-Top Videos By Author','cactusthemes'), $widget_ops);
	}
	function widget($args, $instance) {
		wp_enqueue_script( 'jquery-isotope');
		$cache = wp_cache_get('widget_by_author_videos', 'widget');		
		if ( !is_array($cache) )
			$cache = array();

		if ( !isset( $argsxx['widget_id'] ) )
			$argsxx['widget_id'] = $this->id;
		if ( isset( $cache[ $argsxx['widget_id'] ] ) ) {
			echo $cache[ $argsxx['widget_id'] ];
			return;
		}
		ob_start();
		extract($args);
		$conditions  		= empty($instance['conditions']) ? '' : $instance['conditions'];	
		$show_likes = empty($instance['show_likes']) ? '' : $instance['show_likes'] ;
		$show_com = empty($instance['show_com']) ? '' : $instance['show_com'] ;
		$show_view = empty($instance['show_view']) ? '' : $instance['show_view'] ;
		$show_rate = empty($instance['show_rate']) ? '' : $instance['show_rate'] ;
		$show_excerpt = empty($instance['show_excerpt']) ? '' : $instance['show_excerpt'] ;
		$num_ex = empty($instance['num_ex']) ? '' : $instance['num_ex'] ;
		$author_id 			= empty($instance['author']) ? '' : $instance['author'];
		$title 			= empty($instance['title']) ? '' : $instance['title'];
		$title          = apply_filters('widget_title', $title);
		$link 			= empty($instance['link']) ? '' : $instance['link'];
		$number 		= empty($instance['number']) ? '' : $instance['number'];
		if($link!=''){
			$before_title .= '<a href='.$link.'>';
			$after_title = '</a>' . $after_title;
		}
		if(function_exists('ot_get_option')){ $themes_pur= ot_get_option('theme_purpose');}
		if($conditions=='most_liked'  && $ids=='' && class_exists('CT_ContentHelper'))
		{
			global $wpdb;	
			$show_count = 1 ;
			$time_range = 'all';
			//$show_type = $instance['show_type'];
			$order_by = 'ORDER BY like_count DESC, post_title';
			
			if($number > 0) {
				$limit = "LIMIT " . $number;
			}
			$author ='';
			if(isset($author_id)){ $author = " AND post_author = " . $author_id;}
			$widget_data  = $before_widget;
			$widget_data .= $before_title . $title . $after_title;
		
			$show_excluded_posts = get_option('wti_like_post_show_on_widget');
			$excluded_post_ids = explode(',', get_option('wti_like_post_excluded_posts'));
			
			if(!$show_excluded_posts && count($excluded_post_ids) > 0) {
				$where = "AND post_id NOT IN (" . get_option('wti_like_post_excluded_posts') . ")";
			}
						
			//getting the most liked posts
			$query = "SELECT post_id, SUM(value) AS like_count, post_title FROM `{$wpdb->prefix}wti_like_post` L, {$wpdb->prefix}posts P ";
			$query .= "WHERE L.post_id = P.ID AND post_status = 'publish' $author AND value > -1 $where GROUP BY post_id $order_by $limit";
			$posts = $wpdb->get_results($query);
			$item_loop_video = new CT_ContentHtml;
			$cates_ar = $cates;
			if(count($posts) > 0) {
			$widget_data .= '
			<div class="widget-content">
					<div class="list-rating-item row">';

				foreach ($posts as $post) {
					$cat_cur = get_the_category($post->post_id);
					$cat_s = ($cat_cur[0]->cat_ID);
					$p_data = $excerpt ='';
					$post_title = stripslashes($post->post_title);
					$permalink = get_permalink($post->post_id);
					$like_count = $post->like_count;
					$p_data = get_post($post->post_id);
					$excerpt = strip_tags($p_data->post_content);
					$excerpt =	wp_trim_words( $excerpt , $num_ex, $more = '');
					if($cates!=''){
					foreach ($cates_ar as $categs) {
						if($categs==$cat_s) {
							$widget_data .= $item_loop_video->tm_likes_html($post,$like_count,$themes_pur,$show_likes,$show_com,$show_rate,$show_view,$show_excerpt,$excerpt);
						}
					}
					}else{
						$widget_data .= $item_loop_video->tm_likes_html($post,$like_count,$themes_pur,$show_likes,$show_com,$show_rate,$show_view,$show_excerpt,$excerpt);
					}
				}
			$widget_data .= '</div>
			</div>';			
			}
			$widget_data .= $after_widget;
	   
			echo $widget_data;
			wp_reset_postdata();
			$cache[$argsxx['widget_id']] = ob_get_flush();
			wp_cache_set('widget_trending_videos', $cache, 'widget');
		} else 
		if(class_exists('CT_ContentHelper')){
		  $args = array(
				'post_type' => 'post',
				'posts_per_page' => $number,
				'author' => $author_id,
				'meta_key' => '_count-views_all',
				'orderby' => 'meta_value_num',
				'post_status' => 'publish',
				'ignore_sticky_posts' => 1,
			);
			$the_query = new WP_Query($args);
			$html = $before_widget;
			if ( $title ) $html .= $before_title . $title . $after_title; 
			if($the_query->have_posts()):
				$html .= '
					<div class="widget-content">
					<div class="list-rating-item row">
				';
				$i = 0;
				$item_video = new CT_ContentHtml; 
				while($the_query->have_posts()): $the_query->the_post();$i++;
						 $excerpt = get_the_excerpt();
						 $excerpt =	wp_trim_words( $excerpt , $num_ex, $more = '');
	  					$html.= $item_video->get_item_video_trending($conditions,$themes_pur,$show_likes,$show_com,$show_rate,$show_view,$show_excerpt,$excerpt);
				endwhile;
				$html .= '</div>
				</div>';
			endif;
			
			$html .= $after_widget;
			echo $html;
			wp_reset_postdata();
			$cache[$argsxx['widget_id']] = ob_get_flush();
			wp_cache_set('widget_by_author_videos', $cache, 'widget');
		}
	}
	
	function flush_widget_cache() {
		wp_cache_delete('widget_custom_type_posts', 'widget');
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['conditions'] = esc_attr($new_instance['conditions']);
		$instance['author'] = esc_attr($new_instance['author']);
		$instance['show_likes'] = esc_attr($new_instance['show_likes']);
		$instance['show_com'] = esc_attr($new_instance['show_com']);
		$instance['show_view'] = esc_attr($new_instance['show_view']);
		$instance['show_rate'] = esc_attr($new_instance['show_rate']);
		$instance['show_excerpt'] = esc_attr($new_instance['show_excerpt']);
		$instance['num_ex'] = absint($new_instance['num_ex']);
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['link'] = strip_tags($new_instance['link']);
		$instance['author_id'] = strip_tags($new_instance['author_id']);
		$instance['number'] = absint($new_instance['number']);
		return $instance;
	}
	
	
	
	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$link = isset($instance['link']) ? esc_attr($instance['link']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 3;
		$ids = isset($instance['author_id']) ? esc_attr($instance['author_id']) : '';
		$conditions = isset($instance['conditions']) ? esc_attr($instance['conditions']) : '';
		$show_likes = isset($instance['show_likes']) ? esc_attr($instance['show_likes']) : '';
		$show_com = isset($instance['show_com']) ? esc_attr($instance['show_com']) : '';
		$show_view = isset($instance['show_view']) ? esc_attr($instance['show_view']) : '';
		$show_rate = isset($instance['show_rate']) ? esc_attr($instance['show_rate']) : '';
		$show_excerpt = isset($instance['show_excerpt']) ? esc_attr($instance['show_excerpt']) : '';
		$num_ex = isset($instance['num_ex']) ? absint($instance['num_ex']) : 50;
		$author = isset($instance['author']) ? esc_attr($instance['author']) : '';
?>
        <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','cactusthemes'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" 
        value="<?php echo $title; ?>" /></p>
        <p>
        <p>
        <label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link in title:','cactusthemes'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" 
        value="<?php echo $link; ?>" /></p>
        <p class="auhor-videos">
		<label for="<?php echo $this->get_field_id('author'); ?>"><?php _e('Author:','cactusthemes');?> </label>
            <label for="<?php echo $this->get_field_id('author'); ?>">
            <select id="<?php echo $this->get_field_id("author"); ?>" name="<?php echo $this->get_field_name("author"); ?>">  
                <?php
                   $categories=  get_users();
                     foreach ($categories as $cat) {?>
                         <option value="<?php echo $cat->ID; ?>"<?php selected( $author, $cat->ID ); ?>><?php echo $cat->display_name;?></option>
                         <?php
                     }
                    
                    ?>
            </select>        
            </label>
        </p>
        
        <p>
        <label for="<?php echo $this->get_field_id("conditions"); ?>">
        <?php _e('Conditions','cactusthemes');	 ?>:
            <select id="<?php echo $this->get_field_id("conditions"); ?>" name="<?php echo $this->get_field_name("conditions"); ?>">     
              <option value="most_viewed"<?php selected( $conditions, "most_viewed" ); ?>><?php _e('Most Viewed','cactusthemes');?></option>
              <option value="most_liked"<?php selected( $conditions, "most_liked" ); ?>><?php _e('Most Liked','cactusthemes');?></option>
            </select>
        </label>
        </p>
        <p>
        <label for="<?php echo $this->get_field_id("show_view"); ?>">
        <?php _e('Show View count','cactusthemes');	 ?>:
            <select id="<?php echo $this->get_field_id("show_view"); ?>" name="<?php echo $this->get_field_name("show_view"); ?>">     
              <option value="show_v"<?php selected( $show_view, "show_v" ); ?>><?php _e('Show','cactusthemes');?></option>
              <option value="hide_v"<?php selected( $show_view, "hide_v" ); ?>><?php _e('Hide','cactusthemes');?></option>
            </select>
        </label>
        </p>
        <p>
        <label for="<?php echo $this->get_field_id("show_com"); ?>">
        <?php _e('Show comments count','cactusthemes');	 ?>:
            <select id="<?php echo $this->get_field_id("show_com"); ?>" name="<?php echo $this->get_field_name("show_com"); ?>">     
              <option value="show_c"<?php selected( $show_com, "show_c" ); ?>><?php _e('Show','cactusthemes');?></option>
              <option value="hide_c"<?php selected( $show_com, "hide_c" ); ?>><?php _e('Hide','cactusthemes');	 ?></option>
            </select>
        </label>
        </p>
        <p>
        <label for="<?php echo $this->get_field_id("show_likes"); ?>">
        <?php _e('Show likes count','cactusthemes');	 ?>:
            <select id="<?php echo $this->get_field_id("show_likes"); ?>" name="<?php echo $this->get_field_name("show_likes"); ?>">     
              <option value="show_l"<?php selected( $show_likes, "show_l" ); ?>><?php _e('Show','cactusthemes');	 ?></option>
              <option value="hide_l"<?php selected( $show_likes, "hide_l" ); ?>><?php _e('Hide','cactusthemes');	 ?></option>
            </select>
        </label>
        </p>
        <p>
        <label for="<?php echo $this->get_field_id("show_rate"); ?>">
        <?php _e('Show rate count','cactusthemes');	 ?>:
            <select id="<?php echo $this->get_field_id("show_rate"); ?>" name="<?php echo $this->get_field_name("show_rate"); ?>">     
              <option value="show_r"<?php selected( $show_rate, "show_r" ); ?>><?php _e('Show','cactusthemes');?></option>
              <option value="hide_r"<?php selected( $show_rate, "hide_r" ); ?>><?php _e('Hide','cactusthemes');?></option>
            </select>
        </label>
        </p>
        <p>
        <label for="<?php echo $this->get_field_id("show_excerpt"); ?>">
        <?php _e('Show Exceprt','cactusthemes');	 ?>:
            <select id="<?php echo $this->get_field_id("show_excerpt"); ?>" name="<?php echo $this->get_field_name("show_excerpt"); ?>">     
              <option value="hide_ex"<?php selected( $show_excerpt, "hide_ex" ); ?>><?php _e('Hide','cactusthemes');?></option>
              <option value="show_ex"<?php selected( $show_excerpt, "show_ex" ); ?>><?php _e('Show','cactusthemes');?></option>
            </select>
        </label>
        </p>
<!--end-->
        <p>
          <label for="<?php echo $this->get_field_id('num_ex'); ?>"><?php _e('Number text of excerpt to show:','cactusthemes'); ?></label> 
          <input id="<?php echo $this->get_field_id('num_ex'); ?>" name="<?php echo $this->get_field_name('num_ex'); ?>" type="text" 
          value="<?php echo $num_ex; ?>"  size="3"/>
        </p>
      <!-- /**/-->
<!--abc-->        
        
        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts:','cactusthemes'); ?></label>
        <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php 
		echo $number; ?>" size="3" /></p>
<!--//-->
<?php
	}
}

// register RecentPostsPlus widget
add_action( 'widgets_init', create_function( '', 'return register_widget("Top_By_Author_Widget");' ) );
?>