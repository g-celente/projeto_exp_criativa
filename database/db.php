<?php 

function create_connection() {
    $db_host = "ep-curly-mountain-a5lhcbd0-pooler.us-east-2.aws.neon.tech";
    $db_name = "neondb";
    $db_user = "neondb_owner";
    $db_password = "npg_KWcg9shBZ4ki";
    $endpoint_id = "ep-curly-mountain-a5lhcbd0"; 

    $connection_string = "host=$db_host dbname=$db_name user=$db_user password=$db_password sslmode=require options=endpoint=$endpoint_id";

    $connect = pg_connect($connection_string);

    if (!$connect) {
        die("Erro de conexão: " . pg_last_error());
    }

    return $connect;
}

?>
