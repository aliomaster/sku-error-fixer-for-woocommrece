<?php

// Ajax search old vars
add_action( 'wp_ajax_nopriv_search_old_vars', 'search_old_vars' );
add_action( 'wp_ajax_search_old_vars', 'search_old_vars' );
function search_old_vars() {
	$result = '';
	$needless_childs = array();

	global $wpdb;
	$wpdb->show_errors( true );
	$no_variable_term_ids = $wpdb->get_results( $wpdb->prepare( "SELECT term_id FROM $wpdb->terms WHERE slug IN ( %s, %s, %s )", 'simple', 'grouped', 'external' ) );
	$no_vars = array();
	foreach ( $no_variable_term_ids as $noterm ) {
		$no_vars[] = $noterm->term_id;
	}

	$all_no_vars = array();
	if ( $no_vars && count( $no_vars ) == 3 ) {
		$all_no_vars_request = $wpdb->get_results( $wpdb->prepare( "SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id IN ( %s, %s, %s )", $no_vars[0], $no_vars[1], $no_vars[2] ) );

		if ( $all_no_vars_request && is_array( $all_no_vars_request ) ) {
			foreach ( $all_no_vars_request as $no_var_item ) {
				$all_no_vars[] = $no_var_item->object_id;
			}
		}
	}

	$needless_childs = array();
	if ( $all_no_vars && is_array( $all_no_vars ) ) {
		foreach ( $all_no_vars as $no_var_id ) {
			$childs = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='%s' AND post_parent='%s'", 'product_variation', $no_var_id ) );

			if ( $childs && is_array( $childs ) ) {
				foreach ( $childs as $child_item ) {
					$needless_childs[$child_item->ID] = get_the_title( $child_item->ID );
				}
			}
		}
	}

	if ( $needless_childs ) {
		$result = '<h2 class="found_results_heading">Found ' . count( $needless_childs ) . ' old variations on your website.</h2>';
		$result .= '<a href="" class="show_results">Show list</a>';
		$result .= '<ul class="needless_child_list">';
		foreach ( $needless_childs as $needless_child ) {
			$result .= '<li>' . $needless_child . '</li>';
		}
		$result .= '</ul>';
	} else {
		$result = '<h2 class="found_results_heading">Old variations are not found.</h2>';
	}

	/* removing fully post */
	//$del = wp_delete_post( 14471, true );
	//pr($del->ID); exit;

	/* removing sku post */
	//$del_sku = $wpdb->delete( 'wp_postmeta', array( 'meta_key' => '_sku', 'post_id' => 14573 ) );
	//pr($del_sku); exit;

	//pr($needless_childs); exit;
	//$products_id = $wpdb->get_results( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='%s'", '_sku' ) );

	/*внимание! Номера '#11679' из названий ненужных детей отличаются от $child_item->ID, надо перепроверить, это просто отдельные айдишки и по каким из них удалять будем!*/

	echo $result;

	wp_die();
}
