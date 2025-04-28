<?php
include("../../app/service/UserService.php");
include("../../protected.php");

// Inicializa a sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../../view/auth/LoginView.php");
    exit();
}

// Define valores padrão para evitar erros de undefined
$userName = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : '';

// Debug: Verifica os valores da sessão
error_log("Session name: " . $userName);
error_log("Session email: " . $userEmail);

// Processa o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Usa os valores atuais como padrão
    $name = !empty($_POST['name']) ? $_POST['name'] : $userName;
    $email = !empty($_POST['email']) ? $_POST['email'] : $userEmail;
    $current_password = !empty($_POST['current_password']) ? $_POST['current_password'] : null;
    $new_password = !empty($_POST['new_password']) ? $_POST['new_password'] : null;

    // Debug: Mostra os valores que serão enviados
    error_log("Atualizando usuário: ID=" . $_SESSION['id']);
    error_log("Nome: " . $name);
    error_log("Email: " . $email);
    error_log("Senha atual fornecida: " . ($current_password ? "Sim" : "Não"));
    error_log("Nova senha fornecida: " . ($new_password ? "Sim" : "Não"));

    // Se uma nova senha foi fornecida, verifica se a senha atual também foi fornecida
    if ($new_password !== null && $current_password === null) {
        $error = "Para alterar a senha, é necessário informar a senha atual.";
    } else {
        // Chama a função update_user() para atualizar os dados
        $result = update_user($_SESSION['id'], $name, $email, $current_password, $new_password);

        if ($result === true) {
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $userName = $name;
            $userEmail = $email;
            $success = "Dados atualizados com sucesso!";
        } else {
            $error = $result;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <style>
        .profile-container {
            max-width: 1000px;
            margin: 2rem auto;
        }
        .profile-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #666;
        }
        .info-item {
            margin-bottom: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .info-label {
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }
        .info-value {
            font-size: 1.1rem;
            color: #212529;
        }
        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2e59d9;
        }
        .alert {
            border-radius: 5px;
        }
        .section-title {
            color: #4e73df;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #4e73df;
        }
    </style>
</head>
<body>
    <?php include("../../assets/templates/sideBar/BaseSideBar.php"); ?>
    
    <div class="main-content">
        <div class="profile-container">
            <!-- Seção de Visualização -->
            <div class="profile-section">
                <h3 class="section-title">Meus Dados</h3>
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2><?= htmlspecialchars($userName) ?></h2>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">Nome Completo</div>
                            <div class="info-value"><?= htmlspecialchars($userName) ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value"><?= htmlspecialchars($userEmail) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção de Edição -->
            <div class="profile-section">
                <h3 class="section-title">Editar Dados</h3>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Erro!</strong> <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Sucesso!</strong> <?= htmlspecialchars($success) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="name" name="name">
                                <small class="text-muted">Deixe em branco para manter o nome atual</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                                <small class="text-muted">Deixe em branco para manter o email atual</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Senha Atual</label>
                                <input type="password" class="form-control" id="current_password" name="current_password">
                                <small class="text-muted">Preencha apenas se desejar alterar a senha</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Nova Senha</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                                <small class="text-muted">Preencha apenas se desejar alterar a senha</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>
