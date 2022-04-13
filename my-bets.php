<?php

$db_conn = require_once('init.php');
require_once('queries/user-bets.php');

$user = getSessionUser();

if (empty($user) || empty($user['id'])) {
    header("Location: index.php");
}

$categories_list = getCategories($db_conn);

if (!is_array($categories_list) && !$categories_list) {
    $error = getDbError($db_conn);
    showError($error);
    exit();
}

$user_bets = getUserBets($db_conn, $user['id']);

if (!is_array($user_bets) && !$user_bets) {
    $error = getDbError($db_conn);
    showError($error);
    exit();
}

$categories_list_tmpl = getCategoriesListTemplate($categories_list);

$display_params = [
    'file' => 'my-bets.php',
    'title' => 'Мои ставки',
    'user_bets' => $user_bets,
    'categories_list_tmpl' => $categories_list_tmpl
];

showScreen($display_params);
