<?php

$db_conn = require_once('init.php');
require_once('queries/lots.php');

$categories_list = get_categories($db_conn);

if (!$categories_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$lots_list = get_lots($db_conn);

if (!$lots_list && !is_array($lots_list)) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

show_screen('search.php', 'Результаты поиска', 'goods_list', $lots_list, $categories_list);
