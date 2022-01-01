<?php

$db_conn = require_once('init.php');
$rules = require_once('rules.php');
require_once('queries/lot-by-id.php');
require_once('queries/lot-bets.php');
require_once('queries/create-bet.php');

$categories_list = get_categories($db_conn);

if (!$categories_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$lot_id = get_by_name_from_url('id');

if (!$lot_id) {
    header("Location: 404.php");
    exit();
}

$lot = get_lot_by_id($db_conn, $lot_id);

$error = get_db_error($db_conn);

if ($error) {
    show_error($error);
    exit();
}

if (empty($lot)) {
    header("Location: 404.php");
    exit();
}

$bets_list = get_lot_bets($db_conn, $lot['id']);

if (!is_array($bets_list) && !$bets_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$errors = [];

$_SESSION['lot-info'] = $lot;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filteredData = filterDataByRules($_POST, $rules['lot-bet']);

    $errors = validateForm($filteredData, $rules['lot-bet']);

    if (empty($errors)) {
        $data = [
            'amount' => $filteredData['cost'],
            'user_id' => $_SESSION['user'][0]['id'],
            'lot_id' => $lot['id']
        ];

        $bet = create_bet($db_conn, $data);

        if (!$bet) {
            $error = get_db_error($db_conn);
            show_error($error);
            exit();
        }

        $_SESSION['lot-info'] = null;
        header("Refresh: 0");
    }
}

$categories_list_tmpl = get_categories_list_template($categories_list);

$display_params = [
    'file' => 'lot.php',
    'title' => 'Страница лота',
    'lot' => $lot,
    'bets' => $bets_list,
    'errors' => $errors,
    'categories_list_tmpl' => $categories_list_tmpl
];

show_screen($display_params);
