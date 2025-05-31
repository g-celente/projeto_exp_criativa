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

        <p>Veja suas pendencias</p>

        <div class="flex align-center justify-between">
            <input type="text" placeholder="Buscar..." class="search-input">
        </div>

        <div class="calendar" style="padding-top: 80px;">
            <div id='calendar'></div>
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
                        <a class="btn btn-danger" id="delete-btn-modal" href="./RemindersView_copy.php?action=delete&id=" onclick="return confirm('Tem certeza que deseja deletar este lembrete?')">Deletar</a>

                        <button type="submit" name="edit_submit" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src='Calendar/index.global.min.js'></script>
    <script src="Calendar/locales-all.global.min.js"></script>
    <script src="Calendar/calendar.js"></script>
</body>

</html>




<style>
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap');

    .search-input {
        width: 400px;
        padding: 5px;
        border-radius: 5px;
        border: 1px solid #ccc;
        margin-right: 10px;
    }

    .table {
        background-color: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-top: 20px;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead th {
        background-color: rgb(240, 240, 240);

        font-weight: 600;
        padding: 15px;
        border: none;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .table tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }

    .table tbody tr:hover {
        background-color: #e9f5ff;
        transition: all 0.2s ease;
    }

    .table td {
        padding: 12px 15px;
        border-top: 1px solid #f0f0f0;
        vertical-align: middle;
    }

    .table th:first-child {
        border-radius: 10px 0 0 0;
    }

    .table th:last-child {
        border-radius: 0 10px 0 0;
    }

    .table tr:last-child td:first-child {
        border-radius: 0 0 0 10px;
    }

    .table tr:last-child td:last-child {
        border-radius: 0 0 10px 0;
    }

    .close {
        background: none;
        border: none;
    }
</style>