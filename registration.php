<?php

$db_conn = require_once('init.php');
$rules = require_once('rules.php');
require_once('queries/user-by-email.php');
require_once('queries/create-user.php');

if (isset($_SESSION['user'])) {
    http_response_code(403);
    show_error('Доступ запрещен ' . http_response_code());
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
    $filteredData = filterDataByRules($_POST, $rules['registration']);

    $errors = validateForm($filteredData, $rules['registration']);

    if (empty($errors)) {
        $user = get_user_by_email($db_conn, $filteredData['email']);

        if (!is_array($user) && !$user) {
            $error = get_db_error($db_conn);
            show_error($error);
            exit();
        }

        if (!empty($user)) {
            $errors['email'] = 'Пользователь с такой почтой уже зарегистрирован';
        } else {
            $user = create_user($db_conn, $filteredData);

            if (!$user) {
                $error = get_db_error($db_conn);
                show_error($error);
                exit();
            }

            header("Location: login.php");
        }
    }
}

$categories_list_tmpl = get_categories_list_template($categories_list);

$display_params = [
    'file' => 'registration.php',
    'title' => 'Регистрация',
    'errors' => $errors,
    'categories_list_tmpl' => $categories_list_tmpl
];

show_screen($display_params);
