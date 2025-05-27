<?php
include_once __DIR__ . '../../../../protected.php';
include_once __DIR__ . '../../../../database/db.php';

function calendarList(){
    $conn = create_connection();
    $usuario_id = $_SESSION['id'];
    $query = "SELECT id, nome, descricao, data_vencimento , status  FROM lembretes WHERE usuario_id = $1 ORDER BY data_vencimento ASC";
    $stmt_name = 'get_reminders_'.uniqid();
    pg_prepare($conn , $stmt_name , $query);
    $result = pg_execute($conn , $stmt_name , [$usuario_id]);
    $event=[];


    if ($result) {
        while ($row = pg_fetch_assoc($result)) {
            $color='';
                if($row['status']!=='pendente'){
                    $color='40e0d0';
                }
                else{
                    $color='#FF0000';
                }

            $event[] = [
                'id' => $row['id'],
                'title' => $row['nome'],
                'description' => $row['descricao'],
                'color'=> $color,
                'start' => $row['data_vencimento'],
                'end' => $row['data_vencimento'],
                'status' => $row['status']
            ];
        };
    };
    return $event;
}

header('Content-Type: application/json');
echo json_encode(calendarList());

// id
// title
// color
// start
// end

?>