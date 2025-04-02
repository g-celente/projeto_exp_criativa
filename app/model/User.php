<?php
include __DIR__ . '/../../database/db.php';

function authenticate($email, $senha) {
    $conn = create_connection();
    
    if (!$conn) {
        return "Erro ao conectar ao banco de dados.";
    }
    
    $email = pg_escape_string($conn, $email);
    $senha = pg_escape_string($conn, $senha);

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = pg_query($conn, $query);

    if (!$result) {
        return "Erro ao executar a consulta ao banco de dados.";
    }

    $user = pg_fetch_assoc($result);
    if ($user) {
        if ($user['password'] === $senha) {
            return $user;
        } else {
            return "Senha incorreta.";
        }
    } else {
        return "E-mail não encontrado.";
    }
}


function create_user($name, $email, $cpf, $password) {
    $conn = create_connection();

    $name = pg_escape_string($conn, $name);
    $email = pg_escape_string($conn, $email);
    $cpf = pg_escape_string($conn, $cpf);
    $password = pg_escape_string($conn, $password);

    $check_query = "SELECT id FROM users WHERE email = '$email' OR cpf = '$cpf'";
    $check_result = pg_query($conn, $check_query);

    if (pg_num_rows($check_result) > 0) {
        return "Usuário já existe com este email ou CPF.";
    }

    $query = "INSERT INTO users (name, email, cpf, password) VALUES ('$name', '$email', '$cpf', '$password')";
    $result = pg_query($conn, $query);

    return $result ? true : false;
}

function deleteUser($userId) {
    global $mysqli;

    $sql_code = "DELETE FROM users WHERE id = $userId";
    if ($mysqli->query($sql_code)) {
        return true;
    }
    return false;
}
