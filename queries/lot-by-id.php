<?php

function fetch_lot_by_id(mysqli $conn, int $id) {
    $sql = "SELECT l.id, l.title, c.title AS category_title, img_path, description, expiry_dt, bet_step,
       (initial_price + IFNULL(SUM(b.amount), 0)) AS current_price FROM lots l
    JOIN categories c ON l.category_id = c.id
    LEFT JOIN bets b ON l.id = b.lot_id
    WHERE l.id = $id
    GROUP BY l.id";

    return fetch_from_db($conn, $sql);
}
