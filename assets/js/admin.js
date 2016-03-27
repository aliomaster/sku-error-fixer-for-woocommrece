jQuery(document).ready(function($){

	// Ajax Search Variations
	$('search_vars').on('click', function(event) {
		event.preventDefault();

		//var catName = $(this).val();
		var $resultContainer = $('.search_result');

		$.ajax({
			type: "POST",
			data: {
				action: 'search_old_vars',
				//catName: catName
			},
			url: sku_vars_cleaner_ajaxUrl.url,
			beforeSend: function(){
				$resultContainer.text('Loading...');
			},
			success: function(data){
				$resultContainer.text('');

				if(data.length > 1){
					$resultContainer.html(data);
				}
			}
		});
	});
}); // jQuery end