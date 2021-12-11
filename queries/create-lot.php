<?php

/**
 * @param mysqli $conn
 * @param array $data
 * @param string $file
 * @param string $user_id
 * @return false|int|string
 */
function create_lot(mysqli $conn, array $data, string $file, string $user_id) {
    $data_list = [];

    $data_list['expiry_dt'] = $data['lot-date'];
    $data_list['title'] = $data['lot-name'];
    $data_list['description'] = $data['message'];
    $data_list['initial_price'] = $data['lot-rate'];
    $data_list['bet_step'] = $data['lot-step'];
    $data_list['category_id'] = $data['category'];
    $data_list['img_path'] = $file;
    $data_list['author'] = $user_id;

    $placeholders_list = array_fill(0, count($data_list), '?');
    $columns = implode(", ", array_keys($data_list));
    $placeholders = implode(", ", $placeholders_list);

    $sql = "INSERT INTO lots ($columns) VALUES ($placeholders)";

    return db_insert($conn, $sql, $data_list);
}
