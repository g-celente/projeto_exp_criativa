<!-- IMPLEMENTAR OS FILTROS PARA O HISTORICO DE TRANSACOES -->

<?php
include("../../app/service/DashboardService.php");
include('../../app/service/CategoriesService.php');
include("../../protected.php");
$categoriesList = listCategories();

?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios Financeiros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
    <link rel="stylesheet" href="../../assets/css/main.css">
</head>



<body>
    <?php include("../../assets/templates/sideBar/BaseSideBar.php"); ?>
    
    <div class="container">
        <?php include("../../assets/templates/navBar/navBar.php"); ?>
        <div class="main-content">
            <!-- <header>
                <h1 style="font-family: 'Inter', sans-serif; font-weight:bold;">Relatórios Financeiros</h1>
            </header> -->

            <!--FILTRO DE MES-->
            <!-- <section class="filter-section">
                <div class="period-selector">
                    <div class="select-period-container" style="flex-grow: 1;">
                        <select>
                            <option>Historico geral</option>
                            <option>Abril 2025</option>
                            <option>Março 2025</option>
                            <option>Junho 2025</option>
                            <option>Julho 2025</option>
                        </select>
                    </div>
                    <button class="filter-button">Aplicar</button>
                </div>

            </section> -->

            <!-- RESUMO/DADOS DA CONTA -->
            <section class="stats-grid">
                <div class="stat-card">
                    <div class="card-info">
                        <h4 class="card-label">Receita</h4>
                        <h4 class="card-value">R$ <?= number_format(getTotalRevenueService(), 2, ',', '.') ?></h4>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(16, 185, 129, 0.1); color: #10B981;">
                        <!-- APLICAR ICONE -->
                        <i class="fas fa-arrow-up"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="card-info">
                        <h4 class="card-label">Despesas</h4>
                        <h4 class="card-value">R$ <?= number_format(getTotalExpensesService(), 2, ',', '.') ?></h4>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(249, 115, 22, 0.1); color: #F97316;">
                        <!-- APLICAR ICONE -->
                        <i class="fas fa-arrow-down"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="card-info">
                        <h4 class="card-label">Saldo</h4>
                        <h4 class="card-value">R$ <?= number_format(getTotalBalanceService(), 2, ',', '.') ?></h4>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(110, 89, 165, 0.1); color: #6E59A5;">
                        <!-- APLICAR ICONE -->
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </section>


            <!-- TABELA -->
            <section class="charts-section">
                <div class="tabs">

                    <div class="tab-content" id="tabContent">
                        <div class="tab-panel" id="overview">
                            <div class="charts-grid">
                                <div class="chart">
                                    <p>Gráfico de Despesas</p>
                                    <div style="position: relative; width: 100%; max-width: 400px;">
                                        <canvas id="expensesPieChart"></canvas>
                                    </div>
                                </div>
                                <div class="chart">
                                    Gráfico de Saldo
                                    <br>
                                    <canvas id="balanceLineChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- <section class="comparative-section">
                <h3 class="chart-title">Análise comparativa</h3>
                <DIV class="chart" style="height:400px;">
                    Comparativo entre periodos
                    <br>
                </DIV>
            </section> -->


            <!-- HISTORICO -->
            <div class="filter-panel">
                <div class="filter-select-container">
                    <div class="select-container mr-1">
                        <select id="tipoFiltro">
                            <option value="">Todos</option>
                            <option value="Entrada">Entradas</option>
                            <option value="Saída">Saídas</option>
                        </select>
                    </div>

                    <div class="select-container mr-1">
                        <select id="categoriaFiltro">
                            <option value="">Todas</option>
                            <?php
                            foreach ($categoriesList as $category) {
                                echo "<option value='" . htmlspecialchars($category['categoria_descricao']) . "'>" . htmlspecialchars($category['categoria_descricao']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="select-container mr-1">
                        <input id="valorMinFiltro" type="number" step="0.01" placeholder="Valor mínimo">
                    </div>

                    <div class="select-container mr-1">
                        <input id="valorMaxFiltro" type="number" step="0.01" placeholder="Valor máximo">
                    </div>

                    <div class="select-container mr-1">
                        <button id="aplicarFiltros" class="btn btn-primary">Filtrar</button>
                    </div>
                </div>
            </div>


            <section class="table-container">
                <h3 class="chart-title">Ultimas transacoes</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Descrição</th>
                            <th>Categoria</th>
                            <th>Tipo</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (getRecentTransactionsService() as $transaction): ?>
                            <tr>
                                <td><?= htmlspecialchars($transaction['transacao_id']) ?></td> <!--DATA-->
                                <td><?= htmlspecialchars($transaction['transacao_descricao']) ?></td> <!--DESCRICAO-->
                                <td><?= htmlspecialchars($transaction['categoria_descricao']) ?></td> <!--CATEGORIA-->
                                <td>
                                    <span class="transaction-type <?= $transaction['transacao_tipo_id'] == 1 ? 'transaction-revenue' : 'transaction-expense' ?>">
                                        <?= $transaction['transacao_tipo_id'] == 1 ? 'Entrada' : 'Saída' ?>
                                    </span>
                                </td>
                                <td>R$ <?= number_format($transaction['transacao_valor'], 2, ',', '.') ?></td> <!--VALOR-->
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            //GRAFICOS 
            const expensesData = <?= json_encode(getExpensesByCategoryService()) ?>;
            const labels = expensesData.map(item => item.categoria_descricao);
            const data = expensesData.map(item => parseFloat(item.total));
            const ctx = document.getElementById('expensesPieChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Despesas por Categoria',
                        data: data,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                    }]
                }
            });

            const balanceData = <?= json_encode(getBalanceEvolutionService()) ?>;
            const ids = balanceData.map(item => item.transacao_id);
            const saldo = balanceData.map(item => parseFloat(item.saldo_acumulado));
            const ctx2 = document.getElementById('balanceLineChart').getContext('2d');
            new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: ids,
                    datasets: [{
                        label: 'Evolução do Saldo',
                        data: saldo,
                        borderColor: '#36A2EB',
                        fill: false,
                        tension: 0.1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'ID da Transação'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Saldo Acumulado (R$)'
                            }
                        }
                    }
                }
            });



            //MECANISMO DE BUSCA
            // document.addEventListener('DOMContentLoaded', function() {
            //     const searchInput = document.querySelector('#search-field input'); // Campo de busca
            //     const tableRows = document.querySelectorAll('.table tbody tr');

            //     searchInput.addEventListener('input', function() {
            //         const searchTerm = this.value.toLowerCase(); // Texto digitado no campo de busca
            //         tableRows.forEach(row => {
            //             const descricao = row.querySelector('td:nth-child(2)');
            //             const busca = descricao ? descricao.textContent.toLowerCase() : '';
            //             row.style.display = busca.includes(searchTerm) ? '' : 'none';
            //         });
            //     });
            // });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const tipoFiltro = document.getElementById('tipoFiltro');
            const categoriaFiltro = document.getElementById('categoriaFiltro');
            const valorMinFiltro = document.getElementById('valorMinFiltro');
            const valorMaxFiltro = document.getElementById('valorMaxFiltro');
            const aplicarFiltros = document.getElementById('aplicarFiltros');
            const tableRows = document.querySelectorAll('table tbody tr');

            aplicarFiltros.addEventListener('click', function() {
                const tipoSelecionado = tipoFiltro.value;
                const categoriaSelecionada = categoriaFiltro.value;
                const valorMin = parseFloat(valorMinFiltro.value) || 0;
                const valorMax = parseFloat(valorMaxFiltro.value) || Infinity;

                tableRows.forEach(row => {
                    const tipo = row.querySelector('td:nth-child(4) span').innerText.trim();
                    const categoria = row.querySelector('td:nth-child(3)').innerText.trim();
                    const valorText = row.querySelector('td:nth-child(5)').innerText.trim().replace('R$', '').replace('.', '').replace(',', '.');
                    const valor = parseFloat(valorText);

                    let mostrar = true;

                    if (tipoSelecionado && tipo !== tipoSelecionado) {
                        mostrar = false;
                    }
                    if (categoriaSelecionada && categoria !== categoriaSelecionada) {
                        mostrar = false;
                    }
                    if (valor < valorMin || valor > valorMax) {
                        mostrar = false;
                    }

                    row.style.display = mostrar ? '' : 'none';
                });
            });
        });
    </script>
