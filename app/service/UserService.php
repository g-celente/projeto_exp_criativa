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

function editUserById() {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
        $userId = intval($_POST['id']);
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $cpf = trim($_POST['cpf']);
        $password = $_POST['password'];

        if (edit_user($userId, $name, $email, $cpf, $password)) {
            header("Location: index.php");
            exit();
        } else {
            echo "Erro ao editar usuário.";
        }
    } else {
        echo "Dados inválidos para edição.";
    }
}

function getUserById($userId) {
    global $conn;

    $query = "SELECT * FROM users WHERE id = $1";
    $result = pg_prepare($conn, "get_user", $query);

    if (!$result) {
        die("Erro ao preparar consulta: " . pg_last_error($conn));
    }

    $result = pg_execute($conn, "get_user", [$userId]);

    if ($row = pg_fetch_assoc($result)) {
        return $row;
    } else {
        return null;
    }
}


?>
