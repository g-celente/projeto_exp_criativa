<?php
include("../../protected.php");
include('../../app/service/EntriesService.php');
include('../../app/service/BankAccountService.php');

$entriesList = listEntries();
$bankAccountsList = getBankAccountsList();

//Criar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    $categoria_id =(int)$_POST['categoria'];
    $conta_bancaria_id = (int)$_POST['conta_bancaria'];
    $transacao_valor = (float)str_replace(',', '.', $_POST['valor']); // Formata o valor decimal
    $transacao_descricao = $_POST['descricao'];

    $result = createEntry( $categoria_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao);

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
    $result = deleteEntry($entryId); // Você precisará criar essa função no seu service

    if ($result) {
        $base_url = strtok($_SERVER['REQUEST_URI'], '?'); // Remove query strings
        header("Location: " . $base_url);
        exit;
    } else {
        echo "<script>alert('Erro ao deletar entrada.');</script>";
    }
}

//editar

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_submit'])) {
    $transacao_id = (int)$_POST['entry_id'];
    $categoria_id = (int)$_POST['edit_categoria'];
    $conta_bancaria_id = (int)$_POST['edit_conta_bancaria'];
    $transacao_valor = (float)str_replace(',', '.', $_POST['edit_valor']);
    $transacao_descricao = $_POST['edit_descricao'];

    // Certifique-se de que esta função está implementada no EntriesService.php
    $result = editEntry($transacao_id, $categoria_id, $conta_bancaria_id, $transacao_valor, $transacao_descricao);

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
    document.getElementById('edit_categoria').value = entry.categoria_id;
    document.getElementById('edit_conta_bancaria').value = entry.conta_bancaria_id;
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

    <?php include("../../assets/templates/sideBar/BaseSideBar.php")?>

    <div class="main-content">
        <h1>Entradas</h1>
       
        <p>Visualize e gerencie suas entradas financeiras</p>
        
        <div class="flex align-center justify-between">
            <input type="text" placeholder="Buscar entrada..." class="search-input" >
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adicionarModal">Adicionar Entrada</button>

        </div>

        <div class="data-table mt-5">
            <?php
       

            echo "<table class='table'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Descrição</th>";
            echo "<th>Valor</th>";
            echo "<th>Categoria</th>";
            echo "<th>Conta Bancária</th>";
            echo "<th>Ações</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach($entriesList as $entry) {
                echo "<tr>";
                echo "<td>" . (!empty($entry['transacao_id']) ? htmlspecialchars($entry['transacao_id']) : '-') . "</td>";
                echo "<td>" . (!empty($entry['transacao_descricao']) ? htmlspecialchars($entry['transacao_descricao']) : '-') . "</td>";
                echo "<td>" . (!empty($entry['transacao_valor']) ? htmlspecialchars($entry['transacao_valor']) : '-') . "</td>";
                echo "<td>" . (!empty($entry['categoria_id']) ? htmlspecialchars($entry['categoria_id']) : '-') . "</td>";
                echo "<td>" . (!empty($entry['conta_bancaria_id']) ? htmlspecialchars($entry['conta_bancaria_id']) : '-') . "</td>";
                echo "<td>
                        <a class='btn btn-danger btn-sm' id='delete-btn' href='./EntriesView.php?action=delete&id=". htmlspecialchars($entry['transacao_id']) . "'>Deletar</a>
                        <button class='btn btn-primary btn-sm' id='edit-btn' data-bs-toggle='modal' data-bs-target='#editarModal' onClick='handleOpenEditModal(". json_encode($entry) . ")'>Editar</button>
                    </td>";
        
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            ?>
        </div>


    </div>

    <!-- MODAL PARA ADICIONAR ENTRADA -->
        
    <div class="modal fade" id="adicionarModal" tabindex="-1" role="dialog" aria-labelledby="adicionarModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adicionarModalLabel">Adicionar Despeza</h5>
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
                        <label for="categoria" class="form-label mb-1" >Categoria:</label>
                        <select class="form-control" id="categoria" name="categoria" required>
                            <!-- Opções de categoria devem ser preenchidas aqui -->
                            <option value="#" disabled selected hidden>Selecione uma Opção...</option>
                            <option value="1">Alimentação</option>
                            <option value="2">Transporte</option>
                            <option value="3">Educação</option>
                            <option value="4">Lazer</option>
                            <option value="5">Saúde</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="conta_bancaria" class="form-label mb-1">Conta bancaria (opcional):</label>
                        <select class="form-control" id="conta_bancaria" name="conta_bancaria" required>
                            <!-- Opções de categoria devem ser preenchidas aqui -->
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

       <!-- MODAL PARA EDITAR ENTRADA -->
        
       
       <!-- MODAL PARA EDITAR ENTRADA -->
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
                            <label for="edit_categoria" class="form-label mb-1">Categoria:</label>
                            <select class="form-control" id="edit_categoria" name="edit_categoria" required>
                                <option value="1">Alimentação</option>
                                <option value="2">Transporte</option>
                                <option value="3">Educação</option>
                                <option value="4">Lazer</option>
                                <option value="5">Saúde</option>
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
    .search-input{
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
        background-color: #4a90e2;
        color: white;
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

    .close{
        background: none;
        border: none;
    }
</style>