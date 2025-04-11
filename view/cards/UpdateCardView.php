<?php
include('../../protected.php');
include('../../app/service/UserService.php');

$card_id = $_GET['id'];
$cards = listCards($_SESSION['id']);
$card = array_filter($cards, fn($c) => $c['id'] == $card_id)[0] ?? null;

if (!$card) {
    die("Cartão não encontrado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_name = $_POST['card_name'];
    $card_number = $_POST['card_number'];
    $expiration_date = $_POST['expiration_date'];
    $card_type = $_POST['card_type'];

    $result = updateCard($card_id, $card_name, $card_number, $expiration_date, $card_type);

    if ($result === true) {
        header("Location: CardsView.php");
        exit();
    } else {
        $error = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cartão</title>
</head>
<body>
    <h1>Editar Cartão</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST">
        <label>Nome do Cartão: <input type="text" name="card_name" value="<?= htmlspecialchars($card['card_name']) ?>" required></label><br>
        <label>Número do Cartão: <input type="text" name="card_number" value="<?= htmlspecialchars($card['card_number']) ?>" required></label><br>
        <label>Data de Validade: <input type="date" name="expiration_date" value="<?= htmlspecialchars($card['expiration_date']) ?>" required></label><br>
        <label>Tipo do Cartão: <input type="text" name="card_type" value="<?= htmlspecialchars($card['card_type']) ?>" required></label><br>
        <button type="submit">Salvar</button>
    </form>
</body>
</html>