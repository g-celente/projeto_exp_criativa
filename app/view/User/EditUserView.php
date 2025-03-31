<?php

if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    $user = getUserById($userId);

    if (!$user) {
        echo "Usuário não encontrado.";
        exit();
    }
} else {
    echo "ID do usuário não fornecido.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center">Editar Usuário</h2>
        <div class="card shadow">
            <div class="card-body">
                <form action="index.php?page=2&method=alter" method="POST">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome:</label>
                        <input type="text" class="form-control" name="name" value="<?= $user['name'] ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" name="email" value="<?= $user['email'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="cpf" class="form-label">CPF:</label>
                        <input type="text" class="form-control" name="cpf" value="<?= $user['cpf'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Nova Senha (Deixe em branco para manter a atual):</label>
                        <input type="password" class="form-control" name="password">
                    </div>

                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
