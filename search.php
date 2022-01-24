<?php

$db_conn = require_once('init.php');
require_once('queries/lots-by-search-str.php');

$categories_list = getCategories($db_conn);

if (!$categories_list) {
    $error = getDbError($db_conn);
    showError($error);
    exit();
}

$lots_list = [];

$search_str = $_GET['search'] ?? '';

if ($search_str) {
    $lots_list = getLotsBySearchStr($db_conn, $search_str);
}

if (!$lots_list && !is_array($lots_list)) {
    $error = getDbError($db_conn);
    showError($error);
    exit();
}

$lot_cards_list_tmpl = getLotCardsListTemplate($lots_list);
$categories_list_tmpl = getCategoriesListTemplate($categories_list);

$display_params = [
    'file' => 'search.php',
    'title' => 'Результаты поиска',
    'lot_cards_list_tmpl' => $lot_cards_list_tmpl,
    'categories_list_tmpl' => $categories_list_tmpl
];

showScreen($display_params);
