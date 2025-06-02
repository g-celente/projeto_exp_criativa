<?php

// Inclui o arquivo "db.php" que contém a função create_connection() para conectar ao banco de dados
include __DIR__ . '/../../database/db.php';

function authenticate($email, $senha) {
    $conn = create_connection(); // Chama a função create_connection() para estabelecer uma conexão com o banco de dados.
    
    if (!$conn) {
        return "Erro ao conectar ao banco de dados.";
    }
    
    $email = pg_escape_string($conn, $email); // Evita injeção de SQL.
    $senha = pg_escape_string($conn, $senha);

    $query = "SELECT * FROM users WHERE email = '$email'"; // Consulta SQL para buscar o usuário pelo email.
    $result = pg_query($conn, $query); // Executa a consulta.

    if (!$result) {
        return "Erro ao executar a consulta ao banco de dados.";
    }

    $user = pg_fetch_assoc($result); //Obtem os dados do usuário como um array associativo.
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

    $check_query = "SELECT id FROM users WHERE email = '$email' OR cpf = '$cpf'"; // Verifica se o email ou CPF já existe na tabela de usuários.
    $check_result = pg_query($conn, $check_query); //Envia a consulta ao banco de dados e espera pelo return

    if (pg_num_rows($check_result) > 0) {
        return "Usuário já existe com este email ou CPF.";
    }

    $query = "INSERT INTO users (name, email, cpf, password) VALUES ('$name', '$email', '$cpf', '$password')"; // Insere um novo usuário na tabela de usuários.
    $result = pg_query($conn, $query); // Executa a consulta.

    return $result ? true : false; // Retorna true se a inserção for bem-sucedida, caso contrário, retorna false.
}

// ---------------- TESTE  CRUD CARTOES------------------ //

function create_card($user_id, $card_name, $card_number, $expiration_date, $card_type) {
    $conn = create_connection();

    $user_id = pg_escape_string($conn, $user_id);
    $card_name = pg_escape_string($conn, $card_name);
    $card_number = pg_escape_string($conn, $card_number);
    $expiration_date = pg_escape_string($conn, $expiration_date);
    $card_type = pg_escape_string($conn, $card_type);

    $query = "INSERT INTO cards (user_id, card_name, card_number, expiration_date, card_type) 
              VALUES ('$user_id', '$card_name', '$card_number', '$expiration_date', '$card_type')";
    $result = pg_query($conn, $query);

    return $result ? true : "Erro ao cadastrar o cartão.";
}

function list_cards($user_id) {
    $conn = create_connection();

    $user_id = pg_escape_string($conn, $user_id);

    $query = "SELECT * FROM cartoes WHERE user_id = '$user_id'";
    $result = pg_query($conn, $query);

    if (!$result) {
        return "Erro ao buscar cartões.";
    }

    $cards = [];
    while ($row = pg_fetch_assoc($result)) {
        $cards[] = $row;
    }

    return $cards;
}

function update_card($card_id, $card_name, $card_number, $expiration_date, $card_type) {
    $conn = create_connection();

    $card_id = pg_escape_string($conn, $card_id);
    $card_name = pg_escape_string($conn, $card_name);   
    $card_number = pg_escape_string($conn, $card_number);
    $expiration_date = pg_escape_string($conn, $expiration_date);
    $card_type = pg_escape_string($conn, $card_type);

    $query = "UPDATE cards 
              SET card_name = '$card_name', card_number = '$card_number', expiration_date = '$expiration_date', card_type = '$card_type' 
              WHERE id = '$card_id'";
    $result = pg_query($conn, $query);

    return $result ? true : "Erro ao atualizar o cartão.";
}

function delete_card($card_id) {
    $conn = create_connection();

    $card_id = pg_escape_string($conn, $card_id);

    $query = "DELETE FROM cards WHERE id = '$card_id'";
    $result = pg_query($conn, $query);

    return $result ? true : "Erro ao deletar o cartão.";
}

function deleteUser($userId){
    $conn = create_connection();

    $userId = pg_escape_string($conn, $userId);
    
    $query = "DELETE FROM users WHERE id = $userId" ;

    $result = pg_query($conn, $query);

    return $result ? true : "Erro ao deletar conta do usuário";
}