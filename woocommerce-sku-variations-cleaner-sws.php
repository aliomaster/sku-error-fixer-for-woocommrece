<?php
/**
* Plugin Name: Woocommerce SKU Variations Cleaner SWS
* Description: Removal old product SKUs or configure automatic deletion when changing the product type.
* Version: 1.0
* Author: Alio Master (SWS - SpaceWorkSpace)
* Author URI: aliowebdeveloper@gmail.com
*
* Text Domain: wordpress-plugin-template
* Domain Path: /lang/
*
* @package WordPress
* @author Alio Master (SWS - SpaceWorkSpace)
* @since 1.0.0
*/

define( 'SWS_VAR_CLEANER_PLUGIN_NAME', 'Woocommerce SKU Variations Cleaner SWS' );
define( 'SWS_VAR_CLEANER_PLUGIN_URL', __FILE__ );
define( 'SWS_VAR_CLEANER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'SWS_VAR_CLEANER_TEMPLATE_PATH', plugin_dir_path( __FILE__ ) . "templates/" );
define( 'SWS_VAR_CLEANER_OPTIONS_GROUP', 'sku_vars_cleaner_options' );
define( 'SWS_VAR_CLEANER_AUTO', 'auto_clean' );

// helper function
if ( ! function_exists( 'pr' ) ) {
	function pr($val) {
		echo '<pre>';
		print_r($val);
		echo '</pre>';
	}
}


/**
 * SKU Variations Cleaner class
 *
 * class used as a namespace
 *
 * @package SKU Variations Cleaner
 */
class SKU_Variations_Cleaner {
	/**
	 * Get hooked into init
	 *
	 * @return void
	 */
	function sku_variations_cleaner() {
		add_action( 'admin_init', array( $this, 'sku_variations_cleaner_register_settings' ) );
		add_action( 'admin_menu', array( $this, 'setting_page_menu' ), 99 );
		//add_action( 'admin_init', array( &$this, 'get_title' ) );
		//add_filter( 'media_upload_tabs', array( &$this, 'create_new_tab') );
		//add_action( 'media_buttons', array( &$this, 'context'), 11 );
		//add_filter( 'media_upload_uploadzip', array( &$this, 'media_upload_uploadzip') );

		// Load admin JS & CSS
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		add_filter( "plugin_action_links", array( $this, 'sku_variations_cleaner_plugin_action_links' ), 10, 2 );

	}

	/**
	 * Load admin CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_styles () {
		wp_register_style( 'css-sku-vars-cleaner-admin', plugins_url( 'css/sku-vars-cleaner-admin.css', __FILE__ ) );
		wp_enqueue_style( 'css-sku-vars-cleaner-admin' );
	}

	/**
	 * Load admin Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_scripts () {
		wp_register_script( 'js-sku-vars-cleaner-admin', plugins_url( 'js/sku-vars-cleaner-admin.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'js-sku-vars-cleaner-admin' );
	}

	/**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	function sku_variations_cleaner_plugin_action_links( $links, $file ) {
		if ( $file == plugin_basename( SWS_VAR_CLEANER_PLUGIN_PATH . 'woocommerce-sku-variations-cleaner-sws.php' ) ) {
			$settings_link = '<a href="admin.php?page=sku_variations_cleaner">'
			. _e( 'Plugin Settings' ) . '</a>';
			array_unshift( $links, $settings_link );
		}
		return $links;
	}

	/**
	 * Create admin pages in menu
	 *
	 * @return void
	 */
	function setting_page_menu() {
		add_submenu_page( 'woocommerce', SWS_VAR_CLEANER_PLUGIN_NAME, 'Clear Variations', 'manage_options', 'sku_variations_cleaner', array( $this, 'sku_variations_cleaner_page' ) );
	}

	/**
	 * Register plugin settings
	 *
	 * @return void
	 */
	function sku_variations_cleaner_register_settings() {
		register_setting( SWS_VAR_CLEANER_OPTIONS_GROUP, SWS_VAR_CLEANER_OPTIONS_GROUP/*, array( $this, 'sku_vars_cleaner_sanitize_options' )*/ );
	}

	/**
	 * Sanitize plugin settings
	 *
	 * @return void
	 */
	/*function sku_vars_cleaner_sanitize_options( $options ) {
		$clean_options = array();
		$old_options = get_option( SWS_VAR_CLEANER_OPTIONS_GROUP );
		pr($old_options); exit;
		if ( ! empty( $_FILES['sws-preloader-custom-file']['tmp_name'] ) ) {
			$overrides = array( 'test_form' => false );
			$file = wp_handle_upload( $_FILES['sws-preloader-custom-file'], $overrides );
			$clean_options['url'] = $file['url'];
			$clean_options['file'] = $file['file'];

		} else {
			
			
			if ( ! empty( $old_options['url'] ) && ! empty( $old_options['file'] ) ) {
				$clean_options['url'] = $old_options['url'];
				$clean_options['file'] = $old_options['file'];
			}

		}

		foreach ( $options as $key => $value ) {
			$clean_options[$key] = strip_tags( $value );
		}

		if ( $clean_options['selected-tab'] !== 'upload_a_custom' && ! empty( $clean_options['file'] ) ) {
			unlink( $clean_options['file'] );
			unset( $clean_options['url'] );
			unset( $clean_options['file'] );
		} elseif ( isset( $file ) && isset( $old_options['url'] ) && $file['url'] !== $old_options['url'] ) {
			unlink( $old_options['file'] );
		}

		return $clean_options;
	}*/

	/**
	 * Add the new tab to the woocommerce pop-ip
	 *
	 * @return void
	 */
	function sku_variations_cleaner_page() {
		include SWS_VAR_CLEANER_TEMPLATE_PATH . "main_settings.php";
	}
	/**
	 * Move unzipped content from temp folder to media library
	 *
	 * @param string $dir Directory to loop through
	 * @param integer $parent Page ID to be used as attachment parent
	 * @param string $return String to append results to
	 * @return string Results as <li> items
	 */
	/*function move_from_dir( $dir, $parent, $return = '' ) {}*/
	/**
	 * Handle the initial zip upload
	 *
	 * @return string HTML Results or Error message
	 */
	/*function handler() {}*/

} // end class

$sku_variations_cleaner = new SKU_Variations_Cleaner();


/* removing sku functions */
global $wpdb;
$wpdb->show_errors( true );
$simple_term_id = $wpdb->get_var( $wpdb->prepare( "SELECT term_id FROM $wpdb->terms WHERE slug='%s' LIMIT 1", 'simple' ) );
$simples = array();
if ( $simple_term_id ) {
	$all_simples = $wpdb->get_results( $wpdb->prepare( "SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id='%s'", $simple_term_id ) );
	if ( $all_simples && is_array( $all_simples ) ) {
		foreach ( $all_simples as $simp_item ) {
			$simples[] = $simp_item->object_id;
		}
	}
}

$needless_childs = array();
if ( $simples ) {
	foreach ( $simples as $simple_id ) {
		$childs = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='%s' AND post_parent='%s'", 'product_variation', $simple_id ) );
		if ( $childs && is_array( $childs ) ) {
			foreach ( $childs as $child_item ) {
				$needless_childs[$child_item->ID] = get_the_title( $child_item->ID );
			}
		}
	}
}
//pr($needless_childs); exit;
/* removing fully post */
//$del = wp_delete_post( 14471, true );
//pr($del->ID); exit;

/* removing sku post */
$del_sku = $wpdb->delete( 'wp_postmeta', array( 'meta_key' => '_sku', 'post_id' => 14573 ) );
//pr($del_sku); exit;

//pr($needless_childs); exit;
$products_id = $wpdb->get_results( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='%s'", '_sku' ) );