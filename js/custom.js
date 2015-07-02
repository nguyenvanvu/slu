$(document).ready(function(){
	if($(".hide-message").length > 0)
		setTimeout(function(){ $('.hide-message').fadeOut('slow'); }, 5000);

});

function autoHide(){
	if($(".hide-message").length > 0)
		setTimeout(function(){ $('.hide-message').fadeOut('slow'); }, 5000);
}

function showLoading()
{
    $('#ajax-load').show();
}

function hideLoading()
{
    $('#ajax-load').hide();
}
function showSuccessFlash(msg)
{
	$('.flash-success').html(msg);
	$('.flash-success').show();
	autoHide();
}
function showErrorFlash(msg)
{
	$('.flash-error').html(msg);
	$('.flash-error').show();
	autoHide();
}