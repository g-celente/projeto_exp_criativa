<?php

include_once __DIR__ . '/../../database/db.php';

/*
tabela de transacoes

- transacao_id
- categoria_id
- usuario_id
- transacao_tipo_id
- conta_bancaria_id
- transacao_valor
- transacao_descricao

*/

function getEntriesList(){
    $conn = create_connection();
    $usuario_id = $_SESSION['id'];

    $query = "SELECT * FROM transacoes WHERE usuario_id = '$usuario_id' AND transacao_tipo_id = 1";
    $result = pg_query($conn, $query);


    return $result ? pg_fetch_all($result) : false;
}

function newEntry($categoria_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao){
    $conn = create_connection();
    $categoria_id = pg_escape_string($conn, $categoria_id);
    $conta_bancaria_id = pg_escape_string($conn, $conta_bancaria_id);
    $transacao_valor = pg_escape_string($conn, $transacao_valor);
    $transacao_descricao = pg_escape_string($conn, $transacao_descricao);
    $usuario_id = $_SESSION['id'];
    $transacao_tipo_id = 1; // 1 para entrada, 2 para saída

    $query = "INSERT INTO transacoes (categoria_id, usuario_id, transacao_tipo_id, conta_bancaria_id, transacao_valor, transacao_descricao) values ($categoria_id, $usuario_id, $transacao_tipo_id, $conta_bancaria_id, $transacao_valor, '$transacao_descricao')";

    $result = pg_query($conn, $query);

    return $result ? true : false;

}

function editEntryById($transacao_id, $categoria_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao){
    $conn = create_connection();

    $transacao_id = pg_escape_string($conn, $transacao_id);
    $categoria_id = pg_escape_string($conn, $categoria_id);
    $conta_bancaria_id = pg_escape_string($conn, $conta_bancaria_id);
    $transacao_valor = pg_escape_string($conn, $transacao_valor);
    $transacao_descricao = pg_escape_string($conn, $transacao_descricao);
    $usuario_id = $_SESSION['id'];
    $transacao_tipo_id = 1; // 1 para entrada, 2 para saída

    $query = "UPDATE transacoes
                SET 
                    categoria_id = '$categoria_id',
                    conta_bancaria_id = '$conta_bancaria_id',
                    transacao_valor = '$transacao_valor',
                    transacao_descricao = '$transacao_descricao'
                WHERE 
                    transacao_id = '$transacao_id';
            ";

    $result = pg_query($conn, $query);


    return $result ? true : false;

}

function deleteEntryById($transacao_id){
    $conn = create_connection();
    $transacao_id = pg_escape_string($conn, $transacao_id);
    $query = "DELETE FROM transacoes WHERE transacao_id = $transacao_id";
    $result = pg_query($conn, $query);

    return $result ? true : false;
}