<?php

$db_conn = require_once('init.php');
$rules = require_once('rules.php');
require_once('queries/create-lot.php');

if (empty($_SESSION['user'])) {
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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $formData = array_merge($_POST, $_FILES);
    $filteredData = filterDataByRules($formData, $rules['lot-create']);

    $errors = validateForm($filteredData, $rules['lot-create']);

    if (empty($errors)) {
        $file_url = moveFileToLocalPath($formData['lot-img']);

        $filteredData['file'] = $file_url;
        $filteredData['user_id'] = getSessionUser()['id'];

        if ($file_url) {
            $lot_id = createLot($db_conn, $filteredData);

            if (!$lot_id) {
                $error = getDbError($db_conn);
                showError($error);
                exit();
            }

            header("Location: lot.php?id=" . $lot_id);
        }
    }
}

$categories_list_tmpl = getCategoriesListTemplate($categories_list);

$display_params = [
    'file' => 'add-lot.php',
    'title' => 'Добавление лота',
    'errors' => $errors,
    'categories_list' => $categories_list,
    'categories_list_tmpl' => $categories_list_tmpl
];

showScreen($display_params);
