<?php 

//pr($_POST); exit;

if ( isset( $_POST[SWS_VAR_CLEANER_AUTO] ) ) {
	update_option( SWS_VAR_CLEANER_OPTIONS_GROUP, array( SWS_VAR_CLEANER_AUTO => $_POST[SWS_VAR_CLEANER_AUTO] ) );
	
	echo "<div class='updated'><p>" . _e( 'Settings updated' ) . "</p></div>";
}
/*if ( ! isset( $_REQUEST['settings-updated'] ) ) {
	echo '<div class="updated"><p><strong>' . _e( 'Options saved!' ); . '</strong></p></div>';
}*/
	if ( ! get_option( SWS_VAR_CLEANER_OPTIONS_GROUP, false ) ) {
		update_option( SWS_VAR_CLEANER_OPTIONS_GROUP, array( SWS_VAR_CLEANER_AUTO => 'default' ) );
	}

?>
<div class="wrap">
	<h2 class="dashicons-before dashicons-admin-generic options_icon">
		<?php echo SWS_VAR_CLEANER_PLUGIN_NAME; ?>
	</h2>
	<form method="POST" action="options.php" enctype="multipart/form-data">
	<?php
		settings_fields( SWS_VAR_CLEANER_OPTIONS_GROUP );
		$group_option = get_option( SWS_VAR_CLEANER_OPTIONS_GROUP );
		$auto_option = $group_option[SWS_VAR_CLEANER_AUTO];
	?>

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
				<p><label for="auto_clean0"><input type="radio" name='<?php echo SWS_VAR_CLEANER_AUTO; ?>' value="default" id="auto_clean0"<?php echo ( $auto_option == 'default' ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Default (not clean)' ); ?></label></p>
				<p><label for="auto_clean1"><input type="radio" name='<?php echo SWS_VAR_CLEANER_AUTO; ?>' value="auto_del_fully" id="auto_clean1"<?php echo ( $auto_option == 'auto_del_fully' ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Automatically delete old variations' ); ?></label></p>
				<p><label for="auto_clean2"><input type="radio" name='<?php echo SWS_VAR_CLEANER_AUTO; ?>' value="auto_del_sku" id="auto_clean2"<?php echo ( $auto_option == 'auto_del_sku' ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Automatically clean old skus of variables' ); ?></label></p>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>
	</form>
</div>