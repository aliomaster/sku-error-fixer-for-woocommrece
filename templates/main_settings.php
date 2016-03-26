<?php 
if ( isset( $_POST[SWS_VAR_CLEANER_AUTO]) ) {
	update_option( SWS_VAR_CLEANER_AUTO, $_POST[SWS_VAR_CLEANER_AUTO] );
	echo "<div class='updated'><p>" . _e( 'Settings updated' ) . "</p></div>";
}
?>
<div class="wrap">
	<h2 class="dashicons-before dashicons-admin-generic options_icon">
		<?php echo SWS_VAR_CLEANER_PLUGIN_NAME; ?>
	</h2>
	<form method="POST" action="options" enctype="multipart/form-data">
	<?php //settings_fields( 'theme_option_group' ); ?>
	<table class="form-table wc_sku_cleaner">
		<tr>
			<th scope="row">
				<?php _e( 'Search old product variations' ); ?>
				<p class="description">
					<?php _e( 'Click Start search button to start the search of old variations.' ); ?>
				</p>
			</th>
			<td>
				<button class="button button-primary"><?php _e( 'Start search old variations' ); ?></button>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'Bulk delete' ); ?>
				<p class="description">
					<?php _e( 'Simultaneous removal of all the old variables or cleaning not relevant SKU numbers.' ); ?>
				</p>
			</th>
			<td>
				<button class="button button-primary"><?php _e( 'Clean SKU fields of old variations' ); ?></button>
				<hr class="cleaner_divider">
				<button class="button button-primary"><?php _e( 'Delete the traces of the old variations fully' ); ?></button>
				<p class="description">
					<?php _e( 'This operation cannot be undone. Please backup your database if you are unsure.' ); ?>
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'Automatically' ); ?>
				<p class="description">
					<?php _e( 'When I change the product type to Simple from Variable:' ); ?>
				</p>
			</th>
			<td class="auto_labels">
				<p><label for="auto_clean0"><input type="radio" name="auto_clean" value="default" id="auto_clean0"> <?php _e( 'Default (not clean)' ); ?></label></p>
				<p><label for="auto_clean1"><input type="radio" name="auto_clean" value="auto_del_fully" id="auto_clean1"> <?php _e( 'Automatically delete old variations' ); ?></label></p>
				<p><label for="auto_clean2"><input type="radio" name="auto_clean" value="auto_del_sku" id="auto_clean2"> <?php _e( 'Automatically clean old skus of variables' ); ?></label></p>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>
	</form>
</div>