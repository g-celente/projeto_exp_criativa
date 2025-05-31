<?php

include_once __DIR__ . '/../../database/db.php';

/*
tabela de conta_bancaria

- id
- nome
- usuario_id

*/

function getUserBankAccounts($userId) {
    $conn = create_connection();

    $query = "
        SELECT 
            cb.id, 
            cb.nome, 
            cb.usuario_id, 
            cb.agencia, 
            cb.conta, 
            u.name AS usuario_nome
        FROM conta_bancaria cb
        JOIN users u ON cb.usuario_id = u.id
        WHERE cb.usuario_id = $1
    ";

    $stmt = 'get_user_accounts_' . uniqid();
    pg_prepare($conn, $stmt, $query);
    $result = pg_execute($conn, $stmt, [$userId]);

    return $result ? pg_fetch_all($result) : false;
}


function getTransactionsList(){
    $conn = create_connection();
    $usuario_id = $_SESSION['id'];

    $query = "SELECT 
                t.transacao_id,
                t.transacao_valor,
                t.transacao_descricao,
                t.categoria_id,
                t.conta_bancaria_id,
                t.transacao_tipo_id,
                ca.categoria_descricao,
                c.nome AS conta_bancaria_nome
            FROM transacoes t
            INNER JOIN conta_bancaria c ON t.conta_bancaria_id = c.id
            INNER JOIN categorias ca ON t.categoria_id = ca.categoria_id
            WHERE t.usuario_id = '$usuario_id'";
            
    $result = pg_query($conn, $query);


    return $result ? pg_fetch_all($result) : false;
}

function getBankAccountsList(){
    $conn = create_connection();
    $usuario_id = $_SESSION['id'];

    $query = "SELECT * FROM conta_bancaria WHERE usuario_id = '$usuario_id'";
    $result = pg_query($conn, $query);


    return $result ? pg_fetch_all($result) : false;
}

function createBankAccount($nome, $agencia, $conta){
    $conn = create_connection();
    $nome = pg_escape_string($conn, $nome);
    $agencia = pg_escape_string($conn, $agencia);
    $conta = pg_escape_string($conn, $conta);
    $usuario_id = $_SESSION['id'];

    $query = "INSERT INTO conta_bancaria (nome, usuario_id, agencia, conta) values ('$nome', $usuario_id, $agencia, $conta)";

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