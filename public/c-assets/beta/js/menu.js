function initMenu() {

	document.querySelectorAll('[data-trigger]').forEach(function(everyelement){
		let offcanvas_id = everyelement.getAttribute('data-trigger');
		everyelement.addEventListener('click', function (e) {
			e.preventDefault();
        	showOffCanvas(offcanvas_id);
		});
	});
	document.querySelectorAll('.btn-close').forEach(function(everybutton){
		everybutton.addEventListener('click', function (e) {
			e.preventDefault();
        	closeOffCanvas();
  		});
	});
	document.querySelector('.screen-darken').addEventListener('click', function(event){
		closeOffCanvas();
	});
	window.addEventListener('scroll', function() {
		changeMenuColor();
	});

	changeMenuColor();
}

document.addEventListener("DOMContentLoaded", function(){
	var mobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
	if (mobile === false) {
		window.addEventListener('scroll', function() {
			if (window.scrollY > 50) {
				document.getElementById('navbar-main').classList.add('fixed-top');
				document.getElementById('navbar-main').classList.add("slide-down");
				document.getElementById('navbar-main').classList.add("simple-shadow");
				document.getElementById('navbar-main').classList.add("navbar-main-sticky");
				navbar_height = document.querySelector('.navbar').offsetHeight;
				document.body.style.paddingTop = navbar_height + 'px';
			} else {
				document.getElementById('navbar-main').classList.remove('fixed-top');
				document.getElementById('navbar-main').classList.remove("slide-down");
				document.getElementById('navbar-main').classList.remove("simple-shadow");
				document.getElementById('navbar-main').classList.remove("navbar-main-sticky");
				document.body.style.paddingTop = '0';
			}
		});
	}
}); 

initMenu();

function changeMenuColor() {
	"use strict";
	if ($('#home-page').length > 0) { //Only for home page
		if (window.scrollY > 50) {
			document.getElementById('navbar-main').classList.add('main-navbar-sticky');
		} else {
			document.getElementById('navbar-main').classList.remove('main-navbar-sticky');
		}
	}
}

function darkenScreen(yesno) {
	"use strict";
	if (yesno == true) {
		document.querySelector('.screen-darken').classList.add('active');
	} else if (yesno == false){
		document.querySelector('.screen-darken').classList.remove('active');
	}
}

function closeOffCanvas() {
	"use strict";
	darkenScreen(false);
	document.querySelector('.mobile-offcanvas.show').classList.remove('show');
	document.body.classList.remove('offcanvas-active');
}

function showOffCanvas(offcanvas_id){
	"use strict";
	darkenScreen(true);
	document.getElementById(offcanvas_id).classList.add('show');
	document.getElementById(offcanvas_id).classList.add('mobile-menu-sidebar');
	document.body.classList.add('offcanvas-active');
}
