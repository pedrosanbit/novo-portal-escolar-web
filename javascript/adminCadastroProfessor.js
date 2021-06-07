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
	if(darkSwitch.checked) {
		toggleDarkMode();
		localStorage.setItem("darkTheme", "on");
	}
	else {
		toggleDarkMode();
		localStorage.removeItem("darkTheme");
	}
}

function toggleDarkMode() {
	document.body.classList.toggle("bg-dark");
	document.body.classList.toggle("text-white");
	document.getElementById('navbar').classList.toggle("bg-dark");
	document.getElementById('lua').classList.toggle("fas");
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
	var selects = document.getElementsByTagName('select')
	for(var i=0; i < selects.length; i++) {
		selects[i].classList.toggle('border-white');
		selects[i].classList.toggle('bg-dark');
		selects[i].classList.toggle('text-white');
	}
	if(document.getElementById('tableConsulta'))
		document.getElementById('tableConsulta').classList.toggle("table-dark");
	var modals = document.getElementsByClassName('modal-content');
	for(var i=0; i < modals.length; i++)
		modals[i].classList.toggle("bg-dark");
}