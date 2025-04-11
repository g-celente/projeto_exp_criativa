<?php

include_once __DIR__ . '/../../database/db.php';

/*
tabela de conta_bancaria

- id
- nome
- usuario_id

*/

function getBankAccountsList(){
    $conn = create_connection();
    $usuario_id = $_SESSION['id'];

    $query = "SELECT * FROM conta_bancaria WHERE usuario_id = '$usuario_id'";
    $result = pg_query($conn, $query);


    return $result ? pg_fetch_all($result) : false;
}

