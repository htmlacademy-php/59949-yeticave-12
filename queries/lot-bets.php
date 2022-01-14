<?php

/**
 * @param mysqli $conn
 * @param int $lot_id
 * @return array|false
 */
function get_lot_bets(mysqli $conn, int $lot_id) {
    $sql = "SELECT b.id, b.amount, u.name, b.created_at,
       DATE_FORMAT(b.created_at, '%d.%m.%y') AS date,
       DATE_FORMAT(b.created_at, '%H:%i') AS time
    FROM bets b
    JOIN users u ON u.id = b.user_id
    WHERE lot_id = ?
    ORDER BY b.created_at DESC
    LIMIT 10";

    return fetch_from_db_by_params($conn, $sql, [$lot_id]);
}
