<?php

/**
 * Woocommerce SKU Variations Cleaner Uninstall
 *
 * Uninstalling plugin deletes options.
 *
 * @package     WordPress
 * @author      Alio Master (SWS - SpaceWorkSpace)
 * @version     1.0.0
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Delete all plugin options from Data Base
 */

$auto_clean_option = get_option( 'alio_auto_clean' );
$version_option = get_option( 'sku_vars_cleaner_version' );

if ( isset( $auto_clean_option) ) {
	delete_option( 'alio_auto_clean' );
}
if ( isset( $version_option) ) {
	delete_option( 'sku_vars_cleaner_version' );
}