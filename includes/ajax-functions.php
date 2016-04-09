<?php

// Ajax search old vars
add_action( 'wp_ajax_nopriv_cleaning_old_vars', 'cleaning_old_vars' );
add_action( 'wp_ajax_cleaning_old_vars', 'cleaning_old_vars' );
function cleaning_old_vars() {
	$result = '';
	$needless_childs = array();
	$key = ( $_POST['key'] ) ? $_POST['key'] : false;

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

	if ( $all_no_vars && is_array( $all_no_vars ) ) {
		foreach ( $all_no_vars as $no_var_id ) {
			$childs = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM $wpdb->posts WHERE post_type='%s' AND post_parent='%s'", 'product_variation', $no_var_id ) );

			if ( $childs && is_array( $childs ) ) {
				foreach ( $childs as $child_item ) {
					if ( $sku = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key='%s' AND post_id='%s' LIMIT 1", '_sku', $child_item->ID ) ) ) {
						$needless_childs[$child_item->ID]['sku'] = $sku;
					}
					$needless_childs[$child_item->ID]['title'] = $child_item->post_title;
				}
			}
		}
	}

	if ( $needless_childs && !$key ) {
		$result = '<h2 class="found_results_heading">Found ' . count( $needless_childs ) . ' old variations on your website.</h2>';
		$result .= '<span class="show_results">Show list<i></i></span>';
		$result .= '<ul class="needless_child_list">';
		foreach ( $needless_childs as $needless_child ) {
			if ( $needless_child['title'] ) {
				$sku = ( $needless_child['sku'] ) ? ' ( with SKU <strong>' . $needless_child['sku'] . '</strong>)' : '';
				$result .= '<li>' . $needless_child['title'] . $sku . '</li>';
			}
		}
		$result .= '</ul>';
	} elseif ( $needless_childs && $key == 'clean' ) {
		$deleted = 0;
		$deleted_items = array();
		foreach ( $needless_childs as $post_id => $value ) {
			$sku = ( $value['sku'] ) ? 'SKU <strong>"' . $value['sku'] . '"</strong> of ' . $value['title'] : '<strong>Empty SKU field</strong> of ' . $value['title'];
			$del_sku = $wpdb->delete( $wpdb->prefix . 'postmeta', array( 'meta_key' => '_sku', 'post_id' => $post_id ) );
			if ( $del_sku == 1 ) {
				$deleted_items[] = $sku;
				$deleted++;
			}
		}
		if ( $deleted == count( $deleted_items ) && $deleted != 0 ) {
			$result = '<h2 class="found_results_heading">' . $deleted . ' SKU fields have been successfully removed.</h2>';
			$result .= '<span class="show_results">Show list<i></i></span>';
			$result .= '<ul class="needless_child_list">';
			foreach ( $deleted_items as $deleted_item ) {
				$result .= '<li>' . $deleted_item . '<span class="warning"> deleted</span></li>';
			}
			$result .= '</ul>';
		} elseif ( $deleted == 0 ) {
			$result = '<h2 class="found_results_heading">SKU fields of old variations not found.</h2>';
		} else {
			$result = '<h2 class="found_results_heading">Error. Please try again.</h2>';
		}
	} elseif ( $needless_childs && $key == 'removal' ) {
		$deleted = 0;
		$deleted_items = array();
		foreach ( $needless_childs as $post_id => $value ) {
			$del = wp_delete_post( $post_id, true );
			if ( $del->ID == $post_id ) {
				$deleted++;
				$deleted_items[] = $value['title'];
			}
		}
		if ( $deleted == count( $deleted_items ) && $deleted != 0 ) {
			$result = '<h2 class="found_results_heading">' . $deleted . ' Old variations have been successfully removed.</h2>';
			$result .= '<span class="show_results">Show list<i></i></span>';
			$result .= '<ul class="needless_child_list">';
			foreach ( $deleted_items as $deleted_item ) {
				$result .= '<li>' . $deleted_item . '<span class="warning"> deleted</span></li>';
			}
			$result .= '</ul>';
		} else {
			$result = '<h2 class="found_results_heading">Old variations not found.</h2>';
		}
	} else {
			$result = '<h2 class="found_results_heading">Old variations not found.</h2>';
	}

	echo $result;

	wp_die();
}