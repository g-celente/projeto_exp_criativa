<?php

include __DIR__.'/../model/Entries.php';

function listEntries() {
    return getEntriesList();
}

function createEntry($conta_bancaria_id, $transacao_valor, $transacao_descricao) {
    return newEntry($conta_bancaria_id, $transacao_valor, $transacao_descricao);
}

function editEntry($transacao_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao) {
    return editEntryById($transacao_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao);
}

function deleteEntry($transacao_id) {
    return deleteEntryById($transacao_id);
}