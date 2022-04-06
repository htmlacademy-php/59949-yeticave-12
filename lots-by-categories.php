<?php

$db_conn = require_once('init.php');
require_once('queries/lots-by-category.php');
require_once('queries/category-by-id.php');

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

$lots_list = getLotsByCategory($db_conn, $category_id);

if (!is_array($lots_list) && !$lots_list) {
    $error = getDbError($db_conn);
    showError($error);
    exit();
}

$lot_cards_list_tmpl = getLotCardsListTemplate($lots_list);
$categories_list_tmpl = getCategoriesListTemplate($categories_list);

$display_params = [
    'file' => 'lots-by-categories.php',
    'title' => 'Лоты по категоирям',
    'category' => $category,
    'categories_list' => $categories_list,
    'lot_cards_list_tmpl' => $lot_cards_list_tmpl,
    'categories_list_tmpl' => $categories_list_tmpl
];

showScreen($display_params);
