<?php
include("../../protected.php");
include('../../app/service/ReminderService.php');

$reminders = listReminders();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $lembrete_id = (int)$_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $data_vencimento = $_POST['data_vencimento'];
    $valor = (int)$_POST['valor'];
    $result = addReminder($nome, $descricao, $data_vencimento, $valor);
    if ($result) {
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } else {
        echo "<script>alert('Erro ao criar entrada.');</script>";
    }
}


if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $lembrete_id = (int)$_GET['id'];
    $result = deleteReminder($lembrete_id);
    if ($result) {
        $base_url = strtok($_SERVER['REQUEST_URI'], '?');
        header("Location: " . $base_url);
        exit;
    } else {
        echo "<script>alert('Erro ao deletar entrada.');</script>";
    }
}


//TESTAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_submit'])) {
    $lembrete_id = (int)$_POST['reminder_id'];
    $nome = $_POST['edit_nome'];
    $descricao = $_POST['edit_descricao'];
    $data_vencimento = $_POST['edit_data_vencimento'];
    // $status = $_POST['edit_status'];
    // $data_pagamento = isset($_POST['edit_data_pagamento']);
    $data_pagamento = $_POST['edit_data_pagamento'];

    if (!empty($data_pagamento)) {
        $result = updatePaidReminder($lembrete_id, $nome, $descricao, $data_vencimento, $data_pagamento);
    } else {
        $result = updateReminder($lembrete_id, $nome, $descricao, $data_vencimento);
    }

    if ($result) {
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } else {
        echo "<script>alert('Erro ao editar entrada.');</script>";
    }
}


if (isset($_POST['pago_submit'])) {
    $lembrete_id = (int)$_POST['lembrete_id'];
    $data_pagamento = date('Y-m-d');
    setStatus($lembrete_id, $data_pagamento);
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

$status = isset($reminder['status']) ? $reminder['status'] : 'pendente';
$data_pagamento = isset($reminder['data_pagamento']) ? $reminder['data_pagamento'] : '';
?>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('.search-input');
        const tableRows = document.querySelectorAll('.table tbody tr');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            tableRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(searchTerm) ? '' : 'none';
            });
        });
    });


    // TESTAR
    const handleOpenEditModal = (entry) => {
        document.getElementById('edit-reminder-id').value = entry.id;
        document.getElementById('edit_nome').value = entry.nome;
        document.getElementById('edit_descricao').value = entry.descricao;
        document.getElementById('edit_data_vencimento').value = entry.data_vencimento;

        const pagamentoGroup = document.getElementById('edit-data-pagamento-group');

        if (entry.status !== 'pendente') {
            pagamentoGroup.style.display = '';
            document.getElementById('edit_data_pagamento').value = entry.data_pagamento || '';
        } else {
            pagamentoGroup.style.display = 'none';
            document.getElementById('edit_data_pagamento').value = '';
        }
    }
</script>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/main.css">
</head>

