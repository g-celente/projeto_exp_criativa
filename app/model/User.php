<?php

include __DIR__ . '/../../database/db.php';

$conn = create_connection();

function selectUser() {
    global $conn;
    
    $query = "SELECT * FROM users";
    $resultado = $conn->query($query);
    
    return $resultado;
}

function create_user($name, $email, $cpf, $password) {
    global $conn; 

    $hashed_password = md5($password); // Criptografia da senha

    $query = "INSERT INTO users (name, email, cpf, password, type_id) VALUES (?, ?, ?, ?, 1)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $name, $email, $cpf, $hashed_password);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}