<?php 

include 'app/service/UserService.php';

include 'app/model/User.php';

function renderPage($page, $withSidebar = true) {
    if ($withSidebar) {
        include 'assets/templates/sideBar/sideBar.html';
    }
    include $page;
}

if (isset($_GET['page'])) {

    if ($_GET['page'] == 1 && $_GET['method']) {

        if ($_GET['method'] == 'login') {
            //
        }

        if ($_GET['method'] == "register") {
            renderPage('app/view/auth/CadastroView.php', false);
            register();
        }
    }

    if ($_GET['page'] == 2) {
        if (isset($_GET['method'])) {
            if ($_GET['method'] == 'delete') {
                deleteUserById();
                renderPage('app/view/Home.php');
            } elseif ($_GET['method'] == 'alter') {
                editUserById();
                renderPage('app/view/Home.php');
            }
        } elseif (isset($_GET['id'])) {
            renderPage('app/view/User/EditUserView.php');
        }
    }

    

} else {
    renderPage('app/view/User/UserTableView.php', true);
}

?>
