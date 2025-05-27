<?php
include __DIR__ . '/../model/Reminders.php';



function listReminders(){
    return getRemindersByUser();
}
function addReminder( $nome , $descriacao , $data_vencimento){
    return createReminder( $nome , $descriacao , $data_vencimento);
}
function deleteReminder($lembrete_id){
    return deleteReminderById($lembrete_id);
}
function updateReminder($lembrete_id , $nome , $descricao , $data_vencimento){
    return editReminder($lembrete_id , $nome , $descricao , $data_vencimento);
}
function updatePaidReminder($lembrete_id, $nome, $descricao, $data_vencimento, $data_pagamento ) {
    return editPaidReminder($lembrete_id, $nome, $descricao, $data_vencimento, $data_pagamento );
}



function setStatus($lembrete_id , $data_pagamento){
    return setAsPaid($lembrete_id , $data_pagamento);
}

?>