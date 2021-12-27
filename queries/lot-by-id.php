<?php

/**
 * @param mysqli $conn
 * @param int $id
 * @return array|false
 */
function get_lot_by_id(mysqli $conn, int $id) {
    $sql = "SELECT
    l.id, l.title, c.title AS category_title, img_path, description, expiry_dt, bet_step,
    (initial_price + (
        SELECT IFNULL(SUM(b.amount), 0)
        FROM bets b
        WHERE l.id = b.lot_id
        )
    ) AS current_price FROM lots l
    JOIN categories c ON l.category_id = c.id
    WHERE l.id = ?";

    return fetch_from_db_by_params($conn, $sql, [$id])[0];
}
