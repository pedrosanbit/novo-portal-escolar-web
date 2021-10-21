var darkSwitch = document.getElementById("darkSwitch");
window.addEventListener("load", function () {
	if (darkSwitch)
    	iniciarDark();
});

function iniciarDark() {
	var darkThemeSelected = localStorage.getItem("darkTheme") !== null && localStorage.getItem("darkTheme") === "on";
	darkSwitch.checked = darkThemeSelected;
	if(darkThemeSelected)
		setTema();
}

function setTema() {
	document.getElementById("darkSwitch2").checked = darkSwitch.checked;
	if(darkSwitch.checked) {
		toggleDarkMode();
		localStorage.setItem("darkTheme", "on");
	}
	else {
		toggleDarkMode();
		localStorage.removeItem("darkTheme");
	}
}

function toggleDarkSwitch() {
	darkSwitch.checked = document.getElementById("darkSwitch2").checked;
	setTema();
}

function toggleDarkMode() {
	document.body.classList.toggle("bg-dark");
	document.body.classList.toggle("text-white");
	var navbars = document.getElementsByClassName("navbar");
	for(var i=0; i < navbars.length; i++) {
		navbars[i].classList.toggle("bg-dark");
	}
	document.getElementById('navbar2').classList.toggle("border-bottom");
	document.getElementById('navbar2').classList.toggle("border-light");
	document.getElementById('lua').classList.toggle("fas");
	document.getElementById('lua2').classList.toggle("fas");
	var tabs = document.getElementsByClassName('nav-link');
	for(var i=0; i < tabs.length; i++)
		tabs[i].classList.toggle('text-white');
	document.getElementById('nav-active').classList.toggle("bg-dark");
	document.getElementById('nav-active').classList.toggle("text-white");
	var inputs = document.getElementsByTagName('input')
	for(var i=0; i < inputs.length; i++) {
		inputs[i].classList.toggle('border-white');
		inputs[i].classList.toggle('bg-dark');
		inputs[i].classList.toggle('text-white');
	}
	var textareas = document.getElementsByTagName('textarea');
	for(var i=0; i < textareas.length; i++) {
		textareas[i].classList.toggle('border-white');
		textareas[i].classList.toggle('bg-dark');
		textareas[i].classList.toggle('text-white');
	}
	var checkboxes = document.getElementsByClassName('form-check-input');
	for(var i=0; i < checkboxes.length; i++) {
		checkboxes[i].classList.toggle('border-white');
		checkboxes[i].classList.toggle('bg-dark');
		checkboxes[i].classList.toggle('text-white');
	}
	var selects = document.getElementsByTagName('select')
	for(var i=0; i < selects.length; i++) {
		selects[i].classList.toggle('border-white');
		selects[i].classList.toggle('bg-dark');
		selects[i].classList.toggle('text-white');
	}
	var tabelas = document.getElementsByTagName('table')
	for(var i=0; i < tabelas.length; i++)
		tabelas[i].classList.toggle("table-dark");
	var modals = document.getElementsByClassName('modal-content');
	for(var i=0; i < modals.length; i++)
		modals[i].classList.toggle("bg-dark");
	var accordions = document.getElementsByClassName('accordion-body');
	for(var i=0; i < accordions.length; i++) {
		accordions[i].classList.toggle("bg-dark");
		accordions[i].classList.toggle("text-white");
	}
	var sbebs = document.getElementsByClassName('sbeb');
	for(var i=0; i < sbebs.length; i++) {
		sbebs[i].classList.toggle("border-dark");
	}
}