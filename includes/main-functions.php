<?php

function get_needless_childs() {

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

	return $needless_childs;
}

// Auto cleaner
add_filter( 'wp_insert_post_data' , 'sws_auto_vars_cleaner' , '99', 2 );

function sws_auto_vars_cleaner( $data , $postarr ) {
//pr($postarr); exit;
	global $wpdb;
	$wpdb->show_errors( true );

	$auto_clean_option = get_option( 'alio_auto_clean' );
	if ( $auto_clean_option != 'default' ) {
		//pr($postarr); exit;
		$post_id = $postarr['ID'];
		$product_sku = $postarr['_sku'];
		$variable_sku = $postarr['variable_sku'];

		$post_skus = array();
		$the_same = array();

		if ( $variable_sku && is_array( $variable_sku ) && !empty( $variable_sku ) ) {
			foreach ( $variable_sku as $var_sku ) {
				if ( $var_sku ) {
					$post_skus[$var_sku] = $var_sku;
				}
			}
		}
		if ( $product_sku ) {
			$post_skus[$product_sku] = $product_sku;
		}

		$needless_childs = get_needless_childs();

		if ( $needless_childs && is_array( $needless_childs ) ) {
			foreach ( $needless_childs as $key => $value ) {

				if( $same_sku = array_search( $value['sku'], $post_skus ) ) {
					$the_same[] = $key;
				}
			}
		}
		if ( $the_same && is_array( $the_same ) ) {
			if ( $auto_clean_option == 'auto_del_sku' ) {
				foreach ( $the_same as $remove_id ) {
					$wpdb->delete( $wpdb->postmeta, array( 'meta_key' => '_sku', 'post_id' => $remove_id ) );
				}
			}
			if ( $auto_clean_option == 'auto_del_fully' ) {
				foreach ( $the_same as $remove_id ) {
					wp_delete_post( $remove_id, true );
				}
			}
		}
	}

	return $data;
}