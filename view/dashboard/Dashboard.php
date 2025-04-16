<!-- TODO: MUDAR O CAMPO DE FILTRO E ASSOCIA-LO AO HISTORICO -->

<?php
include("../../database/db.php");
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../../login.php");
    exit();
}

function getTotalRevenue()
{
    $conn = create_connection();
    $usuario_id = $_SESSION['id']; // Certifique-se de que o usuário está logado

    $query = "SELECT SUM(transacao_valor) AS total_revenue 
              FROM transacoes 
              WHERE usuario_id = '$usuario_id' AND transacao_tipo_id = 1"; // 1 para entrada
    $result = pg_query($conn, $query);

    $row = pg_fetch_assoc($result);
    return $row['total_revenue'] ?? 0;
}
function getTotalExpenses()
{
    $conn = create_connection();
    $usuario_id = $_SESSION['id'];

    $query = "SELECT SUM(transacao_valor) AS total_expenses 
              FROM transacoes 
              WHERE usuario_id = '$usuario_id' AND transacao_tipo_id = 2"; // 2 para saída
    $result = pg_query($conn, $query);

    $row = pg_fetch_assoc($result);
    return $row['total_expenses'] ?? 0;
}
function getTotalBalance()
{
    return getTotalRevenue() - getTotalExpenses();
}
function getRecentTransactions()
{
    $conn = create_connection();
    $usuario_id = $_SESSION['id'];
    $query = "SELECT t.transacao_id,
                     t.transacao_descricao,
                     t.transacao_valor,
                     c.categoria_descricao,
                     t.transacao_tipo_id
                     FROM transacoes t
                     JOIN categorias c ON t.categoria_id = c.categoria_id
                     WHERE t.usuario_id = '$usuario_id'";
    //  ORDER BY t.transacao_id DESC LIMIT 5";
    $result = pg_query($conn, $query);
    return pg_fetch_all($result) ?: [];
}
function getExpensesByCategory()
{
    $conn = create_connection();
    $usuario_id = $_SESSION['id'];

    $query = "SELECT c.categoria_descricao, SUM(t.transacao_valor) AS total
              FROM transacoes t
              JOIN categorias c ON t.categoria_id = c.categoria_id
              WHERE t.usuario_id = '$usuario_id' AND t.transacao_tipo_id = 2
              GROUP BY c.categoria_descricao";
    $result = pg_query($conn, $query);

    return pg_fetch_all($result) ?: [];
}
function getBalanceEvolution()
{
    $conn = create_connection();
    $usuario_id = $_SESSION['id'];

    $query = "SELECT t.transacao_id, 
                     SUM(CASE 
                         WHEN t.transacao_tipo_id = 1 THEN t.transacao_valor 
                         ELSE -t.transacao_valor 
                     END) OVER (ORDER BY t.transacao_id) AS saldo_acumulado
              FROM transacoes t
              WHERE t.usuario_id = '$usuario_id'
              ORDER BY t.transacao_id";
    $result = pg_query($conn, $query);

    return pg_fetch_all($result) ?: [];
}


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
            display: flex;
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

        .filter-panel {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            background-color: #ffffff;
            /* card-bg */
            padding: 16px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            /* border */
        }

        .select-container {
            flex-grow: 1;
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
    </style>
</head>

<body>
    <?php include("../../assets/templates/sideBar/BaseSideBar.php"); ?>
    <div class="container">
        <div class="main-content">
            <header>
                <h1 style="font-family: 'Inter', sans-serif; font-weight:bold;">Relatórios Financeiros</h1>
            </header>

            <!--FILTRO DE MES-->
            <section class="filter-section">
                <div class="period-selector">
                    <div class="select-container">
                        <!-- TEMPORARIO -->
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

            </section>

            <!-- RESUMO DA CONTA -->
            <section class="stats-grid">
                <div class="stat-card">
                    <div class="card-info">
                        <h4 class="card-label">Receita</h4>
                        <h4 class="card-value">R$ <?= number_format(getTotalRevenue(), 2, ',', '.') ?></h4>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(16, 185, 129, 0.1); color: #10B981;">
                        <!-- APLICAR ICONE -->
                        <i class="fas fa-arrow-up"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="card-info">
                        <h4 class="card-label">Despesas</h4>
                        <h4 class="card-value">R$ <?= number_format(getTotalExpenses(), 2, ',', '.') ?></h4>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(249, 115, 22, 0.1); color: #F97316;">
                        <!-- APLICAR ICONE -->
                        <i class="fas fa-arrow-down"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="card-info">
                        <h4 class="card-label">Saldo</h4>
                        <h4 class="card-value">R$ <?= number_format(getTotalBalance(), 2, ',', '.') ?></h4>
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
                    <ul class="tab-list" id="tabs">
                        <li class="tab active" data-tab="overview">Visão Geral</li>
                        <li class="tab" data-tab="despesas">Despesas</li>
                        <li class="tab" data-tab="entradas">Entradas</li>
                    </ul>
                    <!-- <div>
                        <h5>Visão geral</h5>
                    </div> -->

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
                <div class="select-container">
                    <select>
                        <option>Todas</option>
                        <option>Entradas</option>
                        <option>Saidas</option>
                    </select>
                </div>
                <div class="select-container">
                    <input type="number" placeholder="Valor mínimo">
                </div>
                <div class="select-container">
                    <input type="number" placeholder="Valor máximo">
                </div>
                <button class="filter-button">Filtrar</button>
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
                        <?php foreach (getRecentTransactions() as $transaction): ?>
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
            const expensesData = <?= json_encode(getExpensesByCategory()) ?>;
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

            const balanceData = <?= json_encode(getBalanceEvolution()) ?>;
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
        });
    </script>
</body>



</html>