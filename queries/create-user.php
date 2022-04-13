<?php

/**
 * @param mysqli $conn
 * @param array $data
 * @return false|int|string
 */
function createUser(mysqli $conn, array $data)
{
    if (isset($data['password'])) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    } else {
        $data['password'] = null;
    }

    $data['contact'] = $data['message'] ?? null;
    unset($data['message']);

    $placeholders_list = array_fill(0, count($data), '?');
    $columns = implode(", ", array_keys($data));
    $placeholders = implode(", ", $placeholders_list);

    $sql = "INSERT INTO users ($columns) VALUES ($placeholders)";

    return dbInsert($conn, $sql, $data);
}
