<?php
include("../../protected.php");

include('../../app/service/BankAccountService.php');


$bankAccountsList = getBankAccountsList();

//Criar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    $nome = $_POST['nome'];

    $result = newBankAccount($nome);

    if ($result) {
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } else {
        echo "<script>alert('Erro ao criar entrada.');</script>";
    }
}


//deletar
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $account_id = (int)$_GET['id'];
    var_dump($account_id);
    $result = deleteBankAccount($account_id); 

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
    $account_id = (int)$_POST['account_id'];
    $nome = $_POST['edit_nome'];
  
    $result = editBankAccount($account_id, $nome);

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

const handleOpenEditModal = (account) => {
    document.getElementById('edit_account_id').value = account.id;
    document.getElementById('edit_nome').value = account.nome;
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
        <h1>Contas Bancárias</h1>
       
        <p>Visualize e gerencie suas contas bancárias</p>
        
        <div class="flex align-center justify-between">
            <input type="text" placeholder="Buscar..." class="search-input" >
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adicionarModal">Adicionar Conta</button>

        </div>

        <div class="data-table mt-5">
            <?php
       

            echo "<table class='table'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Nome</th>";
            echo "<th>Ações</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach($bankAccountsList as $account) {
              
                echo "<tr>";
                echo "<td>" . (!empty($account['id']) ? htmlspecialchars($account['id']) : '-') . "</td>";
                echo "<td>" . (!empty($account['nome']) ? htmlspecialchars($account['nome']) : '-') . "</td>";
                echo "<td>
                        <a class='btn btn-danger btn-sm' id='delete-btn' href='./BankAccountsView.php?action=delete&id=". htmlspecialchars($account['id']) . "'>Deletar</a>
                        <button class='btn btn-primary btn-sm' id='edit-btn' data-bs-toggle='modal' data-bs-target='#editarModal' onClick='handleOpenEditModal(". json_encode($account) . ")'>Editar</button>
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
                <h5 class="modal-title" id="adicionarModalLabel">Adicionar Conta Bancária</h5>
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
                    <h5 class="modal-title" id="editarModalLabel">Editar Conta Bancária</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post">
                    <input type="hidden" name="account_id" id="edit_account_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nome" class="form-label mb-1">Descrição:</label>
                            <input type="text" class="form-control" id="edit_nome" name="edit_nome" required>
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
        background-color:rgb(240, 240, 240);
    
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