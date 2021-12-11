<?php

/**
 * @param mysqli $conn
 * @param array $data
 * @return false|int|string
 */
function create_user(mysqli $conn, array $data) {
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

    $data['contact'] = $data['message'];
    unset($data['message']);

    $placeholders_list = array_fill(0, count($data), '?');
    $columns = implode(", ", array_keys($data));
    $placeholders = implode(", ", $placeholders_list);

    $sql = "INSERT INTO users ($columns) VALUES ($placeholders)";

    return db_insert($conn, $sql, $data);
}
