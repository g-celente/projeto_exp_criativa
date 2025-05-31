<?php

include __DIR__.'/../model/Entries.php';

function listEntries() {
    return getEntriesList();
}

function createEntry($categoria_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao, $date) {
    return newEntry($categoria_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao, $date);
}

function editEntry($transacao_id, $categoria_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao, $date) {
    return editEntryById($transacao_id, $categoria_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao, $date);
}

function deleteEntry($transacao_id) {
    return deleteEntryById($transacao_id);
}