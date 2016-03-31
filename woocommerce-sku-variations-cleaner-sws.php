<?php
/**
* Plugin Name: WooCommerce SKU Variations Cleaner SWS
* Description: Removal old product SKUs or configure automatic deletion when changing the product type.
* Version: 1.0
* Author: Alio Master (SWS - SpaceWorkSpace)
* Author URI: aliowebdeveloper@gmail.com
*
*
* @package WordPress
* @author Alio Master (SWS - SpaceWorkSpace)
* @since 1.0.0
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'SWS_VAR_CLEANER_PLUGIN_PATH', plugin_dir_url(__FILE__) );

// Load plugin class files
require_once( 'includes/class-sku-variations-cleaner-template.php' );
require_once( 'includes/class-sku-variations-cleaner-template-settings.php' );

// Load plugin libraries
require_once( 'includes/lib/class-sku-variations-cleaner-admin-api.php' );

// Load functions
require_once( 'includes/ajax-functions.php' );

// helper developer function
/*if ( ! function_exists( 'pr' ) ) {
	function pr($val) {
		echo '<pre>';
		print_r($val);
		echo '</pre>';
	}
}*/

/**
 * Returns the main instance of SKU_Variations_Cleaner to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object SKU_Variations_Cleaner
 */
function SKU_Variations_Cleaner () {
	$instance = SKU_Variations_Cleaner_Template::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = SKU_Variations_Cleaner_Template_Settings::instance( $instance );
	}

	return $instance;
}

SKU_Variations_Cleaner();