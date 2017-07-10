<?php
/* SHORT CODE FOR COMPARE TABLE 
 *
 * [testimonial name="" job=""]
 *			Testimonial content
 *		[testi-avatar]
 *			Avatar of testimonial author
 *		[/testi-avatar]
 * [/testimonial]
 *
 */
function parse_compare_table($atts, $content){
	/*$name = ($atts['name']) ? $atts['name'] : '';
	$job = ($atts['job']) ? $atts['job'] : '';	
	$testi = strip_shortcodes($content);
	preg_match('/\[testi-avatar\](.*)\[\/testi-avatar\]/s',$content,$matches);
	if(count($matches)>0){
		$avatar = $matches[0];
	} else {
		$avatar = '';
	}*/
	$id = isset($atts['id']) ? $atts['id'] : '';	
	$html = '
		<div class="compare-table-tm" id="'.$id.'">
			'.do_shortcode(str_replace('<br class="nc" />', '', $content, $id)).'			
		</div>
	';
	return $html;	
}

function parse_compare_table_column($atts, $content, $id){
	$class = isset($atts['class']) ? $atts['class'] : '';	
	$id_col=rand();
	$widthcolumn = isset($atts['column']) ? (100/$atts['column']) : 100;
	$bg_color = isset($atts['bg_color']) ? 'background-color:'.$atts['bg_color'].' !important;' : '';
	$html = '
		<style type="text/css" scoped="scoped">
			.compare-table-tm #com1-'.$id_col.' .compare-table-row.row-first,
			.compare-table-tm .compare-table-content .ct-btn{'.$bg_color.'}
		</style>

		<div style="width:'.$widthcolumn.'%; float:left; " class="column" >
			<div class="compare-table-column '.$class.'" id="com1-'.$id_col.'">
				'.do_shortcode(str_replace('<br class="nc" />', '', $content, $id)).'
			</div>
		</div>
	';
	
	return $html;
}

function parse_compare_table_row($atts, $content, $id){
	$class = isset($atts['class']) ? $atts['class'] : '';
	$headding = isset($atts['title']) ? '<h3>'.$atts['title'].'</h3>' : '';
	$color = isset($atts['color']) ?  $atts['color'] : '';
	$id_hl = rand();
	$html = '
		<div class="compare-table-row '.$class.'" id="'.$id_hl.'"><div class="compare-table-content" style="background-color:'.$color.' !important;">
			'.$headding.'<span>'.do_shortcode(str_replace('<br class="nc" />', '', $content)).'</span>'.'
		</div></div>
	';
	$html=str_replace("<p></p>","",$html);
	return $html;
}


add_shortcode( 'comparetable', 'parse_compare_table' );
add_shortcode( 'c-column', 'parse_compare_table_column' );
add_shortcode( 'c-row', 'parse_compare_table_row' );
?>