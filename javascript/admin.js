var darkSwitch = document.getElementById("darkSwitch");
var homeTab = document.getElementById('nav-home-tab');
var cursosTab = document.getElementById('nav-curso-tab');
var turmasTab = document.getElementById('nav-turmas-tab');
var disciplinasTab = document.getElementById('nav-disciplina-tab');
var professoresTab = document.getElementById('nav-professor-tab');
var alunosTab = document.getElementById('nav-aluno-tab');
var activeTab;
window.addEventListener("load", function () {
	startActiveTab();
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
	homeTab.classList.toggle("text-white");
	cursosTab.classList.toggle("text-white");
	turmasTab.classList.toggle("text-white");
	disciplinasTab.classList.toggle("text-white");
	professoresTab.classList.toggle("text-white");
	alunosTab.classList.toggle("text-white");
	activeTab.classList.toggle("bg-dark");
	activeTab.classList.toggle("text-white");
	document.getElementById('nomeAluno').classList.toggle("border-white");
	document.getElementById('nomeAluno').classList.toggle("bg-dark");
	document.getElementById('nomeAluno').classList.toggle("text-white");
	document.getElementById('raAluno').classList.toggle("border-white");
	document.getElementById('raAluno').classList.toggle("bg-dark");
	document.getElementById('raAluno').classList.toggle("text-white");
	document.getElementById('turmaAluno').classList.toggle("border-white");
	document.getElementById('turmaAluno').classList.toggle("bg-dark");
	document.getElementById('turmaAluno').classList.toggle("text-white");
	document.getElementById('ordemConsulta').classList.toggle("border-white");
	document.getElementById('ordemConsulta').classList.toggle("bg-dark");
	document.getElementById('ordemConsulta').classList.toggle("text-white");
	if(document.getElementById('tableConsulta'))
		document.getElementById('tableConsulta').classList.toggle("table-dark");
	var modals = document.getElementsByClassName('modal-content');
	for(var i=0; i < modals.length; i++)
		modals[i].classList.toggle("bg-dark");
	//document.getElementById('modalContentExcluirAluno').classList.toggle("bg-dark");
}

function startActiveTab() {
	activeTab = document.querySelector(".active");
	if(localStorage.getItem("activeTab") !== null) {
		activeTab.classList.toggle("active");
		activeTab.setAttribute("aria-selected", "false");
		toggleActiveContent();
		activeTab = document.getElementById(localStorage.getItem("activeTab"));
		activeTab.classList.toggle("active");
		activeTab.setAttribute("aria-selected", "true");
		toggleActiveContent();
	}
	activeTab.classList.toggle("text-dark");
	activeTab.classList.toggle("text-primary");
	activeTab.style.fontWeight = "bold";
}

function toggleActiveTab() {
	if(darkSwitch.checked)
		toggleDarkMode();
	if(activeTab == homeTab || activeTab == cursosTab || activeTab == disciplinasTab || activeTab == turmasTab || activeTab == professoresTab || activeTab == alunosTab) {
		activeTab.classList.toggle("text-dark");	
		activeTab.classList.toggle("text-primary");
		activeTab.style.fontWeight = "normal";
	}
	localStorage.removeItem("activeTab");
	activeTab = document.querySelector(".active");
	activeTab.classList.toggle("text-dark");	
	activeTab.classList.toggle("text-primary");
	activeTab.style.fontWeight = "bold";
	localStorage.setItem("activeTab", activeTab.getAttribute("id"));
	if(darkSwitch.checked)
		toggleDarkMode();
}

function toggleActiveContent() {
	if(activeTab == homeTab) {
		document.getElementById("nav-inicio").classList.toggle("show");
		document.getElementById("nav-inicio").classList.toggle("active");
	}
	else if(activeTab == cursosTab) {
		document.getElementById("nav-cursos").classList.toggle("show");
		document.getElementById("nav-cursos").classList.toggle("active");
	}
	else if(activeTab == disciplinasTab) {
		document.getElementById("nav-disciplinas").classList.toggle("show");
		document.getElementById("nav-disciplinas").classList.toggle("active");
	}
	else if(activeTab == turmasTab) {
		document.getElementById("nav-turmas").classList.toggle("show");
		document.getElementById("nav-turmas").classList.toggle("active");
	}
	else if(activeTab == professoresTab) {
		document.getElementById("nav-professores").classList.toggle("show");
		document.getElementById("nav-professores").classList.toggle("active");
	}
	else if(activeTab == alunosTab) {
		document.getElementById("nav-alunos").classList.toggle("show");
		document.getElementById("nav-alunos").classList.toggle("active");
	}
}