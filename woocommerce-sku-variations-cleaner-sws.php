<?php
/**
* Plugin Name: Woocommerce SKU Variations Cleaner SWS
* Description: ...
* Version: 1.0
* Author: Alio Master
* Author URI: aliowebdeveloper@gmail.com
*/

/**
 * SKU_Variations_Cleaner class
 *
 * class used as a namespace
 *
 * @package Upload Media By Zip
 */
class SKU_Variations_Cleaner {
	/**
	 * Get hooked into init
	 *
	 * @return void
	 */
	function woocommerce_old_sku_cleaner() {
		add_action( 'admin_menu', array( &$this, 'menu' ) );
		add_action( 'admin_init', array( &$this, 'get_title' ) );
		add_filter( 'media_upload_tabs', array( &$this, 'create_new_tab') );
		add_action( 'media_buttons', array( &$this, 'context'), 11 );
		//add_filter( 'media_upload_uploadzip', array( &$this, 'media_upload_uploadzip') );
	}
	/**
	 * Create admin pages in menu
	 *
	 * @return void
	 */
	function menu() {
		$page = add_submenu_page( 'woocommerce', __( 'Woocommerce Old SKU Cleaner', 'wordpress' ), __( 'Old SKU Cleaner', 'wordpress' ), 'old_sku_cleaner', __FILE__, array( &$this, 'page' ) );
		add_action( 'admin_print_scripts-' . $page, array( &$this, 'scripts' ) );
		/*$page = add_media_page( 'Woocommerce Old SKU Cleaner', 'Woocommerce Old SKU Cleaner', 'old_sku_cleaner', __FILE__, array( &$this, 'page' ) );*/
	}

	/**
	 * Add the new tab to the media pop-ip
	 *
	 * @param array $tabs Existing media tabs
	 * @return array $tabs Modified media tabs
	 */
	/*function create_new_tab( $tabs ) {
		$tabs['uploadzip'] = __( 'Upload Zip Archive', 'upload-media-by-zip' );
	    return $tabs;
	}*/
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
pr($del_sku); exit;

pr($needless_childs); exit;
$products_id = $wpdb->get_results( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='%s'", '_sku' ) );