$(document).ready(function () {
	$('#basic-modal input.login, #basic-modal a.login').click(function (e) {
		e.preventDefault();
		$('#basic-modal-content').modal();
	});
	/*
	$('#basic-modal a.reg').click(function (e) {
		e.preventDefault();
		$('#basic-modal-content-reg').modal();
	});*/
});