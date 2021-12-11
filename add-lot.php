<?php

require_once('init.php');
require_once('queries/create-lot.php');

if (empty($_SESSION['user'])) {
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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    global $lot_create_validation_rules;

    $formData = array_merge($_POST, $_FILES);
    $filteredData = filterDataByRules($formData, $lot_create_validation_rules);

    $errors = validateForm($filteredData, $lot_create_validation_rules);

    if (empty($errors)) {
        $file_url = moveFileToLocalPath($formData['lot-img']);

        if ($file_url) {
            $lot_id = create_lot($db_conn, $formData, $file_url, $_SESSION['user'][0]['id']);

            if (!$lot_id) {
                $error = get_db_error($db_conn);
                show_error($error);
                exit();
            }

            header("Location: lot.php?id=" . $lot_id);
        }
    }
}

show_screen('add-lot.php', 'Добавление лота', 'errors', $errors, $categories_list);
