jQuery(document).ready(function($){

	// Ajax Search Variations
	$('.search_vars').on('click', function(event) {
		event.preventDefault();
console.log('test');
		var $resultContainer = $('.search_result');

		$.ajax({
			type: "POST",
			data: {
				action: 'search_old_vars',
			},
			url: sku_vars_cleaner_ajaxUrl.url,
			beforeSend: function(){
				$('.loader_img').fadeIn();
			},
			success: function(data){
				$('.loader_img').fadeOut();
				$resultContainer.text('');

				if(data.length > 1){
					$resultContainer.html(data);
				}
			}
		});
	});
}); // jQuery end