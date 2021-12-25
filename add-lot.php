<?php

$db_conn = require_once('init.php');
$rules = require_once('rules.php');
require_once('queries/create-lot.php');

if (empty($_SESSION['user'])) {
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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $formData = array_merge($_POST, $_FILES);
    $filteredData = filterDataByRules($formData, $rules['lot-create']);

    $errors = validateForm($filteredData, $rules['lot-create']);

    if (empty($errors)) {
        $file_url = moveFileToLocalPath($formData['lot-img']);

        $filteredData['file'] = $file_url;
        $filteredData['user_id'] = get_session_user()['id'];

        if ($file_url) {
            $lot_id = create_lot($db_conn, $filteredData);

            if (!$lot_id) {
                $error = get_db_error($db_conn);
                show_error($error);
                exit();
            }

            header("Location: lot.php?id=" . $lot_id);
        }
    }
}

$categories_list_tmpl = get_categories_list_template($categories_list);

$display_params = [
    'file' => 'add-lot.php',
    'title' => 'Добавление лота',
    'errors' => $errors,
    'categories_list' => $categories_list,
    'categories_list_tmpl' => $categories_list_tmpl
];

show_screen($display_params);
