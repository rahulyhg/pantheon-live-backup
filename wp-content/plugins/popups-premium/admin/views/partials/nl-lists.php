<?php if( true === $nl_connected ) { ?>
	<h3 class="spu-title"><?php _e( 'Newsletter plugin Lists' ,'spup' ); ?></h3>
	
	<table class="wp-list-table widefat">
		<thead>
		<tr>
			<th class="spu-hide-smallscreens" scope="col"><?php _e( 'List ID', 'spup' ); ?></th>
			<th scope="col"><?php _e( 'List Name', 'spup' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php
		if($nl_lists) {
			foreach($nl_lists as $list) {
				?>

				<tr valign="top">
					<td><?php echo esc_html( $list->id ); ?></td>
					<td><?php echo esc_html( $list->name ); ?></td>
				</tr>
			<?php
			}
		} else { ?>
			<tr>
				<td colspan="5">
					<p><?php _e( 'No lists were found in your Newsletter Plugin account.', 'spup' ); ?></p>
				</td>
			</tr>
		<?php
		}
		?>
		</tbody>
	</table>

<?php } ?>