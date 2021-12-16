<?php

$db_conn = require_once('init.php');

$categories_list = get_categories($db_conn);

if (!$categories_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$lots_list = [];

show_screen('categories.php', 'Лоты по категоирям', 'goods_list', $lots_list, $categories_list);
