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

function getExpensesList(){
    $conn = create_connection();
    $usuario_id = $_SESSION['id'];

    $query = "SELECT 
                t.transacao_id,
                t.transacao_valor,
                t.transacao_descricao,
                t.categoria_id,
                t.conta_bancaria_id,
                ca.categoria_descricao,
                c.nome AS conta_bancaria_nome
            FROM transacoes t
            INNER JOIN conta_bancaria c ON t.conta_bancaria_id = c.id
            INNER JOIN categorias ca ON t.categoria_id = ca.categoria_id
            WHERE t.usuario_id = '$usuario_id' AND t.transacao_tipo_id = 2";
            
            
    $result = pg_query($conn, $query);


    return $result ? pg_fetch_all($result) : false;
}

function newExpense($categoria_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao, $date){
    $conn = create_connection();

    $categoria_id = pg_escape_string($conn, $categoria_id);
    $conta_bancaria_id = pg_escape_string($conn, $conta_bancaria_id);
    $transacao_valor = pg_escape_string($conn, $transacao_valor);
    $transacao_descricao = pg_escape_string($conn, $transacao_descricao);
    $date = pg_escape_string($conn, $date);
    $usuario_id = $_SESSION['id'];
    $transacao_tipo_id = 2; 

    $query = "INSERT INTO transacoes (categoria_id, usuario_id, transacao_tipo_id, conta_bancaria_id, transacao_valor, transacao_descricao, transacao_data) values ($categoria_id, $usuario_id, $transacao_tipo_id, $conta_bancaria_id, $transacao_valor, '$transacao_descricao', '$date')";

    $result = pg_query($conn, $query);
 
    return $result ? true : false;

}

function editExpenseById($categoria_id, $transacao_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao, $date){
    $conn = create_connection();
    $transacao_id = pg_escape_string($conn, $transacao_id);
    $categoria_id = pg_escape_string($conn, $categoria_id);
    $conta_bancaria_id = pg_escape_string($conn, $conta_bancaria_id);
    $transacao_valor = pg_escape_string($conn, $transacao_valor);
    $transacao_descricao = pg_escape_string($conn, $transacao_descricao);
    $date = pg_escape_string($conn, $date);
    $usuario_id = $_SESSION['id'];
    $transacao_tipo_id = 1; // 1 para entrada, 2 para saída

    $query = "UPDATE transacoes
                SET 
                    categoria_id = '$categoria_id',
                    conta_bancaria_id = '$conta_bancaria_id',
                    transacao_valor = '$transacao_valor',
                    transacao_descricao = '$transacao_descricao',
                    transacao_data = '$date'
                WHERE 
                    transacao_id = '$transacao_id';
            ";

    $result = pg_query($conn, $query);


    return $result ? true : false;

}

function deleteExpenseById($transacao_id){
    $conn = create_connection();
    $transacao_id = pg_escape_string($conn, $transacao_id);
    $query = "DELETE FROM transacoes WHERE transacao_id = $transacao_id";
    $result = pg_query($conn, $query);

    return $result ? true : false;
}