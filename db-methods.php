<?php

require_once('db-config.php');

$db_conn = mysqli_connect($host, $username, $password, $db_name);

function set_charset($conn, $charset) {
    mysqli_set_charset($conn, $charset);
}

function check_db_connection($conn) {
    if (!$conn) {
        $error = mysqli_connect_error();
        show_error($error);
        exit();
    }
}

function fetch_from_db($conn, string $sql) {
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        $error = mysqli_error($conn);
        show_error($error);
        exit();
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
