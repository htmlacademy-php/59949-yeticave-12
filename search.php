<?php

$db_conn = require_once('init.php');
require_once('queries/lots-by-search-str.php');
require_once('queries/lots-count-by-search.php');

$LOTS_PER_PAGE = 9;

$categories_list = getCategories($db_conn);

if (!is_array($categories_list) && !$categories_list) {
    $error = getDbError($db_conn);
    showError($error);
    exit();
}

$lots_list = [];
$lots_count = 0;
$pages = [];
$offset = 0;
$cur_page = 0;

$search_str = sanitize($_GET['search'] ?? '');

if ($search_str) {
    $lots_count = getLotsCountBySearch($db_conn, $search_str);
    list($pages, $offset, $cur_page) = getPaginationParams(intval($lots_count), $LOTS_PER_PAGE);
    $lots_list = getLotsBySearchStr($db_conn, $search_str, $LOTS_PER_PAGE, $offset);
}

if (!$lots_list && !is_array($lots_list)) {
    $error = getDbError($db_conn);
    showError($error);
    exit();
}

$pagination_tmpl = getPaginationTemplate($pages, $cur_page);
$lot_cards_list_tmpl = getLotCardsListTemplate($lots_list);
$categories_list_tmpl = getCategoriesListTemplate($categories_list);

$display_params = [
    'file' => 'search.php',
    'title' => 'Результаты поиска',
    'pagination_tmpl' => $pagination_tmpl,
    'lot_cards_list_tmpl' => $lot_cards_list_tmpl,
    'categories_list_tmpl' => $categories_list_tmpl
];

showScreen($display_params);
