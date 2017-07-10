        <div id="slider">
        <?php
		$header_style = ot_get_option('header_home_style','carousel');
		if(is_page_template('page-templates/front-page.php')){
			$header_style = get_post_meta(get_the_ID(),'header_style',true)?get_post_meta(get_the_ID(),'header_style',true):$header_style;
		}
		$condition = get_post_meta(get_the_ID(),'header_home_condition',true)?get_post_meta(get_the_ID(),'header_home_condition',true):ot_get_option('header_home_condition','lastest');
		$ids = get_post_meta(get_the_ID(),'header_home_postids',true)?get_post_meta(get_the_ID(),'header_home_postids',true):ot_get_option('header_home_postids','');
		$categories = get_post_meta(get_the_ID(),'header_home_cat',true)?get_post_meta(get_the_ID(),'header_home_cat',true):ot_get_option('header_home_cat','');
		$tags = get_post_meta(get_the_ID(),'header_home_tag',true)?get_post_meta(get_the_ID(),'header_home_tag',true):ot_get_option('header_home_tag','');
		$sort_by = get_post_meta(get_the_ID(),'header_home_order',true)?get_post_meta(get_the_ID(),'header_home_order',true):ot_get_option('header_home_order','DESC');
		$count = get_post_meta(get_the_ID(),'header_home_number',true)?get_post_meta(get_the_ID(),'header_home_number',true):ot_get_option('header_home_number',12);
		$themes_pur='';
		if(function_exists('ot_get_option')){ $themes_pur= ot_get_option('theme_purpose');}
		if($header_style!='sidebar'){
			$content_helper = new CT_ContentHelper;	
			$postformats = $timerange = $paged = '';
			if($condition =='most_viewed' || $condition =='most_comments' || $condition =='likes'){
				$timerange = get_post_meta(get_the_ID(),'header_time_range',true)?get_post_meta(get_the_ID(),'header_time_range',true):ot_get_option('header_time_range','');
			}
			global $header_query;
			$header_query = $content_helper->tm_get_popular_posts($condition, $tags, $count, $ids,$sort_by, $categories, $args = array(),$themes_pur,$postformats,$timerange,$paged);
		}
		if($header_style=='carousel'){
			get_template_part( 'header', 'home-carousel' );
		}elseif($header_style=='classy'){
			get_template_part( 'header', 'home-classy' );
		}elseif($header_style=='classy2' || $header_style=='classy3'){
			get_template_part( 'header', 'home-classy-horizon' );
		}elseif($header_style=='metro'){
			get_template_part( 'header', 'home-metro' );
		}elseif($header_style=='amazing'){
			get_template_part( 'header', 'home-amazing' );
		}elseif($header_style=='video_slider'){
			get_template_part( 'header', 'home-videoslider' );
		}else{ 
			if ( is_active_sidebar( 'maintop_sidebar' ) ) :
			$maintop_layout = ot_get_option('maintop_layout','full');	
			if($maintop_layout=='boxed'){ ?>
            <div class="container">
            <?php } ?>
                <?php dynamic_sidebar( 'maintop_sidebar' ); ?>
            <?php if($maintop_layout=='boxed'){ ?>
            </div><!--/container-->
            <?php } ?>
            <?php 
			
			$header_bg = get_post_meta(get_the_ID(),'background', true);
			if(!$header_bg) {
				$header_bg = ot_get_option('header_home_bg'); 
			}
			if($header_bg){
			?>
			<style type="text/css" scoped="scoped">
            #slider{
            <?php if($header_bg['background-color']){ echo 'background-color:'.$header_bg['background-color'].';';} ?>
            <?php if($header_bg['background-attachment']){ echo 'background-attachment:'.$header_bg['background-attachment'].';';} ?>
            <?php if($header_bg['background-repeat']){
                echo 'background-repeat:'.$header_bg['background-repeat'].';';
                echo 'background-size: initial;';
            } ?>
            <?php if($header_bg['background-size']){ echo 'background-size:'.$header_bg['background-size'].';';} ?>
            <?php if($header_bg['background-position']){ echo 'background-position:'.$header_bg['background-position'].';';} ?>
            <?php if($header_bg['background-image']){ echo 'background-image:url('.$header_bg['background-image'].');';} ?>
            }
			<?php if($header_bg['background-attachment']=='fixed'){ ?>
			/*@media(min-width:768px){
				#body-wrap{
					position:fixed;
					top:0;
					bottom:0;
					left:0;
					right:0;
				}
				.admin-bar #body-wrap{
					top:32px;
				}
			}
			@media(min-width:768px) and (max-width:782px){
				.admin-bar #body-wrap{
					top:46px;
				}
				.admin-bar #off-canvas{
					top:46px;
				}
			}
			.bg-ad {
				right: 14px;
			}*/
			<?php if(ot_get_option('theme_layout')!=1){ ?>
				/*#body-wrap{
					position:fixed;
					top:0;
					bottom:0;
					left:0;
					right:0;
				}
				.admin-bar #body-wrap{
					top:32px;
				}
				@media(max-width:782px){
					.admin-bar #body-wrap{
						top:46px;
					}
					.admin-bar #off-canvas{
						top:46px;
					}
				}*/
			<?php } 
			}?>
            </style>
            <?php 
			}
			endif;
		} //else $header_style ?>
        </div><!--/slider-->