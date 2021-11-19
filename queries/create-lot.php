<?php

require_once ('db-methods.php');

function create_lot($conn, $data) {
    $sql = "INSERT INTO lots (expiry_dt, title, description, img_path, initial_price, bet_step, category_id, author) "
        . "VALUES (?, ?, ?, ?, ?, ?, ?, 1)";

    $stmt = db_get_prepare_stmt($conn, $sql, $data);
    $res = mysqli_stmt_execute($stmt);

    if (!$res) {
        $error = mysqli_error($conn);
        show_error($error);
        exit();
    }
    return mysqli_insert_id($conn);
}
