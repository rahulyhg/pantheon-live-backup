<!--item listing-->
<div class="cactus-post-item hentry">
    
    <!--content-->
    <div class="entry-content">
        <div class="primary-post-content"> <!--addClass: related-post, no-picture -->
            <?php 
            global $id_cr_pos;
            if($id_cr_pos==''){
                if(isset($_POST['id_playlist'])){
                    $id_cr_pos = $_POST['id_playlist'];
                }else{ $id_cr_pos=''; }
            }?> 
              
            <?php if(has_post_thumbnail()): ?>                                             
            <!--picture-->
            <div class="picture">
                <div class="picture-content">
                    <a href="<?php echo add_query_arg( array('list' => $id_cr_pos), get_the_permalink() );?>" title="<?php echo esc_attr(get_the_title(get_the_ID()));?>">
                        <?php the_post_thumbnail('thumb_520x293'); ?>
                        <div class="thumb-overlay"></div>                                
                    </a>                                        
                    <?php echo tm_post_rating(get_the_ID());?>                            
                </div>
                
            </div><!--picture-->
            <?php endif ?>
            
            <div class="content">
                
                <!--Title-->
                <h3 class="h4 cactus-post-title entry-title"> 
                    <a href="<?php echo add_query_arg( array('list' => $id_cr_pos), get_the_permalink() );?>" title="<?php echo esc_attr(get_the_title(get_the_ID()));?>"><?php echo esc_attr(get_the_title(get_the_ID()));?></a> 
                </h3><!--Title-->
                
                <div class="cactus-last-child"></div> <!--fix pixel no remove-->
            </div>
        </div>
        
    </div><!--content-->
    
</div><!--item listing-->
