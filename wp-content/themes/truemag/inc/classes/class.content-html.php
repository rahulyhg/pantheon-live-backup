<?php
if(!class_exists('CT_ContentHtml')){
	class CT_ContentHtml{
		/* 
		 * Get item for trending, popular
		 *
		 */
		function get_item_video_trending($conditions,$themes_pur,$show_likes,$show_com,$show_rate,$show_view,$show_excerpt,$excerpt){	
		  $html='';
			$html .= '
			<div class="col-md-12 col-sm-4">
			  <div class="video-item">
				  <div class="videos-row">
						  <div class="item-thumbnail">
							<a href="'.get_permalink().'" title="'.esc_attr(get_the_title()).'">';
							if(has_post_thumbnail()){
										$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(),'thumb_139x89', true);
									}else{
										$thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
										$thumbnail[1]=520;
										$thumbnail[2]=293;
									}
							$html .= '<img src="'.$thumbnail[0].'" width="'.$thumbnail[1].'" height="'.$thumbnail[2].'" alt="'.the_title_attribute('echo=0').'" title="'.the_title_attribute('echo=0').'">';
							//'.get_the_post_thumbnail(get_the_ID(), array(139,89) ).'
							if($themes_pur!='0' && get_post_format( get_the_ID() ) != ''){	
						$html .= '<div class="link-overlay fa fa-play "></div>';}
						$html .= '</a>';
						if($show_rate!='hide_r'){
						$html .= tm_post_rating(get_the_ID());
						}
						$html .= '</div>
					  <div class="item-info">
						<div class="all-info">
						<h2 class="rt-article-title"> <a href="'.get_permalink().'" title="'.esc_attr(get_the_title()).'">'.get_the_title().'</a></h2>
						<div class="item-meta">';
					  if($show_view!='hide_v'){
						$html.= tm_html_video_meta('view').'<br />';}
					  if($show_com!='hide_c'){
						$html.= tm_html_video_meta('comment').'<br />';}
					  if($show_likes!='hide_l'){
						$html.= tm_html_video_meta('like').'<br />';
					  }
					  $html.= '
						</div>
						</div>
						</div>';
					  if($show_excerpt!='hide_ex'){
						$html.= '<div class="pp-exceprt">'.$excerpt.'</div>';
					  }
					$html.= '
				   </div>
			  </div>
			</div>
			';
		return $html;
		}

		/* 
		 * Get item for grid layout
		 *
		 */
		function get_item_video($thumb, $show_title, $show_rate, $show_dur, $themes_pur, $quick_view='def'){	
			global $_device_;
			global $_is_retina_;
			if($_device_== 'mobile' && !$_is_retina_){
				$thumb=($thumb=='thumb_520x293')?'thumb_260x146':(($thumb=='thumb_260x146')?'thumb_130x73':$thumb);
			}elseif(!$_is_retina_){
				$thumb=($thumb=='thumb_520x293')?'thumb_370x208':(($thumb=='thumb_260x146')?'thumb_180x101':$thumb);
			}
		  $html='';
		  $quick_if = $quick_view=='def'?ot_get_option('quick_view_info'):$quick_view;
		  $format = get_post_format(get_the_ID()); 
		  if($quick_if=='1'){
		  $html .= '
				<div class="qv_tooltip"  title="
					<h4 class=\'gv-title\'>'.esc_attr(get_the_title()).'</h4>
					<div class=\'gv-ex\' >'.esc_attr(get_the_excerpt()).'</div>
					<div class= \'gv-button\'>';
						if($format=='video'){
							$html .= '<div class=\'quick-view\'><a href='.get_permalink().' title=\''.esc_attr(get_the_title()).'\'>'.__('Watch Now','cactusthemes').'</a></div>';
						}else{
							$html .= '<div class=\'quick-view\'><a href='.get_permalink().' title=\''.esc_attr(get_the_title()).'\'>'.__('Read more','cactusthemes').'</a></div>';
						}
						$html .='
						<div class= \'gv-link\'>'.quick_view_tm().'</div>
					</div>
					</div>
				">';}
			$html .= '	
				<div class="video-item">
					<div class="item-thumbnail">
						 <a href="'.get_permalink().'" title="'.esc_attr(get_the_title()).'">';
							if(has_post_thumbnail()){
										$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(),$thumb , true);
									}else{
										$thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
										$thumbnail[1]=520;
										$thumbnail[2]=293;
									}
							$html .= '<img src="'.$thumbnail[0].'" width="'.$thumbnail[1].'" height="'.$thumbnail[2].'" alt="'.the_title_attribute('echo=0').'" title="'.the_title_attribute('echo=0').'">';
						if($themes_pur!='0'){	
							if($format=='' || $format =='standard' || $format =='gallery'){
								$html .= '<div class="link-overlay fa fa-search"></div>';
							}else {
								$html .= '<div class="link-overlay fa fa-play "></div>';
							}
						}
							
						$html .= '</a>';
						if($show_rate!='0'){
						$html .= tm_post_rating(get_the_ID());
						}
						if($show_dur!='0'){
						if(get_post_meta(get_the_id(),'time_video',true)!='00:00' && get_post_meta(get_the_id(),'time_video',true)!='00' && get_post_meta(get_the_id(),'time_video',true)!='' ){
						$html .= '
							<span class="rating-bar bgcolor2 time_dur">'.get_post_meta(get_the_id(),'time_video',true).'</span>';
							}
						}
						if($show_title!='0'){
						$html .= '
						<div class="item-head">
							<h3><a href="'.get_permalink(get_the_ID()).'">'.strip_tags(get_the_title(get_the_ID())).'</a></h3>
						</div>';}
				$html .= '</div>
				</div>';
				if($quick_if=='1'){
					$html .='</div>';
				}
		return $html;
		}
		/* 
		 * Get item for medium layout
		 *
		 */
		function get_item_medium_video($thumb,$show_title, $show_exceprt, $show_rate,$show_dur,$show_view,$show_com,$show_like, $show_aut,$show_date, $themes_pur,$number_excerpt, $quick_view='def'){
			global $_device_;
			global $_is_retina_;
			if($_device_== 'mobile' && !$_is_retina_){
				$thumb=($thumb=='thumb_520x293')?'thumb_260x146':(($thumb=='thumb_260x146')?'thumb_130x73':$thumb);
			}elseif(!$_is_retina_){
				$thumb=($thumb=='thumb_520x293')?'thumb_370x208':$thumb;
			}
			$thumb=($thumb=='thumb_520x293_e')?'thumb_520x293':$thumb;
		  $format = get_post_format(get_the_ID());
		  $html='';
		  $quick_if = $quick_view=='def'?ot_get_option('quick_view_info'):$quick_view;
			$html .= '	
			<div class="video-item">';
			  if($quick_if=='1'){
			  $html .= '
					<div class="qv_tooltip"  title="
						<h4 class=\'gv-title\'>'.esc_attr(get_the_title()).'</h4>
						<div class=\'gv-ex\' >'.esc_attr(get_the_excerpt()).'</div>
						<div class= \'gv-button\'>';
							if($format=='video'){
								$html .= '<div class=\'quick-view\'><a href='.get_permalink().' title=\''.esc_attr(get_the_title()).'\'>'.__('Watch Now','cactusthemes').'</a></div>';
							}else{
								$html .= '<div class=\'quick-view\'><a href='.get_permalink().' title=\''.esc_attr(get_the_title()).'\'>'.__('Read more','cactusthemes').'</a></div>';
							}
							$html .='
							<div class= \'gv-link\'>'.quick_view_tm().'</div>
						</div>
						</div>
					">';}	
				$html .= '
				  <div class="item-thumbnail">
					  <a href="'.get_permalink().'" title="'.esc_attr(get_the_title()).'">';
							if(has_post_thumbnail()){
										$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(),$thumb , true);
									}else{
										$thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
										$thumbnail[1]=520;
										$thumbnail[2]=293;
									}
							$html .= '<img src="'.$thumbnail[0].'" width="'.$thumbnail[1].'" height="'.$thumbnail[2].'" alt="'.the_title_attribute('echo=0').'" title="'.the_title_attribute('echo=0').'">';
						if($themes_pur!='0'){	
							if($format=='' || $format =='standard' || $format =='gallery'){
								$html .= '<div class="link-overlay fa fa-search"></div>';
							}else {
								$html .= '<div class="link-overlay fa fa-play "></div>';
							}					
						}
						$html .= '</a>';
					  if($show_rate!='0'){
						$html .= tm_post_rating(get_the_ID());
					  }
					  if($show_dur!='0'){
						if(get_post_meta(get_the_id(),'time_video',true)!='00:00' && get_post_meta(get_the_id(),'time_video',true)!='00' && get_post_meta(get_the_id(),'time_video',true)!='' ){
								$html .= '
								<span class="rating-bar bgcolor2 time_dur">'.get_post_meta(get_the_id(),'time_video',true).'</span>';
							}

						}
				  $html .= '
				  </div>';
					if($quick_if=='1'){
						$html .='</div>';
					}			  
				  $html .= '
				  <div class="item-head">';
				  if($show_title!='0'){
				  $html .= '
					  <h3><a href="'.get_permalink(get_the_ID()).'">'.strip_tags(get_the_title(get_the_ID())).'</a></h3>';}
					  
					  $html .= '
					  <div class="item-info">';
						if($show_aut!='0'){
							$author = get_author_posts_url( get_the_author_meta( 'ID' ) );
						  $html .= '<span  class="item-author"><a href="'.$author.'" title="'.get_the_author().'">'.get_the_author().'</a></span>';}
						if($show_date!='0'){  
						  $html .= '<span class="item-date">'.get_the_time(get_option('date_format')).'</span>';}
						  $html .= '<div class="item-meta no-bg">';
						  if($show_view!='0' ){
						  $html .= tm_html_video_meta('view',false,false);}
						  if($show_com!='0' ){
						  $html .= tm_html_video_meta('comment',false,false);}
						  if($show_like!='0'){
						  $html .= tm_html_video_meta('like',false,false);}
						  $html .= '</div>
					  </div>';
				  $html .= '	  
				  </div>';
				  if($show_exceprt!='0'){
				  $html .= '
				  <div class="item-content">'.wp_trim_words(get_the_excerpt(),$number_excerpt,$more = '').'</div>';}
				  $html .='
				  </div>';
		return $html;
		}
		/* 
		 * Get item for small carousel
		 *
		 */
		function get_item_small_video($thumb,$show_title, $show_rate,$show_dur,$themes_pur,$quick_view='def'){	
			global $_device_;
			global $_is_retina_;
			if($_device_== 'mobile' && !$_is_retina_){
				$thumb=($thumb=='thumb_260x146_')?'thumb_260x146':$thumb; //mega menu
				$thumb=($thumb=='thumb_520x293')?'thumb_260x146':(($thumb=='thumb_260x146')?'thumb_130x73':$thumb);
			}elseif(!$_is_retina_){
				$thumb=($thumb=='thumb_260x146')?'thumb_180x101':$thumb;
				$thumb=($thumb=='thumb_260x146_')?'thumb_260x146':$thumb; //mega menu
			}
		  $html='';
		  $quick_if = $quick_view=='def'?ot_get_option('quick_view_info'):$quick_view;
			$html .= '	
			<div class="video-item">';
			$format = get_post_format(get_the_ID());
			  if($quick_if=='1'){
			  $html .= '
					<div class="qv_tooltip"  title="
						<h4 class=\'gv-title\'>'.esc_attr(get_the_title()).'</h4>
						<div class=\'gv-ex\' >'.esc_attr(get_the_excerpt()).'</div>
						<div class= \'gv-button\'>';
							if($format=='video'){
								$html .= '<div class=\'quick-view\'><a href='.get_permalink().' title=\''.esc_attr(get_the_title()).'\'>'.__('Watch Now','cactusthemes').'</a></div>';
							}else{
								$html .= '<div class=\'quick-view\'><a href='.get_permalink().' title=\''.esc_attr(get_the_title()).'\'>'.__('Read more','cactusthemes').'</a></div>';
							}
							$html .='
							<div class= \'gv-link\'>'.quick_view_tm().'</div>
						</div>
						</div>
					">';}
				$html .= '
				  <div class="item-thumbnail">
					  <a href="'.get_permalink().'" title="'.esc_attr(get_the_title()).'">';
						if(has_post_thumbnail()){
							$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(),$thumb , true);
						}else{
							$thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
							$thumbnail[1]=520;
							$thumbnail[2]=293;
						}
						$html .= '<img src="'.$thumbnail[0].'" width="'.$thumbnail[1].'" height="'.$thumbnail[2].'" alt="'.the_title_attribute('echo=0').'" title="'.the_title_attribute('echo=0').'">';
						if($themes_pur!='0'){	
							if($format=='' || $format =='standard' || $format =='gallery'){
								$html .= '<div class="link-overlay fa fa-search"></div>';
							}else {
								$html .= '<div class="link-overlay fa fa-play "></div>';
							}					
						}
						$html .= '</a>';
					  if($show_rate!='0'){
						$html .= tm_post_rating(get_the_ID());
					  }
					  if($show_dur!='0'){
						if(get_post_meta(get_the_id(),'time_video',true)!='00:00' && get_post_meta(get_the_id(),'time_video',true)!='00' && get_post_meta(get_the_id(),'time_video',true)!='' ){
							$html .= '<span class="rating-bar bgcolor2 time_dur">'.get_post_meta(get_the_id(),'time_video',true).'</span>';
						}
					  }
				  $html .= '
				  </div>';
					if($quick_if=='1'){
						$html .='</div>';
					}
				  $html .= '
				  <div class="item-head">';
				  if($show_title!='0'){
				  $html .= '
					  <h3><a href="'.get_permalink(get_the_ID()).'">'.strip_tags(get_the_title(get_the_ID())).'</a></h3>';
				  }
				  $html .= '</div>
				  </div>';
		return $html;
		}
		
		/* 
		 * Get item for single layout
		 *
		 */
		function get_item_single_video($thumb,$show_title, $show_exceprt, $show_rate,$show_dur,$show_view,$show_com,$show_like, $show_aut,$show_date,$themes_pur,$number_excerpt,$quick_view='def'){	
			global $_device_;
			global $_is_retina_;
			if($_device_== 'mobile' && !$_is_retina_){
				$thumb=($thumb=='thumb_520x293')?'thumb_260x146':(($thumb=='thumb_260x146')?'thumb_130x73':$thumb);
			}elseif(!$_is_retina_){
				$thumb=($thumb=='thumb_520x293')?'thumb_370x208':$thumb;
			}
		  $html='';
		  $quick_if = $quick_view=='def'?ot_get_option('quick_view_info'):$quick_view;
			$html .= '	
			<div class="video-item">
			   <div class="col-md-6 col-sm-6">';
			   $format = get_post_format(get_the_ID());
			  if($quick_if=='1'){
			  $html .= '
					<div class="qv_tooltip"  title="
						<h4 class=\'gv-title\'>'.esc_attr(get_the_title()).'</h4>
						<div class=\'gv-ex\' >'.esc_attr(get_the_excerpt()).'</div>
						<div class= \'gv-button\'>';
							if($format=='video'){
								$html .= '<div class=\'quick-view\'><a href='.get_permalink().' title=\''.esc_attr(get_the_title()).'\'>'.__('Watch Now','cactusthemes').'</a></div>';
							}else{
								$html .= '<div class=\'quick-view\'><a href='.get_permalink().' title=\''.esc_attr(get_the_title()).'\'>'.__('Read more','cactusthemes').'</a></div>';
							}
							$html .='
							<div class= \'gv-link\'>'.quick_view_tm().'</div>
						</div>
						</div>
					">';}
					$html .= '	
					<div class="item-thumbnail">
						<a href="'.get_permalink().'" title="'.esc_attr(get_the_title()).'">';
							if(has_post_thumbnail()){
										$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(),$thumb , true);
									}else{
										$thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
										$thumbnail[1]=520;
										$thumbnail[2]=293;
									}
							$html .= '<img src="'.$thumbnail[0].'" width="'.$thumbnail[1].'" height="'.$thumbnail[2].'" alt="'.the_title_attribute('echo=0').'" title="'.the_title_attribute('echo=0').'">';
						if($themes_pur!='0'){	
							if($format=='' || $format =='standard' || $format =='gallery'){
								$html .= '<div class="link-overlay fa fa-search"></div>';
							}else {
								$html .= '<div class="link-overlay fa fa-play "></div>';
							}					
						}
						$html .= '</a>';
						if($show_rate!='0'){
						$html .= tm_post_rating(get_the_ID());
						}
						if($show_dur!='0'){
						if(get_post_meta(get_the_id(),'time_video',true)!='00:00' && get_post_meta(get_the_id(),'time_video',true)!='00' && get_post_meta(get_the_id(),'time_video',true)!='' ){
						$html .= '
							<span class="rating-bar bgcolor2 time_dur">'.get_post_meta(get_the_id(),'time_video',true).'</span>';
							}
						}
						$html .= '
					</div>';
				if($quick_if=='1'){
					$html .='</div>';
				}				
				$html .= '	
				</div>
				<div class="col-md-6 col-sm-6">
					<div class="item-head">';
					if($show_title!='0'){
						$html .= '
						<h3><a href="'.get_permalink(get_the_ID()).'">'.strip_tags(get_the_title(get_the_ID())).'</a></h3>';}
						$html .= '
						<div class="item-info">';
						if($show_aut!='0'){
						  $author = get_author_posts_url( get_the_author_meta( 'ID' ) );
						  $html .= '<span  class="item-author"><a href="'.$author.'" title="'.get_the_author().'">'.get_the_author().'</a></span>';}
						if($show_date!='0'){  
						  $html .= '<span class="item-date">'.get_the_time(get_option('date_format')).'</span>';}
						  $html .= '<div class="item-meta no-bg">';
						  if($show_view!='0' ){
						  $html .= tm_html_video_meta('view',false,false);}
						  if($show_com!='0' ){
						  $html .= tm_html_video_meta('comment',false,false);}
						  if($show_like!='0' ){
						  $html .= tm_html_video_meta('like',false,false);}
						  $html .= '</div>
						</div>';
					$html .= '	
					</div>';
					if($show_exceprt!='0'){
				  $html .= '
				<div class="item-content">'.wp_trim_words(get_the_excerpt(),$number_excerpt,$more = '').'</div>';}
				$html .= '
			   </div>
			   </div>';
		return $html;
		}
		/* 
		 * Get item for style smart content box
		 *
		 */
		function get_item_smart_video($thumb){	
				$html='';
				$html .= '
					  <div class="video-item">
						  <div class="item-thumbnail">
							  <a href="'.get_permalink().'" title="'.esc_attr(get_the_title()).'">';
							if(has_post_thumbnail()){
										$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(),$thumb , true);
									}else{
										$thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
										$thumbnail[1]=520;
										$thumbnail[2]=293;
									}
							$html .= '<img src="'.$thumbnail[0].'" width="'.$thumbnail[1].'" height="'.$thumbnail[2].'" alt="'.the_title_attribute('echo=0').'" title="'.the_title_attribute('echo=0').'">';
						$html .= '<div class="link-overlay fa fa-play "></div>
							  </a>
							  '.tm_post_rating(get_the_ID()).'
						 </div>
						 <div class="item-head">
							<h3><a href="'.get_permalink(get_the_ID()).'">'.strip_tags(get_the_title(get_the_ID())).'</a></h3>
						 </div> 
					</div>			
				';
		return $html;
		}
		/* 
		 * Get all item
		 *
		 */
		function get_all_item_video($item,$num_item,$column,$layout, $show_title, $show_exceprt, $show_rate,$show_dur,$show_view,$show_com,$show_like, $show_aut,$show_date, $themes_pur,$num_r,$number,$row,$class_cssit,$number_excerpt,$quick_view='def'){
			$html='';	
			if($layout=='grid'){
				if($column==4){
					if($item==1 ||($item%5==1) ){$html .= '
						<div class="col-md-6 col-sm-12"> '.$this->get_item_video($thumb='thumb_520x293', $show_title, $show_rate,$show_dur,$themes_pur,$quick_view).' </div>';
					}else {$html .= '
						<div class="col-md-3 col-sm-6 col-xs-6"> '.$this->get_item_video($thumb='thumb_260x146', $show_title, $show_rate,$show_dur,$themes_pur,$quick_view).' </div>';
					}
					if($item%5==0 && $item<$num_item){
						$html .= '</div></div>
						<div class="smart-item '.$class_cssit.'"> <div class="row">';
					}
					if($item==$num_item ) {
						$html .= '</div></div>';
					}
				}else if($column==2){
					$html .= '<div class="col-md-6 col-sm-12">'.$this->get_item_video($thumb='thumb_520x293', $show_title, $show_rate,$show_dur,$themes_pur,$quick_view).' </div>';
					if($item%1==0 && $item< $num_item){
						$html .= '</div></div>
						<div class="smart-item '.$class_cssit.'"> <div class="row">';
					}
					if($item==$num_item) {
						$html .= '</div></div>';
					}
				}else if($column==6){
					if($item==1 ||($item%9==1) ){$html .= '
						<div class="col-md-4 col-sm-12"> '.$this->get_item_video($thumb='thumb_520x293', $show_title, $show_rate,$show_dur,$themes_pur,$quick_view).' </div>';
					}else {$html .= '
						<div class="col-md-2 col-sm-6 col-xs-6"> '.$this->get_item_video($thumb='thumb_260x146', $show_title, $show_rate,$show_dur ,$themes_pur,$quick_view).' </div>';
					}
					if($item%9==0 && $item < $num_item){
						$html .= '</div></div>
						<div class="smart-item '.$class_cssit.'"> <div class="row">';
					}
					if($item==$num_item ) {
						$html .= '</div></div>';
					}
				}
			}else if($layout=='small_carousel')
			{
				if($column==4){
					$html .= '
					<div class="col-md-3 col-sm-6 col-xs-6"> '.$this->get_item_small_video($thumb='thumb_260x146', $show_title, $show_rate, $show_dur,$themes_pur,$quick_view).' </div>';
					if($row > 1){
						if($item%4==0 && $item < $num_item && $item%$num_r!=0){
							$html .= '</div><div class="row">';
						}else
						if(($item%$num_r==0) && ($item < $num_item)){
							$html .= '</div></div> <div class="smart-item '.$class_cssit.'"> <div class="row">';
						}else
						if($item==$num_item) {$html .= '</div></div>';}
					}else{
						if($item%4==0 && $item < $num_item){
							$html .= '</div></div>
							<div class="smart-item '.$class_cssit.'"> <div class="row">';
						}
						if($item==$num_item ) {
							$html .= '</div></div>';
						}
					}
				}else if($column==2){
					$html .= '<div class="col-md-3 col-sm-6 col-xs-6">'.$this->get_item_small_video($thumb='thumb_260x146', $show_title, $show_rate,$show_dur,$themes_pur,$quick_view).' </div>';
					if($row > 1){
						if($item%2==0 && $item < $num_item && $item%$num_r!=0){
							$html .= '</div><div class="row">';
						}else
						if($item==$num_item) {$html .= '</div></div>';}
						else
						if(($item%$num_r==0) && ($item < $num_item)){
							$html .= '</div></div>
							<div class="smart-item '.$class_cssit.'"> <div class="row">';
						}
					}else{
						if($item%2==0 && $item < $num_item){
							$html .= '</div></div>
							<div class="smart-item '.$class_cssit.'"> <div class="row">';
						}
						if($item==$num_item ) {
							$html .= '</div></div>';
						}
					}
				}else if($column==6){
					$html .= '
						<div class="col-md-2 col-sm-6 col-xs-6"> '.$this->get_item_small_video($thumb='thumb_260x146',$show_title, $show_rate,$show_dur,$themes_pur,$quick_view).' </div>';
					if($row > 1){
						if($item%6==0 && $item < $num_item && $item%$num_r!=0){
							$html .= '</div><div class="row">';
						}else
						if($item==$num_item) {$html .= '</div></div>';}
						else
						if(($item%$num_r==0) && ($item < $num_item)){
							$html .= '</div></div>
							<div class="smart-item '.$class_cssit.'"> <div class="row">';
						}
					}else {	
						if($item%6==0 && $item < $num_item){
							$html .= '</div></div>
							<div class="smart-item '.$class_cssit.'"> <div class="row">';
						}
						if($item==$num_item ) {
							$html .= '</div></div>';
						}
					}
				}
				
			}else if($layout=='medium_carousel')
			{
				if($column==2){
					$html .= '
					<div class="col-md-6 col-sm-6">'.$this->get_item_medium_video($thumb='thumb_520x293', $show_title, $show_exceprt, $show_rate,$show_dur,$show_view,$show_com,$show_like, $show_aut,$show_date,$themes_pur,$number_excerpt,$quick_view).' </div>';
					if($row > 1){
						if($item < $num_item && $item%$num_r!=0){
							$html .= '</div><div class="row">';
						}else
						if(($item%$num_r==0) && ($item < $num_item)){
							$html .= '</div></div>
							<div class="smart-item '.$class_cssit.'"><div class="row">';
						}
						if($item==$num_item) {$html .= '</div></div>';}
					}else {	
						if($item%1==0 && $item < $num_item){
							$html .= '</div></div>
							<div class="smart-item '.$class_cssit.'"> <div class="row">';
						}
						if($item==$num_item ) {
							$html .= '</div></div>';
						}
					}
				}else if($column==4){
					$html .= '<div class="col-md-6 col-sm-6 '.($layout=='medium_carousel_2'?'col-xs-6 ':'').'">'.$this->get_item_medium_video($thumb='thumb_520x293', $show_title, $show_exceprt, $show_rate,$show_dur,$show_view,$show_com,$show_like, $show_aut,$show_date, $themes_pur,$number_excerpt,$quick_view).' </div>';
					if($row > 1){
						if($item%2==0 && $item < $num_item && $item%$num_r!=0){
							$html .= '</div><div class="row">';
						}else
						if(($item%$num_r==0) && ($item < $num_item)){
							$html .= '</div></div>
							<div class="smart-item '.$class_cssit.'"><div class="row">';
						}
						if($item==$num_item) {$html .= '</div></div>';}
					}else {
						if($item%2==0 && $item < $num_item){
							$html .= '</div></div>
							<div class="smart-item '.$class_cssit.'"> <div class="row">';
						}
						if($item==$num_item ){$html .= '</div></div>';}
					}
				}else if($column==6){
					$html .= '
						<div class="col-md-4 '.($layout=='medium_carousel_2'?'col-sm-4 col-xs-4 ':'col-sm-6 ').'">'.$this->get_item_medium_video($thumb='thumb_520x293', $show_title, $show_exceprt, $show_rate,$show_dur,$show_view,$show_com,$show_like, $show_aut,$show_date, $themes_pur,$number_excerpt,$quick_view).' </div>';
					if($row > 1){
						if($item%3==0 && $item < $num_item && $item%$num_r!=0){
							$html .= '</div><div class="row">';
						}else
						if(($item%$num_r==0) && ($item < $num_item)){
							$html .= '</div></div>
							<div class="smart-item '.$class_cssit.'"><div class="row">';
						}
						if($item==$num_item) {$html .= '</div></div>';}
					}else {	
						if($item%3==0 && $item<$num_item){
							$html .= '</div></div>
							<div class="smart-item '.$class_cssit.'"> <div class="row">';
						}
						if($item==$num_item ) {$html .= '</div></div>';}
					}
				}
			}else if($layout=='medium_carousel_2')
			{
				$show_exceprt = 0;
				$show_view = 0;
				$show_com = 0;
				$show_like = 0;
				$show_aut = 0;
				$show_date = 0;
				$row = 0;
					$html .= '<div class="col-md-4 '.($layout=='medium_carousel_2'?'col-sm-4 col-xs-4 ':'col-sm-6 ').'">'.$this->get_item_medium_video($thumb='thumb_520x293_e', $show_title, $show_exceprt, $show_rate,$show_dur,$show_view,$show_com,$show_like, $show_aut,$show_date, $themes_pur,$number_excerpt,$quick_view).' </div>';
			}else if($layout=='single')
			{
					$html .= $this->get_item_single_video($thumb='thumb_520x293', $show_title, $show_exceprt, $show_rate,$show_dur,$show_view,$show_com,$show_like, $show_aut,$show_date, $themes_pur,$number_excerpt,$quick_view);
					if($row > 1){
						if($item < $num_item && $item%$row!=0){
							$html .= '</div><div class="row">';
						}else
						if(($item%$row==0) && ($item < $num_item)){
							$html .= '</div></div>
							<div class="smart-item '.$class_cssit.'"><div class="row">';
						}
						if($item==$num_item) {$html .= '</div></div>';}
					}else {
						if($item%1==0 && $item< $num_item){
							$html .= '</div></div>
							<div class="smart-item '.$class_cssit.'"> <div class="row">';
						}
						if($item==$num_item) {
							$html .= '</div></div>';
						}	
					}
			}
			return $html;
		}
		/*Only likes html*/
		function tm_likes_html($post,$like_count,$themes_pur,$show_likes,$show_com,$show_rate,$show_view,$show_excerpt,$excerpt){
				  $widget_data .='
				  <div class="col-md-12 col-sm-4">
					<div class="video-item">
						<div class="videos-row">
							<div class="item-thumbnail">
								  <a href="'.get_permalink($post->post_id).'" title="'.the_title_attribute('echo=0').'">';
								  if(has_post_thumbnail($post->post_id)){
											  $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->post_id),'thumb_139x89', true);
										  }else{
											  $thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
											  $thumbnail[1]=520;
											  $thumbnail[2]=293;
										  }
								  $widget_data .= '<img src="'.$thumbnail[0].'" width="'.$thumbnail[1].'" height="'.$thumbnail[2].'" alt="'.the_title_attribute('echo=0').'" title="'.the_title_attribute('echo=0').'">';
								  //'.get_the_post_thumbnail(get_the_ID(), array(139,89) ).'
							  if($themes_pur!='0'){	
									$widget_data .= '<div class="link-overlay fa fa-play "></div>';}
									$widget_data .= '</a>
								  '.tm_post_rating($post->post_title).'
							</div>	
							<div class="item-info">
							  <div class="all-info">
							  <h2 class="rt-article-title"> <a href="'.get_permalink($post->post_id).'" title="'.get_the_title($post->post_id).'">'.get_the_title($post->post_id).'</a></h2>
							  <div class="item-meta">';
							  if($show_view!='hide_v'){
									$widget_data .= '<span class="pp-icon"><i class="fa fa-eye"></i> '.get_post_meta($post->post_id, '_count-views_all', true).'</span><br>';
							  }
							  if($show_likes!='hide_l' &&function_exists('GetWtiLikeCount')){
									$widget_data .= '<span class="pp-icon iclike"><i class="fa fa-thumbs-up"></i> '.$like_count.'</span><br>';
							  }
							  if($show_com!='hide_c'){
									$widget_data .= '<span class="pp-icon"><i class="fa fa-comment"></i> '.get_comments_number($post->post_id).'</span><br>';			
							  }
							  $widget_data .= '
								</div>
								</div>
								</div>';
							  if($show_excerpt!='hide_ex'){
								$widget_data .= '<div class="pp-exceprt">'.$excerpt.'</div>';
							  }
							  $widget_data .='
						 </div>
					</div>
				  </div>
				  ';
				return $widget_data;
		}	
		
		function tm_likes_cat_html($post,$like_count,$format_cat){
				$widget_data .= '   
					<div class="video-item">
						<div class="item-thumbnail">
							 <a href="'.get_permalink($post->post_id).'" title="'.esc_attr(get_the_title()).'">';
								  if(has_post_thumbnail($post->post_id)){
											  $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->post_id),'thumb_139x89', true);
										  }else{
											  $thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
											  $thumbnail[1]=520;
											  $thumbnail[2]=293;
										  }
								  $widget_data .= '<img src="'.$thumbnail[0].'" width="'.$thumbnail[1].'" height="'.$thumbnail[2].'" alt="'.the_title_attribute('echo=0').'" title="'.the_title_attribute('echo=0').'">';
								if($format_cat=='' || $format_cat =='standard'  || $format_cat =='gallery'){
									$widget_data .= '<div class="link-overlay fa fa-search"></div>';
								}else {
									$widget_data .= '<div class="link-overlay fa fa-play"></div>';
								}
							$widget_data .= '</a>
							'.tm_post_rating($post->post_id).'
							<div class="item-head">
								<h3><a href="'.get_permalink($post->post_id).'" title="'.get_the_title($post->post_id).'">'.get_the_title($post->post_id).'</a></h3>
							</div>
						</div>
					</div>';
					return $widget_data;
		}
		/* 
		 * Get item for related widget
		 *
		 */
		function get_item_related($postformat,$themes_pur){	
		  $html='';
			$html .= '
			<div class="col-md-12 col-sm-4">
			  <div class="video-item">
				 <div class="videos-row">
						  <div class="item-thumbnail">
							<a href="'.get_permalink().'" title="'.esc_attr(get_the_title()).'">';
							if(has_post_thumbnail()){
										$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(),'thumb_139x89', true);
									}else{
										$thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
										$thumbnail[1]=520;
										$thumbnail[2]=293;
									}
							$html .= '<img src="'.$thumbnail[0].'" width="'.$thumbnail[1].'" height="'.$thumbnail[2].'" alt="'.the_title_attribute('echo=0').'" title="'.the_title_attribute('echo=0').'">';
							//'.get_the_post_thumbnail(get_the_ID(), array(139,89) ).'
							if($postformat!='standard' && $themes_pur!='0')
							{
								$html .= '<div class="link-overlay fa fa-play "></div></a>
								'.tm_post_rating(get_the_ID());
							}
					$html .= '</div>
					  </div>	
					  <div class="item-info col-md-5 col-lg-6">
						<h2 class="rt-article-title"> <a href="'.get_permalink().'" title="'.esc_attr(get_the_title()).'">'.get_the_title().'</a></h2>
						<div class="item-meta">';
						if($postformat=='standard'){ $html.= tm_html_video_meta('comment',false,true);}
						else{ $html.= tm_html_video_meta(false,false,true);}
					$html.= '		
					  </div>
				   </div>
			  </div>
			</div>
			';
		return $html;
		}
		/* 
		 * Get item for related fo 
		 *
		 */
		function get_item_relate_video($thumb,$show_title, $show_exceprt, $show_rate,$show_dur,$show_view,$show_com,$show_like, $show_aut,$show_date, $themes_pur){	
		  $format = get_post_format(get_the_ID());
		  $html='';
		  $html .= '
			<div class="video-item">
				  <div class="item-thumbnail">
					  <a href="'.get_permalink().'" title="'.esc_attr(get_the_title()).'">';
							if(has_post_thumbnail()){
										$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(),$thumb , true);
									}else{
										$thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
										$thumbnail[1]=520;
										$thumbnail[2]=293;
									}
							$html .= '<img src="'.$thumbnail[0].'" width="'.$thumbnail[1].'" height="'.$thumbnail[2].'" alt="'.the_title_attribute('echo=0').'" title="'.the_title_attribute('echo=0').'">';
						if($themes_pur!='0'){	
							if($format=='' || $format =='standard' || $format =='gallery'){
								$html .= '<div class="link-overlay fa fa-search"></div>';
							}else {
								$html .= '<div class="link-overlay fa fa-play "></div>';
							}					
						}
						$html .= '</a>';
					  if($show_rate!='0'){
						$html .= tm_post_rating(get_the_ID());
					  }
					  if($show_dur!='0'){
						if(get_post_meta(get_the_id(),'time_video',true)!='00:00' && get_post_meta(get_the_id(),'time_video',true)!='00' && get_post_meta(get_the_id(),'time_video',true)!='' ){
								$html .= '
								<span class="rating-bar bgcolor2 time_dur">'.get_post_meta(get_the_id(),'time_video',true).'</span>';
							}

						}
				  $html .= '
				  </div>
				  <div class="item-head">';
				  if($show_title!='0'){
				  $html .= '
					  <h3><a href="'.get_permalink(get_the_ID()).'">'.strip_tags(get_the_title(get_the_ID())).'</a></h3>';}
					  
					  $html .= '
					  <div class="item-info">';
						if($show_aut!='0'){
							$author = get_author_posts_url( get_the_author_meta( 'ID' ) );
						  $html .= '<span  class="item-author"><a href="'.$author.'" title="'.get_the_author().'">'.get_the_author().'</a></span>';}
						if($show_date!='0'){  
						  $html .= '<span class="item-date">'.get_the_time(get_option('date_format')).'</span>';}
						  $html .= '<div class="item-meta no-bg">';
						  if($show_view!='0' ){
						  $html .= tm_html_video_meta('view',false,false);}
						  if($show_com!='0' ){
						  $html .= tm_html_video_meta('comment',false,false);}
						  if($show_like!='0'){
						  $html .= tm_html_video_meta('like',false,false);}
						  $html .= '</div>
					  </div>';
				  $html .= '	  
				  </div>';
				  if($show_exceprt!='0'){
				  $html .= '
				  <div class="item-content">'.get_the_excerpt().'</div>';}
				  $html .='
			</div>			
		  ';
		return $html;
		}	
	}
	function tm_get_posts_likes($pid = '') {
		global $post;
		if(!$pid)
			$pid = $post->ID;
		if(!$pid)
			return;
		$likes = sprintf(__('%s <span class="suffix">Likes</span>', 'dp'), '<i class="count" data-pid="'.$pid.'">'.tm_get_post_likes($pid).'</i>');
		
		$liked = tm_is_user_liked_post($pid) ? ' liked': '';
					
		$stats .= '<span class="dp-post-likes likes'.$liked.'">'.$likes.'</span>';
		
		return $stats;
	}
}
?>