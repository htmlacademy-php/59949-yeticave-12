<?php

require_once('db-methods.php');
require_once('helpers.php');
require_once('data/data.php');
require_once('queries/categories.php');
require_once('queries/lot-by-id.php');

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

$lot_id = get_by_name_from_url('id');

if (!$lot_id) {
    header("Location: /pages/404.html");
    exit();
}

$lot_by_id_list = get_lot_by_id($db_conn, $lot_id);

if (!is_array($lot_by_id_list) && !$lot_by_id_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

if (empty($lot_by_id_list)) {
    header("Location: /pages/404.html");
    exit();
}

show_screen('lot.php', 'Страница лота', 'lot', $lot_by_id_list[0], $categories_list);
