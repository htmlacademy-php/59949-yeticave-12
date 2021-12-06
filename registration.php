<?php

require_once('db-methods.php');
require_once('validations.php');
require_once('queries/categories.php');
require_once('data/data.php');
require_once('helpers.php');
require_once('rules.php');

$db_conn = get_db_connect();

if (!$db_conn) {
    $error = get_db_connection_error();
    show_error($error);
    exit();
}

$categories_list = get_categories($db_conn);

if (!$categories_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $registration_validation_rules;
    $filteredData = filterDataByRules($_POST, $registration_validation_rules);

    $errors = validateForm($filteredData, $registration_validation_rules);
}

show_screen('registration.php', 'Регистрация', 'errors', $errors, $categories_list);
