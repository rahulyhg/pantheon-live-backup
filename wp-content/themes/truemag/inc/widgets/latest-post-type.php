<?php
/*
Plugin Name: Latest post type
Plugin URI: http://www.cactusthemes.com
Description: Adds a widget that can display recent posts from multiple categories or from custom post types.
Version: 1.0
Author: Mr - THss
Author URI: http://www.cactusthemes.com
*/
/*  

*/

class Recent_Posts_Widget extends WP_Widget {	

	function __construct() {
    	$widget_ops = array(
			'classname'   => 'advanced_recent_posts_widget', 
			'description' => __('Shows recent posts / custom post types....','cactusthemes')
		);
    	parent::__construct('advanced-recent-posts', __('GP-Advanced Recent Posts','cactusthemes'), $widget_ops);
	}


	function widget($args, $instance) {
		wp_enqueue_script( 'jquery-isotope');
		$cache = wp_cache_get('widget_recent_posts', 'widget');		
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
		
		$ids 			= empty($instance['ids']) ? '' : $instance['ids'];
		$title 			= empty($instance['title']) ? '' : $instance['title'];
		$title          = apply_filters('widget_title', $title);
		$cats 			= empty($instance['cats']) ? '' : $instance['cats'];
		$sort_by 		= empty($instance['sort_by']) ? '' : $instance['sort_by'];		
		$show_type 		= empty($instance['show_type']) ? 'post' : $instance['show_type'];
		$asc_sort_order = empty($instance['asc_sort_order']) ? 'DESC' : $instance['asc_sort_order'];
		$number 		= empty($instance['number']) ? 5 : $instance['number'];
		$perpage 		= empty($instance['perpage']) ? '' : $instance['perpage'];
		$thumb 			= empty($instance['thumb']) ? '' : $instance['thumb'];
		$date 			= empty($instance['date']) ? '' : $instance['date'];
		$comment_num 	= empty($instance['comment_num']) ? '' : $instance['comment_num'];
		$thumb_w 		= empty($instance['thumb_w']) ? '' : $instance['thumb_w'];
		$thumb_h 		= empty($instance['thumb_h']) ? '' : $instance['thumb_h'];
		$args = array(
			'post_type' => $show_type,
			'posts_per_page' => $number,
			'orderby' => $sort_by,
			'order' => $asc_sort_order,
			'post_status' => 'publish',
			
		);
		
		if(count($cats) > 0){
			$args += array('category__in' => $cats, 'showposts' => $number);
		}
		$the_query = new WP_Query( $args );
		$carol = false; $pgs = 0; 
		if(count($the_query->posts) / $perpage > 1){
			$carol = true;
			$pgs = ((count($the_query->posts) / $perpage) == intval(count($the_query->posts) / $perpage)) ? (count($the_query->posts) / $perpage) : intval(count($the_query->posts) / $perpage) + 1;
		}
		$html = $before_widget;
		if ( $title ) $html .= $before_title . $title . $after_title; 
		if($the_query->have_posts()):
			if($carol):
				$html .= head_slide($pgs, 'recent-posts-'.rand(),'recent-post');
			else:
				$html .= '<div class="recent-post">';
			endif;
			$i=0;
			while($the_query->have_posts()): $the_query->the_post();$i++;
				$thumb_htm = '';
				$post_type = get_post_format(get_the_ID());
				if($thumb == 'on' && $post_type != 'video'):
					if($thumb_w && $thumb_h):
						$thumb_htm .= '
							<div class="postleft">
							  <div class="rt-image">
								<a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_post_thumbnail(get_the_ID(), array($thumb_w, $thumb_h), array('alt' => get_the_title())).'</a>
							  </div>
							</div>
						';
					else:
						$thumb_htm .= '
							<div class="postleft">
							  <div class="rt-image">
								<a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_post_thumbnail(get_the_ID(), 'thumb160x120', array('alt' => get_the_title())).'</a>
							  </div>
							</div>
						';
					endif;
				endif;
				$date_html = '';
				if($date == 'on'):
					$date_html .= '                           
						<!-- Begin Date & Time --> 
						<span class="rt-date-posted"><span class="icon-calendar">'.date_i18n(get_option('date_format') ,get_the_time('U')).'</span></span> 
						<!-- End Date & Time -->
					';
				endif;
				$comment_num_html = '';
				if($comment_num == 'on'):
					$comment_num_html .= '
						<!-- Begin Comments -->
						<div class="rt-comment-block ">
						<a href="#" class="rt-comment-text"> <span class="icon-comment">'.get_comments_number().' '.__('Comments','cactusthemes').'</span> </a> 
						</div>                                        
						<!-- End Comments -->
					';
				endif;	
				apply_filters('the_content', get_the_excerpt);
				$sticky_tag = '';
				if(is_sticky()){
					$sticky_tag = get_post_meta(get_the_ID(),'sticky_tag',true);
					$sticky_tag_all = function_exists('ot_get_option')?ot_get_option('sticky_tag_blog'):'';
					if($sticky_tag=='')
					{
						if($sticky_tag_all!='')
						{
							$sticky_tag='<i class="bookmark">'.$sticky_tag_all.'</i>';
						}
					}else
					{
						$sticky_tag='<i class="bookmark">'.$sticky_tag.'</i>';
					}
				}
				$html .= '
					<div class="row-fluid">
					  <div class="rt-article span12">
						<div class="rt-article-bg format-'.$post_type.'">
						  <div class="post">
						  	'.$thumb_htm.'
							<div class="postright post-wrap">
							  <div class="rp-video">
								
							  </div>
							  <div class="rt-headline">
								<h4 class="rt-article-title"> <a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a>'.$sticky_tag.'</h4>
							  </div>
							  <div class="rt-articleinfo">
							  	'.$date_html.$comment_num_html.'
							  </div>
							  <div class="recentpost-content">
								'.get_the_excerpt().'
							  </div>
							</div>
							<!-- end post wrap -->
							<div class="clear"><!-- --></div>
						  </div>
						</div>
					  </div>
					  <!-- end span -->                              
					</div>
				';
				if($carol):
					if($i % $perpage == 0 && $i != $number)
						$html .= '
								</div>
							</div>
							<div class="slide" style="width:'.(100/$pgs).'%;">
								<div class="recent-post">
						';					
				endif;
			endwhile;
			if($carol):
				$html .= footer_slide();
			else:
				$html .= '</div>';
			endif;
		endif;
		
		$html .= $after_widget;
		echo $html;
		wp_reset_postdata();
		$cache[$argsxx['widget_id']] = ob_get_flush();
		wp_cache_set('widget_recent_posts', $cache, 'widget');
	}
	
