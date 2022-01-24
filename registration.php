<?php

$db_conn = require_once('init.php');
$rules = require_once('rules.php');
require_once('queries/user-by-email.php');
require_once('queries/create-user.php');

if (isset($_SESSION['user'])) {
    http_response_code(403);
    showError('Доступ запрещен ' . http_response_code());
    exit();
}

$categories_list = getCategories($db_conn);

if (!$categories_list) {
    $error = getDbError($db_conn);
    showError($error);
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filteredData = filterDataByRules($_POST, $rules['registration']);

    $errors = validateForm($filteredData, $rules['registration']);

    if (empty($errors)) {
        $user = getUserByEmail($db_conn, $filteredData['email']);

        if (!is_array($user) && !$user) {
            $error = getDbError($db_conn);
            showError($error);
            exit();
        }

        if (!empty($user)) {
            $errors['email'] = 'Пользователь с такой почтой уже зарегистрирован';
        } else {
            $user = createUser($db_conn, $filteredData);

            if (!$user) {
                $error = getDbError($db_conn);
                showError($error);
                exit();
            }

            header("Location: login.php");
        }
    }
}

$categories_list_tmpl = getCategoriesListTemplate($categories_list);

$display_params = [
    'file' => 'registration.php',
    'title' => 'Регистрация',
    'errors' => $errors,
    'categories_list_tmpl' => $categories_list_tmpl
];

showScreen($display_params);
