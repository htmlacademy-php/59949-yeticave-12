<?php

$db_conn = require_once('init.php');
require_once('queries/user-bets.php');

$user = get_session_user();

if (empty($user)) {
    header("Location: index.php");
}

$categories_list = get_categories($db_conn);

if (!$categories_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$user_bets = get_user_bets($db_conn, get_session_user()['id']);

if (!is_array($user_bets) && !$user_bets) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$categories_list_tmpl = get_categories_list_template($categories_list);

$display_params = [
    'file' => 'my-bets.php',
    'title' => 'Мои ставки',
    'user_bets' => $user_bets,
    'categories_list_tmpl' => $categories_list_tmpl
];

show_screen($display_params);
