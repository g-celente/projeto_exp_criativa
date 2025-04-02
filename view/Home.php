<?php
include("../protected.php");
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar"><?php include("../assets/templates/sideBar/BaseSideBar.php"); ?></aside>

        <div class="main-content">
            <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
            <p>Conteúdo principal do seu painel aqui</p>
            <!-- Restante do seu conteúdo -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap');

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Open Sans', sans-serif;
        background-color: #ecf0f1;
        overflow-x: hidden;
    }

    .wrapper {
        display: flex;
        min-height: 100vh;
        width: 100%;
    }


    /* Seus outros estilos da sidebar aqui */
</style>