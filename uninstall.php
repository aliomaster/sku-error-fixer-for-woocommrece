<?php

/**
 * Woocommerce SKU Variations Cleaner Uninstall
 *
 * Uninstalling plugin deletes options.
 *
 * @package WordPress
 * @author      Alio Master (SWS - SpaceWorkSpace)
 * @version     1.0.0
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Delete all plugin options from Data Base
 */

$options = get_option( 'sku_vars_cleaner_settings' );
if ( isset( $options) && ! empty( $options ) ) {
	delete_option( 'sku_vars_cleaner_settings' );
}