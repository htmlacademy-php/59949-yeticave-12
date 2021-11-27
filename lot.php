<?php

require_once('db-methods.php');
require_once('helpers.php');
require_once('data/data.php');
require_once('queries/categories.php');
require_once('queries/lot-by-id.php');

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

$lot_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!$lot_id) {
    header("Location: /pages/404.html");
    exit();
}

$result = fetch_lot_by_id($db_conn, $lot_id);

if (!$result) {
    $error = mysqli_error($db_conn);
    show_error($error);
    exit();
}
$lot_by_id_list = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (empty($lot_by_id_list)) {
    header("Location: /pages/404.html");
    exit();
}

$page_content = include_template('lot.php', [
    'lot' => $lot_by_id_list[0],
    'categories_list' => $categories_list
]);

global $is_auth, $user_name;

$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $page_content,
    'categories_list' => $categories_list,
    'title' => 'GifTube - Страница лота'
]);

print($layout_content);