<body>

    <?php include("../../assets/templates/sideBar/BaseSideBar.php") ?>

    <div class="main-content">
        <h1>Lembretes</h1>

        <p>Veja suas pendencias</p>        <div class="flex align-center justify-between mb-4">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" placeholder="Buscar lembretes..." class="search-input">
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adicionarModal">
                <i class="fas fa-plus"></i>
                Novo Lembrete
            </button>
        </div>

        <div class="reminders-container">
            <?php            echo "<table class='table'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Nome</th>";
            echo "<th>Descrição</th>";
            echo "<th>Vencimento</th>";
            echo "<th>Status</th>";
            echo "<th>Ações</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            foreach ($reminders as $reminder) {
                $statusClass = $reminder['status'] === 'pago' ? 'status-paid' : 'status-pending';
                $statusIcon = $reminder['status'] === 'pago' ? 'fa-check-circle' : 'fa-clock';
                $statusText = $reminder['status'] === 'pago' 
                    ? "Pago em " . date('d/m/Y', strtotime($reminder['data_pagamento']))
                    : "Pendente";
                
                echo "<tr>";
                echo "<td class='font-medium'>" . (!empty($reminder['nome']) ? htmlspecialchars($reminder['nome']) : '-') . "</td>";
                echo "<td>" . (!empty($reminder['descricao']) ? htmlspecialchars($reminder['descricao']) : '-') . "</td>";
                echo "<td>" . (!empty($reminder['data_vencimento']) ? date('d/m/Y', strtotime($reminder['data_vencimento'])) : '-') . "</td>";
                echo "<td><span class='status-badge {$statusClass}'><i class='fas {$statusIcon} mr-2'></i>{$statusText}</span></td>";                echo "<td class='actions'>
                    <div class='btn-group'>
                        <button class='btn btn-icon btn-primary' id='edit-btn' data-bs-toggle='modal' data-bs-target='#editarModal' onClick='handleOpenEditModal(" . json_encode($reminder) . ")'><i class='fas fa-edit'></i></button>
                        <a class='btn btn-icon btn-danger' id='delete-btn' href='./RemindersView.php?action=delete&id=" . htmlspecialchars($reminder['id']) . "'><i class='fas fa-trash-alt'></i></a>";
                if ($reminder['status'] !== 'pago') {                    echo "<form method='post' style='display:inline;'>
                        <input type='hidden' name='lembrete_id' value='" . htmlspecialchars($reminder['id']) . "'>
                        <button type='submit' name='pago_submit' class='btn btn-icon btn-success'><i class='fas fa-check'></i></button>
                    </form>";
                }
                echo "</div>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            ?>
        </div>


    </div>

    <!-- MODAL PARA ADICIONAR -->
    <div class="modal fade" id="adicionarModal" tabindex="-1" role="dialog" aria-labelledby="adicionarModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adicionarModalLabel">Adicionar Lembrete</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nome" class="form-label mb-1">Nome:</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label mb-1">Descricao:</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" required>
                        </div>
                        <div class="mb-3">
                            <label for="valor" class="form-label mb-1">Valor:</label>
                            <input type="number" class="form-control" id="valor" name="valor" required>
                        </div>

                        <div class="mb-3">
                            <label for="data_vencimento" class="form-label mb-1">Data de Vencimento:</label>
                            <input type="date" class="form-control" id="data_vencimento" name="data_vencimento" required>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="submit" class="btn btn-primary">Adicionar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <!-- MODAL PARA EDITAR -->
    <div class="modal fade" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarModalLabel">Editar Lembrete</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post">
                    <input type="hidden" name="reminder_id" id="edit-reminder-id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nome" class="form-label mb-1">Nome:</label>
                            <input type="text" class="form-control" id="edit_nome" name="edit_nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_descricao" class="form-label mb-1">Descrição:</label>
                            <input type="text" class="form-control" id="edit_descricao" name="edit_descricao" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_data_vencimento" class="form-label mb-1">Data de vencimento:</label>
                            <input type="date" class="form-control" id="edit_data_vencimento" name="edit_data_vencimento" required>
                        </div>

                        <!-- TESTAR -->
                        <div class="mb-3" id="edit-data-pagamento-group" style="display:none;">
                            <label for="edit_data_pagamento" class="form-label mb-1">Data de Pagamento:</label>
                            <input type="date" class="form-control" id="edit_data_pagamento" name="edit_data_pagamento">
                        </div>

                    </div>



                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="edit_submit" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>




<style>
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap');

    .search-container {
        position: relative;
        width: 400px;
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .search-input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
    }

    .reminders-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin-top: 24px;
        padding: 24px;
    }

    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead th {
        background-color: #f8fafc;
        font-weight: 600;
        padding: 16px;
        color: #1e293b;
        font-size: 14px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        border-bottom: 2px solid #e2e8f0;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #f1f5f9;
        transform: translateY(-1px);
    }

    .table td {
        padding: 16px;
        color: #475569;
        font-size: 14px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-paid {
        background-color: #dcfce7;
        color: #166534;
    }

    .btn {
        padding: 8px 16px;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.2s;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn i {
        font-size: 16px;
    }

    .btn-primary {
        background: var(--primary-color);
        border: none;
        color: white;
    }

    .btn-primary:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }

    .btn-danger {
        background: #ef4444;
        border: none;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }

    .btn-success {
        background: #10b981;
        border: none;
        color: white;
    }

    .btn-success:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    .btn-group {
        display: flex;
        gap: 8px;
    }

    .btn-icon {
        padding: 8px;
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        border-bottom: 1px solid #e2e8f0;
        padding: 20px 24px;
    }

    .modal-title {
        font-weight: 600;
        font-size: 18px;
        color: #1e293b;
    }

    .modal-body {
        padding: 24px;
    }

    .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 16px 24px;
    }

    .form-label {
        font-weight: 500;
        color: #475569;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 10px 12px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
    }

    .mb-3 {
        margin-bottom: 20px;
    }

    .mb-4 {
        margin-bottom: 24px;
    }

    .font-medium {
        font-weight: 500;
    }

    .mr-2 {
        margin-right: 8px;
    }
</style>