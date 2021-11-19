<?php

require_once('helpers.php');
require_once('data/data.php');
require_once('queries/categories.php');
require_once('queries/lots.php');

$lots_list = fetch_lots($db_conn);
$categories_list = fetch_categories($db_conn);

$page_content = include_template('main.php', [
    'goods_list' => $lots_list,
    'categories_list' => $categories_list
]);

$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $page_content,
    'categories_list' => $categories_list,
    'title' => 'GifTube - Главная страница'
]);

print($layout_content);
