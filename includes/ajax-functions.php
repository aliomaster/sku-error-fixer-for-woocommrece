<?php

// Ajax search old vars
add_action( 'wp_ajax_nopriv_cleaning_old_vars', 'cleaning_old_vars' );
add_action( 'wp_ajax_cleaning_old_vars', 'cleaning_old_vars' );
function cleaning_old_vars() {
	$result = '';
	$needless_childs = get_needless_childs();
	$key = ( $_POST['key'] ) ? $_POST['key'] : false;

	global $wpdb;
	$wpdb->show_errors( true );

	if ( $needless_childs && !$key ) {
		$result = '<h2 class="found_results_heading">Found ' . count( $needless_childs ) . ' old variations on your website.</h2>';
		$result .= '<span class="show_results">Show list<i></i></span>';
		$result .= '<ul class="needless_child_list">';
		foreach ( $needless_childs as $needless_child ) {
			if ( $needless_child['title'] ) {
				$sku = ( $needless_child['sku'] ) ? 'SKU <strong>"' . $needless_child['sku'] . '"</strong>' : 'empty SKU field';
				$result .= '<li>' . $needless_child['title'] . ' (with ' . $sku . ')</li>';
			}
		}
		$result .= '</ul>';
	} elseif ( $needless_childs && $key == 'clean' ) {
		$deleted = 0;
		$deleted_items = array();
		foreach ( $needless_childs as $post_id => $value ) {
			$sku = ( $value['sku'] ) ? 'SKU <strong>"' . $value['sku'] . '"</strong> of ' . $value['title'] : '<strong>Empty SKU field</strong> of ' . $value['title'];
			$del_sku = $wpdb->delete( $wpdb->postmeta, array( 'meta_key' => '_sku', 'post_id' => $post_id ) );
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

// Ajax search old vars
add_action( 'wp_ajax_nopriv_auto_change_cleaning', 'auto_change_cleaning' );
add_action( 'wp_ajax_auto_change_cleaning', 'auto_change_cleaning' );

function auto_change_cleaning() {
	$result = 'unique!';
	$needless_childs = get_needless_childs();
	$shanged_sku = ( $_POST['sku'] ) ? $_POST['sku'] : false;
	$same_sku_post = '';
	$auto_clean_option = get_option( 'alio_auto_clean' );

	global $wpdb;
	$wpdb->show_errors( true );

	if ( $needless_childs && is_array( $needless_childs ) ) {
		foreach ( $needless_childs as $key => $value ) {

			if( $value['sku'] == $shanged_sku ) {
				$same_sku_post = $key;
			}
		}
	}

	if ( $auto_clean_option != 'default' && $same_sku_post ) {

		if ( $auto_clean_option == 'auto_del_sku' ) {
			if( $wpdb->delete( $wpdb->postmeta, array( 'meta_key' => '_sku', 'post_id' => $same_sku_post ) ) ) {
				$result = 'unique!';
			} else {
				$result = 'sku not unique!';
			}
		}
		if ( $auto_clean_option == 'auto_del_fully' ) {
			if( wp_delete_post( $same_sku_post, true ) ) {
				$result = 'unique!';
			} else {
				$result = 'sku not unique!';
			}
		}
	} elseif ( $auto_clean_option == 'default' && $same_sku_post ) {
		$result = 'sku not unique! <a href="admin.php?page=sku_vars_cleaner_settings" target="_blank">settings</a>';
	}

	echo $result;

	wp_die();
}