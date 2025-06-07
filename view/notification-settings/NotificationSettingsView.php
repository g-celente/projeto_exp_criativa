<?php
include("../../protected.php");
include('../../app/service/NotificationService.php');

$preferences = getUserNotificationPreferences();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_preferences'])) {
    $ativo = isset($_POST['ativo']) ? true : false;
    $dias_antecedencia = (int)$_POST['dias_antecedencia'];
    $tipo_notificacao = $_POST['tipo_notificacao'];

    $result = updateNotificationPreferences($ativo, $dias_antecedencia, $tipo_notificacao);

    if ($result) {
        $_SESSION['success_message'] = "Preferências salvas com sucesso!";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } else {
        $_SESSION['error_message'] = "Erro ao salvar preferências.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoneyTrack - Configurações de Notificação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <style>
        .notification-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .notification-card:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
            cursor: pointer;
        }

        .form-switch .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }

        .alert-float {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            min-width: 300px;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .section-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }

        .settings-icon {
            font-size: 1.2rem;
            margin-right: 0.5rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <?php include "../../assets/templates/sideBar/BaseSideBar.php"; ?>
    
    <div class="main-content">
        <div class="container py-4">
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-float alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-float alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['error_message'];
                    unset($_SESSION['error_message']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row justify-content-center">
                <div class="col-12 col-md-8">
                    <h2 class="section-title">
                        <i class="fas fa-bell settings-icon"></i>
                        Configurações de Notificação
                    </h2>
                    
                    <div class="notification-card p-4">
                        <form method="post" class="needs-validation" novalidate>
                            <div class="mb-4">
                                <div class="form-switch d-flex align-items-center">
                                    <input type="checkbox" class="form-check-input me-3" id="ativo" name="ativo" 
                                        <?php echo ($preferences && $preferences['ativo']) ? 'checked' : ''; ?>>
                                    <label class="form-check-label h5 mb-0" for="ativo">
                                        Ativar notificações por email
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    Receba lembretes importantes sobre seus compromissos financeiros
                                </small>
                            </div>

                            <div class="mb-4">
                                <label for="dias_antecedencia" class="form-label h6">
                                    <i class="fas fa-clock settings-icon"></i>
                                    Dias de antecedência
                                </label>
                                <select class="form-select form-select-lg" id="dias_antecedencia" name="dias_antecedencia" required>
                                    <option value="1" <?php echo ($preferences && $preferences['dias_antecedencia'] == 1) ? 'selected' : ''; ?>>1 dia antes</option>
                                    <option value="2" <?php echo ($preferences && $preferences['dias_antecedencia'] == 2) ? 'selected' : ''; ?>>2 dias antes</option>
                                    <option value="3" <?php echo ($preferences && $preferences['dias_antecedencia'] == 3) ? 'selected' : ''; ?>>3 dias antes</option>
                                </select>
                                <div class="invalid-feedback">
                                    Por favor, selecione os dias de antecedência.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="tipo_notificacao" class="form-label h6">
                                    <i class="fas fa-filter settings-icon"></i>
                                    Tipo de notificação
                                </label>
                                <select class="form-select form-select-lg" id="tipo_notificacao" name="tipo_notificacao" required>
                                    <option value="vencimentos" <?php echo ($preferences && $preferences['tipo_notificacao'] == 'vencimentos') ? 'selected' : ''; ?>>
                                        Apenas vencimentos
                                    </option>
                                    <option value="todos" <?php echo ($preferences && $preferences['tipo_notificacao'] == 'todos') ? 'selected' : ''; ?>>
                                        Todos os lembretes
                                    </option>
                                </select>
                                <div class="invalid-feedback">
                                    Por favor, selecione o tipo de notificação.
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" name="save_preferences" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>
                                    Salvar Preferências
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert-float');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>