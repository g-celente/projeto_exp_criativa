<?php
// File: models/DashboardModel.php
include_once __DIR__ . '/../../database/db.php';

function getTotalRevenueModel($usuario_id) {
    $conn = create_connection();
    $query = "SELECT SUM(transacao_valor) AS total_revenue FROM transacoes WHERE usuario_id = '$usuario_id' AND transacao_tipo_id = 1";
    $result = pg_query($conn, $query);
    $row = pg_fetch_assoc($result);
    return $row['total_revenue'] ?? 0;
}

function getTotalExpensesModel($usuario_id) {
    $conn = create_connection();
    $query = "SELECT SUM(transacao_valor) AS total_expenses FROM transacoes WHERE usuario_id = '$usuario_id' AND transacao_tipo_id = 2";
    $result = pg_query($conn, $query);
    $row = pg_fetch_assoc($result);
    return $row['total_expenses'] ?? 0;
}

function getRecentTransactionsModel($usuario_id) {
    $conn = create_connection();
    $query = "SELECT t.transacao_id, t.transacao_descricao, t.transacao_valor, c.categoria_descricao, t.transacao_tipo_id FROM transacoes t JOIN categorias c ON t.categoria_id = c.categoria_id WHERE t.usuario_id = '$usuario_id'";
    $result = pg_query($conn, $query);
    return pg_fetch_all($result) ?: [];
}

function getExpensesByCategoryModel($usuario_id) {
    $conn = create_connection();
    $query = "SELECT c.categoria_descricao, SUM(t.transacao_valor) AS total FROM transacoes t JOIN categorias c ON t.categoria_id = c.categoria_id WHERE t.usuario_id = '$usuario_id' AND t.transacao_tipo_id = 2 GROUP BY c.categoria_descricao";
    $result = pg_query($conn, $query);
    return pg_fetch_all($result) ?: [];
}

function getBalanceEvolutionModel($usuario_id) {
    $conn = create_connection();
    $query = "SELECT t.transacao_id, SUM(CASE WHEN t.transacao_tipo_id = 1 THEN t.transacao_valor ELSE -t.transacao_valor END) OVER (ORDER BY t.transacao_id) AS saldo_acumulado FROM transacoes t WHERE t.usuario_id = '$usuario_id' ORDER BY t.transacao_id";
    $result = pg_query($conn, $query);
    return pg_fetch_all($result) ?: [];
}
