<?php

require_once('helpers.php');
require_once('data/data.php');

$link = mysqli_connect("localhost", "root", "root","yeticave");
mysqli_set_charset($link, "utf8");

if (!$link) {
    $error = mysqli_connect_error();
    $layout_content = include_template('error.php', ['error' => $error]);
}
else {
    $sql = 'SELECT * FROM categories';
    $result = mysqli_query($link, $sql);

    if ($result) {
        $categoriesList = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        $error = mysqli_error($link);
        $content = include_template('error.php', ['error' => $error]);
    }

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
