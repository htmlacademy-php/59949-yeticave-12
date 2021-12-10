<?php

/**
 * @param mysqli $conn
 * @param array $data
 * @param string $user_id
 * @return false|int|string
 */
function create_lot(mysqli $conn, array $data, string $user_id) {
    $sql = "INSERT INTO lots (expiry_dt, title, description, initial_price, bet_step, category_id, img_path, author) "
        . "VALUES (?, ?, ?, ?, ?, ?, ?, $user_id)";

    return db_insert($conn, $sql, $data);
}
