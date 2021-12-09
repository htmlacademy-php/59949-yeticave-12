<?php

require_once('init.php');

global $db_conn;
$categories_list = get_categories($db_conn);

if (!$categories_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

show_screen('404.php', '404', '', [], $categories_list);