</body>

</html>


<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: #f9fafb;
        /* bg-main */
        color: #333333;
        /* text-main */
        line-height: 1.5;
    }

    .container {
        min-height: 100vh;
        max-width: 95%;
        margin: auto;
    }

    /* MAIN-CONTENT */
    .main-content {
        flex-grow: 1;
        /* margin-left: 200px; Espaço reservado para a sidebar */
        padding: 24px;
        transition: all 0.3s ease;
    }

    .main-header {
        margin-bottom: 24px;
    }

    .main-header h1 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 16px;
    }

    /* FILTROS */
    .filter-section {
        margin-bottom: 24px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .period-selector {
        display: flex;
        gap: 8px;
        align-items: center;
        justify-content: space-between;
        background-color: #ffffff;
        /* card-bg */
        padding: 16px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        /* border */
    }



    select,
    input,
    button {
        padding: 8px 12px;
        border: 1px solid #e5e7eb;
        /* border */
        border-radius: 4px;
        font-family: inherit;
    }

    select {
        width: 100%;
    }

    .filter-button {
        background-color: #3498db;
        /* primary */
        color: white;
        border: none;
        border-radius: 4px;
        padding: 8px 16px;
        cursor: pointer;
    }

    .filter-button:hover {
        background-color: #3498db;
        /* primary-dark */
    }

    /* STATS CARDS */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background-color: #ffffff;
        /* card-bg */
        border-radius: 8px;
        padding: 16px;
        border: 1px solid #e5e7eb;
        /* border */
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-info {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .card-label {
        color: #6b7280;
        /* text-muted */
        font-size: 14px;
    }

    .card-value {
        font-family: 'Inter', sans-serif;
        font-size: 24px;
        font-weight: 700;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    /* CHART TABS */
    .tabs {
        margin-bottom: 24px;
        border-bottom: 1px solid #e5e7eb;
        /* border */
    }

    .tab-list {
        display: flex;
        list-style: none;
    }

    .tab {
        padding: 8px 16px;
        cursor: pointer;
        border-bottom: 2px solid transparent;
    }

    .tab.active {
        color: #6E59A5;
        /* primary */
        border-bottom-color: #6E59A5;
        /* primary */
    }

    .tab-content {
        background-color: #ffffff;
        /* card-bg */
        border: 1px solid #e5e7eb;
        /* border */
        border-radius: 8px;
        padding: 16px;
        margin-top: 16px;
    }

    /* GRAFICOS */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 16px;
    }

    .chart {
        background-color: #E5DEFF;
        border-radius: 8px;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        height: auto;
        width: 100%;
    }

    /* GRAFICO DE COMPARACAO */
    .comparative-section {
        background-color: #ffffff;
        /* card-bg */
        border: 1px solid #e5e7eb;
        /* border */
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
    }

    .chart-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 16px;
    }

    /* HISTORICO */
    .table-container {
        background-color: #ffffff;
        /* card-bg */
        border: 1px solid #e5e7eb;
        /* border */
        border-radius: 8px;
        padding: 16px;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
        /* border */
    }

    th {
        font-weight: 600;
        color: #6b7280;
        /* text-muted */
    }

    .transaction-type {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }

    .transaction-expense {
        background-color: rgba(249, 115, 22, 0.1);
        /* expense background */
        color: #F97316;
        /* expense */
    }

    .transaction-revenue {
        background-color: rgba(16, 185, 129, 0.1);
        /* revenue background */
        color: #10B981;
        /* revenue */
    }

    canvas {
        width: 100% !important;
        height: auto !important;
    }

    /* TRANSACTIONS FILTER */
    .filter-panel {
        display: flex;
        flex-direction: row;
        flex-grow: 1;
        justify-content: space-evenly;
        gap: 8px;
        background-color: #ffffff;
        /* card-bg */
        padding: 16px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        /* border */
    }

    .filter-select-container {
        display: flex;
        flex-direction: row;
        flex-grow: 1;
        /* gap: 8px;  */
        align-items: center;
    }

    .select-container {
        width: 100%;
    }
</style>