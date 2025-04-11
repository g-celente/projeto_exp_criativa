<?php

include_once __DIR__ . '/../../database/db.php';

/*
tabela de conta_bancaria

- id
- nome
- usuario_id

*/

function getBankAccountsList(){
    $conn = create_connection();
    $usuario_id = $_SESSION['id'];

    $query = "SELECT * FROM conta_bancaria WHERE usuario_id = '$usuario_id'";
    $result = pg_query($conn, $query);


    return $result ? pg_fetch_all($result) : false;
}

function createBankAccount($nome){
    $conn = create_connection();
    $nome = pg_escape_string($conn, $nome);
    $usuario_id = $_SESSION['id'];

    $query = "INSERT INTO conta_bancaria (nome, usuario_id) values ('$nome', $usuario_id)";

    $result = pg_query($conn, $query);

    return $result ? true : false;
}

function deleteBankAccountById($id){
    $conn = create_connection();
    $id = pg_escape_string($conn, $id);

    $query = "DELETE FROM conta_bancaria WHERE id = $id";

    $result = pg_query($conn, $query);

    if (!$result) {
        echo "Erro na query: " . pg_last_error($conn);
    }

    return $result ? true : false;
}


function editBankAccountById($id, $nome){
    $conn = create_connection();
    $id = pg_escape_string($conn, $id);
    $nome = pg_escape_string($conn, $nome);

    $query = "UPDATE conta_bancaria
              SET nome = '$nome'
              WHERE id = '$id';";

    $result = pg_query($conn, $query);

    return $result ? true : false;
}