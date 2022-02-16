<?php

$db_conn = require_once('init.php');

$categories_list = getCategories($db_conn);

if (!$categories_list) {
    $error = getDbError($db_conn);
    showError($error);
    exit();
}

$categories_list_tmpl = getCategoriesListTemplate($categories_list);

$display_params = [
    'file' => '404.php',
    'title' => '404',
    'categories_list_tmpl' => $categories_list_tmpl
];

showScreen($display_params);
