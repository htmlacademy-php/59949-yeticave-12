<?php

$db_conn = require_once('init.php');
require_once('queries/lots-search.php');

$categories_list = get_categories($db_conn);

if (!$categories_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$lots_list = [];

$search = $_GET['search'] ?? '';

if ($search) {
    $lots_list = search_lots($db_conn, $search);
}

if (!$lots_list && !is_array($lots_list)) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$lot_cards_list_tmpl = get_lot_cards_list_template($lots_list);
$categories_list_tmpl = get_categories_list_template($categories_list);

$display_params = [
    'file' => 'search.php',
    'title' => 'Результаты поиска',
    'lot_cards_list_tmpl' => $lot_cards_list_tmpl,
    'categories_list_tmpl' => $categories_list_tmpl
];

show_screen($display_params);
