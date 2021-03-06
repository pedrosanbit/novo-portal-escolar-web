<?php
    if (empty($_GET["login"]) && empty($_GET["msg"])) { 
        $msg = "";
    }
    else if ($_GET["login"]=="logado") { 
        if($_GET["tipo"]=="admin"){
            header("location:admin/admin.php");
        }
        else if($_GET["tipo"]=="prof"){
            header("location:professor/professor.php");
        }
        else if($_GET["tipo"]=="aluno"){
            header("location:aluno/aluno.php");
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
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
						O Portal Escolar do Col??gio T??cnico de Limeira ?? um projeto desenvolvido por alunos do curso de inform??tica para que estudantes, professores e outros funcion??rios da escola possam acompanhar o ano letivo e gerenciar seu saldo do Cart??o de Identidade Institucional da UNICAMP.
					</div>
					<div class="col-md-6 col-sm-12 mt-4 mb-5">
						<div class="text-dark bg-white border border-3 border-dark rounded-3 p-4 align-self-center w-75 mx-auto" id="form-login">
							<h2>Login</h2><br>
							<form method="get" action="validaLogin.php">
  								<div class="mb-3">
    								<label for="campoUser" class="form-label">Usu??rio</label>
    								<input class="form-control" type="text" id="campoUser" name="usuario">
  								</div>
  								<div class="mb-3">
    								<label for="inputSenha" class="form-label">Senha</label>
    								<input type="password" class="form-control" id="inputSenha" name="senha">
    								<div id="esqueceuSenha" class="form-text"><a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">Esqueceu sua senha?</a></div>
 								</div>
  								<div class="mb-3 form-check">
    								<input name="ManterUser" type="checkbox" class="form-check-input" id="checkLembrar">
    								<label class="form-check-label" for="checkLembrar">Lembrar usu??rio</label>
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
					?? poss??vel visualizar matr??culas, boletins, di??rios de classe, hor??rios de aula e planos de ensino de acordo com a data, disciplina e etapa de sua escolha. Assim como pode-se obter, em arquivo de texto, materiais que podem ser consultados no site.
				</div>
				<div class="col-md-4 col-sm-12">
					<div style="font-size: 5rem"><i class="fas fa-chalkboard-teacher"></i></div>
					Professores podem manipular a plataforma de forma r??pida e intuitiva. Al??m das pr??prias consultas, esses usu??rios podem lan??ar informa????o acad??mica e gerir esse conte??do com poucos cliques. Por fim, fica ?? escolha dos profissionais quais atividades ir??o compor a avalia????o, e seus respectivos pesos.
				</div>
				<div class="col-md-4 col-sm-12">
					<div style="font-size: 5rem"><i class="fas fa-money-bill-wave"></i></div>
					O usu??rio pode adicionar cr??dito ao seu Cart??o de Identidade Institucional direto do Portal, por meio de transa????es on-line, e utiliza-lo nas depend??ncias do col??gio.
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
							<b>Localiza????o</b><br>
							<i class="fas fa-map-marker-alt"></i> Rua Paschoal Marmo, 1888 ??? Jardim Nova It??lia ??? Limeira/SP ??? CEP: 13484-332
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  			<div class="modal-dialog">
    			<div class="modal-content">
      				<div class="modal-header">
        				<h5 class="modal-title" id="exampleModalLabel">Redefini????o de Senha</h5>
        				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      				</div>
      				<form method="post" action="esqueciMinhaSenha.php">
      				<div class="modal-body">
        				<label for="email" class="form-label">Email:</label>
                        <input class="form-control mb-3" type="email" id="email" name="email">
                        <div class="g-recaptcha" data-sitekey="6LczkOUcAAAAADNSILk63GGB3EzxdG9A1UpS_a72"></div>
      				</div>
      				<div class="modal-footer">
        				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        				<button type="submit" class="btn btn-primary" id="botaoModal">Enviar</button>
        			</form>
      				</div>
    			</div>
  			</div>
		</div>
		<script type="text/javascript" src="javascript/index.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
	</body>
</html>