$('[data-toggle=popover]').popover();

function closeSystemMessage(key) {
	$.post('/site/close-system-message',{key: key}).success(function(data) {
		console.log(data);
	});
	$('#w-sysmsg-'+key).css('display', 'none');
	if ($($('[data-sysmsg-id='+key+']').parent()[1]).hasClass('alert-warning')) {
		$($('[data-sysmsg-id='+key+']').parent()[1]).css('display', 'none');
	}
}