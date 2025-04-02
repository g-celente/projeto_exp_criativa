<?php
include __DIR__.'/../model/User.php';

function login($email, $senha) {
    if (empty($email)) {
        return "Preencha seu e-mail.";
    }
    if (empty($senha)) {
        return "Preencha sua senha.";
    }

    $user = authenticate($email, $senha);

    if (is_array($user)) {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        return true;
    }

    return $user; // Retorna a mensagem de erro específica
}


function register($name, $email, $cpf, $password) {
    $result = create_user($name, $email, $cpf, $password);

    if ($result === true) {
        header("Location: LoginView.php");
        exit();
    } else {
        return $result;
    }
}

function deleteUserById($userId) {
    if (deleteUser($userId)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Erro ao deletar o usuário.";
    }
}
