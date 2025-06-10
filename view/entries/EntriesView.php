<?php
include("../../protected.php");
include('../../app/service/EntriesService.php');
include('../../app/service/BankAccountService.php');
include('../../app/service/CategoriesService.php');

$entriesList = listEntries();
$bankAccountsList = getBankAccountsList();
$categoriesList = listCategories(); //Exibição categorias tabela de entrada

//Criar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    $conta_bancaria_id = (int)$_POST['conta_bancaria'];
    $transacao_valor = (float)str_replace(',', '.', $_POST['valor']);
    $transacao_descricao = $_POST['descricao'];
    $categoria_id = (int)$_POST['categoria_id'];
    $date = $_POST['data']; // criar categoria

    $result = createEntry($categoria_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao, $date);

    if ($result) {
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } else {
        echo "<script>alert('Erro ao criar entrada.');</script>";
    }
}


//deletar
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $entryId = (int)$_GET['id'];
    $result = deleteEntry($entryId);

    if ($result) {
        $base_url = strtok($_SERVER['REQUEST_URI'], '?');
        header("Location: " . $base_url);
        exit;
    } else {
        echo "<script>alert('Erro ao deletar entrada.');</script>";
    }
}

//editar

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_submit'])) {
    $transacao_id = (int)$_POST['entry_id'];
    $categoria_id = (int)$_POST['edit_categoria_id']; // editar categoria
    $conta_bancaria_id = (int)$_POST['edit_conta_bancaria'];
    $transacao_valor = (float)str_replace(',', '.', $_POST['edit_valor']);
    $transacao_descricao = $_POST['edit_descricao'];
    $date = $_POST['edit_data']; 


    $result = editEntry($transacao_id, $categoria_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao, $date);

    if ($result) {
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } else {
        echo "<script>alert('Erro ao editar entrada.');</script>";
    }
}
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

    const handleOpenEditModal = (entry) => {
        document.getElementById('edit-entry-id').value = entry.transacao_id;
        document.getElementById('edit_descricao').value = entry.transacao_descricao;
        document.getElementById('edit_valor').value = entry.transacao_valor;
        document.getElementById('edit_conta_bancaria').value = entry.conta_bancaria_id;
        document.getElementById('edit_categoria_id').value = entry.categoria_id;
        document.getElementById('edit_data').value = entry.transacao_data;
    }
</script>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel</title>        <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/main.css">
</head>

