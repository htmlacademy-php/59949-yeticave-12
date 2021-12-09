<?php

/**
 * @param mysqli $conn
 * @param array $data
 * @return false|int|string
 */
function create_user(mysqli $conn, array $data) {
    $sql = 'INSERT INTO users (email, name, password, contact) '
    . 'VALUES(?, ?, ?, ?)';

    return db_insert($conn, $sql, $data);
}
