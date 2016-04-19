jQuery(document).ready(function($){

	// Ajax Search Variations
	$('.search_vars').on('click', function(event) {
		event.preventDefault();

		var $resultContainer = $('.search_result');
		var loaderImg = $(this).next('.loader_img');

		$.ajax({
			type: "POST",
			data: {
				action: 'cleaning_old_vars',
			},
			url: sku_vars_cleaner_ajaxUrl.url,
			beforeSend: function(){
				loaderImg.fadeIn();
			},
			success: function(data){
				loaderImg.fadeOut();
				$resultContainer.text('');
				if(data.length > 1){
					$resultContainer.html(data);
					$resultContainer.slideDown();
				}
			}
		});
	});

	$(document).on('click', '.show_results', function(event) {
		event.preventDefault();
		if ($(this).next('.needless_child_list').size() > 0) {
			$(this).next('.needless_child_list').slideToggle();
			$(this).toggleClass('open');
			if ($(this).hasClass('open')) {
				$(this).html('Hide list<i></i>');
			} else {
				$(this).html('Show list<i></i>');
			}
		}
	});


	// Ajax Clean SKU Variations
	$('.clean_sku').on('click', function(event) {
		event.preventDefault();

		var $resultContainer = $('.clean_result');
		var loaderImg = $(this).next('.loader_img');
		var key = 'clean';

		$.ajax({
			type: "POST",
			data: {
				action: 'cleaning_old_vars',
				key: key,

			},
			url: sku_vars_cleaner_ajaxUrl.url,
			beforeSend: function(){
				loaderImg.fadeIn();
			},
			success: function(data){
				loaderImg.fadeOut();
				$resultContainer.text('');
				if(data.length > 1){
					$resultContainer.html(data);
					$resultContainer.slideDown();
				}
			}
		});
	});

	// Ajax Removal Variations
	$('.removal_vars').on('click', function(event) {
		event.preventDefault();

		var $resultContainer = $('.removal_result');
		var loaderImg = $(this).next('.loader_img');
		var key = 'removal';

		$.ajax({
			type: "POST",
			data: {
				action: 'cleaning_old_vars',
				key: key,

			},
			url: sku_vars_cleaner_ajaxUrl.url,
			beforeSend: function(){
				loaderImg.fadeIn();
			},
			success: function(data){
				loaderImg.fadeOut();
				$resultContainer.text('');
				if(data.length > 1){
					$resultContainer.html(data);
					$resultContainer.slideDown();
				}
			}
		});
	});

	// Ajax on change SKU
	$( document.body ).on( 'change', 'input[name^="variable_sku"]', function( event ) {
		if ( $( '.auto_clean_result' ).size() > 0 ) {
			$( '.auto_clean_result' ).remove();
		}
		$( this ).prev( 'label' ).append( '<span class="auto_clean_result"></span>' );
		$resultContainer = $( '.auto_clean_result' );
		var changing_input = $( this );
		var sku = $( this ).val();
		var postID = $( 'input#post_ID' ).val();

		$.ajax({
			type: "POST",
			data: {
				action: 'auto_change_cleaning',
				sku: sku,
				postID: postID,
			},
			url: sku_vars_cleaner_ajaxUrl.url,
			beforeSend: function(){
				$resultContainer.html('<i>loading...</i>');
			},
			success: function(data){
				$resultContainer.text('');
				if(data.length > 1){
					$resultContainer.html(data);
				}
			}
		});
	});

	$( 'form#post' ).on( 'submit', function( e ) {
		e.preventDefault();
		console.log('test');
	} );


	// Ajax on change Product Type
/*	if ( $( '#product-type' ).size() > 0 && $( 'optgroup' ) ) {
		$( '#product-type' ).on('change', function(event) {
			if ( $(this).val != 'variable' ) {
				console.log($(this).val);
				if ( $('.auto_clean_result').size() > 0 ) {
					$('.auto_clean_result').remove();
				}
				$(this).parents( '.type_box' ).append( '<span class="auto_clean_result"></span>' );
				$resultContainer = $('.auto_clean_result');
				var changing_input = $(this);
				var postID = $( 'input#post_ID' ).val();

				$.ajax({
					type: "POST",
					data: {
						action: 'auto_change_cleaning',
						postID: postID,

					},
					url: sku_vars_cleaner_ajaxUrl.url,
					beforeSend: function(){
						$resultContainer.html('<i>loading...</i>');
					},
					success: function(data){
						$resultContainer.text( '' );
						if( data.length > 1 ){
							$resultContainer.html( data );
						}
					}
				});
			}
			
		});
	}*/
	

}); // jQuery end