<?php

require_once('db-config.php');

function get_db_connect() {
    global $host, $username, $password, $db_name;
    return mysqli_connect($host, $username, $password, $db_name);
}

function check_db_connection(mysqli $conn) {
    if (!$conn) {
        return mysqli_connect_error();
    }
    return null;
}

function fetch_from_db(mysqli $conn, string $sql) {
    return mysqli_query($conn, $sql);
}
