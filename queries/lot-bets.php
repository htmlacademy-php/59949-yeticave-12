<?php

function get_lot_bets(mysqli $conn, int $lot_id) {
    $sql = "SELECT b.id, b.amount, b.created_at, u.name
    FROM bets b
    JOIN users u ON u.id = b.user_id
    WHERE lot_id = ?
    ORDER BY created_at DESC
    LIMIT 10";

    return fetch_from_db_by_params($conn, $sql, [$lot_id]);
}
