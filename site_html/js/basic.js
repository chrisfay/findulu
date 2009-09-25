$(document).ready(function () {
	$('#basic-modal input.basic, #basic-modal a.basic').click(function (e) {
		e.preventDefault();
		$('#basic-modal-content').modal();
	});
	/*
	$('#basic-modal a.reg').click(function (e) {
		e.preventDefault();
		$('#basic-modal-content-reg').modal();
	});*/
});