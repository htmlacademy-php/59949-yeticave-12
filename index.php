<?php

$db_conn = require_once('init.php');
require_once('queries/lots.php');
require_once('queries/lots-count.php');

$lots_per_page = 6;

$lots_count = getLotsCount($db_conn);

list($pages, $offset, $cur_page) = getPaginationParams($lots_count, $lots_per_page);

$categories_list = get_categories($db_conn);

if (!$categories_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$lots_list = get_lots($db_conn, $lots_per_page, $offset);

if (!is_array($lots_list) && !$lots_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$pagination_tmpl = get_pagination_template($pages, $cur_page);
$lot_cards_list_tmpl = get_lot_cards_list_template($lots_list);
$categories_list_tmpl = get_categories_list_template($categories_list);

$display_params = [
    'file' => 'main.php',
    'title' => 'Главная страница',
    'categories_list' => $categories_list,
    'pagination_tmpl' => $pagination_tmpl,
    'lot_cards_list_tmpl' => $lot_cards_list_tmpl,
    'categories_list_tmpl' => $categories_list_tmpl
];

show_screen($display_params);
