<?php
include '../../app/model/BankAccount.php';
include("../../protected.php");
include '../../app/model/Entries.php';
include("../../app/service/DashboardService.php");
include("../../app/model/Reminders.php");


$userId = $_SESSION['id'];
$hasAccount = getUserBankAccounts($userId);
$reminders = getRemindersByUserPago();

$transactions = getTransactionsListByAccount($hasAccount[0]['id']);
$entries = getEntriesListByAccount($hasAccount[0]['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    $conta_bancaria = $_POST['name'];
    $agencia = (int)$_POST['agencia'];
    $conta = (int)$_POST['conta'];

    $result = createBankAccount($conta_bancaria, $agencia, $conta);

    if ($result) {
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } else {
        echo "<script>alert('Erro ao criar entrada.');</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit-submit'])) {

    $conta_bancaria = $_POST['name'];
    $agencia = (int)$_POST['agencia'];
    $conta = (int)$_POST['conta'];

    $result = createBankAccount($conta_bancaria, $agencia, $conta);

    if ($result) {
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } else {
        echo "<script>alert('Erro ao criar entrada.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoneyTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.9.6/lottie.min.js"></script>
</head>

<body>
    <?php include "../../assets/templates/sideBar/BaseSideBar.php"; ?>
    <!-- Main Content -->
    <div class="main-content">
        <?php if (!$hasAccount): ?>
            <div class="error-container">
                <div class="lottie-animation"></div>
                <div class="error-content">
                    <h1>Ops</h1>
                    <p>Você ainda não possui uma conta bancária cadastrada.</p>
                    <button onclick="openModal()" class="btn btn-primary">Criar Conta Bancária</button>
                </div>
            </div>

            <div class="modal fade" id="adicionarModal" tabindex="-1" role="dialog" aria-labelledby="adicionarModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="adicionarModalLabel">Adicionar Conta</h5>
                            <button type="button" class="close" onclick="closeModal()" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="post">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="Nome" class="form-label mb-1">Nome:</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="Agencia" class="form-label mb-1">Agência:</label>
                                    <input type="number" class="form-control" id="agencia" name="agencia" required>
                                </div>

                                <div class="mb-3">
                                    <label for="Conta" class="form-label mb-1">Conta:</label>
                                    <input type="number" class="form-control" id="conta" name="conta" required>
                                    </select>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" onclick="closeModal()">Cancelar</button>
                                <button type="submit" name="submit" class="btn btn-primary">Adicionar</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="account-header">
                <h1 class="section-title">Minha Conta</h1>
                <div class="account-actions">
                    <button class="btn edit-btn" onclick="openEditModal()">Editar</button>
                    <a href="../../app/helpers/DeleteBankAccount.php?id=<?= $hasAccount[0]['id'] ?>" onclick="return confirm('Tem certeza que deseja deletar esta conta?')" class="btn delete-btn">Deletar Conta</a>
                </div>
            </div>

            <div class="grid-two-column">
                <!-- Card da Conta Bancária -->
                <div class="account-card">
                    <div class="card">
                        <div class="card-header">
                            <span class="card-type">Conta Bancária</span>
                            <span class="card-logo">
                                <?php echo htmlspecialchars($hasAccount[0]["nome"]); ?>
                            </span>
                        </div>
                        <div class="card-balance">Saldo: R$ <?= number_format(getTotalBalanceServiceByAccount($hasAccount[0]['id']), 2, ',', '.') ?></div>
                        <div class="card-number">•••• •••• •••• 1234</div>
                        <div class="card-footer">
                            <span>Atualizado</span>
                            <span><?php echo htmlspecialchars($hasAccount[0]["usuario_nome"]); ?></span>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <span class="card-type">Entradas</span>
                            <span class="card-logo"><?php echo htmlspecialchars($hasAccount[0]["nome"]); ?></span>
                        </div>
                        <div class="card-balance">R$ <?= number_format(getTotalRevenueServiceByAccount($hasAccount[0]['id']), 2, ',', '.') ?></div>
                        <div class="card-number">•••• •••• •••• 4562</div>
                        <div class="card-footer">
                            <span>08/25</span>
                            <span><?php echo htmlspecialchars($hasAccount[0]["usuario_nome"]); ?></span>
                        </div>
                    </div>

                    <!-- Cartão 2 -->
                    <div class="card" style="background: linear-gradient(135deg, #10b981, #34d399);">
                        <div class="card-header">
                            <span class="card-type">Saídas</span>
                            <span class="card-logo"><?php echo htmlspecialchars($hasAccount[0]["nome"]); ?></span>
                        </div>
                        <div class="card-balance">R$ <?= number_format(getTotalExpensesServiceByAccount($hasAccount[0]['id']), 2, ',', '.') ?></div>
                        <div class="card-number">•••• •••• •••• 4562</div>
                        <div class="card-footer">
                            <span>05/26</span>
                            <span><?php echo htmlspecialchars($hasAccount[0]["usuario_nome"]); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Histórico de Transações -->
                <div class="history-section" style="padding: 20px; background: #fff; border-radius: 8px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h2 style="font-size: 20px; font-weight: 600;">Contas Pagas</h2>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="color: #3498db; font-size: 14px;">
                                <i class="fas fa-clock"></i> Filtrado Por: <strong>&nbsp;Recentes</strong>
                            </div>
                        </div>
                    </div>

                    <div class="transaction-cards" style="display: flex; flex-direction: column; gap: 16px;">
                        <?php if ($reminders): ?>
                            <?php foreach ($reminders as $tx): ?>
                                <?php
                                $valor = number_format($tx['valor'], 2, ',', '.');
                                $descricao = htmlspecialchars($tx['nome']);
                                $tipo = 'Transferência';
                                $data = date('d/m/Y', strtotime($tx['data_pagamento'])); // Substitua com data real se quiser
                                $tempo = "2m atrás"; // Substitua com cálculo de tempo real

                                $status = 'Done';
                                $statusColor = $status == 'Done' ? '#22c55e' : '#fbbf24';
                                $statusIcon = $status == 'Done' ? 'fas fa-check-circle' : 'fas fa-clock';
                                ?>
                                <div class="transaction-card">
                                    <div class="col descricao">
                                        <div><?= $descricao ?></div>
                                    </div>
                                    <div class="col data">
                                        <div><?= $data ?></div>
                                        <div><?= $tempo ?></div>
                                    </div>
                                    <div class="col valor">
                                        <div>R$ <?= $valor ?></div>
                                        <div><?= $tipo ?></div>
                                    </div>
                                    <div class="col status">
                                        <i class="<?= $statusIcon ?>"></i> <?= $status ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="padding: 12px; color: #999;">Nenhuma transação encontrada.</p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>


            <!-- Stats Cards
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-title">Business</div>
                <div class="stat-value">$ 1260.00</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Income</div>
                <div class="stat-value">$ 1240.60</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Outcome</div>
                <div class="stat-value">$ 240.35</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Savings</div>
                <div class="stat-value">$ 550.00</div>
            </div>
        </div> -->

            <!-- Card History -->
            <div class="history-section">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h2 class="section-title">Histórico de Transações</h2>
                    <a href="../../app/helpers/GerarExtrato.php" class="button" style="padding: 8px 16px; font-size: 14px;">Gerar Extrato</a>
                </div>

                <ul class="transaction-list" id="transactionList">
                    <?php if ($transactions): ?>
                        <?php foreach ($transactions as $index => $tx): ?>
                            <?php
                            $valor = number_format($tx['transacao_valor'], 2, ',', '.');
                            $icone = ($tx['transacao_tipo_id'] == 1)
                                ? '<i class="fas fa-arrow-up" style="color: #22c55e;"></i>'
                                : '<i class="fas fa-arrow-down" style="color: #ef4444;"></i>';
                            $hiddenClass = $index >= 5 ? 'class="transaction-item extra-transaction" style="display: none;"' : 'class="transaction-item"';

                            ?>
                            <li class="transaction-item" <?= $hiddenClass ?>>
                                <div class="transaction-details">
                                    <div class="transaction-merchant">
                                        <?= $icone ?>
                                        <?= htmlspecialchars($tx['transacao_descricao']) ?>
                                    </div>
                                    <div class="transaction-time">
                                        Categoria: <?= htmlspecialchars($tx['categoria_descricao']) ?> |
                                        Conta: <?= htmlspecialchars($tx['conta_bancaria_nome']) ?>
                                    </div>
                                </div>
                                <div class="transaction-amount">
                                    R$ <?= $valor ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="transaction-item">
                            <div class="transaction-details">
                                <div class="transaction-merchant">Nenhuma transação encontrada.</div>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>

                <?php if (count($transactions) > 5): ?>
                    <div style="text-align: right; margin-top: 10px;">
                        <button id="toggleTransactions" class="action-button service" style="padding: 6px 12px; font-size: 13px;">
                            Ver mais <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Modal de Editar !-->

            <div class="modal fade" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editarModalLabel">Editar Conta</h5>
                            <button type="button" class="close" onclick="closeEditModal()" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="post">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="Nome" class="form-label mb-1">Nome:</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="Agencia" class="form-label mb-1">Agência:</label>
                                    <input type="number" class="form-control" id="agencia" name="agencia" required>
                                </div>

                                <div class="mb-3">
                                    <label for="Conta" class="form-label mb-1">Conta:</label>
                                    <input type="number" class="form-control" id="conta" name="conta" required>
                                    </select>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" onclick="closeEditModal()">Cancelar</button>
                                <button type="submit" name="edit-submit" class="btn btn-primary">Adicionar</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        const animation = lottie.loadAnimation({
            container: document.querySelector('.lottie-animation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: 'https://lottie.host/d987597c-7676-4424-8817-7fca6dc1a33e/BVrFXsaeui.json'
        });
        const toggleBtn = document.getElementById('toggleTransactions');
        if (toggleBtn) {
            let expanded = false;
            toggleBtn.addEventListener('click', () => {
                document.querySelectorAll('.extra-transaction').forEach(el => {
                    el.style.display = expanded ? 'none' : 'flex';
                });
                toggleBtn.innerHTML = expanded ? 'Ver mais <i class="fas fa-chevron-down"></i>' : 'Ver menos <i class="fas fa-chevron-up"></i>';
                expanded = !expanded;
            });
        }

        function openModal() {
            document.getElementById('adicionarModal').classList.add('show');
        }

        function closeModal() {
            document.getElementById('adicionarModal').classList.remove('show');
        }

        function openEditModal() {
            document.getElementById('editarModal').classList.add('show');
        }

        function closeEditModal() {
            document.getElementById('editarModal').classList.remove('show')
        }
    </script>
</body>

</html>
<style>
    .error-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: #f8f9fa;
    }

    .error-content {
        text-align: center;
    }

    .error-content h1 {
        font-size: 6rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .error-content p {
        font-size: 1.5rem;
        margin-bottom: 2rem;
    }

    .lottie-animation {
        max-width: 400px;
        margin-bottom: 2rem;
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        min-width: 100vh;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.3s ease-in-out;
    }

    .modal-content {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        width: 320px;
        transform: scale(0.95);
        animation: popIn 0.3s ease-in-out forwards;
    }

    .modal.show {
        display: flex;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes popIn {
        from {
            transform: scale(0.95);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .grid-two-column {
        display: grid;
        grid-template-columns: 1fr 2fr;
        align-items: stretch;
        margin-bottom: 20px;
        margin-top: 10px;
    }

    .account-card {
        width: 100%;
    }


    /* Estilo dos Cartões */
    .card {
        background: linear-gradient(135deg, var(--primary-color), #6366f1);
        color: white;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 10px;
        width: 300px;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        height: 180px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }

    .card-type {
        font-weight: 500;
        font-size: 14px;
    }

    .card-logo {
        font-weight: bold;
        font-size: 18px;
    }

    .card-balance {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .card-number {
        font-family: 'Courier New', monospace;
        letter-spacing: 1px;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .card-footer {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        margin-top: 10px;
    }


    /* Card Stats */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background-color: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .stat-title {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 10px;
    }

    .stat-value {
        font-size: 22px;
        font-weight: 600;
    }

    /* Card History */
    .history-section {
        background-color: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
    }

    .transaction-list {
        list-style: none;
    }

    .transaction-item {
        display: flex;
        justify-content: space-between;
        padding: 16px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .transaction-item:last-child {
        border-bottom: none;
    }

    .transaction-details {
        flex: 1;
    }

    .transaction-merchant {
        font-weight: 500;
        margin-bottom: 4px;
    }

    .transaction-time {
        font-size: 12px;
        color: #64748b;
    }

    .transaction-amount {
        font-weight: 600;
    }

    /* Card Actions */
    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }

    .action-button {
        flex: 1;
        padding: 12px;
        border: none;
        border-radius: 8px;
        background-color: var(--primary-color);
        color: white;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .action-button:hover {
        background-color: #2563eb;
    }

    .action-button.service {
        background-color: #e2e8f0;
        color: #334155;
    }

    /* User Avatar Container */
    .user-avatar-container {
        position: absolute;
        width: 450px;
        height: 120px;
        right: 20px;
        top: 1px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        background-color: transparent;
        border-bottom-left-radius: 50px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        z-index: 2;
    }

    .user-avatar-container::before {
        content: "";
        position: absolute;
        width: 50%;
        height: 100%;
        background-color: var(--primary-color);
        top: 0;
        left: 0;
        z-index: -1;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-left: auto;
        margin-bottom: 20px;
    }

    /* Botões de navegação do carrossel */
    .carousel-nav {
        position: absolute;
        right: 500px;
        bottom: 0px;
        display: flex;
        gap: 10px;
        z-index: 3;
    }


    /* Efeito de opacidade quando perto do avatar */
    .card.near-avatar {
        opacity: 0;
    }

    .button {
        border: none;
        border-radius: 8px;
        background-color: var(--primary-color);
        color: white;
        text-decoration: none;
    }

    .button:hover {
        background-color: #2563eb;
    }

    .btn-gray {
        padding: 6px 12px;
        font-size: 13px;
        border: none;
        background: #e5e7eb;
        border-radius: 6px;
        color: #111827;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-gray:hover {
        background: #d1d5db;
    }

    .transaction-cards {
        display: flex;
        flex-direction: column;
        gap: 16px;
        align-items: center;
        /* Centraliza horizontalmente */
    }

    .transaction-card {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        /* Define o layout fixo das colunas */
        width: 100%;
        max-width: 800px;
        gap: 20px;
        align-items: start;
        /* Alinha conteúdo no topo */
        padding: 16px;
        border-bottom: 1px solid #e2e8f0;
        border-radius: 8px;
    }

    .transaction-card .col {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        font-size: 14px;
    }

    .transaction-card .descricao div:first-child {
        font-weight: 600;
        font-size: 16px;
    }

    .transaction-card .valor div:first-child {
        font-weight: bold;
        font-size: 16px;
        color: var(--primary-color);
    }

    .transaction-card .status {
        display: flex;
        align-items: center;
        font-weight: 600;
    }

    .transaction-card .status i {
        margin-right: 6px;
    }

    .account-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .section-title {
        font-size: 24px;
        font-weight: bold;
        margin: 0;
    }

    .account-actions {
        display: flex;
        gap: 10px;
    }

    .btn {
        padding: 8px 16px;
        font-size: 14px;
        border: none;
        border-radius: 4px;
        color: #fff;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .edit-btn {
        background-color: darkorange;
    }

    .edit-btn:hover {
        background-color: #e67e00;
        color: white;
    }

    .delete-btn {
        background-color: red;
    }

    .delete-btn:hover {
        background-color: darkred;
        color: white;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('cardsSlider');
        const container = document.getElementById('cardsContainer');
        const cards = document.querySelectorAll('.card');
        const avatarContainer = document.querySelector('.user-avatar-container');

        let isDragging = false;
        let startX, startScrollLeft, velocity, lastX, animationFrame;
        // (posicao inicial do cursor, posicao inicial do carrossel, velocidade, ultima posicao do cursor, frame de animacao)
        const cardWidth = cards[0].offsetWidth + 20; // Largura do card + gap entre eles
        let currentPosition = 0;
        const maxPosition = -(cardWidth * (cards.length - 3)); // Mostra 3 cards por vez

        // Inicia o arraste
        const startDrag = (e) => {
            isDragging = true;
            slider.classList.add('dragging');

            // Posição inicial do cursor
            startX = e.pageX;
            lastX = startX;

            // Posição inicial do scroll
            startScrollLeft = currentPosition;

            // Cancela qualquer animação em andamento
            // cancelAnimationFrame(animationFrame);

            // Reseta a velocidade
            velocity = 0;
        };

        // Durante o arraste
        const duringDrag = (e) => {
            if (!isDragging) return;
            e.preventDefault(); // ???

            // Posição atual do cursor
            const x = e.pageX;

            // Calcula a velocidade (para efeito de inércia)
            velocity = x - lastX;
            lastX = x;

            // Calcula o deslocamento
            const walk = x - startX;

            // Atualiza a posição
            currentPosition = startScrollLeft + walk;

            // Limites do carrossel
            currentPosition = Math.min(0, Math.max(maxPosition, currentPosition));

            // Aplica o movimento
            slider.style.transform = `translateX(${currentPosition}px)`;

            // Verifica cartões próximos ao avatar
            checkCardsNearAvatar();
        };

        // Finaliza o arraste
        const endDrag = () => {
            if (!isDragging) return;
            isDragging = false;
            slider.classList.remove('dragging');

            // Aplica efeito de inércia
            applyInertia();
        };

        // Efeito de inércia
        const applyInertia = () => {
            // Reduz a velocidade gradualmente
            velocity *= 0.92;

            // Atualiza a posição com a velocidade
            currentPosition += velocity;

            // Limites do carrossel
            currentPosition = Math.min(0, Math.max(maxPosition, currentPosition));

            // Aplica o movimento
            slider.style.transform = `translateX(${currentPosition}px)`;

            // Verifica cartões próximos ao avatar
            checkCardsNearAvatar();

            // Continua a animação se ainda houver velocidade
            if (Math.abs(velocity) > 0.5) {
                animationFrame = requestAnimationFrame(applyInertia);
            } else {
                // Alinha ao cartão mais próximo quando parar
                snapToCard();
            }
        };


        function checkCardsNearAvatar() {
            const avatarRect = avatarContainer.getBoundingClientRect();
            const avatarLeft = avatarRect.left;
            const avatarRight = avatarRect.right;

            cards.forEach(card => {
                const cardRect = card.getBoundingClientRect();
                const cardCenter = (cardRect.left + cardRect.right) / 2;

                // Calcula a distância do centro do cartão ao centro do avatar
                const avatarCenter = (avatarLeft + avatarRight) / 2;
                const distance = Math.abs(cardCenter - avatarCenter);

                // Define o limite para começar a reduzir a opacidade
                const maxDistance = 200; // Ajuste conforme necessário
                // const opacity = Math.max(0, 1 - distance / maxDistance); // Opacidade mínima de 0.3

                // // Aplica a opacidade ao cartão
                // card.style.opacity = opacity;
            });
        }

        // Event listeners para mouse
        slider.addEventListener('mousedown', startDrag);
        document.addEventListener('mousemove', duringDrag);
        document.addEventListener('mouseup', endDrag);

        // Event listeners para touch
        slider.addEventListener('touchstart', startDrag, {
            passive: false
        });
        slider.addEventListener('touchmove', (e) => {
            e.preventDefault();
            duringDrag(e);
        }, {
            passive: false
        });
        slider.addEventListener('touchend', endDrag);

        // Inicializa a posição
        slider.style.transform = 'translateX(0px)';
        checkCardsNearAvatar();
    });
</script>