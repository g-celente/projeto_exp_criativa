<?php
require __DIR__ . '/../../vendor/autoload.php';
include("../model/User.php");

function GerarExtrato() {
    $transacoes = getAllTransactionByUser();
    if (!$transacoes) return ['success' => false, 'message' => 'Nenhuma transação encontrada'];

    try {
        $pdf = new FPDF();
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->Cell(0, 10, utf8_decode('Extrato de conta corrente - Últimos 90 dias'), 0, 1, 'L', true);

        $pdf->Ln(2);
        $pdf->SetFont('Arial', '', 10);
        $usuario = getUserById($_SESSION['id']);
        $pdf->Cell(100, 6, utf8_decode("Nome: {$usuario['name']}"), 0);
        $pdf->Cell(90, 6, utf8_decode("Data de emissão: ") . date('d/m/Y'), 0, 1, 'R');
        $pdf->Cell(100, 6, utf8_decode("Agência: {$usuario['agencia']}"), 0);
        $pdf->Cell(90, 6, utf8_decode("Conta: {$usuario['conta']}"), 0, 1, 'R');

        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(25, 8, 'Data', 1, 0, 'C', true);
        $pdf->Cell(90, 8, utf8_decode('Lançamento'), 1, 0, 'L', true);
        $pdf->Cell(40, 8, 'Valor (R$)', 1, 0, 'R', true);
        $pdf->Cell(35, 8, 'Saldo (R$)', 1, 1, 'R', true);

        $pdf->SetFont('Arial', '', 9);
        $saldo = 0;
        foreach ($transacoes as $tx) {
            $data = date('d/m', strtotime($tx['transacao_data']));
            $desc = utf8_decode($tx['transacao_descricao']);
            $valor = number_format($tx['transacao_valor'], 2, ',', '.');
            $tipo = $tx['transacao_tipo_id'];
            $valorFloat = (float)$tx['transacao_valor'];
            $saldo += ($tipo == 1) ? $valorFloat : -$valorFloat;
            $saldoFormat = number_format($saldo, 2, ',', '.');

            $pdf->Cell(25, 6, $data, 1);
            $pdf->Cell(90, 6, substr($desc, 0, 45), 1);
            $pdf->SetTextColor(($tipo == 1) ? 0 : 255, 0, 0);
            $pdf->Cell(40, 6, ($tipo == 1 ? '' : '-') . 'R$ ' . $valor, 1, 0, 'R');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(35, 6, 'R$ ' . $saldoFormat, 1, 1, 'R');
        }

        $pdf->Output('D', 'extrato_' . date('Ymd_His') . '.pdf');
        exit;
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Erro ao gerar PDF: ' . $e->getMessage()];
    }
}

$resultado = GerarExtrato();
