<?php

require_once('helpers.php');
require_once('data/data.php');
require_once('db-config.php');
require_once('queries/categories.php');

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if(!$id) {
    header("Location: /pages/404.html");
    exit();
}

$sql = "SELECT l.*, c.title AS category_title, (initial_price + IFNULL(SUM(b.amount), 0)) AS current_price FROM lots l
    JOIN categories c ON l.category_id = c.id
    LEFT JOIN bets b ON l.id = b.lot_id
    WHERE l.id = $id
    HAVING l.id";

$result = mysqli_query($db_conn, $sql);

if (!$result) {
    print(include_template('error.php', ['error' => mysqli_error($db_conn)]));
    exit();
}
$lot_by_id_list = mysqli_fetch_all($result, MYSQLI_ASSOC);

if(!count($lot_by_id_list)) {
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
