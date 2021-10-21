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
	document.getElementById('navbar').classList.toggle("bg-dark");
	document.getElementById('navbar').classList.toggle("border-bottom");
	document.getElementById('navbar').classList.toggle("border-light");
	document.getElementById('lua').classList.toggle("fas");
	document.getElementById('gradiente').classList.toggle("gradiente-dark");
	document.getElementById('texto-explicativo').classList.toggle("text-white");
	document.getElementById('rodape').classList.toggle("bg-dark");
	document.getElementById('rodape').classList.toggle("border-top");
	document.getElementById('rodape').classList.toggle("border-light");
	var modals = document.getElementsByClassName('modal-content');
	for(var i=0; i < modals.length; i++) {
		modals[i].classList.toggle("bg-dark");
		modals[i].classList.toggle("text-white");
	}
	document.getElementById('botaoModal').classList.toggle('text-white');
	document.getElementById('email').classList.toggle('border-white');
	document.getElementById('email').classList.toggle('bg-dark');
	document.getElementById('email').classList.toggle('text-white');

}