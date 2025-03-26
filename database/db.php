<?php 

function create_connection() {
    $db_host = "127.0.0.1:3306";
    $db_username = "financial_project";
    $db_user = "root";
    $db_password = "root";
    $connect = new mysqli($db_host, $db_user, $db_password, $db_username);
    return $connect;
}

