          <div class="cactus-listing-heading fix-channel">
              <div class="navi-channel">
                  <div class="channel-name-wrap pull-left">
                  	  <div class="table-channel-heading">
						  <?php  if(has_post_thumbnail()){ ?>
                          <div class="channel-picture">
                              <?php
                                  the_post_thumbnail('thumb_72x72');
                             ?>
                          </div>
                          <?php };?>
                          <h3><a href="<?php the_permalink();?>" title="<?php the_title_attribute();?>"><?php the_title(); ?></a></h3>
                      </div>
                  </div>
                                                          
                  <div class="subs pull-right">                                            	
                      
                      
                      <?php 
                      if(function_exists('cactus_subcribe_button')){
                          cactus_subcribe_button();
                      }
                      ?>                                             
                      
                  </div>
                  
              </div>
          </div>
          <?php 
		  $subscreibed_item_page ='';
		  if(function_exists('op_get')){
		  	$subscreibed_item_page = op_get('ct_channel_settings','subscreibed_item_page');
		  }
		  if($subscreibed_item_page ==''){$subscreibed_item_page = 4;}
          $args = array(
              'post_type' => 'post',
              'post_status' => 'publish',
              'ignore_sticky_posts' => 1,
              'posts_per_page' => $subscreibed_item_page,
              'orderby' => 'latest',
              'meta_query' => array(
                  array(
                      'key' => 'channel_id',
                       'value' => get_the_ID(),
                       'compare' => 'LIKE',
                  ),
              )
          );
          ?>
          <div class="cactus-sub-wrap">
          <?php
          $list_query = new WP_Query( $args );
          $it = $list_query->post_count;
          if($list_query->have_posts()){
              while($list_query->have_posts()){$list_query->the_post(); 
			  $format = get_post_format(get_the_ID());?>
                  <!--item listing-->
                  <div class="cactus-post-item hentry">
                      
                      <!--content-->
                      <div class="entry-content">
                          <div class="primary-post-content"> <!--addClass: related-post, no-picture -->
                              
                              <?php  if(has_post_thumbnail()){ ?>                            
                              <!--picture-->
                              <div class="picture">                              	  
                                  <div class="picture-content">
                                      <a href="<?php the_permalink();?>" title="<?php echo esc_attr(get_the_title(get_the_ID()));?>">
                                          <?php the_post_thumbnail('thumb_520x293'); ?>
                                          <div class="thumb-overlay <?php if($format=='' || $format =='standard'){ ?> fa fa-search<?php }?>"></div>
                                      </a>                                        
                                      <?php echo tm_post_rating(get_the_ID());?>                                      
                                  </div>
                                  
                              </div><!--picture-->
                              <?php };?>
                              
                              <div class="content">
                                  
                                  <!--Title-->
                                  <h3 class="h4 cactus-post-title entry-title"> 
                                      <a href="<?php the_permalink();?>" title="<?php echo esc_attr(get_the_title(get_the_ID()));?>"><?php echo esc_attr(get_the_title(get_the_ID()));?></a> 
                                  </h3><!--Title-->
                                  
                                  <div class="cactus-last-child"></div> <!--fix pixel no remove-->
                              </div>
                          </div>
                          
                      </div><!--content-->
                      
                  </div><!--item listing-->
              <?php }
              wp_reset_postdata();
          }?>
          </div><!--End listing-->
