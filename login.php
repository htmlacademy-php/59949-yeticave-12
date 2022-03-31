<?php

$db_conn = require_once('init.php');
$rules = require_once('rules.php');
require_once('queries/user-by-email.php');

if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$categories_list = getCategories($db_conn);

if (!is_array($categories_list) && !$categories_list) {
    $error = getDbError($db_conn);
    showError($error);
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filteredData = filterDataByRules($_POST, $rules['login']);

    $errors = validateForm($filteredData, $rules['login']);

    if (empty($errors)) {
        $user = getUserByEmail($db_conn, $filteredData['email']);

        if (!is_array($user) && !$user) {
            $error = getDbError($db_conn);
            showError($error);
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

$categories_list_tmpl = getCategoriesListTemplate($categories_list);

$display_params = [
    'file' => 'login.php',
    'title' => 'Аутентификация',
    'errors' => $errors,
    'categories_list_tmpl' => $categories_list_tmpl
];

showScreen($display_params);
