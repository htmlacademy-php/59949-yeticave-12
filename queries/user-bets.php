<?php

/**
 * @param mysqli $conn
 * @param int $user_id
 * @return array|false
 */
function get_user_bets(mysqli $conn, int $user_id) {
    $sql = "SELECT
       l.id, contact, amount, c.title AS category, l.title AS lot_title, img_path, expiry_dt, winner, SQ.bet_created, SQ.user_id
    FROM (
        SELECT lot_id, user_id, MAX(created_at) AS bet_created
        FROM bets
        WHERE user_id = ?
        GROUP BY lot_id
    ) AS SQ
    JOIN lots l ON l.id = lot_id
    JOIN bets b ON b.created_at = SQ.bet_created
    JOIN categories c ON c.id = l.category_id
    JOIN users u ON u.id = l.author
    ORDER BY SQ.bet_created DESC";

    return fetch_from_db_by_params($conn, $sql, [$user_id]);
}
