<?php
/**
 * Script para verificação automática de vencimentos e envio de notificações
 * Deve ser executado diariamente via CRON
 */

// Inicia a sessão e inclui dependências necessárias
session_start();
include __DIR__ . '/../config.php';
include __DIR__ . '/../app/service/NotificationService.php';

// Define timezone
date_default_timezone_set('America/Sao_Paulo');

// Log file para registro de execução
$logFile = __DIR__ . '/notification_cron.log';

function writeLog($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

try {
    writeLog("Iniciando verificação de notificações...");
    
    // Verifica e envia as notificações
    $results = checkAndSendNotifications();
    
    // Registra resultados
    foreach ($results as $result) {
        $message = "Lembrete #{$result['lembrete_id']}: {$result['status']} - {$result['mensagem']}";
        writeLog($message);
    }
    
    writeLog("Verificação concluída. " . count($results) . " notificações processadas.");
    
} catch (Exception $e) {
    writeLog("ERRO: " . $e->getMessage());
    exit(1);
}

exit(0);