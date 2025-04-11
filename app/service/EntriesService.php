<?php

include __DIR__.'/../model/Entries.php';

function listEntries() {
    return getEntriesList();
}

function createEntry($categoria_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao) {
    return newEntry($categoria_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao);
}