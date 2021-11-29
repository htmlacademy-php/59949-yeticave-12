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

//$categories_list = fetch_categories($db_conn);

//if (!$categories_list) {
//    $error = get_db_error($db_conn);
//    show_error($error);
//    echo 'mmmm';
//    exit();
//}

$lot_id = get_by_name_from_url('id');
if (!$lot_id) {
    header("Location: /pages/404.html");
    exit();
}

$lot_by_id_list = fetch_lot_by_id($db_conn, $lot_id);
//echo gettype($lot_by_id_list);
//exit();
if (!$lot_by_id_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    echo $lot_by_id_list . ' !!!$lot_by_id_list';
    exit();
}

if (empty($lot_by_id_list)) {
    header("Location: /pages/404.html");
    echo 'empty list 888';
    exit();
}

$page_content = include_template('lot.php', [
    'lot' => $lot_by_id_list[0],
//    'categories_list' => $categories_list
]);

global $is_auth, $user_name;

$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $page_content,
//    'categories_list' => $categories_list,
    'title' => 'GifTube - Страница лота'
]);

print($layout_content);
