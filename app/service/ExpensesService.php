<?php

include __DIR__.'/../model/Expenses.php';

function listExpenses() {
    return getExpensesList();
}

function createExpense($categoria_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao) {
    return newExpense($categoria_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao);
}

function editExpense($categoria_id, $transacao_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao) {
    return editExpenseById($categoria_id, $transacao_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao);
}

function deleteExpense($transacao_id) {
    return deleteExpenseById($transacao_id);
}