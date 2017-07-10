<?php if(is_active_sidebar('body_bottom')){?>
<div id="body-bottom">
	<div class="container">
    	<div class="container-pad">
            <div class="row-fluid">
                <?php echo get_dynamic_sidebar('body_bottom');?>
            </div>
        </div>
	</div>
</div>
<?php }?>