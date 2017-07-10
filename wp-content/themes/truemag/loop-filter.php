<?php
global $wp;
if(get_option('permalink_structure') != ''){
	$curent_url = home_url( $wp->request );
	if(function_exists('qtrans_getLanguage') && qtrans_getLanguage()!=''){
		$curent_url = '//'.$_SERVER["HTTP_HOST"].$_SERVER['REDIRECT_URL'];
	}
}else{
	$query_string = $wp->query_string;
	if(isset($_GET['lang'])){
		$query_string = $wp->query_string.'&lang='.$_GET['lang'];
	}
	$curent_url = add_query_arg( $query_string, '', home_url( $wp->request ) );
}
$orderby=isset($_GET['orderby'])?$_GET['orderby']:(ot_get_option('default_blog_order')?ot_get_option('default_blog_order'):'date');
?>
<?php if(ot_get_option('show_blog_order',1)||ot_get_option('show_blog_layout',1)){ ?>
<div class="video-listing-filter">
    <div class="btn-group btn-group-sm hidden-xs">
    <?php if(ot_get_option('show_blog_order',1)){ ?>
      <a href="<?php echo esc_url(add_query_arg( array('orderby' => 'date'), $curent_url )) ?>" class="btn btn-default maincolor2hover <?php echo $orderby=='date'?'current':'' ?>"><?php _e('Date','cactusthemes') ?></a>
      <a href="<?php echo esc_url(add_query_arg( array('orderby' => 'title'), $curent_url )); ?>" class="btn btn-default maincolor2hover <?php echo $orderby=='title'?'current':'' ?>"><?php _e('Title','cactusthemes') ?></a>
      <a href="<?php echo esc_url(add_query_arg( array('orderby' => 'view'), $curent_url )); ?>" class="btn btn-default maincolor2hover <?php echo $orderby=='view'?'current':'' ?>"><?php _e('Views','cactusthemes') ?></a>
      <?php if(function_exists('GetWtiLikeCount')){ ?>
      <a href="<?php echo esc_url(add_query_arg( array('orderby' => 'like'), $curent_url )); ?>" class="btn btn-default maincolor2hover <?php echo $orderby=='like'?'current':'' ?>"><?php _e('Likes','cactusthemes') ?></a>
      <?php }?>
      <a href="<?php echo esc_url(add_query_arg( array('orderby' => 'comment'), $curent_url )); ?>" class="btn btn-default maincolor2hover com-ment <?php echo $orderby=='comment'?'current':'' ?>"><?php _e('Comments','cactusthemes') ?></a>
	  <?php }
	  if(ot_get_option('show_blog_layout',1)){
		  $default_style = ot_get_option('default_listing_layout');
	  ?>
      <div class="pull-right style-filter hidden-xs">
          <a <?php echo !$default_style?'class="current"':'' ?> data-style="style-grid"><i class="fa fa-th"></i></a>
          <a <?php echo $default_style=='style-grid-2'?'class="current"':'' ?> data-style="style-grid-2"><i class="fa fa-th-large"></i></a>
          <?php /*?><a <?php echo $default_style=='style-list-2'?'class="current"':'' ?> data-style="style-list-2"><i class="fa fa-list"></i></a><?php */?>
          <a <?php echo $default_style=='style-list-1'?'class="current"':'' ?> data-style="style-list-1"><i class="fa fa-th-list"></i></a>
      </div>
      <?php }?>
    </div>
    <div class="dropdown visible-xs btn-group">
        <button type="button" class="btn btn-default btn-block dropdown-toggle visible-xs" data-toggle="dropdown">
        	<?php _e('Order by','cactusthemes'); ?> <i class="fa fa-angle-down pull-right"></i>
        </button>
        <ul class="dropdown-menu">
		<?php if(ot_get_option('show_blog_order',1)){ ?>
			<li><a href="<?php echo esc_url(add_query_arg( array('orderby' => 'date'), $curent_url )) ?>" class="btn btn-default maincolor2hover <?php echo $orderby=='date'?'current':'' ?>"><?php _e('Date','cactusthemes') ?></a></li>
			<li><a href="<?php echo esc_url(add_query_arg( array('orderby' => 'title'), $curent_url )); ?>" class="btn btn-default maincolor2hover <?php echo $orderby=='title'?'current':'' ?>"><?php _e('Title','cactusthemes') ?></a></li>
			<li><a href="<?php echo esc_url(add_query_arg( array('orderby' => 'view'), $curent_url )); ?>" class="btn btn-default maincolor2hover <?php echo $orderby=='view'?'current':'' ?>"><?php _e('Views','cactusthemes') ?></a></li>
      <?php if(function_exists('GetWtiLikeCount')){ ?>
			<li><a href="<?php echo esc_url(add_query_arg( array('orderby' => 'like'), $curent_url )); ?>" class="btn btn-default maincolor2hover <?php echo $orderby=='like'?'current':'' ?>"><?php _e('Likes','cactusthemes') ?></a></li>
      <?php }?>
			<li><a href="<?php echo esc_url(add_query_arg( array('orderby' => 'comment'), $curent_url )); ?>" class="btn btn-default maincolor2hover com-ment <?php echo $orderby=='comment'?'current':'' ?>"><?php _e('Comments','cactusthemes') ?></a></li>
		<?php }?>
        </ul>
    </div>
</div>
<?php }?>