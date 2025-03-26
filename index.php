<?php 

    //SERVICES
    include 'app/service/UserService.php';

    //MODELS
    include 'app/model/User.php';

    if (isset($_GET['page'])) {

        //PÁGINAS DE LOGIN E REGISTRO
        if ($_GET['page'] == 1 && $_GET['method']) {

            if ($_GET['method'] == 'login') {
                //
            }

            if($_GET['method'] == "register"){
                register();
            }
            
        }
    }
     
    else {
        include 'app/view/Home.php';
    }

?>