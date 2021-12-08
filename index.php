<?php

require_once('init.php');
require_once('data/data.php');
require_once('queries/lots.php');

global $db_conn;
$categories_list = get_categories($db_conn);

if (!$categories_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$lots_list = get_lots($db_conn);

if (!is_array($lots_list) && !$lots_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

show_screen('main.php', 'Главная страница', 'goods_list', $lots_list, $categories_list );
