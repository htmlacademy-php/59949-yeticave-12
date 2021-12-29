<?php

$db_conn = require_once('init.php');
require_once('queries/lots-by-category.php');
require_once('queries/category-by-id.php');

$categories_list = get_categories($db_conn);

if (!$categories_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$category_id = get_by_name_from_url('category');

$category = get_category_by_id($db_conn, $category_id);

if (!$category_id || !$category) {
    header("Location: 404.php");
    exit();
}

$lots_list = get_lots_by_category($db_conn, $category_id);

if (!is_array($lots_list) && !$lots_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$lot_cards_list_tmpl = get_lot_cards_list_template($lots_list);
$categories_list_tmpl = get_categories_list_template($categories_list);

$display_params = [
    'file' => 'lots-by-categories.php',
    'title' => 'Лоты по категоирям',
    'categories_list' => $categories_list,
    'lot_cards_list_tmpl' => $lot_cards_list_tmpl,
    'categories_list_tmpl' => $categories_list_tmpl
];

show_screen($display_params);