<body>

    <?php include("../../assets/templates/sideBar/BaseSideBar.php") ?>

    <div class="main-content">
        <h1>Entradas</h1>

        <p>Visualize e gerencie suas entradas financeiras</p>

        <div class="flex align-center justify-between">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" placeholder="Buscar entradas..." class="search-input">
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adicionarModal">
                <i class="fas fa-plus"></i>
                Nova Entrada
            </button>

        </div>

        <div class="data-table mt-5">
            <?php            echo "<table class='table'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Descrição</th>";
            echo "<th>Valor</th>";
            echo "<th>Categoria</th>";
            echo "<th>Conta</th>";
            echo "<th>Data</th>";
            echo "<th>Ações</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($entriesList as $entry) {                echo "<tr>";
                echo "<td class='font-medium'>" . (!empty($entry['transacao_descricao']) ? htmlspecialchars($entry['transacao_descricao']) : '-') . "</td>";
                echo "<td class='value-positive'>R$ " . (!empty($entry['transacao_valor']) ? number_format($entry['transacao_valor'], 2, ',', '.') : '-') . "</td>";
                echo "<td>" . (!empty($entry['categoria_descricao']) ? htmlspecialchars($entry['categoria_descricao']) : '-') . "</td>";
                echo "<td>" . (!empty($entry['conta_bancaria_nome']) ? htmlspecialchars($entry['conta_bancaria_nome']) : '-') . "</td>";
                echo "<td>" . (!empty($entry['transacao_data']) ? date('d/m/Y', strtotime($entry['transacao_data'])) : date('d/m/Y')) . "</td>";
                echo "<td class='actions'>
                        <div class='btn-group'>
                            <button class='btn btn-icon btn-primary' data-bs-toggle='modal' data-bs-target='#editarModal' onClick='handleOpenEditModal(" . json_encode($entry) . ")'>
                                <i class='fas fa-edit'></i>
                            </button>
                            <a class='btn btn-icon btn-danger' href='./EntriesView.php?action=delete&id=" . htmlspecialchars($entry['transacao_id']) . "'>
                                <i class='fas fa-trash-alt'></i>
                            </a>
                        </div>
                    </td>";

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
                    <h5 class="modal-title" id="adicionarModalLabel">Adicionar Entrada</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="descricao" class="form-label mb-1">Descrição:</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" required>
                        </div>
                        <div class="mb-3">
                            <label for="valor" class="form-label mb-1">Valor:</label>
                            <input type="number" class="form-control" id="valor" name="valor" required>
                        </div>
                        <div class="mb-3">
                            <label for="data" class="form-label mb-1">Data:</label>
                            <input type="date" class="form-control" id="data" name="data" required>
                        </div>

                        <div class="mb-3">
                            <label for="categoria_id" class="form-label mb-1">Categoria:</label>
                            <select class="form-control" id="categoria_id" name="categoria_id" required>
                                <option value="#" disabled selected hidden>Selecione uma Categoria...</option>
                                <?php
                                foreach ($categoriesList as $category) {
                                    echo "<option value='" . htmlspecialchars($category['categoria_id']) . "'>" . htmlspecialchars($category['categoria_descricao']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="conta_bancaria" class="form-label mb-1">Conta bancaria:</label>
                            <select class="form-control" id="conta_bancaria" name="conta_bancaria" required>
                                <option value="#" disabled selected hidden>Selecione uma Opção...</option>
                                <?php
                                foreach ($bankAccountsList as $account) {
                                    echo "<option value='" . htmlspecialchars($account['id']) . "'>" . htmlspecialchars($account['nome']) . "</option>";
                                }
                                ?>
                            </select>
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
                    <h5 class="modal-title" id="editarModalLabel">Editar Entrada</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post">
                    <input type="hidden" name="entry_id" id="edit-entry-id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_descricao" class="form-label mb-1">Descrição:</label>
                            <input type="text" class="form-control" id="edit_descricao" name="edit_descricao" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_valor" class="form-label mb-1">Valor:</label>
                            <input type="number" step="0.01" class="form-control" id="edit_valor" name="edit_valor" required>
                        </div>
                        <div class="mb-3">
                            <label for="data" class="form-label mb-1">Data:</label>
                            <input type="date" class="form-control" id="edit_data" name="edit_data" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_categoria_id" class="form-label mb-1">Categoria:</label>
                            <select class="form-control" id="edit_categoria_id" name="edit_categoria_id">
                                <?php
                                foreach ($categoriesList as $category) {
                                    echo "<option value='" . htmlspecialchars($category['categoria_id']) . "'>" . htmlspecialchars($category['categoria_descricao']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_conta_bancaria" class="form-label mb-1">Conta bancária:</label>
                            <select class="form-control" id="edit_conta_bancaria" name="edit_conta_bancaria">
                                <option value="0">Nenhuma</option>
                                <?php
                                foreach ($bankAccountsList as $account) {
                                    echo "<option value='" . htmlspecialchars($account['id']) . "'>" . htmlspecialchars($account['nome']) . "</option>";
                                }
                                ?>
                            </select>
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
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap');    .search-input {
        width: 400px;
        padding: 10px 15px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        margin-right: 15px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
    }

    .table {
        background-color: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin-top: 24px;
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
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

    .table tbody tr:nth-child(even) {
        background-color: #f8fafc;
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

    .table th:first-child {
        padding-left: 24px;
        border-top-left-radius: 12px;
    }

    .table th:last-child {
        padding-right: 24px;
        border-top-right-radius: 12px;
    }

    .table td:first-child {
        padding-left: 24px;
    }

    .table td:last-child {
        padding-right: 24px;
    }

    .table tr:last-child td:first-child {
        border-bottom-left-radius: 12px;
    }

    .table tr:last-child td:last-child {
        border-bottom-right-radius: 12px;
    }

    .btn {
        padding: 8px 16px;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.2s;
        font-size: 14px;
    }

    .btn-primary {
        background: var(--primary-color);
        border: none;
    }

    .btn-primary:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }

    .btn-danger {
        background: #ef4444;
        border: none;
    }

    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }

    .close {
        background: none;
        border: none;
        padding: 8px;
        transition: opacity 0.2s;
        opacity: 0.6;
    }

    .close:hover {
        opacity: 1;
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

    .value-positive {
        color: #10b981;
    }
</style>