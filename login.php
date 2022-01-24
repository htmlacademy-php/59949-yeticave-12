<?php

$db_conn = require_once('init.php');
$rules = require_once('rules.php');
require_once('queries/user-by-email.php');

if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$categories_list = get_categories($db_conn);

if (!$categories_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filteredData = filterDataByRules($_POST, $rules['login']);

    $errors = validateForm($filteredData, $rules['login']);

    if (empty($errors)) {
        $user = get_user_by_email($db_conn, $filteredData['email']);

        if (!is_array($user) && !$user) {
            $error = get_db_error($db_conn);
            show_error($error);
            exit();
        }

        if (empty($user)) {
            $errors['email'] = 'Пользователь с такой почтой не найден';
        } elseif (password_verify($filteredData['password'], $user[0]['password'])) {
            $_SESSION['user'] = $user;
            header("Location: index.php");
        } else {
            $errors['password'] = 'Неверный пароль';
        }
    }
}

$categories_list_tmpl = get_categories_list_template($categories_list);

$display_params = [
    'file' => 'login.php',
    'title' => 'Аутентификация',
    'errors' => $errors,
    'categories_list_tmpl' => $categories_list_tmpl
];

show_screen($display_params);
