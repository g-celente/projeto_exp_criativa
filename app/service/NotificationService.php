<?php
include __DIR__.'/../model/Notifications.php';
require __DIR__.'/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Retorna as preferências de notificação do usuário logado
 */
function getUserNotificationPreferences() {
    if (!isset($_SESSION['id'])) return false;
    return getNotificationPreferences($_SESSION['id']);
}

/**
 * Atualiza as preferências de notificação do usuário logado
 */
function updateNotificationPreferences($ativo, $dias_antecedencia, $tipo_notificacao) {
    if (!isset($_SESSION['id'])) return false;
    return saveNotificationPreferences($_SESSION['id'], $ativo, $dias_antecedencia, $tipo_notificacao);
}

/**
 * Envia um email usando PHPMailer
 */
function sendNotificationEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Alterar conforme seu provedor
        $mail->SMTPAuth = true;
        $mail->Username = 'contato.guiarendaprevidencia@gmail.com'; // Configurar
        $mail->Password = 'uprw htxp tkyy dckj'; // Configurar
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('contato.guiarendaprevidencia@gmail.com', 'MoneyTrack'); // Configurar
        $mail->addAddress($to);
        $mail->isHTML(true);
        
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Erro no envio: {$mail->ErrorInfo}";
    }
}

/**
 * Verifica e envia notificações pendentes
 */
function checkAndSendNotifications() {
    $reminders = getRemindersToNotify();
    $results = array();

    foreach ($reminders as $reminder) {
        $dias = floor((strtotime($reminder['data_vencimento']) - time()) / (60 * 60 * 24));
        
        $subject = "Lembrete de Vencimento - {$reminder['nome']}";
        $body = "
            <h2>Olá {$reminder['usuario_nome']},</h2>
            <p>Você tem um pagamento se aproximando:</p>
            <ul>
                <li><strong>Nome:</strong> {$reminder['nome']}</li>
                <li><strong>Descrição:</strong> {$reminder['descricao']}</li>
                <li><strong>Data de Vencimento:</strong> " . date('d/m/Y', strtotime($reminder['data_vencimento'])) . "</li>
                <li><strong>Dias Restantes:</strong> {$dias}</li>
            </ul>
            <p>Acesse o sistema para mais detalhes.</p>
        ";

        $sent = sendNotificationEmail($reminder['email'], $subject, $body);
        
        $status = $sent === true ? 'success' : 'error';
        $message = $sent === true ? 'Email enviado com sucesso' : $sent;
        
        logNotification($reminder['id'], $status, $message);
        
        $results[] = array(
            'lembrete_id' => $reminder['id'],
            'status' => $status,
            'mensagem' => $message
        );
    }

    return $results;
}