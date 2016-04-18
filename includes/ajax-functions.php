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
		$cnt = 0;
		if ( is_array( $needless_childs ) ) {
			foreach ( $needless_childs as $value ) {
				$cnt = $cnt + count( $value );
			}
		}
		$result = '<h2 class="found_results_heading">Found ' . $cnt . ' old variations on your website.</h2>';
		$result .= '<span class="show_results">Show list<i></i></span>';
		$result .= '<ul class="needless_child_list">';
		foreach ( $needless_childs as $parent => $childs ) {
			foreach ( $childs as $child_id => $info ) {
				if ( $info['title'] ) {
					$sku = ( $info['sku'] ) ? 'SKU <strong>"' . $info['sku'] . '"</strong>' : 'empty SKU field';
					$result .= '<li>' . $info['title'] . ' (with ' . $sku . ')</li>';
				}
			}
		}
		$result .= '</ul>';
	} elseif ( $needless_childs && $key == 'clean' ) {
		$deleted = 0;
		$deleted_items = array();
		foreach ( $needless_childs as $parent_id => $child_arr ) {
			if ( is_array( $child_arr ) ) {
				foreach ( $child_arr as $post_id => $info ) {
					$sku = ( $info['sku'] ) ? 'SKU <strong>"' . $info['sku'] . '"</strong> of ' . $info['title'] : '<strong>Empty SKU field</strong> of ' . $info['title'];
					$del_sku = $wpdb->delete( $wpdb->postmeta, array( 'meta_key' => '_sku', 'post_id' => $post_id ) );
					if ( $del_sku == 1 ) {
						$deleted_items[] = $sku;
						$deleted++;
					} else {
						if ( ! $sku = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key='%s' AND post_id='%s' LIMIT 1", '_sku', $post_id ) ) ) {
							$deleted_items[] = '';
							$deleted--;
						}
					}
				}
			}
		}
		if ( $deleted == count( $deleted_items ) && $deleted != 0 ) {
			$result = '<h2 class="found_results_heading">' . $deleted . ' SKU fields have been successfully removed.</h2>';
			$result .= '<span class="show_results">Show list<i></i></span>';
			$result .= '<ul class="needless_child_list">';
			foreach ( $deleted_items as $deleted_item ) {
				if ( ! empty( $deleted_item ) ) {
					$result .= '<li>' . $deleted_item . '<span class="warning"> deleted</span></li>';
				}
				
			}
			$result .= '</ul>';
		} elseif ( $deleted == 0 ) {
			$result = '<h2 class="found_results_heading">SKU fields of old variations not found.</h2>';
		} elseif( $deleted == -(count( $deleted_items )) ) {
			$result = '<h2 class="found_results_heading">All the possible SKU fields were already deleted.</h2>';
		} else {
			$result = '<h2 class="found_results_heading">Error. Please try again.</h2>';
		}
	} elseif ( $needless_childs && $key == 'removal' ) {
		$deleted = 0;
		$deleted_items = array();
		foreach ( $needless_childs as $parent_id => $child_arr ) {
			if ( is_array( $child_arr ) ) {
				foreach ($child_arr as $post_id => $info) {
					$del = wp_delete_post( $post_id, true );
					if ( $del->ID == $post_id ) {
						$deleted++;
						$deleted_items[] = $info['title'];
					} else {
						$deleted--;
						$deleted_items[] = $info['title'];
					}
				}
			}
		}
		if ( $deleted <= count( $deleted_items ) && $deleted != -(count( $deleted_items )) && $deleted != 0 ) {
			$result = '<h2 class="found_results_heading">' . $deleted . ' Old variations have been successfully removed.</h2>';
			$result .= '<span class="show_results">Show list<i></i></span>';
			$result .= '<ul class="needless_child_list">';
			foreach ( $deleted_items as $deleted_item ) {
				$result .= '<li>' . $deleted_item . '<span class="warning"> deleted</span></li>';
			}
			$result .= '</ul>';
		} elseif ( $deleted == -(count( $deleted_items )) ) {
			$result = '<h2 class="found_results_heading">Old variations not found.</h2>';
		} else {
			$result = '<h2 class="found_results_heading">Error. Please try again.</h2>';
		}
	} else {
			$result = '<h2 class="found_results_heading">Old variations not found.</h2>';
	}

	echo $result;

	wp_die();
}

// Ajax change clean
add_action( 'wp_ajax_nopriv_auto_change_cleaning', 'auto_change_cleaning' );
add_action( 'wp_ajax_auto_change_cleaning', 'auto_change_cleaning' );

function auto_change_cleaning() {
	$result = '';
	$needless_childs = get_needless_childs();
	$post_ID = ( $_POST['postID'] ) ? $_POST['postID'] : false;
	$for_clean_childs = '';
	$auto_clean_option = get_option( 'alio_auto_clean' );
	$settings_link = '<a href="admin.php?page=sku_vars_cleaner_settings" class="settings_lnk"></a>';

	if ( $post_ID ) {
		global $wpdb;
		$wpdb->show_errors( true );

		
	}

	if ( $for_clean_childs && is_array( $for_clean_childs ) ) {
		if ( count( $for_clean_childs ) > 1 ) {
			$for_clean_childs = implode( ', ', $for_clean_childs );
		} else {
			$for_clean_childs = $for_clean_childs[0];
		}
	}

	if ( $auto_clean_option != 'default' && $for_clean_childs ) {

		if ( $auto_clean_option == 'auto_del_sku' ) {
			$result = 'SKU of variations ' . $for_clean_childs . ' will be cleared ' . $settings_link;
		}
		if ( $auto_clean_option == 'auto_del_fully' ) {
			$result = 'Variaions ' . $for_clean_childs . ' will be removed ' . $settings_link;
		}
	}

	echo $result;

	wp_die();
}