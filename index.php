<?php

require_once('helpers.php');
require_once('data/data.php');

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
