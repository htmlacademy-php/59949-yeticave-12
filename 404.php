<?php

$db_conn = require_once('init.php');

$categories_list = get_categories($db_conn);

if (!$categories_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$categories_list_tmpl = get_categories_list_template($categories_list);

$display_params = [
    'file' => '404.php',
    'title' => '404',
    'categories_list_tmpl' => $categories_list_tmpl
];

show_screen($display_params);
