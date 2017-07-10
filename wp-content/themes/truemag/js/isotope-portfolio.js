(function($){
/* Isotope custom layout */
	$.Isotope.prototype._getCenteredMasonryColumns = function() {
		this.width = this.element.width();		
		var parentWidth = this.element.parent().width();		
		// i.e. options.masonry && options.masonry.columnWidth
		var colW = this.options.masonry && this.options.masonry.columnWidth ||
		  // or use the size of the first item
		  this.$filteredAtoms.outerWidth(true) ||
		  // if there's no items, use size of container
		  parentWidth;		
		var cols = Math.floor( parentWidth / colW );
		cols = Math.max( cols, 1 );		
		// i.e. this.masonry.cols = ....
		this.masonry.cols = cols;
		// i.e. this.masonry.columnWidth = ...
		this.masonry.columnWidth = colW;	
	};

	// modified Isotope methods for gutters in masonry
	$.Isotope.prototype._getMasonryGutterColumns = function() {
		var gutter = this.options.masonry && this.options.masonry.gutterWidth || 0;
		containerWidth = this.element.parent().width();
	
		this.masonry.columnWidth = this.options.masonry && this.options.masonry.columnWidth ||
			// or use the size of the first item
			this.$filteredAtoms.outerWidth(true) ||
			// if there's no items, use size of container
			containerWidth;
		
		this.masonry.columnWidth += gutter;
		
		this.masonry.cols = Math.floor( ( containerWidth + gutter ) / this.masonry.columnWidth );
		this.masonry.cols = Math.max( this.masonry.cols, 1 );
	};

	$.Isotope.prototype._masonryReset = function() {
		// layout-specific props
		this.masonry = {};
		// FIXME shouldn't have to call this again
		
		this._getCenteredMasonryColumns();	
		this._getMasonryGutterColumns();
		var i = this.masonry.cols;
		this.masonry.colYs = [];
		while (i--) {
		  this.masonry.colYs.push( 0 );
		}
	};

	$.Isotope.prototype._masonryResizeChanged = function() {
		var prevColCount = this.masonry.cols;
		// get updated colCount
		this._getCenteredMasonryColumns();
		this._getMasonryGutterColumns();		
		return ( this.masonry.cols !== prevColCount );
	};

	$.Isotope.prototype._masonryGetContainerSize = function() {
		var unusedCols = 0,
			i = this.masonry.cols;
		// count unused columns
		while ( --i ) {
		  if ( this.masonry.colYs[i] !== 0 ) {
			break;
		  }
		  unusedCols++;
		}    
    	return {
          height : Math.max.apply( Math, this.masonry.colYs ),
          // fit container to columns that have been used;
          width : (this.masonry.cols - unusedCols) * this.masonry.columnWidth
        };
	};
	
	
	// Isotope 
	if(is_isotope){
		// Theme opsition check use Isotope
		$('.portfolio-1 .portfolio-container').each(function(){
			if($(this).parent().hasClass('portfolio-modern'))
				$('.element', $(this)).each( function() { $(this).hoverdir(); } );
		});	
	}else{
		// Theme opsition check do not use Isotope
		$('.portfolio-1 .portfolio-container').each(function(){	
			var parent = $(this);		
			$(".project-tags",$(this)).find("a").click(function(){
				
				//obj.isotope({ filter: $(this).attr('data-filter')});
				var data_filter = $(this).attr('data-filter').replace('.', '');
				if(data_filter == '*'){
					$('.element', parent).each(function(){
						$(this).removeAttr('style');
					});
				}else{
					$('.element', parent).each(function(){
						if($(this).attr('class') != $(this).attr('class').replace(data_filter, '')){
							$(this).removeAttr('style');
						}else{
							$(this).css({'width': 0, 'height': 0});
						}
					});
				}				
				$(this).parent().parent().find('a').removeClass('active');
				$(this).addClass('active');
			});
			if($(this).parent().hasClass('portfolio-modern'))
				$('.element', $(this)).each( function() { $(this).hoverdir(); } );
		});
	}
	$(".portfolio-container a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'fast',slideshow:10000, hideflash: true});
})(jQuery);

function build_portfolio_modern(portfolio_id, width){
	$j = jQuery;
	var obj = $j(portfolio_id);
	obj.isotope({
		masonry: {
			columnWidth: width
		},
		sortBy: 'number'
	});
	
	$j(".project-tags",obj.parent()).find("a").click(function(){
		$j(portfolio_id).isotope({ filter: $j(this).attr('data-filter')});
		$j(this).parent().parent().find('a').removeClass('active');
		$j(this).addClass('active');
	});
}

function build_portfolio_classic(portfolio_id, width){
	var obj = jQuery(portfolio_id);
	obj.isotope({
		masonry: {},
		sortBy: "number"
	});
	
	jQuery(".project-tags",obj.parent()).find("a").click(function(){
		obj.isotope({ filter: jQuery(this).attr("data-filter")});
		jQuery(this).parent().parent().find("a").removeClass("active");
		jQuery(this).addClass("active");
	});

}