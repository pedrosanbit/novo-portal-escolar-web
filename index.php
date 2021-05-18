<?php
    if (empty($_GET["login"]) && empty($_GET["msg"])) { 
        $msg = "";
    }
    else if ($_GET["login"]=="logado") { 
        if($_GET["tipo"]=="admin"){
            header("location:admin.php");
        }
        else if($_GET["tipo"]=="prof"){
            header("location:sucesso.html");
        }
        else if($_GET["tipo"]=="estudante"){
            header("location:sucesso.html");
        }
        $msg = "";
    }
    else {
        $msg = $_GET["msg"];
    }
?>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=0.5, maximum-scale=3, shrink-to-fit=no">
		<title>Portal Escolar</title>
		<link rel="icon" href="logoUnicampAzul.png">
		<!--link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous"-->
		<link rel="stylesheet" href="custom.css">
		<style type="text/css">
			.gradiente{
				background-image: linear-gradient(#0F74AC, white);
			}

			.gradiente-dark{
				background-image: linear-gradient(#0F74AC, #212529);
			}
		</style>
		<script src="https://kit.fontawesome.com/ebb5206ba7.js" crossorigin="anonymous"></script>
	</head>
	<body>
		<nav class="navbar navbar-dark bg-primary" id="navbar">
  			<div class="container-fluid">
    			<a class="navbar-brand" href="#">
    				<img src="logoUnicamp.png" width="32" class="d-inline-block align-text-top">
    				Portal Escolar
    			</a>
	    		<form class="d-flex text-white">
	      			<div class="form-check form-switch">
	  					<input class="form-check-input" type="checkbox" id="darkSwitch" onchange="setTema();">
	  					<i id='lua' class="far fa-moon"></i>
					</div>
	    		</form>
  			</div>
		</nav>
		<div class="container-fluid gradiente" id="gradiente">
			<div class="container text-white ">
				<div class="row">
					<div class="col-md-6 col-sm-12 mt-1 mb-2 align-self-center">
						<h1>Bem-vindo!</h1>
						O Portal Escolar do Colégio Técnico de Limeira é um projeto desenvolvido por alunos do curso de informática para que estudantes, professores e outros funcionários da escola possam acompanhar o ano letivo e gerenciar seu saldo do Cartão de Identidade Institucional da UNICAMP.
					</div>
					<div class="col-md-6 col-sm-12 mt-4 mb-5">
						<div class="text-dark bg-white border border-3 border-dark rounded-3 p-4 align-self-center w-75 mx-auto" id="form-login">
							<h2>Login</h2><br>
							<form method="get" action="validaLogin.php">
  								<div class="mb-3">
    								<label for="campoUser" class="form-label">Usuário</label>
    								<input class="form-control" type="text" id="campoUser" name="usuario">
  								</div>
  								<div class="mb-3">
    								<label for="inputSenha" class="form-label">Senha</label>
    								<input type="password" class="form-control" id="inputSenha" name="senha">
    								<div id="esqueceuSenha" class="form-text"><a href="#">Esqueceu sua senha?</a></div>
 								</div>
  								<div class="mb-3 form-check">
    								<input name="ManterUser" type="checkbox" class="form-check-input" id="checkLembrar">
    								<label class="form-check-label" for="checkLembrar">Lembrar usuário</label>
  								</div>
  								<div class="text-center">
  									<button type="submit" class="btn btn-primary rounded-pill text-white w-50">Entrar</button>
  								</div>
  								<?php
            						echo "<div id='msg'>".$msg."</div>";
        						?>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container mb-5" id="texto-explicativo">
			<div class="row text-center">
				<div class="col-md-4 col-sm-12">
					<div style="font-size: 5rem"><i class="far fa-clipboard"></i></div>
					É possível visualizar matrículas, boletins, diários de classe, horários de aula e planos de ensino de acordo com a data, disciplina e etapa de sua escolha. Assim como pode-se obter, em arquivo de texto, materiais que podem ser consultados no site.
				</div>
				<div class="col-md-4 col-sm-12">
					<div style="font-size: 5rem"><i class="fas fa-chalkboard-teacher"></i></div>
					Professores podem manipular a plataforma de forma rápida e intuitiva. Além das próprias consultas, esses usuários podem lançar informação acadêmica e gerir esse conteúdo com poucos cliques. Por fim, fica à escolha dos profissionais quais atividades irão compor a avaliação, e seus respectivos pesos.
				</div>
				<div class="col-md-4 col-sm-12">
					<div style="font-size: 5rem"><i class="fas fa-money-bill-wave"></i></div>
					O usuário pode adicionar crédito ao seu Cartão de Identidade Institucional direto do Portal, por meio de transações on-line, e utiliza-lo nas dependências do colégio.
				</div>
			</div>
		</div>
		<div class="container-fluid bg-primary pt-4" id="rodape">
			<div class="container text-white">
				<div class="row">
					<div class="col-md-6 col-sm-12 mb-4">
						<div class="row">
							<div class="col-md-6">
								<b>Contato</b>
								<br>
								<i class="fas fa-at"></i><span> </span><a href="https://www.cotil.unicamp.br/" class="text-white" style="text-decoration: none;">cotil.unicamp.br</a><br>
								<i class="fas fa-phone"></i> (19) 2113-3303
							</div>
							<div class="col-md-6">
								<br>
								<i class="fab fa-instagram"></i><span> </span><a href="https://www.instagram.com/unicamp.cotil/" class="text-white" style="text-decoration: none;">@unicamp.cotil</a><br>
								<i class="fab fa-youtube"></i><span> </span><a href="https://www.youtube.com/c/UNICAMPCOTIL" class="text-white" style="text-decoration: none;">/UNICAMPCOTIL</a><br>
								<i class="fab fa-facebook-f"></i><span> </span><a href="https://www.facebook.com/unicamp.cotil" class="text-white" style="text-decoration: none;">/unicamp.cotil</a><br>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-sm-12 mb-4">
						<div class="w-75">
							<b>Localização</b><br>
							<i class="fas fa-map-marker-alt"></i> Rua Paschoal Marmo, 1888 – Jardim Nova Itália – Limeira/SP – CEP: 13484-332
						</div>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="js/index.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
	</body>
</html>