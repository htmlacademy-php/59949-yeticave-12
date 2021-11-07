<?php

require_once('helpers.php');
require_once('data/data.php');

$link = mysqli_connect("localhost", "root", "root","yeticave");
mysqli_set_charset($link, "utf8");

if (!$link) {
    $error = mysqli_connect_error();
    print(include_template('error.php', ['error' => $error]));
    exit();
}

$sql = 'SELECT * FROM categories';
$result = mysqli_query($link, $sql);

if(!$result) {
    print(include_template('error.php', ['error' => mysqli_error($link)]));
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
    WHERE l.id = $id";

$result = mysqli_query($link, $sql);

if (!$result) {
    print(include_template('error.php', ['error' => mysqli_error($link)]));
    exit();
}
$lotByID = mysqli_fetch_all($result, MYSQLI_ASSOC);

$page_content = include_template('lot.php', [
    'lot' => $lotByID[0],
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
