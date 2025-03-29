<?php

function register () {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $cpf = trim($_POST['cpf']);
        $password = $_POST['password'];
    
        if (create_user($name, $email, $cpf, $password)) {
            header("Location: index.php");
            exit();
        } else {
            echo "Erro ao cadastrar usuário.";
        }
    }
}

function deleteUserById () {
    if (isset($_GET['id'])) {
        $userId = intval($_GET['id']); 
    
        if (deleteUser($userId)) {
            header("Location: index.php"); 
            exit();
        } else {
            echo "Erro ao deletar o usuário.";
        }
    } else {
        echo "ID de usuário inválido.";
    }
}

?>
