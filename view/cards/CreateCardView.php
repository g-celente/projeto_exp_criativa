<?php
include('../../protected.php');
include('../../app/service/UserService.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id'];
    $card_name = $_POST['card_name'];
    $card_number = $_POST['card_number'];
    $expiration_date = $_POST['expiration_date'];
    $card_type = $_POST['card_type'];

    $result = createCard($user_id, $card_name, $card_number, $expiration_date, $card_type);

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
    <title>Adicionar Cartão</title>
</head>
<body>
    <h1>Adicionar Novo Cartão</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST">
        <label>Nome do Cartão: <input type="text" name="card_name" required></label><br>
        <label>Número do Cartão: <input type="text" name="card_number" required></label><br>
        <label>Data de Validade: <input type="date" name="expiration_date" required></label><br>
        <label>Tipo do Cartão: <input type="text" name="card_type" required></label><br>
        <button type="submit">Salvar</button>
    </form>
</body>
</html>