<?php
include_once __DIR__ . '/../../database/db.php';

function getTotalRevenueModel($usuario_id) {
    $conn = create_connection();  
    $query = "SELECT SUM(transacao_valor) AS total_revenue FROM transacoes WHERE usuario_id = $1 AND transacao_tipo_id = $2";
    //CONSULTA PEGA SOMA DE TODOS OS VALORES DE UM CERTO TIPO, DE UM CERTO USUARIO

    //PREPARED STATEMENTS
    $stmt_name = 'get_total_revenue_'.uniqid();                    //CRIA UM NOME UNICO 
    pg_prepare($conn , $stmt_name , $query);                       //(CONEXAO COM BANCO , NOME DA CONSULTA , QUERY)
    $result = pg_execute($conn , $stmt_name , [$usuario_id,1]);    //(CONEXAO COM BANCO , NOME DA CONSULTA , ARRAY)

    $row = pg_fetch_assoc($result);                                //TRANSFORMA O PRIMEIRO RESULTADO DA QUERY EM UM DICIONARIO COM A CHAVE SENDO OS HEADERS E OS DADOS SENDO AS COLUNAS
    return $row['total_revenue'] ?? 0;                             //RETORNA O VALOR DA CHAVE DO DICIONARIO, OU 0 CASO ELE SEJA NULO
}

function getTotalExpensesModel($usuario_id) {
    $conn = create_connection();
    $query = "SELECT SUM(transacao_valor) AS total_expenses FROM transacoes WHERE usuario_id = $1 AND transacao_tipo_id = $2";
    $stmt_name = 'get_total_expenses_'.uniqid();
    pg_prepare($conn , $stmt_name , $query);
    $result = pg_execute($conn , $stmt_name , [$usuario_id , 2]);
    $row = pg_fetch_assoc($result);
    return $row['total_expenses'] ?? 0;
}

function getRecentTransactionsModel($usuario_id) {
    $conn = create_connection();
    $query = "SELECT t.transacao_id, t.transacao_descricao, t.transacao_valor, c.categoria_descricao, t.transacao_tipo_id FROM transacoes t JOIN categorias c ON t.categoria_id = c.categoria_id WHERE t.usuario_id = $1";
    $stmt_name = 'get_recent_transactions_'.uniqid();
    pg_prepare($conn , $stmt_name , $query);
    $result = pg_execute($conn , $stmt_name , [$usuario_id]);
    return pg_fetch_all($result) ?: [];
}


function getExpensesByCategoryModel($usuario_id) {
    $conn = create_connection();
    $query = "SELECT c.categoria_descricao, SUM(t.transacao_valor) AS total FROM transacoes t JOIN categorias c ON t.categoria_id = c.categoria_id WHERE t.usuario_id = $1 AND t.transacao_tipo_id = $2 GROUP BY c.categoria_descricao";
    $stmt_name = 'get_expenses_by_category_'.uniqid();
    pg_prepare($conn , $stmt_name , $query);
    $result = pg_execute($conn , $stmt_name , [$usuario_id,2]);
    return pg_fetch_all($result) ?: [];
}

function getBalanceEvolutionModel($usuario_id) {
    $conn = create_connection();
    $query = "SELECT t.transacao_id, SUM(CASE WHEN t.transacao_tipo_id = 1 THEN t.transacao_valor ELSE -t.transacao_valor END) OVER (ORDER BY t.transacao_id) AS saldo_acumulado FROM transacoes t WHERE t.usuario_id = $1 ORDER BY t.transacao_id";
    $stmt_name = 'get_balance_evolution_'.uniqid();
    pg_prepare($conn , $stmt_name , $query);
    $result = pg_execute($conn , $stmt_name , [$usuario_id]);
    return pg_fetch_all($result) ?: [];
}

