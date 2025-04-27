<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['id'])) {
?>

    <!DOCTYPE html>
    <html lang="pt-BR">

    <head>
        <meta charset="UTF-8">
        <title>Acesso Negado</title>
        <link rel="stylesheet" href="../../assets/css/main.css"> <!-- ajuste o caminho conforme necessário -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
        <style>
            :root {
                --primary-color: #2563eb;
                /* Azul bonito */
                --hover-color: #1d4ed8;
                /* Azul mais forte */
            }

            .card {
                background: white;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.08);
                max-width: 400px;
                text-align: center;
                padding: 2rem;
                transition: 0.3s ease;
            }

            .btn-primary {
                background-color: var(--primary-color);
                color: white;
                padding: 10px 20px;
                border-radius: 8px;
                text-decoration: none;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                transition: background-color 0.3s ease;
            }

            .btn-primary:hover {
                background-color: var(--hover-color);
            }

            .lock-icon {
                font-size: 60px;
                color: var(--primary-color);
                margin-bottom: 20px;
            }

            h2 {
                color: #1e293b;
                margin-bottom: 10px;
                font-size: 24px;
            }

            p {
                color: #64748b;
                margin-bottom: 20px;
            }
        </style>
    </head>

    <body>
        <div class="justify-center align-center m-a pt-5">
            <div class="card flex flex-col align-center justify-center">
                <i class="fas fa-lock lock-icon"></i>
                <h2>Acesso Restrito</h2>
                <p>Você precisa estar logado para acessar esta página.</p>
                <a href="../auth/LoginView.php" class="btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Entrar
                </a>
            </div>
        </div>
    </body>

    </html>

<?php
    die();
}
?>