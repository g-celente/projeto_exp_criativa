<?php
include("../model/BankAccount.php");

function deleteBankAccount() {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        return false;
    }

    $id = intval($_GET['id']); // segurança básica, força tipo inteiro

    return deleteBankAccountById($id); // função que deve existir no seu model
}

$result = deleteBankAccount();

if ($result) {
    header("Location: ../../view/bank-accounts/BankAccountsView.php");
    exit;
} else {
    echo "Erro ao deletar a conta bancária.";
}
