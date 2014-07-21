jQuery(function($){
	
	$('.diaryState').live('click', function(){
		var params = $(this).attr('id').split('-');
		$.get(PATH+'ajax/diaries/status/'+params[1]+'/'+params[0]+'/'+params[2], function(html){
			if(html == 'ok'){
				$.get(document.location, function(html){
					$('#detail'+params[0]).slideUp(500, function(){
						$(this).empty().html($(html).find('#detail'+params[0]).html()).slideDown();
						$('#percent').empty().html($(html).find('#percent'));
					});
				});
			}
		});
		return false;
	});

	$('.autoScroll').live('click', function(){
		$.scrollTo($(this).attr('href'), 500);
		return false;
	});

});