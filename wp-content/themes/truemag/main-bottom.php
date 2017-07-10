<?php if(is_active_sidebar('main_bottom')){?>
<div id="main-bottom">
	<div class="container">
    	<div class="container-pad">
            <div class="row-fluid">
                <?php echo get_dynamic_sidebar('main_bottom');?>
            </div>
        </div>
	</div>
</div>
<?php }?>