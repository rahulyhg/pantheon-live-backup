<?php

class Popular_video_Widget extends WP_Widget {	

	function __construct() {
    	$widget_ops = array(
			'classname'   => 'advanced_popular_videos_widget', 
			'description' => __('Shows Most Viewed/Most Comments/High Rated/Most Liked/Latest ','cactusthemes')
		);
    	parent::__construct('advanced_popular_videos', __('TM-Popular Video','cactusthemes'), $widget_ops);
	}
	function widget($args, $instance) {
		wp_enqueue_script( 'jquery-isotope');
		$cache = wp_cache_get('widget_popular_videos', 'widget');		
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
		$ids 			= empty($instance['ids']) ? '' : $instance['ids'];
		$title 			= empty($instance['title']) ? '' : $instance['title'];
		$title          = apply_filters('widget_title', $title);
		$link 			= empty($instance['link']) ? '' : $instance['link'];
		$sort_by 		= empty($instance['sort_by']) ? '' : $instance['sort_by'];		
		$number 		= empty($instance['number']) ? '' : $instance['number'];
		$thumb 			= empty($instance['thumb']) ? '' : $instance['thumb'];
		$thumb_w 		= empty($instance['thumb_w']) ? '' : $instance['thumb_w'];
		$thumb_h 		= empty($instance['thumb_h']) ? '' : $instance['thumb_h'];
		$cates 			= empty($instance['cats']) ? '' : $instance['cats'];
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
			
			$widget_data  = $before_widget;
			$widget_data .= $before_title . $title . $after_title;
		
			$show_excluded_posts = get_option('wti_like_post_show_on_widget');
			$excluded_post_ids = explode(',', get_option('wti_like_post_excluded_posts'));
			
			if(!$show_excluded_posts && count($excluded_post_ids) > 0) {
				$where = "AND post_id NOT IN (" . get_option('wti_like_post_excluded_posts') . ")";
			}
						
			//getting the most liked posts
			$query = "SELECT post_id, SUM(value) AS like_count, post_title FROM `{$wpdb->prefix}wti_like_post` L, {$wpdb->prefix}posts P ";
			$query .= "WHERE L.post_id = P.ID AND post_status = 'publish' AND value > -1 $where GROUP BY post_id $order_by $limit";
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
				//$widget_data .= $show_count == '1' ? ' ('.$like_count.')' : '';
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
			$item_loop_video = new CT_ContentHelper;
			$tag = $categories = '';	
			if($ids==''){$categories = $cates;}
            
			$the_query = $item_loop_video->tm_get_popular_posts($conditions, $tag, $number, $ids, $sort_by, $categories, $args = array(),$themes_pur, $postformats=false,$timerange=false,$paged = false, 'widget');

//			if(count($cats) > 0){
//				$args = array('category__in' => $cats, 'showposts' => $number);
//			}	
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
			wp_cache_set('widget_popular_videos', $cache, 'widget');
		}
	}
	
	function flush_widget_cache() {
		wp_cache_delete('widget_custom_type_posts', 'widget');
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['conditions'] = esc_attr($new_instance['conditions']);
		$instance['show_likes'] = esc_attr($new_instance['show_likes']);
		$instance['show_com'] = esc_attr($new_instance['show_com']);
		$instance['show_view'] = esc_attr($new_instance['show_view']);
		$instance['show_rate'] = esc_attr($new_instance['show_rate']);
		$instance['show_excerpt'] = esc_attr($new_instance['show_excerpt']);
		$instance['num_ex'] = absint($new_instance['num_ex']);
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['link'] = strip_tags($new_instance['link']);
		$instance['ids'] = strip_tags($new_instance['ids']);
		$instance['sort_by'] = esc_attr($new_instance['sort_by']);
		$instance['number'] = absint($new_instance['number']);
		$instance['cats'] = $new_instance['cats'];
		return $instance;
	}
	
	
	
	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$link = isset($instance['link']) ? esc_attr($instance['link']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 3;
		$ids = isset($instance['ids']) ? esc_attr($instance['ids']) : '';
		$conditions = isset($instance['conditions']) ? esc_attr($instance['conditions']) : '';
		$show_likes = isset($instance['show_likes']) ? esc_attr($instance['show_likes']) : '';
		$show_com = isset($instance['show_com']) ? esc_attr($instance['show_com']) : '';
		$show_view = isset($instance['show_view']) ? esc_attr($instance['show_view']) : '';
		$show_rate = isset($instance['show_rate']) ? esc_attr($instance['show_rate']) : '';
		$show_excerpt = isset($instance['show_excerpt']) ? esc_attr($instance['show_excerpt']) : '';
		$num_ex = isset($instance['num_ex']) ? absint($instance['num_ex']) : 50;
		$cates = isset($instance['cats']) ? esc_attr($instance['cats']) : '';
		$sort_by = isset($instance['sort_by']) ? esc_attr($instance['sort_by']) : '';
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
        <p>
          <label for="<?php echo $this->get_field_id('ids'); ?>"><?php _e('IDs list show:','cactusthemes'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('ids'); ?>" name="<?php echo $this->get_field_name('ids'); ?>" type="text" 
          value="<?php echo $ids; ?>" />
        </p>

        <p>
        <label for="<?php echo $this->get_field_id("conditions"); ?>">
        <?php _e('Conditions');	 ?>:
            <select id="<?php echo $this->get_field_id("conditions"); ?>" name="<?php echo $this->get_field_name("conditions"); ?>">     
              <option value="most_viewed"<?php selected( $conditions, "most_viewed" ); ?>><?php _e('Most Viewed','cactusthemes');?></option>
              <option value="most_comments"<?php selected( $conditions, "most_comments" ); ?>><?php _e('Most Comments','cactusthemes');?></option>
              <option value="high_rated"<?php selected( $conditions, "high_rated" ); ?>><?php _e('High Rated','cactusthemes');?></option>
              <option value="most_liked"<?php selected( $conditions, "most_liked" ); ?>><?php _e('Most Liked','cactusthemes');?></option>
              <option value="latest"<?php selected( $conditions, "latest" ); ?>><?php _e('Latest','cactusthemes');?></option>
            </select>
        </label>
        </p>
        <p>
        <label for="<?php echo $this->get_field_id("show_view"); ?>">
        <?php _e('Show View count');	 ?>:
            <select id="<?php echo $this->get_field_id("show_view"); ?>" name="<?php echo $this->get_field_name("show_view"); ?>">     
              <option value="show_v"<?php selected( $show_view, "show_v" ); ?>><?php _e('Show','cactusthemes');?></option>
              <option value="hide_v"<?php selected( $show_view, "hide_v" ); ?>><?php _e('Hide','cactusthemes');?></option>
            </select>
        </label>
        </p>
        <p>
        <label for="<?php echo $this->get_field_id("show_com"); ?>">
        <?php _e('Show comments count');	 ?>:
            <select id="<?php echo $this->get_field_id("show_com"); ?>" name="<?php echo $this->get_field_name("show_com"); ?>">     
              <option value="show_c"<?php selected( $show_com, "show_c" ); ?>><?php _e('Show','cactusthemes');?></option>
              <option value="hide_c"<?php selected( $show_com, "hide_c" ); ?>><?php _e('Hide','cactusthemes');	 ?></option>
            </select>
        </label>
        </p>
        <p>
        <label for="<?php echo $this->get_field_id("show_likes"); ?>">
        <?php _e('Show likes count');	 ?>:
            <select id="<?php echo $this->get_field_id("show_likes"); ?>" name="<?php echo $this->get_field_name("show_likes"); ?>">     
              <option value="show_l"<?php selected( $show_likes, "show_l" ); ?>><?php _e('Show','cactusthemes');	 ?></option>
              <option value="hide_l"<?php selected( $show_likes, "hide_l" ); ?>><?php _e('Hide','cactusthemes');	 ?></option>
            </select>
        </label>
        </p>
        <p>
        <label for="<?php echo $this->get_field_id("show_rate"); ?>">
        <?php _e('Show rate count');	 ?>:
            <select id="<?php echo $this->get_field_id("show_rate"); ?>" name="<?php echo $this->get_field_name("show_rate"); ?>">     
              <option value="show_r"<?php selected( $show_rate, "show_r" ); ?>><?php _e('Show','cactusthemes');?></option>
              <option value="hide_r"<?php selected( $show_rate, "hide_r" ); ?>><?php _e('Hide','cactusthemes');?></option>
            </select>
        </label>
        </p>
        <p>
        <label for="<?php echo $this->get_field_id("show_excerpt"); ?>">
        <?php _e('Show Exceprt');	 ?>:
            <select id="<?php echo $this->get_field_id("show_excerpt"); ?>" name="<?php echo $this->get_field_name("show_excerpt"); ?>">     
              <option value="hide_ex"<?php selected( $show_excerpt, "hide_ex" ); ?>><?php _e('Hide','cactusthemes');?></option>
              <option value="show_ex"<?php selected( $show_excerpt, "show_ex" ); ?>><?php _e('Show','cactusthemes');?></option>
            </select>
        </label>
        </p>
<!--Categories-->
		 <label for="<?php echo $this->get_field_id('cats'); ?>"><?php _e('Categories:','cactusthemes');?> </label>
         <p class="cat-videos">
            <label for="<?php echo $this->get_field_id('cats'); ?>">
                <?php
                   $categories=  get_categories('hide_empty=0');
                     foreach ($categories as $cat) {
                         $option='<input type="checkbox" id="'. $this->get_field_id( 'cats' ) .'[]" name="'. $this->get_field_name( 'cats' ) .'[]"';
						 echo "<span>";
                            if (is_array($instance['cats'])) {
                                foreach ($instance['cats'] as $cats) {
                                    if($cats==$cat->term_id) {
                                         $option=$option.' checked="checked"';
                                    }
                                }
                            }
                            $option .= ' value="'.$cat->term_id.'" />';
        
                            $option .= $cat->cat_name;
                            
                            $option .= '</span>';
                            echo $option;
                         }
                    
                    ?>
            </label>
        </p>
<!--end-->
        <p>
          <label for="<?php echo $this->get_field_id('num_ex'); ?>"><?php _e('Number text of excerpt to show:','cactusthemes'); ?></label> 
          <input id="<?php echo $this->get_field_id('num_ex'); ?>" name="<?php echo $this->get_field_name('num_ex'); ?>" type="text" 
          value="<?php echo $num_ex; ?>"  size="3"/>
        </p>
      <!-- /**/-->
        <p>
        
            <label for="<?php echo $this->get_field_id("sort_by"); ?>">
        
        <?php _e('Sort by');	 ?>:
        
        <select id="<?php echo $this->get_field_id("sort_by"); ?>" name="<?php echo $this->get_field_name("sort_by"); ?>">    
          <option value="ASC"<?php selected( $sort_by, "ASC" ); ?>><?php _e('ASC');?></option>
          <option value="DESC"<?php selected( $sort_by, "DESC" ); ?>><?php _e('DESC');?></option>
        </select>
       </label>
        
        </p>
        
        
<!--abc-->        
        
        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts:','cactusthemes'); ?></label>
        <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php 
		echo $number; ?>" size="3" /></p>
<!--//-->
<?php
	}
}

// register RecentPostsPlus widget
add_action( 'widgets_init', create_function( '', 'return register_widget("Popular_video_Widget");' ) );
?>