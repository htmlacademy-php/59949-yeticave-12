<?php

/**
 * @param mysqli $conn
 * @param array $data
 * @return false|int|string
 */
function create_lot(mysqli $conn, array $data) {
    $sql = "INSERT INTO lots (expiry_dt, title, description, initial_price, bet_step, category_id, img_path, author) "
        . "VALUES (?, ?, ?, ?, ?, ?, ?, 1)";

    return db_insert($conn, $sql, $data);
}
