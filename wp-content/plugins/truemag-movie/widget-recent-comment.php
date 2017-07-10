<?php
class TM_Recent_Comments extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'tm_recent_comments', 'description' => __( 'The most recent comments','cactusthemes' ) );
		parent::__construct('tm-recent-comments', __('TM - Recent Comments','cactusthemes'), $widget_ops);
		$this->alt_option_name = 'tm_recent_comments';

		if ( is_active_widget(false, false, $this->id_base) )
			add_action( 'wp_head', array($this, 'recent_comments_style') );

		add_action( 'comment_post', array($this, 'flush_widget_cache') );
		add_action( 'edit_comment', array($this, 'flush_widget_cache') );
		add_action( 'transition_comment_status', array($this, 'flush_widget_cache') );
	}

	function recent_comments_style() {
		if ( ! current_theme_supports( 'widgets' ) // Temp hack #14876
			|| ! apply_filters( 'show_recent_comments_widget_style', true, $this->id_base ) )
			return;
		?>
	<style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
<?php
	}

	function flush_widget_cache() {
		wp_cache_delete('tm_recent_comments', 'widget');
	}

	function widget( $args, $instance ) {
		global $comments, $comment;

		$cache = wp_cache_get('tm_recent_comments', 'widget');

		if ( ! is_array( $cache ) )
			$cache = array();

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}
		
		$d = get_option( 'date_format' ) ;
		
 		extract($args, EXTR_SKIP);
 		$output = '';

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Comments','cactusthemes' );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		$number_char = ( ! empty( $instance['number_char'] ) ) ? absint( $instance['number_char'] ) : 50;
		if ( ! $number )
 			$number = 5;

		$comments = get_comments( apply_filters( 'widget_comments_args', array( 'number' => $number, 'status' => 'approve', 'post_status' => 'publish' ) ) );
		$output .= $before_widget;
		if ( $title )
			$output .= $before_title . $title . $after_title;

		$output .= '<ul id="tm_recentcomments">';
		if ( $comments ) {
			// Prime cache for associated posts. (Prime post term cache if we need it for permalinks.)
			$post_ids = array_unique( wp_list_pluck( $comments, 'comment_post_ID' ) );
			_prime_post_caches( $post_ids, strpos( get_option( 'permalink_structure' ), '%category%' ), false );

			foreach ( (array) $comments as $comment) {
				$num_char = get_comment_text($comment->comment_ID);
				$len = strlen($num_char);
				if($len > $number_char){$num_char = substr($num_char,0,$number_char).'...';}
				
				$output .=  '<li class="tm_recentcomments"><a class="cm-avatar" href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">'.get_avatar($comment, 60, '', get_the_author()).'</a>' . /* translators: comments widget: 1: comment author, 2: post link */ sprintf(_x('%1$s  %2$s', 'widgets'), '<div class="info_rc"><a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">' .$num_char. '</a>','<p><span>'. get_comment_author_link()) . '</span>'.get_comment_date( $d, $comment->comment_ID ).''.__( ' at ','cactusthemes' ).''.get_comment_time().'</p></div></li>';
			}
 		}
		$output .= '</ul>';
		$output .= $after_widget;

		echo $output;
		$cache[$args['widget_id']] = $output;
		wp_cache_set('tm_recent_comments', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = absint( $new_instance['number'] );
		$instance['number_char'] = absint( $new_instance['number_char'] );
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['tm_recent_comments']) )
			delete_option('tm_recent_comments');

		return $instance;
	}

	function form( $instance ) {
		$title  = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$number_char = isset( $instance['number_char'] ) ? absint( $instance['number_char'] ) : 50;
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','cactusthemes' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of comments to show:','cactusthemes' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
        
        <p><label for="<?php echo $this->get_field_id( 'number_char' ); ?>"><?php _e( 'Number of character to show:','cactusthemes' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number_char' ); ?>" name="<?php echo $this->get_field_name( 'number_char' ); ?>" type="text" value="<?php echo $number_char; ?>" size="3" /></p>
<?php
	}
}




// register  widget
add_action( 'widgets_init', create_function( '', 'return register_widget("TM_Recent_Comments");' ) );
?>