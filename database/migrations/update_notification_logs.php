<?php
include __DIR__ . '/../database/db.php';

// Execute this SQL to create or update the notification_logs table
$sql = "DROP TABLE IF EXISTS notification_logs;

CREATE TABLE notification_logs (
    id SERIAL PRIMARY KEY,
    lembrete_id INTEGER NOT NULL REFERENCES lembretes(id),
    status VARCHAR(20) NOT NULL CHECK (status IN ('success', 'error', 'pending')),
    mensagem TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";

$conn = create_connection();
$result = pg_query($conn, $sql);

if (!$result) {
    die("Erro ao criar tabela: " . pg_last_error($conn));
}

echo "Tabela notification_logs atualizada com sucesso!";
