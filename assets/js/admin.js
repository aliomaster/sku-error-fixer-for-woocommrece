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

}); // jQuery end