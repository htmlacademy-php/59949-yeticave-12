<?php

$db_conn = require_once('init.php');
require_once('queries/lots-by-category.php');
require_once('queries/category-by-id.php');
require_once('queries/lots-count-by-category.php');

$LOTS_PER_PAGE = 9;

$categories_list = getCategories($db_conn);

if (!is_array($categories_list) && !$categories_list) {
    $error = getDbError($db_conn);
    showError($error);
    exit();
}

$category_id = getByNameFromUrl('category');
$category = null;

foreach ($categories_list as $val) {
    if ($category_id === $val['id']) {
        $category = getCategoryById($db_conn, $category_id);
    }
}

if (!$category) {
    header("Location: 404.php");
    exit();
}

$lots_count = getLotsCountByCategory($db_conn, $category_id);

list($pages, $offset, $cur_page) = getPaginationParams($lots_count, $LOTS_PER_PAGE);

$lots_list = getLotsByCategory($db_conn, $category_id, $LOTS_PER_PAGE, $offset);

if (!is_array($lots_list) && !$lots_list) {
    $error = getDbError($db_conn);
    showError($error);
    exit();
}

$pagination_tmpl = getPaginationTemplate($pages, $cur_page);
$lot_cards_list_tmpl = getLotCardsListTemplate($lots_list);
$categories_list_tmpl = getCategoriesListTemplate($categories_list);

$display_params = [
    'file' => 'lots-by-categories.php',
    'title' => 'Лоты по категоирям',
    'category' => $category,
    'categories_list' => $categories_list,
    'pagination_tmpl' => $pagination_tmpl,
    'lot_cards_list_tmpl' => $lot_cards_list_tmpl,
    'categories_list_tmpl' => $categories_list_tmpl
];

showScreen($display_params);
