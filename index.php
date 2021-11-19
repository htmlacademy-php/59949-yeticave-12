<?php

require_once('helpers.php');
require_once('data/data.php');
require_once('db-config.php');
require_once('queries/categories.php');

$categories_list = fetch_categories($db_conn);

$sql = 'SELECT '
    . 'l.id, l.title, c.title category_title, expiry_dt, initial_price, img_path '
    . 'FROM lots l '
    . 'LEFT JOIN bets b ON l.id = b.lot_id '
    . 'JOIN categories c ON l.category_id = c.id '
    . 'WHERE l.expiry_dt > NOW() '
    . 'GROUP BY l.id '
    . 'ORDER BY l.created_at DESC '
    . 'LIMIT 6';
$result = mysqli_query($db_conn, $sql);

if (!$result) {
    print(include_template('error.php', ['error' => mysqli_error($db_conn)]));
    exit();
}
$goods_list = mysqli_fetch_all($result, MYSQLI_ASSOC);

$page_content = include_template('main.php', [
    'goods_list' => $goods_list,
    'categories_list' => $categories_list
]);

$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $page_content,
    'categories_list' => $categories_list,
    'title' => 'GifTube - Главная страница'
]);

print($layout_content);
