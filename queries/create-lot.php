<?php

/**
 * @param mysqli $conn
 * @param array $data
 * @return false|int|string
 */
function create_lot(mysqli $conn, array $data) {
    $data_list = [
        'expiry_dt' => $data['lot-date'],
        'title' => $data['lot-name'],
        'description' => $data['message'],
        'initial_price' => $data['lot-rate'],
        'bet_step' => $data['lot-step'],
        'category_id' => $data['category'],
        'img_path' => $data['file'],
        'author' => $data['user_id']
    ];

    $placeholders_list = array_fill(0, count($data_list), '?');
    $columns = implode(", ", array_keys($data_list));
    $placeholders = implode(", ", $placeholders_list);

    $sql = "INSERT INTO lots ($columns) VALUES ($placeholders)";

    return db_insert($conn, $sql, $data_list);
}
