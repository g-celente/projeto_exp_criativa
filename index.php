<?php 

    ob_start(); // Inicia o buffer de saída

    //SERVICES
    include 'app/service/UserService.php';

    //MODELS
    include 'app/model/User.php';

    if (isset($_GET['page'])) {

        //PÁGINAS DE LOGIN E REGISTRO
        if ($_GET['page'] == 1) {

            if ($_GET['method'] == 'login') {
                //
            }

            if($_GET['method'] == "register"){
                register();
            }
            
        }
    }
     
    else {
        include 'app/view/auth/CadastroView.php';
    }

?>