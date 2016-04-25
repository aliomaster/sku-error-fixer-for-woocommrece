<?php
/**
* Plugin Name: WooCommerce SKU Error Fixer SWS
* Description: Automatic fix Unique SKU Error for your WooCommerce products.
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
require_once( 'includes/class-sku-error-fixer-template.php' );
require_once( 'includes/class-sku-error-fixer-template-settings.php' );

// Load plugin libraries
require_once( 'includes/lib/class-sku-error-fixer-admin-api.php' );

// Load functions
require_once( 'includes/ajax-functions.php' );
require_once( 'includes/main-functions.php' );

/**
 * Returns the main instance of SKU_Error_Fixer to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object SKU_Error_Fixer
 */
function SKU_Error_Fixer () {
	$instance = SKU_Error_Fixer_Template::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = SKU_Error_Fixer_Template_Settings::instance( $instance );
	}

	return $instance;
}

SKU_Error_Fixer();