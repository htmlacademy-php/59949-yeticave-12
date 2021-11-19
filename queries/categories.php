<?php

require_once ('db-methods.php');

function fetch_categories($conn) {
    $sql = 'SELECT * FROM categories';

    check_db_connection($conn);

    set_charset($conn, "utf8");
    return fetch_from_db($conn, $sql);
}
