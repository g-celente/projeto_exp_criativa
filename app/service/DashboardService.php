<?php
include_once __DIR__ . '/../model/Dashboard.php';


function getTotalRevenueService() {
    $usuario_id = $_SESSION['id'] ?? null;
    if (!$usuario_id) return 0;
    return getTotalRevenueModel($usuario_id);
}

function getTotalExpensesService() {
    $usuario_id = $_SESSION['id'] ?? null;
    if (!$usuario_id) return 0;
    return getTotalExpensesModel($usuario_id);
}

function getTotalBalanceService() {
    return getTotalRevenueService() - getTotalExpensesService();
}

function getRecentTransactionsService() {
    $usuario_id = $_SESSION['id'] ?? null;
    if (!$usuario_id) return [];
    return getRecentTransactionsModel($usuario_id);
}

function getExpensesByCategoryService() {
    $usuario_id = $_SESSION['id'] ?? null;
    if (!$usuario_id) return [];
    return getExpensesByCategoryModel($usuario_id);
}

function getBalanceEvolutionService() {
    $usuario_id = $_SESSION['id'] ?? null;
    if (!$usuario_id) return [];
    return getBalanceEvolutionModel($usuario_id);
}
