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

show_screen('search.php', 'Результаты поиска', 'goods_list', $lots_list, $categories_list);
