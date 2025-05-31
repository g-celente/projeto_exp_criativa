<?php

include __DIR__.'/../model/Expenses.php';

function listExpenses() {
    return getExpensesList();
}

function createExpense($categoria_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao, $date) {
    return newExpense($categoria_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao, $date);
}

function editExpense($categoria_id, $transacao_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao, $date) {
    return editExpenseById($categoria_id, $transacao_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao, $date);
}

function deleteExpense($transacao_id) {
    return deleteExpenseById($transacao_id);
}