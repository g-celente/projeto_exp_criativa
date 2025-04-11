<?php

include_once __DIR__ . '/../../database/db.php';

/*
tabela de categorias

- categoria_id
- categoria_descricao
*/

function getCategoriesList(){
    $conn = create_connection();

    $query = "SELECT * FROM categorias";
    $result = pg_query($conn, $query);


    return $result ? pg_fetch_all($result) : false;
}

