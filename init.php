<?php

require_once('db-methods.php');
require_once('helpers.php');
require_once('validations.php');
require_once('queries/categories.php');

session_start();

$db_conn = getDbConnect();

if (!$db_conn) {
    $error = getDbConnectionError();
    showError($error);
    exit();
}

return $db_conn;
