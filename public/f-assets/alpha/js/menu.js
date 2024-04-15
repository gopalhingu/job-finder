document.addEventListener("DOMContentLoaded", function(){
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
});

document.addEventListener("DOMContentLoaded", function(){
	window.addEventListener('scroll', function() {
		changeMenuColor();
	});
});
changeMenuColor();

function changeMenuColor(){
	if ($('#home-page').length > 0) { //Only for home page
		if (window.scrollY > 50) {
			document.getElementById('navbar_main').classList.add('main-navbar-sticky');
		} else {
			document.getElementById('navbar_main').classList.remove('main-navbar-sticky');
		}
	} else {
		document.getElementById('navbar_main').classList.add('main-navbar-sticky');
	}
}
function darkenScreen(yesno){
	if (yesno == true) {
		document.querySelector('.screen-darken').classList.add('active');
	} else if (yesno == false){
		document.querySelector('.screen-darken').classList.remove('active');
	}
}
function closeOffCanvas(){
	darkenScreen(false);
	document.querySelector('.mobile-offcanvas.show').classList.remove('show');
	document.body.classList.remove('offcanvas-active');
}
function showOffCanvas(offcanvas_id){
	darkenScreen(true);
	document.getElementById(offcanvas_id).classList.add('show');
	document.getElementById(offcanvas_id).classList.add('mobile-menu-sidebar');
	document.body.classList.add('offcanvas-active');
}
