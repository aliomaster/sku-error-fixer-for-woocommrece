<?php

// Ajax search old vars
add_action( 'wp_ajax_nopriv_search_old_vars', 'search_old_vars' );
add_action( 'wp_ajax_search_old_vars', 'search_old_vars' );
function search_old_vars() {
	 $res = 'results here';

	 if ( $_POST['catName'] ) {
		$catName = $_POST['catName'];

		if ( $catName == 'bank_deposits_and_notice_accounts' ) {
			get_template_part( 'template-parts/cat_bank_deposits' );
		} elseif( $catName == 'retirement' ){
			get_template_part( 'template-parts/cat_pension_provident' );
		}

		echo $res;
	}

	wp_die();
}
