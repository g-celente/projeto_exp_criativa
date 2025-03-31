<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuários</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <h2 class="text-center mb-4">Lista de Usuários</h2>

        <div class="card shadow">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $resultados = selectUser();

                        // Caso não existam registros
                        if (!$resultados) {
                            echo "<tr><td colspan='3'>Nenhum usuário encontrado.</td></tr>";
                        } else {
                            // Percorre o array associativo
                            foreach ($resultados as $resultado) {
                                //BOTOES ADD-DELETE
                                echo "<tr>
                                        <td>{$resultado['name']}</td>
                                        <td>{$resultado['email']}</td>
                                        <td>
                                            <a href='index.php?page=2&id={$resultado['id']}' class='btn btn-warning btn-sm'>Alterar</a>
                                            <a href='index.php?page=2&method=delete&id={$resultado['id']}' class='btn btn-danger btn-sm'>Excluir</a>
                                        </td>

                                      </tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>