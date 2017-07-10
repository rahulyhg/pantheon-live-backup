<?php

class Related_Video_Widget extends WP_Widget {	

	function __construct() {
    	$widget_ops = array(
			'classname'   => 'related_videos_widget', 
			'description' => __('Related News','cactusthemes')
		);
    	parent::__construct('related_videos', __('TM-Related News/Video','cactusthemes'), $widget_ops);
	}
	function widget($args, $instance) {
		wp_enqueue_script( 'jquery-isotope');
		$cache = wp_cache_get('widget_related_videos', 'widget');		
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
		$tags  		= empty($instance['tags']) ? '' : $instance['tags'];	
		$postformat 		= empty($instance['postformat']) ? '' : $instance['postformat'];
		$title 			= empty($instance['title']) ? '' : $instance['title'];
		$title          = apply_filters('widget_title', $title);
		$orderby 		= empty($instance['sortby']) ? '' : $instance['sortby'];		
		$count 		= empty($instance['count']) ? '' : $instance['count'];
		$posttypes = 'post';
		if(function_exists('ot_get_option')){ $themes_pur= ot_get_option('theme_purpose');}
		if($themes_pur=='0')
		{
			$postformat='';
		}
		if(is_single()){
			if($tags==''){
			  $tag = '';
			  $posttags = get_the_tags();
			  if ($posttags) {
				  foreach($posttags as $tag) {
					  $tags .= ',' . $tag->slug; 
				  }
				  $tags = substr($tags, 1); 
			  }		
			}
		}

		if(class_exists('CT_ContentHelper')){
			$item_loop_video = new CT_ContentHelper;	
			$the_query = $item_loop_video->tm_get_related_posts($posttypes, $tags, $postformat, $count, $orderby, $args = array());
			//$the_query = new WP_Query($args);
			$html = $before_widget;
			if ( $title ) $html .= $before_title . $title . $after_title; 
			if($the_query->have_posts()):
				$html .= '
					<div class="widget-content">
					<div class="list-rating-item row">
				';
				$item_video = new CT_ContentHtml; 
				$i=0;
				while($the_query->have_posts()): $the_query->the_post();$i++;
					$html.= $item_video->get_item_related($postformat, $themes_pur);
				endwhile;
				$html .= '</div>
				</div>';
			endif;
			
			$html .= $after_widget;
			if(is_singular('post') && $tags!=''){
				echo $html;
			}
		}
		wp_reset_postdata();
		$cache[$argsxx['widget_id']] = ob_get_flush();
		wp_cache_set('widget_popular_videos', $cache, 'widget');
	}
	
	function flush_widget_cache() {
		wp_cache_delete('widget_custom_type_posts', 'widget');
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['tags'] = esc_attr($new_instance['tags']);
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['postformat'] = esc_attr($new_instance['postformat']);
		$instance['sortby'] = esc_attr($new_instance['sortby']);
		$instance['count'] = absint($new_instance['count']);
		return $instance;
	}
	
	
	
	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$postformat = isset($instance['postformat']) ? esc_attr($instance['postformat']) : '';
		$count = isset($instance['count']) ? absint($instance['count']) : 3;
		$sortby = isset($instance['sortby']) ? esc_attr($instance['sortby']) : '';
		$tags = isset($instance['tags']) ? esc_attr($instance['tags']) : '';
		if(function_exists('ot_get_option')){ $themes_pur= ot_get_option('theme_purpose');}
?>
        <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','cactusthemes'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" 
        value="<?php echo $title; ?>" /></p>
      <!-- /**/-->
		<?php if ($themes_pur!='0') { ?>
        <p>
        <label for="<?php echo $this->get_field_id("postformat"); ?>">
        <?php _e('Post format','cactusthemes');	 ?>:
        <select id="<?php echo $this->get_field_id("postformat"); ?>" name="<?php echo $this->get_field_name("postformat"); ?>">
          <option value="video"<?php selected( $instance["postformat"], "video" ); ?>><?php _e('Video','cactusthemes'); ?></option>
          <option value="standard"<?php selected( $instance["postformat"], "standard" ); ?>><?php _e('News','cactusthemes'); ?></option>
          <option value=""<?php selected( $instance["postformat"], "" ); ?>><?php _e('Both','cactusthemes'); ?></option>
        </select>
       </label>
        </p>
		<?php }?>
        <p>
        <label for="<?php echo $this->get_field_id("tags"); ?>">
        <?php _e('Tag','cactusthemes');	 ?>:
        </label>
        <input class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>" type="text"  value="<?php echo $tags; ?>" />
        </p>
        
        <p>
        <label for="<?php echo $this->get_field_id("sortby"); ?>">
        <?php _e('Sort by','cactusthemes');	 ?>:
        <select id="<?php echo $this->get_field_id("sortby"); ?>" name="<?php echo $this->get_field_name("sortby"); ?>">
          <option value="date"<?php selected( $instance["sortby"], "date" ); ?>><?php _e('Date','cactusthemes'); ?></option>
          <option value="rand"<?php selected( $instance["sortby"], "rand" ); ?>><?php _e('Random','cactusthemes'); ?></option>
        </select>
       </label>
        </p>
<!--abc-->    
        <p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of posts:','cactusthemes'); ?></label>
        <input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php 
		echo $count; ?>" size="3" /></p>
<!--//-->
<?php
	}
}

// register RecentPostsPlus widget
add_action( 'widgets_init', create_function( '', 'return register_widget("Related_Video_Widget");' ) );
?>