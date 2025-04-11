<?php
include('../../protected.php');
include('../../app/service/UserService.php');

$card_id = $_GET['id'];

$result = deleteCard($card_id);

if ($result === true) {
    header("Location: CardsView.php");
    exit();
} else {
    die("Erro ao deletar o cartão: " . htmlspecialchars($result));
}
?>