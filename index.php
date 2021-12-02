<?php

require_once('db-methods.php');
require_once('helpers.php');
require_once('data/data.php');
require_once('queries/categories.php');
require_once('queries/lots.php');

$db_conn = get_db_connect();

if (!$db_conn) {
    $error = get_db_connection_error();
    show_error($error);
    exit();
}

$categories_list = get_categories($db_conn);

if (!$categories_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$lots_list = get_lots($db_conn);

if (!$lots_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

show_screen('main.php', 'Главная страница', 'goods_list', $lots_list, $categories_list );
