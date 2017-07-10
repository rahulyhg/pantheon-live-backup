<?php
class TM_Top_Authors extends WP_Widget {



	function __construct() {
    	$widget_ops = array(
			'classname'   => 'top_authors_widget', 
			'description' => __('Top author','cactusthemes')
		);
    	parent::__construct('top_authors', __('TM-Top author'), $widget_ops);
	}

	/**
	 * This is the part where the heart of this widget is!
	 * here we get al the authors and count their posts. 
	 *
	 * The frontend function
	 */
	function widget( $args, $instance ) {
		extract( $args );
		
		// default values 
		$exclude_admin = false;
		$exclude_zero = true;
		
		/* Our variables from the widget settings. (nice tabbed huh!?)*/
		if(isset($instance))
		{
						
			if( isset( $instance[ 'title' ] ) ) {
				$title = 				apply_filters( 'widget_title', $instance[ 'title' ] );
			}
			
			if( isset( $instance[ 'number' ]) ) {
				$number_of_authors = 	$instance[ 'number' ];
			}
			$ct_text = 'Videos';
			if(function_exists('ot_get_option'))
			{
				$theme_purpose= ot_get_option('theme_purpose');
				if($theme_purpose!='0'){
					$ct_text = 'Videos';
				}else
				{ $ct_text = 'Posts'; }
			}
			$template = 			htmlspecialchars_decode( '<li><a href="%linktoposts%" class="tm_img">%gravatar%</a> <p class="tm_img2"><a href="%linktoposts%" >%firstname% %lastname% </a>%nrofposts% '.__($ct_text, 'cactusthemse').'</p></li>' );
			$before_the_list =		htmlspecialchars_decode( '<div class="tm_top_author"><ul>' );
			$after_the_list = 		htmlspecialchars_decode('</ul></div>' );
			$gravatar_size =		'60';
//			if( isset( $instance[ 'exclude_admin' ] ) ) {
//				$exclude_admin = 		$instance[ 'exclude_admin' ];
//		 	}
		 	
//			if( isset( $instance[ 'exclude_zero' ] ) ) {
//				$exclude_zero = 		$instance[ 'exclude_zero' ];
//			}
		}
		$author_slug = 'author' ;
		$author_link = 'username' ;
		
		// define vars
		$counter=0;
		
		/* Before widget (defined by themes). */
		if( isset( $before_widget ) ) {
			echo $before_widget;
		}

		/* Display the widget title if one was input (before and after defined by themes). */
		if( isset( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		$user_list = array();
		
		$blogusers = get_users(); // doh

		// this part can be a heavyload process if you have a lot of authors
	
 		if ( $blogusers ) {
		  foreach ( $blogusers as $bloguser ) {
		  	
		   	$user_list[] = $bloguser->ID;
		   }
		   global $wpdb;
		
		   
		  // to add CPT support we have to use a custom query
		  foreach ($user_list as $user)
			{
					//if(isset($include_CPT))
					//{
						//$posts[$user] = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_author = $user AND NOT post_type = 'page' AND post_status = 'publish'" );
					//}else{
						$posts[$user] = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_author = $user AND post_type = 'post' AND NOT  post_type = 'page' AND post_status = 'publish'" );
					//}
			}
	 
		 arsort( $posts ); //use asort($user_list) if ascending by post count is desired
		
		  
		  // user defined html element before the list
		  if( $user_list ) { echo $before_the_list; }
		
		  if( count( $user_list ) < $number_of_authors )
		  {
		  	$number_of_authors=count($user_list);
		  }

		 foreach($posts as  $userid => $post) 
		 {
			$counter++;
			if($counter>$number_of_authors)
			{
				break;
			}

			// create a WP user object
			$user = new WP_User( $userid );
			
			// detect if user is administrator
			// Introduced in version 0.5 of top-authors. Hope this is fool-proof.
			if( isset( $user->wp_capabilities[ 'administrator' ] ) || isset( $user->blog_capabilities[ 'administrator' ] ) ){
				$user_is_admin = true;
			}
			else
			{
				$user_is_admin = false;
			}
		
			$author_posts_url = get_author_posts_url($userid);
			
			if(!$user->user_firstname && !$user->user_lastname)
			{
				$user->user_firstname = $user->user_login;
			}    
			//replace anchors in usertemplate		
			//author_slug / display_name	
			
			// linkbase - author_link
			
			$arr_replace['username'] = $user->user_login;
			$arr_replace['nickname'] = $user->nickname;
			$arr_replace['display_name'] = $user->display_name;
			
			$output = str_replace("%linktoposts%",get_bloginfo("url") .'/'.$author_slug.'/'.str_replace(" ","-",strtolower($arr_replace[$author_link])),$template);
			
			$output = str_replace("%firstname%",$user->user_firstname,$output);
			$output = str_replace("%lastname%",$user->user_lastname,$output);
			$output = str_replace("%nrofposts%",$post,$output);
			$output = str_replace("%nickname%",$user->nickname,$output);
			$output = str_replace("%displayname%",$user->display_name,$output);
			$output = str_replace("%author_id%",$user->ID,$output);
			$output = str_replace("%link_author_id%",get_bloginfo("url") .'/?author=' .$user->ID,$output);
			
			$gravatar_detect = strpos($output,"%gravatar%");
			
			if($gravatar_detect !== false){
				$gravatar = get_avatar($user->ID, $gravatar_size);
				 $output = str_replace("%gravatar%",$gravatar,$output);
			}
			 
			  if(($user_is_admin && $exclude_admin == "on") || ($post<1 && $exclude_zero=="on"))
			  {
			  	// aiii we skipped a user but we still want to get the total number of users right!
			  	$counter--;
			  }
			  else
			  {
				   //newline in html, al  for the looks!
				  echo $output ."\n";
			  }
			}
	
		  // user defined html after the list
		  if($user_list){echo $after_the_list;}
		}
		/* After widget (defined by themes). */
		echo $after_widget;
	
	}

	/**
	 * Update the widget settings.
	 *
	 * Backend widget settings
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = 			strip_tags( $new_instance['title'] );
				
		//$instance['author_link']	=	$new_instance['author_link'];
		
		//$instance['exclude_admin'] =	$new_instance['exclude_admin'];
		//$instance['exclude_zero'] =		$new_instance['exclude_zero'];
		//$instance['include_CPT']	= $new_instance['include_CPT'];
		// check if datainput isnummeric		
		// check if datainput isnummeric and postive and under 100
		if(is_numeric($new_instance['number']))
		{
			if($new_instance['number'] <100 && $new_instance['number'] >0)
			{
				$instance['number'] =  $new_instance['number'];
			}
			else
			{
				if($new_instance['number'] < 1)
				{
					$instance['number'] = 1;
				}	
				else
				{
					$instance['number'] = 99;
				}
			
			}
		}
		

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 *
	 * Backend widget options form
	 */
	function form( $instance ) {
		$defaults = array( 
			'title' => __(	'Top Authors', 'cactusthemes'), 
							'number' => __(5, 'cactusthemes'), 
						);
						
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'cactusthemes'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:96%;float:right;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Number of authors: (1-99)', 'cactusthemes'); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" style="width:96%;float:right;" />
		</p>
		
	<?php
	}
}




// register  widget
add_action( 'widgets_init', create_function( '', 'return register_widget("TM_Top_Authors");' ) );
?>