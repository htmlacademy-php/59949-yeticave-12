<?php

require_once('init.php');
require_once('queries/user-by-email.php');
require_once('queries/create-user.php');

if (isset($_SESSION['user'])) {
    http_response_code(403);
    show_error('Доступ запрещен ' . http_response_code());
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
    global $registration_validation_rules;
    $filteredData = filterDataByRules($_POST, $registration_validation_rules);

    $errors = validateForm($filteredData, $registration_validation_rules);

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

show_screen('registration.php', 'Регистрация', 'errors', $errors, $categories_list);
