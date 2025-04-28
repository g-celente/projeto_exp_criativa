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
        $_SESSION['email'] = $user['email']; // Armazena o email do usuário na sessão
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

function update_user($userId, $name, $email, $current_password = null, $new_password = null) {
    $conn = create_connection();

    if (!$conn) {
        return "Erro ao conectar ao banco de dados.";
    }

    $userId = pg_escape_string($conn, $userId);
    $name = pg_escape_string($conn, $name);
    $email = pg_escape_string($conn, $email);

    // Verifica se o email já existe para outro usuário
    $check_query = "SELECT id FROM users WHERE email = '$email' AND id != '$userId'";
    $check_result = pg_query($conn, $check_query);

    if (pg_num_rows($check_result) > 0) {
        return "Email já está em uso por outro usuário.";
    }

    // Se uma nova senha foi fornecida
    if ($new_password !== null) {
        $current_password = pg_escape_string($conn, $current_password);
        $new_password = pg_escape_string($conn, $new_password);

        // Verifica se a senha atual está correta
        $check_password_query = "SELECT password FROM users WHERE id = '$userId'";
        $check_password_result = pg_query($conn, $check_password_query);
        $user = pg_fetch_assoc($check_password_result);

        if ($user['password'] !== $current_password) {
            return "Senha atual incorreta.";
        }

        // Atualiza com a nova senha
        $query = "UPDATE users SET name = '$name', email = '$email', password = '$new_password' WHERE id = '$userId'";
    } else {
        // Atualiza apenas nome e email
        $query = "UPDATE users SET name = '$name', email = '$email' WHERE id = '$userId'";
    }

    $result = pg_query($conn, $query);

    if (!$result) {
        return "Erro ao atualizar os dados.";
    }

    return true;
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
