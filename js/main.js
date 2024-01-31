(function ($) {
	"use strict"

	// Preloader
	$(window).on('load', function () {
		$("#preloader").delay(600).fadeOut();
	});

	// Mobile Toggle Btn
	$('.navbar-toggle').on('click', function () {
		$('#header').toggleClass('nav-collapse');
	});

})(jQuery);
function mobile_toggle_btn(){
	// Chọn phần tử .navbar-toggle
	var navbarToggle = document.querySelector('.navbar-toggle');

	// Chọn phần tử #header
	var header = document.getElementById('header');

	// Kiểm tra xem #header đã có lớp 'nav-collapse' chưa
	if (header.classList.contains('nav-collapse')) {
		// Nếu có, loại bỏ lớp 'nav-collapse'
		header.classList.remove('nav-collapse');
	} else {
		// Nếu không, thêm lớp 'nav-collapse'
		header.classList.add('nav-collapse');
	}
}