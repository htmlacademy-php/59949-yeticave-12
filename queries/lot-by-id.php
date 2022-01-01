<?php

/**
 * @param mysqli $conn
 * @param int $id
 * @return array|false
 */
function get_lot_by_id(mysqli $conn, int $id) {
    $sql = "SELECT
    l.id, l.title, c.title AS category_title, img_path, description, expiry_dt, bet_step, initial_price,
    (
        SELECT b.amount
        FROM bets b
        WHERE b.lot_id = l.id
        ORDER BY b.created_at DESC
        LIMIT 1
    ) AS current_price FROM lots l
    JOIN categories c ON l.category_id = c.id
    WHERE l.id = ?";

    return fetch_from_db_by_params($conn, $sql, [$id])[0];
}
