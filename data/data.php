<?php

$isAuth = rand(0, 1);

$userName = 'Sergey';

$categoriesList = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];

$halfAnHourFromNow = date('Y-m-d H:i', strtotime("+30 minutes"));

$goodsList = [
    ['name' => '2014 Rossignol District Snowboard', 'category' => 'Доски и лыжи', 'price' => 10999, 'img_url' => 'img/lot-1.jpg', 'exp_date' => $halfAnHourFromNow],
    ['name' => 'DC Ply Mens 2016/2017 Snowboard', 'category' => 'Доски и лыжи', 'price' => 15999, 'img_url' => 'img/lot-2.jpg', 'exp_date' => '2021-06-26'],
    ['name' => 'Крепления Union Contact Pro 2015 года размер L/XL', 'category' => 'Крепления', 'price' => 8000, 'img_url' => 'img/lot-3.jpg', 'exp_date' => '2021-06-30'],
    ['name' => 'Ботинки для сноуборда DC Mutiny Charocal', 'category' => 'Ботинки', 'price' => 10999, 'img_url' => 'img/lot-4.jpg', 'exp_date' => '2021-07-03 15:38'],
    ['name' => 'Куртка для сноуборда DC Mutiny Charocal', 'category' => 'Одежда', 'price' => 7500, 'img_url' => 'img/lot-5.jpg', 'exp_date' => '2021-07-04'],
    ['name' => 'Маска Oakley Canopy', 'category' => 'Разное', 'price' => 5400, 'img_url' => 'img/lot-6.jpg', 'exp_date' => '2021-07-06']
];
