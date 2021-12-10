<?php

require_once('init.php');
require_once('queries/user-by-email.php');
require_once('queries/create-user.php');
require_once('data/data.php');

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
    global $registration_validation_rules;
    $filteredData = filterDataByRules($_POST, $registration_validation_rules);

    $errors = validateForm($filteredData, $registration_validation_rules);

    if (empty($errors)) {
        $user_by_email = get_user_by_email($db_conn, $filteredData['email']);

        if (!is_array($user_by_email) && !$user_by_email) {
            $error = get_db_error($db_conn);
            show_error($error);
            exit();
        }

        if (count($user_by_email)) {
            $errors['email'] = 'Пользователь с такой почтой уже зарегистрирован';
        } else {
            $password = password_hash($filteredData['password'], PASSWORD_DEFAULT);

            $user = create_user($db_conn, [
                $filteredData['email'],
                $filteredData['name'],
                $password,
                $filteredData['message']
            ]);

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
