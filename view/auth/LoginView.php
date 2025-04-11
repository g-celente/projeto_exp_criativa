<?php

// Inclui o arquivo "UserService.php" que contém funções relacionadas ao banco de dados
// login() chama authenticate() e register() em "UserService.php"
include('../../app/service/UserService.php');

$error = null;

if (isset($_POST['email']) || isset($_POST['senha'])) {  // Verifica se o email e a senha foram preenchidos, e o metodo de requisicao
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $result = login($email, $senha);        // Chama a função login definida em UserService.php para verificar as credenciais no banco de dados.

    if ($result === true) {                 // Verifica se o login foi bem-sucedido
        header("Location: ../Home.php");    // Redireciona para a página inicial
        exit();                            
    }

    $error = $result; //????
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="content first-content">
            <div class="first-column">
                <h2 class="title title-primary">Olá, amigo!</h2>
                <p class="description description-primary">Entre com seus dados pessoais</p>
                <p class="description description-primary">e comece sua jornada conosco</p>
                <a href="CadastroView.php" class="btn btn-primary">CADASTRAR</a>
            </div>
            <div class="second-column">
                <h2 class="title title-second">Acesse sua conta</h2>
                <div class="social-media">
                    <ul class="list-social-media">
                        <a class="link-social-media" href="#">
                            <li class="item-social-media">
                                <i class="fab fa-facebook-f"></i>
                            </li>
                        </a>
                        <a class="link-social-media" href="#">
                            <li class="item-social-media">
                                <i class="fab fa-google-plus-g"></i>
                            </li>
                        </a>
                        <a class="link-social-media" href="#">
                            <li class="item-social-media">
                                <i class="fab fa-linkedin-in"></i>
                            </li>
                        </a>
                    </ul>
                </div><!-- social media -->
                <p class="description description-second">ou use seu e-mail:</p>
                <form class="form" method="POST">
                    <!-- Exibição de erros, caso existam -->
                    <?php if (isset($error) && $error): ?>
                        <div class="alert-danger"><?= htmlspecialchars($error) ?></div> <!-- Exibe a mensagem de erro, se atentando a injecoes -->
                    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error): ?> <!-- Verifica se o método de requisição é POST e se não há erro -->
                        <div class="alert-success">
                            Cadastro realizado com sucesso! <a href="login.php">Clique aqui para fazer login</a> <!-- Link para fazer login após o cadastro -->
                        </div>
                    <?php endif; ?>

                    <label class="label-input" for="email">
                        <i class="far fa-envelope icon-modify"></i>
                        <input type="email" name="email" id="email" placeholder="E-mail" required>
                    </label>

                    <label class="label-input" for="senha">
                        <i class="fas fa-lock icon-modify"></i>
                        <input type="password" name="senha" id="senha" placeholder="Senha" required>
                    </label>

                    <a class="password" href="#">Esqueceu sua senha?</a>
                    <button type="submit" class="btn btn-second">Entrar</button>
                    <!-- Como o atributo action não está especificado no <form>, 
                    o formulário será enviado para o mesmo arquivo em que está sendo exibido, ou seja, LoginView.php. -->
                </form>
            </div><!-- second column -->
        </div><!-- first content -->
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {}); // ??????????
    </script>
</body>

</html>


<style>
    @import url('https://fonts.googleapis.com/css?family=Open+Sans:300,400,700&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Open Sans', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #ecf0f1;
    }

    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        width: 100%;
        background-color: #ecf0f1;
    }

    .content {
        background-color: #fff;
        border-radius: 15px;
        width: 100%;
        max-width: 1000px;
        height: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        padding: 20px;
    }

    .content::before {
        content: "";
        position: absolute;
        background-color: #58af9b;
        width: 40%;
        height: 100%;
        border-top-left-radius: 15px;
        border-bottom-left-radius: 15px;
        left: 0;
    }

    .title {
        font-size: 28px;
        font-weight: bold;
        text-transform: capitalize;
    }

    .title-primary {
        color: #fff;
    }

    .title-second {
        color: #58af9b;
    }

    .description {
        font-size: 14px;
        font-weight: 300;
        line-height: 30px;
    }

    .description-primary {
        color: #fff;
    }

    .description-second {
        color: #7f8c8d;
    }

    .btn {
        border-radius: 15px;
        text-transform: uppercase;
        color: #fff;
        font-size: 10px;
        padding: 10px 50px;
        cursor: pointer;
        font-weight: bold;
        width: 150px;
        align-self: center;
        border: none;
        margin-top: 1rem;
    }

    .btn-primary {
        background-color: transparent;
        border: 1px solid #fff;
        transition: background-color .5s;
    }

    .btn-primary:hover {
        background-color: #fff;
        color: #58af9b;
    }

    .btn-second {
        background-color: #58af9b;
        border: 1px solid #58af9b;
        transition: background-color .5s;
    }

    .btn-second:hover {
        background-color: #fff;
        border: 1px solid #58af9b;
        color: #58af9b;
    }

    .first-content {
        display: flex;
    }

    .first-content .second-column {
        z-index: 11;
    }

    .first-column {
        text-align: center;
        width: 40%;
        z-index: 10;
    }

    .first-column a {
        margin-top: 15px;
    }

    .second-column {
        width: 60%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .social-media {
        margin: 1rem 0;
    }

    .link-social-media:not(:first-child) {
        margin-left: 10px;
    }

    .link-social-media .item-social-media {
        transition: background-color .5s;
    }

    .link-social-media:hover .item-social-media {
        background-color: #58af9b;
        color: #fff;
        border-color: #58af9b;
    }

    .list-social-media {
        display: flex;
        list-style-type: none;
    }

    .item-social-media {
        border: 1px solid #bdc3c7;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        line-height: 35px;
        text-align: center;
        color: #95a5a6;
    }

    .form {
        display: flex;
        flex-direction: column;
        width: 55%;
    }

    .form input {
        height: 45px;
        width: 100%;
        border: none;
        background-color: #ecf0f1;
    }

    input:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px #ecf0f1 inset !important;
        -webkit-text-fill-color: #000 !important;
    }

    .label-input {
        background-color: #ecf0f1;
        display: flex;
        align-items: center;
        margin: 8px;
    }

    .icon-modify {
        color: #7f8c8d;
        padding: 0 5px;
    }

    .password {
        color: #34495e;
        font-size: 14px;
        margin: 15px 0;
        text-align: center;
    }

    .password::first-letter {
        text-transform: capitalize;
    }

    /* Error message styling */
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 4px;
        text-align: center;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 4px;
        text-align: center;
    }

    .alert-success a {
        color: #0b2e13;
        font-weight: bold;
        text-decoration: underline;
    }
</style>