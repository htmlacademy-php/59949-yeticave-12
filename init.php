<?php

require_once('db-methods.php');
require_once('helpers.php');
require_once('rules.php');
require_once('validations.php');
require_once('queries/categories.php');

session_start();

$db_conn = get_db_connect();

if (!$db_conn) {
    $error = get_db_connection_error();
    show_error($error);
    exit();
}
