<?php

$db_conn = require_once('init.php');
require_once('queries/lots.php');
require_once('queries/lots-count.php');
require_once('get-winner.php');

$LOTS_PER_PAGE = 9;

$lots_count = getLotsCount($db_conn);

list($pages, $offset, $cur_page) = getPaginationParams($lots_count, $LOTS_PER_PAGE);

$categories_list = getCategories($db_conn);

if (!is_array($categories_list) && !$categories_list) {
    $error = getDbError($db_conn);
    showError($error);
    exit();
}

$lots_list = getLots($db_conn, $LOTS_PER_PAGE, $offset);

if (!is_array($lots_list) && !$lots_list) {
    $error = getDbError($db_conn);
    showError($error);
    exit();
}

$pagination_tmpl = getPaginationTemplate($pages, $cur_page);
$lot_cards_list_tmpl = getLotCardsListTemplate($lots_list);
$categories_list_tmpl = getCategoriesListTemplate($categories_list);

$display_params = [
    'file' => 'main.php',
    'title' => 'Главная страница',
    'categories_list' => $categories_list,
    'pagination_tmpl' => $pagination_tmpl,
    'lot_cards_list_tmpl' => $lot_cards_list_tmpl,
    'categories_list_tmpl' => $categories_list_tmpl
];

showScreen($display_params);
