<?php

require_once('helpers.php');
require_once('data/data.php');
require_once('db-config.php');

if (!$db_conn) {
    $error = mysqli_connect_error();
    print(include_template('error.php', ['error' => $error]));
    exit();
}

$sql = 'SELECT * FROM categories';
$result = mysqli_query($db_conn, $sql);

if(!$result) {
    print(include_template('error.php', ['error' => mysqli_error($db_conn)]));
    exit();
}
$categoriesList = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
$lotByIDList = mysqli_fetch_all($result, MYSQLI_ASSOC);

if(!count($lotByIDList)) {
    header("Location: /pages/404.html");
    exit();
}

$page_content = include_template('lot.php', [
    'lot' => $lotByIDList[0],
    'categoriesList' => $categoriesList
]);

$layout_content = include_template('layout.php', [
    'isAuth' => $isAuth,
    'userName' => $userName,
    'content' => $page_content,
    'categoriesList' => $categoriesList,
    'title' => 'GifTube - Страница лота'
]);

print($layout_content);
