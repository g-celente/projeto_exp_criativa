<?php

include("../model/User.php");

function deleteAccount(): void {
    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['id'])) {
        header("Location: ../../view/auth/LoginView.php");
        exit();
    }

    $userID = $_SESSION['id'];

    $result = deleteUser($userID); 

    if($result) {
        session_destroy();
        session_unset(); 

        header("Location: ../../view/auth/CadastroView.php");
        exit(); 
    } else {
        header("Location: ../../view/profile/ProfileView.php?error=delete_failed");
        exit();
    }
}

deleteAccount();

?>