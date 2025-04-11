<?php

include __DIR__.'/../model/BankAccount.php';

function listBankAccounts() {
    return getBankAccountsList();
}

function newBankAccount($nome) {
    return createBankAccount($nome);
}

function editBankAccount($id, $nome) {
    return editBankAccountById($id, $nome);
}

function deleteBankAccount($id) {
    return deleteBankAccountById($id);
}