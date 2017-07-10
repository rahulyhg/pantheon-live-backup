    <div class="combo-change">
        <div class="listing-select">
            <ul>
                <li>
                    latest <i class="fa fa-sort-desc"></i>
                    <ul>
                        <li><a href="#" title="">popular</a></li>
                        <li><a href="#" title="">top viewer</a></li>
                        <li><a href="#" title="">top rating</a></li>
                    </ul>
                </li>
                
            </ul>
        </div>                                        
    </div>
    <?php 
	$paged = get_query_var('paged')?get_query_var('paged'):(get_query_var('page')?get_query_var('page'):1);
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
		'paged' => $paged,
        'orderby' => 'meta_value_num',
        'meta_query' => array(
            array(
                'key' => 'channel_id',
                 'value' => get_the_ID(),
                 'compare' => 'LIKE',
            ),
        )
    );
    $the_query = new WP_Query( $args );
	global $check_empty_it;	 
    $it = $the_query->post_count;
    if($the_query->have_posts()){?>
    
    <?php
	foreach($the_query->query as $key=>$value){
	   if(is_numeric($value)){
		   $js_query_vars .= '"'.$key.'":'.$value.',';
	   }
		elseif(is_array($value)) {
			$output = array();
			foreach($value as $json_arr)
			{
				$output[] = '"' . $json_arr . '"';
			}
			$js_query_vars .= '"'.$key.'":['.implode(',', $output).'],';
		}
		else
		   $js_query_vars .= '"'.$key.'":"'.$value.'",';

	}
	?>

	<script type="text/javascript">
	 var cactus = {"ajaxurl":"<?php echo admin_url( 'admin-ajax.php' );?>","query_vars":{<?php echo $js_query_vars; ?>},"current_url":"<?php echo home_url($wp->request);?>" }
	</script>
    
    <div class="cactus-sub-wrap">
    	<?php while($the_query->have_posts()){ $the_query->the_post(); ?>
        <!--item listing-->
        <div class="cactus-post-item hentry">
            
            <!--content-->
            <div class="entry-content">
                <div class="primary-post-content"> <!--addClass: related-post, no-picture -->
                                                
                    <!--picture-->
                    <div class="picture">
                        <div class="picture-content">
                            <a href="#" title="">
                                <?php the_post_thumbnail( 'thumb_314x251' ); ?>
                                <div class="thumb-overlay"></div>
                                <i class="fa fa-play-circle-o cactus-icon-fix"></i>
                            </a>                                          
                            <div class="cactus-note-point">10.0</div>
                            <div class="cactus-note-time">3:21</div>
                        </div>
                        
                    </div><!--picture-->
                    
                    <div class="content">
                        
                        <!--Title-->
                        <h3 class="h4 cactus-post-title entry-title"> 
                            <a href="<?php the_permalink();?>" title="<?php echo esc_attr(get_the_title(get_the_ID()));?>"><?php echo esc_attr(get_the_title(get_the_ID()));?></a> 
                        </h3><!--Title-->
                        
                        <!--info-->
                        <div class="posted-on">
                            <a href="#" class="entry-date cactus-info"><?php echo date_i18n(get_option('date_format') ,get_the_time('U'));?></a>
                            <a href="#" class="author cactus-info"><?php the_author_posts_link(); ?></a>
                            <a href="<?php comments_link(); ?>" class="comment cactus-info"><?php echo get_comments_number(get_the_ID());?></a>
                            <?php
							if(is_plugin_active('baw-post-views-count/bawpv.php')){ 
								if(function_exists('GetWtiLikeCount')){$like = GetWtiLikeCount(get_the_ID());}
								if(function_exists('GetWtiUnlikeCount')){$unlike = GetWtiUnlikeCount(get_the_ID());}
								?>
								<div class="like cactus-info"><?php echo $like ?></div>
								<div class="dislike cactus-info"><?php echo $unlike ?></div>
								<?php
							}?>
                            <?php if(is_plugin_active('baw-post-views-count/bawpv.php')){ ?>
                            <div class="view cactus-info"><?php echo  tm_short_number(get_post_meta(get_the_ID(),'_count-views_all',true)) ?></div>
                            <?php }?>
                        </div><!--info-->
                        
                        <div class="cactus-last-child"></div> <!--fix pixel no remove-->
                    </div>
                </div>
                
            </div><!--content-->
            
        </div><!--item listing-->
        <?php }?>
    </div>
    <?php }
	wp_reset_postdata();
	?>
	<div class="page-navigation"><?php cactus_paging_nav('.cactus-sub-wrap','html/loop/content'); ?></div>