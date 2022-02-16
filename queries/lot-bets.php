<?php

/**
 * @param mysqli $conn
 * @param int $lot_id
 * @return array|false
 */
function getLotBets(mysqli $conn, int $lot_id)
{
    $sql = "SELECT b.id, b.amount, u.name, user_id, b.created_at,
       DATE_FORMAT(b.created_at, '%d.%m.%y') AS date,
       DATE_FORMAT(b.created_at, '%H:%i') AS time
    FROM bets b
    JOIN users u ON u.id = b.user_id
    WHERE lot_id = ?
    ORDER BY b.created_at DESC
    LIMIT 10";

    return fetchFromDbByParams($conn, $sql, [$lot_id]);
}
