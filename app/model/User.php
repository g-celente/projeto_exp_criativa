<?php

include __DIR__ . '/../../database/db.php';

$conn = create_connection();

function selectUser() {
    global $conn;
    
    $query = "SELECT * FROM users";
    $resultado = pg_query($conn, $query);

    if (!$resultado) {
        die("Erro ao executar consulta: " . pg_last_error($conn));
    }

    return pg_fetch_all($resultado);
}

function create_user($name, $email, $cpf, $password) {
    global $conn; 

    //$hashed_password = md5($password); 

    $query = "INSERT INTO users (name, email, cpf, password, type_id) VALUES ($1, $2, $3, $4, 1)";
    $result = pg_prepare($conn, "insert_user", $query);

    if (!$result) {
        die("Erro ao preparar a consulta: " . pg_last_error($conn));
    }

    $result = pg_execute($conn, "insert_user", [$name, $email, $cpf, $password]);

    if ($result) {
        return true;
    } else {
        return false;
    }
}

?>
