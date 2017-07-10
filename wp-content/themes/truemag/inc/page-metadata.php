<?php

/**
 * Initialize the meta boxes. 
 */
add_action( 'admin_init', 'ct_page_meta_boxes' );

if ( ! function_exists( 'ct_page_meta_boxes' ) ){
	function ct_page_meta_boxes() {
	  $page_meta_box = array(
		'id'        => 'page_meta_box',
		'title'     => 'Page Settings',
		'desc'      => '',
		'pages'     => array( 'page' ),
		'context'   => 'normal',
		'priority'  => 'high',
		'fields'    => array(
			array(
			  'id'          => 'sidebar',
			  'label'       => __('Sidebar','cactusthemes'),
			  'desc'        => __('Main Sidebar appearance','cactusthemes'),
			  'std'         => '',
			  'type'        => 'select',
			  'class'       => '',
			  'choices'     => array(
			  	  array(
					'value'       => '',
					'label'       => 'Default',
					'src'         => ''
				  ),
				  array(
					'value'       => 'left',
					'label'       => 'Left Sidebar',
					'src'         => ''
				  ),
				  array(
					'value'       => 'right',
					'label'       => 'Right Sidebar',
					'src'         => ''
				  ),
				  array(
					'value'       => 'full',
					'label'       => 'No Sidebar',
					'src'         => ''
				  )
			   )
			),
			array(	
					'id'          => 'background',
				  'label'       => __('Background','cactusthemes'),
				  'desc'        => __('If used with Page Template "Front Page" and Header Style is "Sidebar" or "Classy", this will be header background','cactusthemes'),
				  'std'         => '',
				  'type'        => 'background'
			)
		 )
	  );
	  ot_register_meta_box( $page_meta_box );
	  
	  //front page metabox
	  $front_meta_box = array(
		'id'        => 'front_meta_box',
		'title'     => 'Front Page Settings',
		'desc'      => '',
		'pages'     => array( 'page' ),
		'context'   => 'normal',
		'priority'  => 'high',
		'fields'    => array(
			array(
			  'id'          => 'header_style',
			  'label'       => __('Header Stye','cactusthemes'),
			  'desc'        => __('Only use with Page template "Front Page"','cactusthemes'),
			  'std'         => '',
			  'type'        => 'select',
			  'class'       => '',
			  'choices'     => array(
			  	  array(
					'value'       => 0,
					'label'       => 'Default',
					'src'         => ''
				  ),
				  array(
					'value'       => 'carousel',
					'label'       => 'Big Carousel',
					'src'         => ''
				  ),
				  array(
					'value'       => 'metro',
					'label'       => 'Metro Carousel',
					'src'         => ''
				  ),
				  array(
					'value'       => 'classy',
					'label'       => 'Classy Slider',
					'src'         => ''
				  ),
				  array(
					'value'       => 'classy2',
					'label'       => 'Classy Slider 2',
					'src'         => ''
				  ),
				  array(
					'value'       => 'classy3',
					'label'       => 'Classy Slider 3',
					'src'         => ''
				  ),
				  array(
					'value'       => 'amazing',
					'label'       => 'Amazing Slider',
					'src'         => ''
				  ),
				  array(
					'value'       => 'video_slider',
					'label'       => 'Video Slider',
					'src'         => ''
				  ),
				  array(
					'value'       => 'sidebar',
					'label'       => 'Sidebar',
					'src'         => ''
				  )
			   )
			),
			array(
				'id'          => 'header_home_postids',
				'label'       => 'Header - Items IDs',
				'desc'        => 'Include post IDs to show on Header of Front Page',
				'std'         => '',
				'type'        => 'text',
				'section'     => 'home',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'min_max_step'=> '',
				'class'       => ''
			  ),
			  array(
				'id'          => 'header_home_condition',
				'label'       => 'Header - Condition',
				'desc'        => 'Select condition to query post on Header of Front Page',
				'std'         => '',
				'type'        => 'select',
				'section'     => 'home',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'min_max_step'=> '',
				'class'       => '',
				'choices'     => array( 
				  array(
					'value'       => 0,
					'label'       => 'Default',
					'src'         => ''
				  ),
				  array(
					'value'       => 'latest',
					'label'       => 'Lastest',
					'src'         => ''
				  ),
				  array(
					'value'       => 'most_viewed',
					'label'       => 'Most Viewed',
					'src'         => ''
				  ),
				  array(
					'value'       => 'most_comments',
					'label'       => 'Most Comments',
					'src'         => ''
				  ),
				  array(
					'value'       => 'likes',
					'label'       => 'Most liked',
					'src'         => ''
				  ),
				  array(
					'value'       => 'title',
					'label'       => 'Title',
					'src'         => ''
				  ),
				  array(
					'value'       => 'modified',
					'label'       => 'Modified',
					'src'         => ''
				  ),
				  array(
					'value'       => 'random',
					'label'       => 'Random',
					'src'         => ''
				  )
				),
			  ),
			  array(
				  'id'          => 'header_time_range',
				  'label'       => __('Time range','cactusthemes'),
				  'desc'        => __('','cactusthemes'),
				  'std'         => '',
				  'condition'   => 'header_home_condition:not(latest),header_home_condition:not(modified),header_home_condition:not(random),header_home_condition:not(title),header_home_condition:not(0)',
				  'type'        => 'select',
				  'choices'     => array(
					array(
					  'value'       => '',
					  'label'       => __( 'All', 'cactusthemes' ),
					  'src'         => ''
					),
					array(
					  'value'       => 'day',
					  'label'       => __( 'Day', 'cactusthemes' ),
					  'src'         => ''
					),
					array(
					  'value'       => 'week',
					  'label'       => __( 'Week', 'cactusthemes' ),
					  'src'         => ''
					),
					array(
					  'value'       => 'month',
					  'label'       => __( 'Month', 'cactusthemes' ),
					  'src'         => ''
					),
					array(
					  'value'       => 'year',
					  'label'       => __( 'Year', 'cactusthemes' ),
					  'src'         => ''
					),
				  )
			),
			  array(
				'id'          => 'header_home_cat',
				'label'       => 'Header - Category (ID or slug)',
				'desc'        => 'Include Category ID, slug to show on Header Home section (Ex: 15,26,37 or foo,bar,jazz)',
				'std'         => '',
				'type'        => 'text',
				'section'     => 'home',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'min_max_step'=> '',
				'class'       => ''
			  ),
			  array(
				'id'          => 'header_home_tag',
				'label'       => 'Header - Items Tags',
				'desc'        => 'Include Tags to show on Header Home section (Ex: foo,bar)',
				'std'         => '',
				'type'        => 'text',
				'section'     => 'home',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'min_max_step'=> '',
				'class'       => ''
			  ),
			  array(
				'id'          => 'header_home_order',
				'label'       => 'Header - Items Order',
				'desc'        => '',
				'std'         => '',
				'type'        => 'select',
				'section'     => 'home',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'min_max_step'=> '',
				'class'       => '',
				'choices'     => array( 
				  array(
					'value'       => 0,
					'label'       => 'Default',
					'src'         => ''
				  ),
				  array(
					'value'       => 'desc',
					'label'       => 'DESC',
					'src'         => ''
				  ),
				  array(
					'value'       => 'asc',
					'label'       => 'ASC',
					'src'         => ''
				  )
				),
			  ),
			  array(
				'id'          => 'header_home_number',
				'label'       => 'Header - Number of Items',
				'desc'        => 'Adjust this value to have best layout that fits screen',
				'std'         => '',
				'type'        => 'text',
				'section'     => 'home',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'min_max_step'=> '',
				'class'       => ''
			  ),
			
		 )
	  );
	  ot_register_meta_box( $front_meta_box );
	  
	  $front_page_meta_box2 = array(
		'id'        => 'front_page_meta_box2',
		'title'     => __('Front Page Content Settings','cactusthemes'),
		'desc'      => '',
		'pages'     => array( 'page' ),
		'context'   => 'normal',
		'priority'  => 'high',
		'fields'    => array(
			array(
				  'id'          => 'page_content',
				  'label'       => __('Content','cactusthemes'),
				  'desc'        => __('','cactusthemes'),
				  'std'         => 'page_ct',
				  'type'        => 'select',
				  'choices'     => array(
					array(
					  'value'       => 'page_ct',
					  'label'       => __( 'This Page Content', 'cactusthemes' ),
					  'src'         => ''
					),
					array(
					  'value'       => 'blog',
					  'label'       => __( 'Blog(latest post)', 'cactusthemes' ),
					  'src'         => ''
					),
				  )
			),
			array(
			  'id'          => 'post_categories_ct',
			  'label'       => __('Post categories', 'cactusthemes' ),
			  'desc'        => __('Enter category Ids or slugs to get posts from, separated by a comma', 'cactusthemes' ),
			  'std'         => '',
			  'type'        => 'text',
			),
			array(
			  'id'          => 'post_tags_ct',
			  'label'       => __('Post tags', 'cactusthemes' ),
			  'desc'        => __('Enter tags to get posts from, separated by a comma', 'cactusthemes' ),
			  'std'         => '',
			  'type'        => 'text',
			),
			array(
				  'id'          => 'condition_ct',
				  'label'       => __('Order by','cactusthemes'),
				  'desc'        => __('','cactusthemes'),
				  'std'         => 'post-date',
				  'type'        => 'select',
				  'choices'     => array(
					array(
					  'value'       => 'latest',
					  'label'       => __( 'Latest', 'cactusthemes' ),
					  'src'         => ''
					),
					array(
					  'value'       => 'most_viewed',
					  'label'       => __( 'Most viewed', 'cactusthemes' ),
					  'src'         => ''
					),
					array(
					  'value'       => 'likes',
					  'label'       => __( 'Most Liked', 'cactusthemes' ),
					  'src'         => ''
					),
					array(
					  'value'       => 'most_comments',
					  'label'       => __( 'Most commented', 'cactusthemes' ),
					  'src'         => ''
					),
					array(
					  'value'       => 'title',
					  'label'       => __( 'Title', 'cactusthemes' ),
					  'src'         => ''
					),
					array(
					  'value'       => 'modified',
					  'label'       => __( 'Modified', 'cactusthemes' ),
					  'src'         => ''
					),
					array(
					  'value'       => 'random',
					  'label'       => __( 'Random', 'cactusthemes' ),
					  'src'         => ''
					)
				  )
			),
			array(
				  'id'          => 'time_range_ct',
				  'label'       => __('Time range','cactusthemes'),
				  'desc'        => __('','cactusthemes'),
				  'std'         => '',
				  'condition'   => 'condition_ct:not(latest),condition_ct:not(modified),condition_ct:not(random),condition_ct:not(title)',
				  'type'        => 'select',
				  'choices'     => array(
					array(
					  'value'       => '',
					  'label'       => __( 'All', 'cactusthemes' ),
					  'src'         => ''
					),
					array(
					  'value'       => 'day',
					  'label'       => __( 'Day', 'cactusthemes' ),
					  'src'         => ''
					),
					array(
					  'value'       => 'week',
					  'label'       => __( 'Week', 'cactusthemes' ),
					  'src'         => ''
					),
					array(
					  'value'       => 'month',
					  'label'       => __( 'Month', 'cactusthemes' ),
					  'src'         => ''
					),
					array(
					  'value'       => 'year',
					  'label'       => __( 'Year', 'cactusthemes' ),
					  'src'         => ''
					),
				  )
			),
			array(
				  'id'          => 'order_ct',
				  'label'       => __('Order','cactusthemes'),
				  'desc'        => __('','cactusthemes'),
				  'std'         => '',
				  'type'        => 'select',
				  'choices'     => array(
					array(
					  'value'       => 'DESC',
					  'label'       => __( 'DESC', 'cactusthemes' ),
					  'src'         => ''
					),
					array(
					  'value'       => 'ASC',
					  'label'       => __( 'ASC', 'cactusthemes' ),
					  'src'         => ''
					),
				  )
			),
		 )
		);
	ot_register_meta_box( $front_page_meta_box2 );


	$video_sitemap_meta = array(
		'id'        => 'video_sitemap_meta',
		'title'     => __('Video Sitemap Settings','cactusthemes'),
		'desc'      => '',
		'pages'     => array( 'page' ),
		'context'   => 'normal',
		'priority'  => 'high',
		'fields'    => array(
			array(
			  'id'          => 'offset_videosm',
			  'label'       => __('Export Video From', 'cactusthemes' ),
			  'desc'        => __('Enter offset number. For example, if you want to export from video at 101st position, enter 100', 'cactusthemes' ),
			  'std'         => '',
			  'type'        => 'text',
			),
			array(
			  'id'          => 'number_videosm',
			  'label'       => __('Number of videos', 'cactusthemes' ),
			  'desc'        => __('Enter number of videos to include in the sitemap. Leave blank to export all', 'cactusthemes' ),
			  'std'         => '',
			  'type'        => 'text',
			),
		 )
		);
	ot_register_meta_box( $video_sitemap_meta );


	}
}


