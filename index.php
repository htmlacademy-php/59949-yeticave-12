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

$sql = 'SELECT '
    . 'l.title, c.title category_title, expiry_dt, initial_price, img_path '
    . 'FROM lots l '
    . 'JOIN bets b ON l.id = b.lot_id '
    . 'JOIN categories c ON l.category_id = c.id '
    . 'WHERE l.expiry_dt > NOW() '
    . 'GROUP BY b.lot_id '
    . 'ORDER BY l.created_at DESC '
    . 'LIMIT 6';
$result = mysqli_query($link, $sql);

if (!$result) {
    print(include_template('error.php', ['error' => mysqli_error($link)]));
    exit();
}
$goodsList = mysqli_fetch_all($result, MYSQLI_ASSOC);

$page_content = include_template('main.php', [
    'goodsList' => $goodsList,
    'categoriesList' => $categoriesList
]);

$layout_content = include_template('layout.php', [
    'isAuth' => $isAuth,
    'userName' => $userName,
    'content' => $page_content,
    'categoriesList' => $categoriesList,
    'title' => 'GifTube - Главная страница'
]);

print($layout_content);
