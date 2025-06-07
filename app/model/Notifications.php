<?php
include_once __DIR__ . '/../../database/db.php';

/**
 * Busca as preferências de notificação do usuário
 */
function getNotificationPreferences($usuario_id) {
    $conn = create_connection();
    $stmt_name = 'get_notification_preferences_'.uniqid();
    
    $query = "SELECT * FROM notification_preferences WHERE usuario_id = $1";
    pg_prepare($conn, $stmt_name, $query);
    $result = pg_execute($conn, $stmt_name, array($usuario_id));
    
    if (!$result) {
        return null;
    }
    return pg_fetch_assoc($result);
}

/**
 * Salva ou atualiza as preferências de notificação do usuário
 */
function saveNotificationPreferences($usuario_id, $ativo, $dias_antecedencia, $tipo_notificacao) {
    $conn = create_connection();
    $stmt_name = 'save_notification_preferences_'.uniqid();
    
    $query = "INSERT INTO notification_preferences (usuario_id, ativo, dias_antecedencia, tipo_notificacao) 
              VALUES ($1, $2, $3, $4)
              ON CONFLICT (usuario_id) 
              DO UPDATE SET 
                ativo = $2,
                dias_antecedencia = $3,
                tipo_notificacao = $4";
                
    pg_prepare($conn, $stmt_name, $query);
    $result = pg_execute($conn, $stmt_name, array($usuario_id, $ativo, $dias_antecedencia, $tipo_notificacao));
    
    return $result ? true : false;
}

/**
 * Busca os lembretes que precisam de notificação
 * com base nas preferências de cada usuário
 */
function getRemindersToNotify() {
    $conn = create_connection();
    $stmt_name = 'get_reminders_to_notify_'.uniqid();
    
    $query = "
        SELECT 
            l.id,
            l.nome,
            l.descricao,
            l.data_vencimento,
            l.usuario_id,
            u.email,
            u.name as usuario_nome,
            np.dias_antecedencia,
            np.tipo_notificacao
        FROM lembretes l
        INNER JOIN users u ON l.usuario_id = u.id
        INNER JOIN notification_preferences np ON l.usuario_id = np.usuario_id
        WHERE 
            l.status = 'pendente'
            AND np.ativo = true 
            AND l.data_vencimento BETWEEN 
                CURRENT_DATE + INTERVAL '1 day'
                AND CURRENT_DATE + (np.dias_antecedencia || ' days')::INTERVAL";
                
    pg_prepare($conn, $stmt_name, $query);
    $result = pg_execute($conn, $stmt_name, array());
    
    return pg_fetch_all($result) ?: array();
}

/**
 * Registra log de notificação enviada
 */
function logNotification($lembrete_id, $status, $mensagem) {
    $conn = create_connection();
    $stmt_name = 'log_notification_'.uniqid();
    
    // Validação do status
    if (!in_array($status, ['success', 'error', 'pending'])) {
        $status = 'error';
    }
    
    $query = "INSERT INTO notification_logs (lembrete_id, status, mensagem)
              VALUES ($1, $2, $3)";
              
    pg_prepare($conn, $stmt_name, $query);
    $result = pg_execute($conn, $stmt_name, array($lembrete_id, $status, $mensagem));
    
    return $result ? true : false;
}