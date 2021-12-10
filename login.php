<?php

require_once('init.php');
require_once('queries/user-by-email.php');

if (isset($_SESSION['user'])) {
    header("Location: 404.php");
    exit();
}

global $db_conn;
$categories_list = get_categories($db_conn);

if (!$categories_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $login_validation_rules;
    $filteredData = filterDataByRules($_POST, $login_validation_rules);

    $errors = validateForm($filteredData, $login_validation_rules);

    if (empty($errors)) {
        $user = get_user_by_email($db_conn, $filteredData['email']);

        if (!is_array($user) && !$user) {
            $error = get_db_error($db_conn);
            show_error($error);
            exit();
        }

        if (!count($user)) {
            $errors['email'] = 'Пользователь с такой почтой не найден';
        } else {
            if (password_verify($filteredData['password'], $user[0]['password'])) {
                $_SESSION['user'] = $user;
                header("Location: index.php");
            } else {
                $errors['password'] = 'Неверный пароль';
            }
        }
    }
}

show_screen('login.php', 'Аутентификация', 'errors', $errors, $categories_list);
