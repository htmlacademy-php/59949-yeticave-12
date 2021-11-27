<?php

function create_lot($conn, $data) {
    $sql = "INSERT INTO lots (expiry_dt, title, description, img_path, initial_price, bet_step, category_id, author) "
        . "VALUES (?, ?, ?, ?, ?, ?, ?, 1)";

    $stmt = db_get_prepare_stmt($conn, $sql, $data);

    return mysqli_stmt_execute($stmt);
}