	function flush_widget_cache() {
		wp_cache_delete('widget_custom_type_posts', 'widget');
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['ids'] = strip_tags($new_instance['ids']);
        $instance['cats'] = $new_instance['cats'];
		$instance['sort_by'] = esc_attr($new_instance['sort_by']);
		$instance['show_type'] = esc_attr($new_instance['show_type']);
		$instance['asc_sort_order'] = esc_attr($new_instance['asc_sort_order']);
		$instance['number'] = absint($new_instance['number']);

		$instance['perpage'] = absint($new_instance['perpage']);

		$instance["thumb"] = esc_attr($new_instance['thumb']);
        $instance['date'] =esc_attr($new_instance['date']);
        $instance['comment_num']=esc_attr($new_instance['comment_num']);
		$instance["thumb_w"]=absint($new_instance["thumb_w"]);
		$instance["thumb_h"]=absint($new_instance["thumb_h"]);
		return $instance;
	}
	
	
	
	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : 'Recent Posts';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;

		$perpage = isset($instance['perpage']) ? absint($instance['perpage']) : 2;

		$thumb_h = isset($instance['thumb_h']) ? absint($instance['thumb_h']) : 50;
		$thumb_w = isset($instance['thumb_w']) ? absint($instance['thumb_w']) : 50;
		$show_type = isset($instance['show_type']) ? esc_attr($instance['show_type']) : 'post';
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','cactusthemes'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
      <!-- /**/-->
        <p>
          <label for="<?php echo $this->get_field_id('ids'); ?>"><?php _e('ID list show:','cactusthemes'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('ids'); ?>" name="<?php echo $this->get_field_name('ids'); ?>" type="text" value="<?php echo $ids; ?>" />
        </p>
      <!-- /**/-->
        <p>
        
            <label for="<?php echo $this->get_field_id("sort_by"); ?>">
        
        <?php _e('Sort by','cactusthemes');	 ?>:
        
        <select id="<?php echo $this->get_field_id("sort_by"); ?>" name="<?php echo $this->get_field_name("sort_by"); ?>">
        
          <option value="date"<?php selected( $instance["sort_by"], "date" ); ?>>Date</option>
        
          <option value="title"<?php selected( $instance["sort_by"], "title" ); ?>>Title</option>
        
          <option value="comment_count"<?php selected( $instance["sort_by"], "comment_count" ); ?>>Number of comments</option>
        
          <option value="rand"<?php selected( $instance["sort_by"], "rand" ); ?>>Random</option>
        
        </select>
        
            </label>
        
        </p>
        
        
        <p>
        
            <label for="<?php echo $this->get_field_id("asc_sort_order"); ?>">
        
        <input type="checkbox" class="checkbox" 
        
          id="<?php echo $this->get_field_id("asc_sort_order"); ?>" 
        
          name="<?php echo $this->get_field_name("asc_sort_order"); ?>"
        
          <?php checked( (bool) $instance["asc_sort_order"], true ); ?> />
        
                <?php _e( 'Reverse sort order (ascending)','cactusthemes'); ?>
        
            </label>
        
        </p>     
<!--abc-->        
        <p>
        
            <label for="<?php echo $this->get_field_id("date"); ?>">
        
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("date"); ?>" name="<?php echo $this->get_field_name("date"); ?>"<?php checked( (bool) $instance["date"], true ); ?> />
        
                <?php _e( 'Include post date','cactusthemes' ); ?>
        
            </label>
        
        </p>
        
        
        <p>
        
            <label for="<?php echo $this->get_field_id("comment_num"); ?>">
        
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("comment_num"); ?>" name="<?php echo $this->get_field_name("comment_num"); ?>"<?php checked( (bool) $instance["comment_num"], true ); ?> />
        
                <?php _e( 'Show number of comments','cactusthemes'); ?>
        
            </label>
        
        </p>
        
        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts:','cactusthemes'); ?></label>
        <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<!--//-->
		<p><label for="<?php echo $this->get_field_id('perpage'); ?>"><?php _e('Number of posts to show:','cactusthemes'); ?></label>
        <input id="<?php echo $this->get_field_id('perpage'); ?>" name="<?php echo $this->get_field_name('perpage'); ?>" type="text" value="<?php echo $perpage; ?>" size="3" /></p>
<!--//-->        
        <?php if ( function_exists('the_post_thumbnail') && current_theme_supports("post-thumbnails") ) : ?>
        
        <p>
        
            <label for="<?php echo $this->get_field_id("thumb"); ?>">
        
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("thumb"); ?>" name="<?php echo $this->get_field_name("thumb"); ?>"<?php checked( (bool) $instance["thumb"], true ); ?> />
        
                <?php _e( 'Show post thumbnail','cactusthemes'); ?>
        
            </label>
        
        </p>
        
        <p>
        
            <p>
        
                <?php _e('Thumbnail dimensions','cactusthemes'); ?>:<br />
        
                <label for="<?php echo $this->get_field_id("thumb_w"); ?>">        
                    W: <input class="widefat" style="width:40%;" type="text" id="<?php echo $this->get_field_id("thumb_w"); ?>" name="<?php echo $this->get_field_name("thumb_w"); ?>" value="<?php echo $thumb_w; ?>" />
        
                </label>
                <label for="<?php echo $this->get_field_id("thumb_h"); ?>">        
                    H: <input class="widefat" style="width:40%;" type="text" id="<?php echo $this->get_field_id("thumb_h"); ?>" name="<?php echo $this->get_field_name("thumb_h"); ?>" value="<?php echo $thumb_h; ?>" onfocus="" onclick="" />
        
                </label>
                
        
            </p>
        
        </p>
        
        <?php endif; ?>
        
         <p>
            <label for="<?php echo $this->get_field_id('cats'); ?>"><?php _e('Categories:','cactusthemes');?> 
            
                <?php
                   $categories=  get_categories('hide_empty=0');
                     echo "<br/>";
                     foreach ($categories as $cat) {
                         $option='<input type="checkbox" id="'. $this->get_field_id( 'cats' ) .'[]" name="'. $this->get_field_name( 'cats' ) .'[]"';
                            if (is_array($instance['cats'])) {
                                foreach ($instance['cats'] as $cats) {
                                    if($cats==$cat->term_id) {
                                         $option=$option.' checked="checked"';
                                    }
                                }
                            }
                            $option .= ' value="'.$cat->term_id.'" />';
        
                            $option .= $cat->cat_name;
                            
                            $option .= '<br />';
                            echo $option;
                         }
                    
                    ?>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('show_type'); ?>"><?php _e('Show Post Type:','cactusthemes');?> 
                <select class="widefat" id="<?php echo $this->get_field_id('show_type'); ?>" name="<?php echo $this->get_field_name('show_type'); ?>">
                <?php
                    global $wp_post_types;
                    foreach($wp_post_types as $k=>$sa) {
                        if($sa->exclude_from_search) continue;
                        echo '<option value="' . $k . '"' . selected($k,$show_type,true) . '>' . $sa->labels->name . '</option>';
                    }
                ?>
                </select>
            </label>
        </p>
<?php
	}
}

// register RecentPostsPlus widget
add_action( 'widgets_init', create_function( '', 'return register_widget("Recent_Posts_Widget");' ) );
?>