<form role="search" onsubmit="if(jQuery('.ss',jQuery(this)).val() == '<?php echo $default_text;?>' || jQuery('.ss',jQuery(this)).val() == '') return false;" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
	<div class="asf">
		<?php if($show_categories){?>
		<div class="select">
			<div class="select-inner">
				<span class="s-chosen-cat">&nbsp;<?php echo __('All','cactusthemes');?><i class="fa fa-angle-down"></i></span>
				<select id="s-cat" name="cat" onchange="asf_on_change_cat(this)">
					<option value="">&nbsp;<?php echo __('All','cactusthemes');?>&nbsp;</option>
					<?php
						if(!is_array($select_categories) || (count($select_categories) == 0)){
							$select_categories = get_terms(array('category'));// get all categories
							
							foreach($select_categories as $cat){
								$cat_id = $cat->term_id;
								
								$taxonomy = $cat->name;
								
								if($taxonomy != 'Uncategorized'){
								?>
								<option value="<?php echo $cat_id;?>" <?php if($cat_id == $search_cat) echo "selected='selected'";?>> &nbsp;<?php echo $taxonomy;?>&nbsp;</option>
								<?php
								}
							}
						} else {
							foreach($select_categories as $cat){
								$cat_id = $cat;
								
								if(is_array($cat)){
									$cat_id = implode($cat);
								}
								
									$taxonomy = get_term_by('id',$cat_id,'category');
								
								
								?>
								<option value="<?php echo $cat_id;?>" <?php if($cat_id == $search_cat) echo "selected='selected'";?>><?php echo $taxonomy->name;?></option>
								<?php
							}
						}
						?>
				</select>
			</div>
		</div>
		<?php } else {
			if(!is_array($select_categories) || (count($select_categories) > 0)){
			?>
			<input type="hidden" name="cat" value="<?php echo $post_type;
				foreach($select_categories as $cat){
					echo $cat.",";
				}
			?>" />		
				
				<?php
			}
		}?>
		<span class="searchtext">
		<i class="ficon-cancel hide"></i>
		<input type="text" placeholder="<?php echo $default_text;?>" name="s" class="ss" autocomplete="off" value="<?php if(isset($_GET['s'])) echo $_GET['s'];?>"/>
		<?php if($suggestion){?>
		<span class="suggestion"><!-- --></span>
		<?php }?>
		</span>
		<?php if(is_array($search_for)){
			foreach($search_for as $post_type){
		?>
				<input type="hidden" name="post_type[]" value="<?php echo $post_type;?>" />
		<?php }
		}?>
		<?php if($button_text != ''){?>
		<div class="button-wrap">
			<input type="submit" id="searchsubmit" value="<?php echo $button_text;?>" />
		</div>
		<?php }?>
	</div>
</form>
<?php 	
echo $after_widget;