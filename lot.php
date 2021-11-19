<?php

require_once('helpers.php');
require_once('data/data.php');
require_once('db-config.php');
require_once('queries/categories.php');
require_once('queries/lot_by_id.php');

$categories_list = fetch_categories($db_conn);

$lot_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if(!$lot_id) {
    header("Location: /pages/404.html");
    exit();
}

$lot_by_id_list = fetch_lot_by_id($db_conn, $lot_id);
if(empty($lot_by_id_list)) {
    header("Location: /pages/404.html");
    exit();
}

$page_content = include_template('lot.php', [
    'lot' => $lot_by_id_list[0],
    'categories_list' => $categories_list
]);

$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $page_content,
    'categories_list' => $categories_list,
    'title' => 'GifTube - Страница лота'
]);

print($layout_content);
