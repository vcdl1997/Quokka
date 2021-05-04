<?php 
	$dots = array_key_exists("url", $_GET) ? explode("/", $_GET['url']) : ['dashboard'];
	switch(count($dots)){
		case 0: case 1: $dots = ''; break;
		case 2: $dots = '../'; break;
		case 3: $dots = '../../'; break;
		default: $dots = '../../../'; break;
	}
?>
<html lang="pt-BR" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?= $dots; ?>public/css/login.css">
        <link href="https://fonts.googleapis.com/css?family=Comfortaa" rel="stylesheet">
        <link href="<?= $dots; ?>public/css/toastr.min.css" rel="stylesheet">
        <title>Autenticação</title>
    </head>
    <body>
        <section class="login">

            <img src="<?= $dots; ?>public/img/user.png" class="usuario" width="100" height="100" alt="">

            <h1>Login</h1>

            <form action="/logar" id="form-login">

                <div>
                    <label>Usuário: </label>
                    <input type="text" name="username" placeholder="Insira seu nome de usuário" autocomplete="off" required>
                </div>

                <div>
                    <label>Senha: </label>
                    <input type="password" name="password" placeholder="Insira sua senha" autocomplete="off" required>
                </div>

                <input type="submit" name="" value="Entrar">
                <a href="">Esqueceu sua senha?</a><br>
                <a href="">Ainda não possui uma conta</a>
            </form>
        </section>
        <script src="<?= $dots; ?>public/js/jquery.min.js"></script>
        <script src="<?= $dots; ?>public/js/toastr.min.js"></script>
        <script src="<?= $dots; ?>public/js/login.js"></script>
    </body>
</html>