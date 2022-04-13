<?php

$db_conn = require_once('init.php');
$rules = require_once('rules.php');
require_once('queries/lot-by-id.php');
require_once('queries/lot-bets.php');
require_once('queries/create-bet.php');

$categories_list = getCategories($db_conn);

if (!is_array($categories_list) && !$categories_list) {
    $error = getDbError($db_conn);
    showError($error);
    exit();
}

$lot_id = getByNameFromUrl('id');

if (!$lot_id) {
    header("Location: 404.php");
    exit();
}

$lot = getLotById($db_conn, $lot_id);

$error = getDbError($db_conn);

if ($error) {
    showError($error);
    exit();
}

if (empty($lot) || empty($lot['id'])) {
    header("Location: 404.php");
    exit();
}

$bets_list = getLotBets($db_conn, $lot['id']);

foreach ($bets_list as $key => $val) {
    $bets_list[$key]['time_passed'] = calcTimeHavePassed($val, 'created_at');
}

if (!is_array($bets_list) && !$bets_list) {
    $error = getDbError($db_conn);
    showError($error);
    exit();
}

$errors = [];

$_SESSION['lot-info'] = $lot;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filteredData = filterDataByRules($_POST, $rules['lot-bet'] ?? []);

    $errors = validateForm($filteredData, $rules['lot-bet'] ?? []);

    if (empty($errors)) {
        $data = [
            'amount' => $filteredData['cost'] ?? null,
            'user_id' => $_SESSION['user'][0]['id'] ?? null,
            'lot_id' => $lot['id'] ?? null
        ];

        $bet = createBet($db_conn, $data);

        if (!$bet) {
            $error = getDbError($db_conn);
            showError($error);
            exit();
        }

        $_SESSION['lot-info'] = null;
        header("Refresh: 0");
    }
}

$user = getSessionUser();

$categories_list_tmpl = getCategoriesListTemplate($categories_list);

$display_params = [
    'file' => 'lot.php',
    'title' => 'Страница лота',
    'lot' => $lot,
    'bets' => $bets_list,
    'errors' => $errors,
    'is_visible' => betFormIsVisible($lot, $user, $bets_list),
    'categories_list_tmpl' => $categories_list_tmpl
];

showScreen($display_params);
