<?php

require_once('db-methods.php');
require_once('rules.php');
require_once('helpers.php');
require_once('data/data.php');
require_once('queries/categories.php');
require_once('queries/create-lot.php');
require_once ('validations.php');

$db_conn = get_db_connect();

if (!$db_conn) {
    $error = get_db_connection_error();
    show_error($error);
    exit();
}

$categories_list = get_categories($db_conn);

if (!$categories_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $formData = getFormPostedData();
    $formFiles = getFormPostedFiles();

    global $lot_create_data_validation_rules, $lot_create_files_validation_rules;

    $data_errors = validateForm($formData, $lot_create_data_validation_rules);
    $file_errors = validateFiles($formFiles, $lot_create_files_validation_rules);

    $errors = array_merge($data_errors, $file_errors);

    if (empty($errors)) {
        $file_url = copyFileToLocalPath('lot-img');

        if ($file_url) {
            $lot_id = create_lot($db_conn, [
                $formData['lot-date'],
                $formData['lot-name'],
                $formData['message'],
                $formData['lot-rate'],
                $formData['lot-step'],
                $formData['category'],
                $file_url
            ]);

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
