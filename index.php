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

show_screen('main.php', 'Главная страница', 'goods_list', $lots_list, $categories_list, $pages, $cur_page );
