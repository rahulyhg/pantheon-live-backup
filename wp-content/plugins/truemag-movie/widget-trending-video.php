<?php

class Trending_Video_Widget extends WP_Widget {	

	function __construct() {
    	$widget_ops = array(
			'classname'   => 'advanced_trending_videos_widget', 
			'description' => __('Shows Most Viewed/Most Comments/Most Liked','cactusthemes')
		);
    	parent::__construct('advanced_trending_videos', __('TM-Trending Video','cactusthemes'), $widget_ops);
	}


	function widget($args, $instance) {
		wp_enqueue_script( 'jquery-isotope');
		$cache = wp_cache_get('widget_trending_videos', 'widget');		
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
		$title 			= empty($instance['title']) ? '' : $instance['title'];
		$title          = apply_filters('widget_title', $title);
		$number 		= empty($instance['number']) ? '' : $instance['number'];
		$timerange 		= empty($instance['timerange']) ? '' : $instance['timerange'];
		$show_likes = empty($instance['show_likes']) ? '' : $instance['show_likes'] ;
		$show_com = empty($instance['show_com']) ? '' : $instance['show_com'] ;
		$link 			= empty($instance['link']) ? '' : $instance['link'];
		$show_view = empty($instance['show_view']) ? '' : $instance['show_view'] ;
		$show_rate = empty($instance['show_rate']) ? '' : $instance['show_rate'] ;
		$show_excerpt = empty($instance['show_excerpt']) ? '' : $instance['show_excerpt'] ;
		$num_ex = empty($instance['num_ex']) ? '' : $instance['num_ex'] ;
		if($link!=''){
			$before_title .= '<a href='.$link.'>';
			$after_title = '</a>' . $after_title;
		}
		if(function_exists('ot_get_option')){ $themes_pur= ot_get_option('theme_purpose');}
		if($conditions=='most_liked' && class_exists('CT_ContentHelper')){
			global $wpdb;
			
			$show_count = 1;
			if($timerange=='day'){$time_range='1';}
			else if($timerange=='week'){$time_range='7';}
			else if($timerange=='month'){$time_range='1m';}
			else if($timerange=='year'){$time_range='1y';}
			//$time_range = $instance['time_range'];
			//$show_type = $instance['show_type'];
			$order_by = 'ORDER BY like_count DESC, post_title';
			
			if($number > 0) {
				$limit = "LIMIT " . $number;
			}
			
			$widget_data  = $before_widget;
			$widget_data .= $before_title . $title . $after_title;
		
			$show_excluded_posts = get_option('wti_like_post_show_on_widget');
			$excluded_post_ids = explode(',', get_option('wti_like_post_excluded_posts'));
			$where = '';
			if(!$show_excluded_posts && count($excluded_post_ids) > 0) {
				$where = "AND post_id NOT IN (" . get_option('wti_like_post_excluded_posts') . ")";
			}
			
			if($time_range != 'all' && function_exists('GetWtiLastDate')) {
				$last_date = GetWtiLastDate($time_range);
				$where .= " AND date_time >= '$last_date'";
			}
			
			//getting the most liked posts
			$query = "SELECT post_id, SUM(value) AS like_count, post_title FROM `{$wpdb->prefix}wti_like_post` L, {$wpdb->prefix}posts P ";
			$query .= "WHERE L.post_id = P.ID AND post_status = 'publish' AND value > 0 $where GROUP BY post_id $order_by $limit";
			$posts = $wpdb->get_results($query);
	
			if(count($posts) > 0) {
			$item_loop_video = new CT_ContentHtml;	
			$widget_data .= '
			<div class="widget-content">
					<div class="list-rating-item row">';
				foreach ($posts as $post) {
					$p_data = $excerpt ='';
					$post_title = stripslashes($post->post_title);
					$permalink = get_permalink($post->post_id);
					$like_count = $post->like_count;
					$p_data = get_post($post->post_id);
					$excerpt = strip_tags($p_data->post_content);
					$excerpt =	wp_trim_words( $excerpt , $num_ex, $more = '');
					$widget_data .= $item_loop_video->tm_likes_html($post,$like_count,$themes_pur,$show_likes,$show_com,$show_rate,$show_view,$show_excerpt,$excerpt);
				}
			$widget_data .= '</div>
			</div>';
			} 
	   
			$widget_data .= $after_widget;
	   
			echo $widget_data;


		} else
		if(class_exists('CT_ContentHelper')){		
			if($conditions == 'most_viewed' || $conditions == ''){
                $synchronize_views_count = function_exists('ot_get_option') ? ot_get_option('synchronize_views_count', '') : '';
                // check if BAW is installed and active
                if(function_exists('bawpvc_main') || $synchronize_views_count != ''){
                    if($timerange == 'day')
                    {
                        $qargs = array(
                            'post_type' => 'post',
                            'posts_per_page' => $number,
                            'meta_key' => '_count-views_day-'.date("Ymd"),
                            'orderby' => 'meta_value_num',
                            'order' => 'DESC',
                            'post_status' => 'publish',
                        );				
                    }elseif($timerange == 'week')
                    {
                        $qargs = array(
                            'post_type' => 'post',
                            'posts_per_page' => $number,
                            'meta_key' => '_count-views_week-'.date("YW"),
                            'orderby' => 'meta_value_num',
                            'order' => '',
                            'post_status' => 'publish',
                        );				
                    }elseif($timerange == 'month')
                    {
                        $qargs = array(
                            'post_type' => 'post',
                            'posts_per_page' => $number,
                            'meta_key' => '_count-views_month-'.date("Ym"),
                            'orderby' => 'meta_value_num',
                            'order' => '',
                            'post_status' => 'publish',
                        );				
                    }elseif($timerange == 'year')
                    {
                        $qargs = array(
                            'post_type' => 'post',
                            'posts_per_page' => $number,
                            'meta_key' => '_count-views_year-'.date("Y"),
                            'orderby' => 'meta_value_num',
                            'order' => '',
                            'post_status' => 'publish',
                        );				
                    }else{
                        $qargs = array(
                            'post_type' => 'post',
                            'posts_per_page' => $number,
                            'meta_key' => '_count-views_all',
                            'orderby' => 'meta_value_num',
                            'order' => '',
                            'post_status' => 'publish',
                        );
                    }
                } elseif(function_exists('get_tptn_post_count_only')) {
                    $ids = '';
                    
                    $offset = 0;
                    $posts_per_page = get_option('posts_per_page');
                    
                    if($paged && $paged > 0) $offset = ($paged - 1) * $posts_per_page;

                    if($timerange == 'day')
                    {
                        $args2 = array(
                            'daily' => 1,
                            'daily_range' => 1,
                            'post_types' =>'post',
                            'limit' => $number,
                            'ofset' => $offset
                        );
                        $ids = cactus_get_tptn_pop_posts($args2);
                        
                    }elseif($timerange == 'week'){
                        $args2 = array(
                            'daily' => 1,
                            'daily_range' => 7,
                            'post_types' =>'post',
                            'limit' => $number,
                            'offset' => $offset
                        );
                        $ids = cactus_get_tptn_pop_posts($args2);
                    }elseif($timerange=='month'){
                        $args2 = array(
                            'daily' => 1,
                            'daily_range' => 30,
                            'post_types' =>'post',
                            'limit' => $number,
                            'offset' => $offset
                        );
                        $ids = cactus_get_tptn_pop_posts($args2);
                    }elseif($timerange == 'year'){
                        $args2 = array(
                            'daily' => 1,
                            'daily_range' => 365,
                            'post_types' =>'post',
                            'limit' => $number,
                            'offset' => $offset
                        );
                        $ids = cactus_get_tptn_pop_posts($args2);
                        
                    }else{
                        $args2 = array(
                            'daily' => 0,
                            'post_types' =>'post',
                            'limit' => $number,
                            'offset' => $offset
                        );
                        $ids = cactus_get_tptn_pop_posts($args2);
                    }
                    
                    $qargs = array(
                        'post__in'=> $ids,
                        'orderby'=> 'post__in'
                    );
                    
                }
                
				$the_query = new WP_Query( $qargs );

				$html = $before_widget;
				if ( $title ) $html .= $before_title . $title . $after_title; 
				if($the_query->have_posts()):
					$html .= '
						<div class="widget-content">
						<div class="list-rating-item row">
					';
					$item_video = new CT_ContentHtml; 
					$i = 0;
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

			}else
			if($conditions=='most_comments'){
				wp_reset_postdata();
				if($timerange=='day'){	
					$some_comments = get_comments( array(
						'date_query' => array(
							array(
								'after' => '1 day ago',
							),
						),
					) );
				}else
				if($timerange=='week'){
					$some_comments = get_comments( array(
						'date_query' => array(
							array(
								'after' => '1 week ago',
							),
						),
					) );
				}else
				if($timerange=='month'){
					$some_comments = get_comments( array(
						'date_query' => array(
							array(
								'after' => '1 month ago',
							),
						),
					) );	
				}else
				if($timerange=='year'){	
					$some_comments = get_comments( array(
						'date_query' => array(
							array(
								'after' => '1 year ago',
							),
						),
					) );	
				}
				$html = $before_widget;
				if ( $title ) $html .= $before_title . $title . $after_title; 
				
				$arr_id= array();
				foreach($some_comments as $comment){
					$arr_id[] = $comment->comment_post_ID;
				}
				$arr_id = array_unique($arr_id, SORT_REGULAR);
				//$arr_id = implode(",", $arr_id);
				$args = array(
					'post_type' => 'post',
					'posts_per_page' => $number,
					'order' => $sort_by,
					'post_status' => 'publish',
					'post__in' =>  $arr_id,
					'ignore_sticky_posts' => 1,
				);
				$query = new WP_Query($args);
				if($query->have_posts()):
				$html .= '
				<div class="widget-content">
					<div class="list-rating-item row">';
					while($query->have_posts()): $query->the_post();
						$excerpt = get_the_excerpt();
						$html .= '
						<div class="col-md-12 col-sm-4">
						  <div class="video-item">
							  <div class="videos-row">
									<div class="item-thumbnail">
										<a href="'.get_permalink(get_the_ID()).'" title="'.get_the_title(get_the_ID()).'">';
										if(has_post_thumbnail(get_the_ID())){
													$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()),'thumb_139x89', true);
												}else{
													$thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
												}
										$html .= '<img src="'.$thumbnail[0].'" alt="'.the_title_attribute('echo=0').'" title="'.the_title_attribute('echo=0').'">';
										//'.get_the_post_thumbnail(get_the_ID(), array(139,89) ).'
										if($themes_pur!='0'){	
									$html .= '<div class="link-overlay fa fa-play "></div>';}
									$html .= '</a>';
									if($show_rate!='hide_r'){
									$html .= tm_post_rating(get_the_ID());
									}
									$html .= '</div>
								  <div class="item-info">
									<div class="all-info">
									<h2 class="rt-article-title"> <a href="'.get_permalink(get_the_ID()).'" title="'.get_the_title(get_the_ID()).'">'.get_the_title(get_the_ID()).'</a></h2>
									<div class="item-meta">';
									if($show_view!='hide_v'){
											$html .= '<span class="pp-icon"><i class="fa fa-eye"></i> '.get_post_meta(get_the_ID(), '_count-views_all', true).'</span><br>';
									  }
									  if($show_likes!='hide_l' &&function_exists('GetWtiLikeCount')){
											$html .= '<span class="pp-icon iclike"><i class="fa fa-thumbs-up"></i> '.str_replace('+','',GetWtiLikeCount(get_the_ID())).'</span><br>';
									  }
									  if($show_com!='hide_c'){
											$html .= '<span class="pp-icon"><i class="fa fa-comment"></i> '.get_comments_number(get_the_ID()).'</span><br>';			
									  }
									  $html.= '
										</div>
										</div>
										</div>';
									  if($show_excerpt!='hide_ex'){
										$html.= '<div class="pp-exceprt">'.$excerpt.'</div>';
									  }
									$html .='
							   </div>
						  </div>
						</div>
						';
					endwhile;
				$html .= '</div>
				</div>';
				endif;
				$html .= $after_widget;
				echo $html;
			}
			wp_reset_postdata();
			$cache[$argsxx['widget_id']] = ob_get_flush();
			wp_cache_set('widget_trending_videos', $cache, 'widget');
		}
	}
	
	function flush_widget_cache() {
		wp_cache_delete('widget_custom_type_posts', 'widget');
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['conditions'] = esc_attr($new_instance['conditions']);
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = absint($new_instance['number']);
		$instance['show_likes'] = esc_attr($new_instance['show_likes']);
		$instance['show_com'] = esc_attr($new_instance['show_com']);
		$instance['show_view'] = esc_attr($new_instance['show_view']);
		$instance['show_rate'] = esc_attr($new_instance['show_rate']);
		$instance['show_excerpt'] = esc_attr($new_instance['show_excerpt']);
		$instance['num_ex'] = absint($new_instance['num_ex']);
		$instance['link'] = strip_tags($new_instance['link']);
		$instance['timerange']=esc_attr($new_instance['timerange']);
		return $instance;
	}
	
	
	
	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$link = isset($instance['link']) ? esc_attr($instance['link']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 3;
		$conditions = isset($instance['conditions']) ? esc_attr($instance['conditions']) : '';
		$timerange = isset($instance['timerange']) ? esc_attr($instance['timerange']) : '';
		$show_likes = isset($instance['show_likes']) ? esc_attr($instance['show_likes']) : '';
		$show_com = isset($instance['show_com']) ? esc_attr($instance['show_com']) : '';
		$show_view = isset($instance['show_view']) ? esc_attr($instance['show_view']) : '';
		$show_rate = isset($instance['show_rate']) ? esc_attr($instance['show_rate']) : '';
		$show_excerpt = isset($instance['show_excerpt']) ? esc_attr($instance['show_excerpt']) : '';
		$num_ex = isset($instance['num_ex']) ? absint($instance['num_ex']) : 50;

?>

        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','cactusthemes'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
      <!-- /**/--> 
      <p>
        <label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link in title:','cactusthemes'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" 
        value="<?php echo $link; ?>" /></p>       
        <p>        
        <label for="<?php echo $this->get_field_id("conditions"); ?>">
        
        <?php _e('Conditions','cactusthemes');	 ?>:
        
            <select id="<?php echo $this->get_field_id("conditions"); ?>" name="<?php echo $this->get_field_name("conditions"); ?>">     
              <option value="most_viewed"<?php selected( $instance["conditions"], "most_viewed" ); ?>><?php _e('Most Viewed','cactusthemes');	 ?></option>
              <option value="most_comments"<?php selected( $instance["conditions"], "most_comments" ); ?>><?php _e('Most Comments','cactusthemes');	 ?></option>
              <option value="most_liked"<?php selected( $instance["conditions"], "most_liked" ); ?>><?php _e('Most Liked','cactusthemes');	 ?></option>
            </select>
        </label>
        </p>
        

<!--abc-->        
        
        <p>        
        <label for="<?php echo $this->get_field_id("timerange"); ?>">
        
        <?php _e('Time Range','cactusthemes');	 ?>:
        
            <select id="<?php echo $this->get_field_id("timerange"); ?>" name="<?php echo $this->get_field_name("timerange"); ?>">     
              <option value="day"<?php selected( $instance["timerange"], "day" ); ?>><?php _e('Day','cactusthemes');	 ?></option>
              <option value="week"<?php selected( $instance["timerange"], "week" ); ?>><?php _e('Week','cactusthemes');	 ?></option>
              <option value="month"<?php selected( $instance["timerange"], "month" ); ?>><?php _e('Month','cactusthemes');	 ?></option>
              <option value="year"<?php selected( $instance["timerange"], "year" ); ?>><?php _e('Year','cactusthemes');	 ?></option>
            </select>
        </label>
        </p>
<!--//-->
        <p>
        <label for="<?php echo $this->get_field_id("show_view"); ?>">
        <?php _e('Show View count','cactusthemes');	 ?>:
            <select id="<?php echo $this->get_field_id("show_view"); ?>" name="<?php echo $this->get_field_name("show_view"); ?>">     
              <option value="show_v"<?php selected( $instance["show_view"], "show_v" ); ?>><?php _e('Show','cactusthemes');?></option>
              <option value="hide_v"<?php selected( $instance["show_view"], "hide_v" ); ?>><?php _e('Hide','cactusthemes');?></option>
            </select>
        </label>
        </p>
        <p>
        <label for="<?php echo $this->get_field_id("show_com"); ?>">
        <?php _e('Show comments count','cactusthemes');	 ?>:
            <select id="<?php echo $this->get_field_id("show_com"); ?>" name="<?php echo $this->get_field_name("show_com"); ?>">     
              <option value="show_c"<?php selected( $instance["show_com"], "show_c" ); ?>><?php _e('Show','cactusthemes');?></option>
              <option value="hide_c"<?php selected( $instance["show_com"], "hide_c" ); ?>><?php _e('Hide','cactusthemes');	 ?></option>
            </select>
        </label>
        </p>
        <p>
        <label for="<?php echo $this->get_field_id("show_likes"); ?>">
        <?php _e('Show likes count','cactusthemes');	 ?>:
            <select id="<?php echo $this->get_field_id("show_likes"); ?>" name="<?php echo $this->get_field_name("show_likes"); ?>">     
              <option value="show_l"<?php selected( $instance["show_likes"], "show_l" ); ?>><?php _e('Show','cactusthemes');	 ?></option>
              <option value="hide_l"<?php selected( $instance["show_likes"], "hide_l" ); ?>><?php _e('Hide','cactusthemes');	 ?></option>
            </select>
        </label>
        </p>
        <p>
        <label for="<?php echo $this->get_field_id("show_rate"); ?>">
        <?php _e('Show rate count','cactusthemes');	 ?>:
            <select id="<?php echo $this->get_field_id("show_rate"); ?>" name="<?php echo $this->get_field_name("show_rate"); ?>">     
              <option value="show_r"<?php selected( $instance["show_rate"], "show_r" ); ?>><?php _e('Show','cactusthemes');?></option>
              <option value="hide_r"<?php selected( $instance["show_rate"], "hide_r" ); ?>><?php _e('Hide','cactusthemes');?></option>
            </select>
        </label>
        </p>
        <p>
        <label for="<?php echo $this->get_field_id("show_excerpt"); ?>">
        <?php _e('Show Exceprt','cactusthemes');	 ?>:
            <select id="<?php echo $this->get_field_id("show_excerpt"); ?>" name="<?php echo $this->get_field_name("show_excerpt"); ?>">     
              <option value="hide_ex"<?php selected( $instance["show_excerpt"], "hide_ex" ); ?>><?php _e('Hide','cactusthemes');?></option>
              <option value="show_ex"<?php selected( $instance["show_excerpt"], "show_ex" ); ?>><?php _e('Show','cactusthemes');?></option>
            </select>
        </label>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('num_ex'); ?>"><?php _e('Number text of excerpt to show:','cactusthemes'); ?></label> 
          <input id="<?php echo $this->get_field_id('num_ex'); ?>" name="<?php echo $this->get_field_name('num_ex'); ?>" type="text" 
          value="<?php echo $num_ex; ?>"  size="3"/>
        </p>
      <!-- /**/-->

        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number:','cactusthemes'); ?></label>
        <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<!--//-->

<?php
	}
}

// register RecentPostsPlus widget
add_action( 'widgets_init', create_function( '', 'return register_widget("Trending_Video_Widget");' ) );
?>