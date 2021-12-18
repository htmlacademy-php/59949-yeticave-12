<?php

$db_conn = require_once('init.php');
require_once('queries/lot-by-id.php');

$categories_list = get_categories($db_conn);

if (!$categories_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$lot_id = get_by_name_from_url('id');

if (!$lot_id) {
    header("Location: 404.php");
    exit();
}

$lot = get_lot_by_id($db_conn, $lot_id);

$error = get_db_error($db_conn);

if ($error) {
    show_error($error);
    exit();
}

if (empty($lot)) {
    header("Location: 404.php");
    exit();
}

$categories_list_tmpl = get_categories_list_template($categories_list);

$display_params = [
    'file' => 'lot.php',
    'title' => 'Страница лота',
    'lot' => $lot,
    'categories_list_tmpl' => $categories_list_tmpl
];

show_screen($display_params);
