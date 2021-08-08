<?php

require_once('helpers.php');
require_once('data/data.php');

$link = mysqli_connect("localhost", "rootz", "root","yeticave");
mysqli_set_charset($link, "utf8");

if (!$link) {
    $error = mysqli_connect_error();
    $layout_content = include_template('error.php', ['error' => $error]);
}
else {
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
}

print($layout_content);
