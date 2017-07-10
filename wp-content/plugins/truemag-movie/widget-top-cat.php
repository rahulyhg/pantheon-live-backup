<?php
class TM_Top_Cat extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'tm_widget_categories', 'description' => __( "Top categories","cactusthemes" ) );
		parent::__construct('tm_categories', __('TM-Top Categories','cactusthemes'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Categories','cactusthemes' ) : $instance['title'], $instance, $this->id_base);
		$ids 			= empty($instance['ids']) ? '' : $instance['ids'];
		$number_cat 			= empty($instance['number_cat']) ? '' : $instance['number_cat'];
		$column 		= empty($instance['column']) ? '' : $instance['column'];
		$sort_by 		= empty($instance['sort_by']) ? '' : $instance['sort_by'];
		$order 		= empty($instance['order']) ? '' : $instance['order'];
		$h='';
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		$cat_args = array('orderby' => $sort_by, 'order'=>$order, 'show_count' => '1' , 'include' =>$ids, 'hierarchical' => $h,'number' => $number_cat);

?>
		<ul>
<?php
		if($column!='2')
		{
			echo '<style type="text/css" scoped="scoped">
			li.cat-item{ width:100%  !important}
			</style>';
		}
		
		$cat_args['title_li'] = '';
		tm_wp_list_categories(apply_filters('tm_wp_list_categories', $cat_args));
?>
		</ul>
<?php

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['ids'] = strip_tags($new_instance['ids']);
		$instance['number_cat'] = strip_tags($new_instance['number_cat']);
		$instance['column'] = esc_attr($new_instance['column']);
		$instance['sort_by'] = esc_attr($new_instance['sort_by']);
		$instance['order'] = esc_attr($new_instance['order']);
		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = esc_attr( $instance['title'] );
		$ids = isset($instance['ids']) ? esc_attr($instance['ids']) : '';
		$number_cat = isset($instance['number_cat']) ? esc_attr($instance['number_cat']) : '';
		$sort_by = isset($instance['sort_by']) ? esc_attr($instance['sort_by']) : '';
		$column = isset($instance['column']) ? esc_attr($instance['column']) : '';
		$order = isset($instance['order']) ? esc_attr($instance['order']) : '';
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:','cactusthemes' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
        
		<p><label for="<?php echo $this->get_field_id('ids'); ?>"><?php _e( 'Ids:','cactusthemes' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('ids'); ?>" name="<?php echo $this->get_field_name('ids'); ?>" type="text" value="<?php echo $ids; ?>" /></p>
        <p><label for="<?php echo $this->get_field_id('ids'); ?>"><?php _e( 'Number of categories to show:' ,'cactusthemes'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('number_cat'); ?>" name="<?php echo $this->get_field_name('number_cat'); ?>" type="text" value="<?php echo $number_cat; ?>" /></p>
        <p>
			<label for="<?php echo $this->get_field_id('column'); ?>"><?php _e( 'Column:','cactusthemes' ); ?></label>
			<select name="<?php echo $this->get_field_name('column'); ?>" id="<?php echo $this->get_field_id('column'); ?>" class="widefat">
				<option value="1"<?php selected( $instance['column'], '1' ); ?>><?php _e('1'); ?></option>
				<option value="2"<?php selected( $instance['column'], '2' ); ?>><?php _e('2'); ?></option>
			</select>
		</p>
        <p>
			<label for="<?php echo $this->get_field_id('sort_by'); ?>"><?php _e( 'Order By:','cactusthemes' ); ?></label>
			<select name="<?php echo $this->get_field_name('sort_by'); ?>" id="<?php echo $this->get_field_id('sort_by'); ?>" class="widefat">
				<option value="count"<?php selected( $instance['sort_by'], 'count' ); ?>><?php _e('Number of Post','cactusthemes'); ?></option>
				<option value="name"<?php selected( $instance['sort_by'], 'name' ); ?>><?php _e('Title','cactusthemes'); ?></option>
			</select>
		</p>
        <p>
			<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e( 'Order:','cactusthemes' ); ?></label>
			<select name="<?php echo $this->get_field_name('order'); ?>" id="<?php echo $this->get_field_id('order'); ?>" class="widefat">
            	<option value="DESC"<?php selected( $instance['order'], 'DESC' ); ?>><?php _e('DESC'); ?></option>
				<option value="ASC"<?php selected( $instance['order'], 'ASC' ); ?>><?php _e('ASC'); ?></option>
			</select>
		</p>

<?php
	}

}

//categories

class Tm_Category_Walker extends  Walker_Category {
		function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		extract($args);

		$cat_name = esc_attr( $category->name );
		$cat_name = apply_filters( 'list_cats', $cat_name, $category );
		$link = '<a href="' . esc_url( get_term_link($category) ) . '" ';
		if ( $use_desc_for_title == 0 || empty($category->description) )
			$link .= 'title="' . esc_attr( sprintf(__( 'View all posts filed under %s' ), $cat_name) ) . '"';
		else
			$link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
		$link .= '>';
		$link .= $cat_name . '</a>';

		if ( !empty($feed_image) || !empty($feed) ) {
			$link .= ' ';

			if ( empty($feed_image) )
				$link .= '(';

			$link .= '<a href="' . esc_url( get_term_feed_link( $category->term_id, $category->taxonomy, $feed_type ) ) . '"';

			if ( empty($feed) ) {
				$alt = ' alt="' . sprintf(__( 'Feed for all posts filed under %s' ), $cat_name ) . '"';
			} else {
				$title = ' title="' . $feed . '"';
				$alt = ' alt="' . $feed . '"';
				$name = $feed;
				$link .= $title;
			}

			$link .= '>';

			if ( empty($feed_image) )
				$link .= $name;
			else
				$link .= "<img src='$feed_image'$alt$title" . ' />';

			$link .= '</a>';

			if ( empty($feed_image) )
				$link .= ')';
		}

		if ( !empty($show_count) )
			$link .= '<p>' . intval($category->count) . '';

		if ( 'list' == $args['style'] ) {
			$output .= "\t<li";
			$class = 'cat-item cat-item-' . $category->term_id;
			if ( !empty($current_category) ) {
				$_current_category = get_term( $current_category, $category->taxonomy );
				if ( $category->term_id == $current_category )
					$class .=  ' current-cat';
				elseif ( $category->term_id == $_current_category->parent )
					$class .=  ' current-cat-parent';
			}
			$output .=  ' class="' . $class . '"';
			$output .= ">$link\n";
		} else {
			$output .= "\t$link<br />\n";
		}
	}

	
	function end_el( &$output, $page, $depth = 0, $args = array() ) {
		if ( 'list' != $args['style'] )
			return;
		$ct_text = 'Videos';
		if(function_exists('ot_get_option'))
		{
			$theme_purpose= ot_get_option('theme_purpose');
			if($theme_purpose!='0'){
				$ct_text = __('Videos','cactusthemes');
			}else
			{ $ct_text = __('Posts','cactusthemes'); }
		}

		$output .= sprintf($ct_text) ."</p></li>\n";
	}
}

function tm_walk_category_tree() {
	$args = func_get_args();
	// the user's options are the third parameter
	if ( empty($args[2]['walker']) || !is_a($args[2]['walker'], 'Walker') )
		$walker = new Tm_Category_Walker;
	else
		$walker = $args[2]['walker'];

	return call_user_func_array(array( &$walker, 'walk' ), $args );
}

function tm_wp_list_categories( $args = '' ) {
	$defaults = array(
		'show_option_all' => '', 'show_option_none' => __('No categories','cactusthemes'),
		'orderby' => 'name', 'order' => 'ASC',
		'style' => 'list',
		'show_count' => 0, 'hide_empty' => 1,
		'use_desc_for_title' => 1, 'child_of' => 0,
		'feed' => '', 'feed_type' => '',
		'feed_image' => '', 'exclude' => '',
		'exclude_tree' => '', 'current_category' => 0,
		'hierarchical' => true, 'title_li' => __( 'Categories','cactusthemes' ),
		'echo' => 1, 'depth' => 0,
		'number'                   => '',
		'taxonomy' => 'category'
	);

	$r = wp_parse_args( $args, $defaults );

	if ( !isset( $r['pad_counts'] ) && $r['show_count'] && $r['hierarchical'] )
		$r['pad_counts'] = true;

	if ( true == $r['hierarchical'] ) {
		$r['exclude_tree'] = $r['exclude'];
		$r['exclude'] = '';
	}

	if ( !isset( $r['class'] ) )
		$r['class'] = ( 'category' == $r['taxonomy'] ) ? 'categories' : $r['taxonomy'];

	extract( $r );

	if ( !taxonomy_exists($taxonomy) )
		return false;

	$categories = get_categories( $r );

	$output = '';
	if ( $title_li && 'list' == $style )
			$output = '<li class="' . esc_attr( $class ) . '">' . $title_li . '<ul>';

	if ( empty( $categories ) ) {
		if ( ! empty( $show_option_none ) ) {
			if ( 'list' == $style )
				$output .= '<li>' . $show_option_none . '</li>';
			else
				$output .= $show_option_none;
		}
	} else {
		if ( ! empty( $show_option_all ) ) {
			$posts_page = ( 'page' == get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/' );
			$posts_page = esc_url( $posts_page );
			if ( 'list' == $style )
				$output .= "<li ><a href='$posts_page'>$show_option_all</a></li>";
			else
				$output .= "<a href='$posts_page'>$show_option_all</a>";
		}

		if ( empty( $r['current_category'] ) && ( is_category() || is_tax() || is_tag() ) ) {
			$current_term_object = get_queried_object();
			if ( $current_term_object && $r['taxonomy'] === $current_term_object->taxonomy )
				$r['current_category'] = get_queried_object_id();
		}

		if ( $hierarchical )
			$depth = $r['depth'];
		else
			$depth = -1; // Flat.

		$output .= tm_walk_category_tree( $categories, $depth, $r );
	}

	if ( $title_li && 'list' == $style )
		$output .= '</ul></li>';

	$output = apply_filters( 'tm_wp_list_categories', $output, $args );

	if ( $echo )
		echo $output;
	else
		return $output;
}


// register  widget
add_action( 'widgets_init', create_function( '', 'return register_widget("TM_Top_Cat");' ) );
?>