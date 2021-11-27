<?php

require_once('db-methods.php');
require_once('helpers.php');
require_once('data/data.php');
require_once('queries/categories.php');
require_once('queries/lots.php');

$db_conn = get_db_connect();
$conn_error = check_db_connection($db_conn);

if ($conn_error) {
    show_error($conn_error);
    exit();
}

mysqli_set_charset($db_conn, "utf8");

$result = fetch_categories($db_conn);

if (!$result) {
    $error = mysqli_error($db_conn);
    show_error($error);
    exit();
}
$categories_list = mysqli_fetch_all($result, MYSQLI_ASSOC);

$result = fetch_lots($db_conn);

if (!$result) {
    $error = mysqli_error($db_conn);
    show_error($error);
    exit();
}
$lots_list = mysqli_fetch_all($result, MYSQLI_ASSOC);

$page_content = include_template('main.php', [
    'goods_list' => $lots_list,
    'categories_list' => $categories_list
]);

global $is_auth, $user_name;

$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $page_content,
    'categories_list' => $categories_list,
    'title' => 'GifTube - Главная страница'
]);

print($layout_content);
