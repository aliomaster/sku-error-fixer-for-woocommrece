<?php

// Ajax search old vars
add_action( 'wp_ajax_nopriv_search_old_vars', 'search_old_vars' );
add_action( 'wp_ajax_search_old_vars', 'search_old_vars' );
function search_old_vars() {
	 $res = '';

	 global $wpdb;
	 $wpdb->show_errors( true );
	 $no_variable_term_ids = $wpdb->get_results( $wpdb->prepare( "SELECT term_id FROM $wpdb->terms WHERE slug IN ( %s, %s, %s )", 'simple', 'grouped', 'external' ) );
	 $no_vars = array();
	 foreach ( $no_variable_term_ids as $noterm ) {
	 	$no_vars[] = $noterm->term_id;
	 }
	 $no_vars = implode( ', ', $no_vars );

	 if ( $no_vars ) {exit;
	 	$all_no_vars = $wpdb->get_results( $wpdb->prepare( "SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id='%s'", $no_vars ) );
	 	if ( $all_no_vars && is_array( $all_no_vars ) ) {
	 		foreach ( $all_no_vars as $simp_item ) {
	 			$no_vars[] = $simp_item->object_id;
	 		}
	 	}
	 }

	 $needless_childs = array();
	 if ( $no_vars ) {
	 	foreach ( $no_vars as $simple_id ) {
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

		echo $res;

	wp_die();
}
