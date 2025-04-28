<?php

// Inclui o arquivo "User.php" que contém funcoes relacionadas ao banco de dados
// Register() chama create_user() e deleteUser() em "User.php" 
include __DIR__.'/../model/User.php';

function login($email, $senha) {
    if (empty($email)) {                // Verifica se o email está vazio
        return "Preencha seu e-mail.";
    }
    if (empty($senha)) {                // Verifica se a senha está vazia
        return "Preencha sua senha.";
    }

    $user = authenticate($email, $senha);   // Chama a função authenticate definida em User.php para verificar as credenciais no banco de dados.

    if (is_array($user)) {                  // Verifica se user é um array, o que indica que as credenciais estão corretas.
        if (!isset($_SESSION)) {            // Verifica se a sessão não foi iniciada
            session_start();                // Inicia a sessão
        }
        $_SESSION['id'] = $user['id'];      // Armazena o ID do usuário na sessão
        $_SESSION['name'] = $user['name'];  // Armazena o nome do usuário na sessão
        return true;                        // Indica que a operação foi bem-sucedida
    }

    return $user;                           // Retorna a mensagem de erro específica
}


function register($name, $email, $cpf, $password) {
    $result = create_user($name, $email, $cpf, $password);  //Chama a função create_user para inserir os dados do usuário no banco de dados.

    if ($result === true) {                     // Verifica se a operação foi bem-sucedida
        header("Location: LoginView.php");      // Redireciona para a página de login
        exit();                                 // Encerra o script após o redirecionamento
    //TODO: Adicionar verificação de email ja cadastrado
    } else {
        return $result;                         // Retorna a mensagem de erro específica
    }
}

function deleteUserById($userId) {
    if (deleteUser($userId)) {                 // Verifica se a operação de deletar o usuário for bem-sucedida
        header("Location: index.php");         // Redireciona para a página inicial
        exit();
    } else {
        echo "Erro ao deletar o usuário.";
    }
}

function logout() {

    if(isset($_SESSION)) {
        session_destroy();
        header("Location: ../../view/auth/LoginView.php");
    }

}


// CRUD Cartoes
// function createCard($user_id, $card_name, $card_number, $expiration_date, $card_type) {
//     return create_card($user_id, $card_name, $card_number, $expiration_date, $card_type);
// }

// function listCards($user_id) {
//     return list_cards($user_id);
// }

// function updateCard($card_id, $card_name, $card_number, $expiration_date, $card_type) {
//     return update_card($card_id, $card_name, $card_number, $expiration_date, $card_type);
// }

// function deleteCard($card_id) {
//     return delete_card($card_id);
// }
