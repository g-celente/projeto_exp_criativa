<?php
include_once __DIR__ . '/../../database/db.php';    


function getRemindersByUser(){
    $conn = create_connection();
    $usuario_id = $_SESSION['id'];
    $query = "SELECT * FROM lembretes WHERE usuario_id = $1 ORDER BY data_vencimento ASC";
    $stmt_name = 'get_reminders_'.uniqid();
    pg_prepare($conn , $stmt_name , $query);
    $result = pg_execute($conn , $stmt_name , [$usuario_id]);
    return $result ? pg_fetch_all($result) : [];
}

function getRemindersByUserPago() {
    $conn = create_connection();
    $usuario_id = $_SESSION['id'];
    $query = "SELECT * FROM lembretes WHERE usuario_id = $1 AND status = 'pago' ORDER BY data_pagamento ASC";
    $stmt_name = 'get_reminders_' . uniqid();
    pg_prepare($conn, $stmt_name, $query);
    $result = pg_execute($conn, $stmt_name, [$usuario_id]);
    return $result ? pg_fetch_all($result) : [];
}

function createReminder( $nome , $descricao , $data_vencimento, $valor){ //arrunar
    $conn = create_connection();
    $usuario_id = $_SESSION['id'];
    $descricao = pg_escape_string($conn , $descricao);
    $query = "INSERT INTO lembretes (usuario_id , nome , descricao , data_vencimento, valor)
              VALUES ($1 ,$2 , $3 , $4, $5)";
    $stmt_name = 'create_reminder_' . uniqid();
    pg_prepare($conn , $stmt_name , $query);
    $result = pg_execute($conn , $stmt_name , [$usuario_id , $nome , $descricao , $data_vencimento, $valor]);
    return $result ? true : false;
}

function deleteReminderById($lembrete_id){
    $conn = create_connection();
    $lembrete_id = pg_escape_string($conn, $lembrete_id);
    $query = "DELETE FROM lembretes WHERE id = $1";
    $stmt_name = 'delete_reminder_' . uniqid();
    pg_prepare($conn , $stmt_name , $query);
    $result = pg_execute($conn , $stmt_name , [$lembrete_id]);
    return $result ? true:false;
}
function editReminder($lembrete_id , $nome , $descricao , $data_vencimento){
    $conn = create_connection();
    
    $query = "UPDATE lembretes SET 
                    nome = $1,
                    descricao = $2,
                    data_vencimento=$3
                WHERE id = $4";
    
    $stmt_name = 'edit_reminder_'.uniqid();
    pg_prepare($conn , $stmt_name , $query);
    $result = pg_execute($conn , $stmt_name , [$nome , $descricao , $data_vencimento , $lembrete_id]);
    return $result ? true : false;
}
function editPaidReminder($lembrete_id, $nome, $descricao, $data_vencimento, $data_pagamento ) {
    $conn = create_connection();

    $query = "UPDATE lembretes SET nome = $1, descricao = $2, data_vencimento = $3, data_pagamento = $4 WHERE id = $5";
    $stmt_name = 'edit_reminder_pagamento_'.uniqid();
    pg_prepare($conn, $stmt_name, $query);
    $result = pg_execute($conn, $stmt_name, [$nome, $descricao, $data_vencimento, $data_pagamento, $lembrete_id ]);

    return $result ? true : false;
}


function setAsPaid($lembrete_id , $data_pagamento){
    $conn = create_connection();
    $query = "UPDATE lembretes SET status ='pago' , data_pagamento = $1 WHERE id = $2";
    $stmt_name = 'marcar_pago_'.uniqid();
    pg_prepare($conn , $stmt_name , $query);
    $result = pg_execute($conn , $stmt_name , [$data_pagamento , $lembrete_id]);
    return $result ? true:false; 
}


?>