<?php

require_once ('db-methods.php');

function fetch_lots($conn) {
    $sql = 'SELECT '
        . 'l.id, l.title, c.title category_title, expiry_dt, initial_price, img_path '
        . 'FROM lots l '
        . 'LEFT JOIN bets b ON l.id = b.lot_id '
        . 'JOIN categories c ON l.category_id = c.id '
        . 'WHERE l.expiry_dt > NOW() '
        . 'GROUP BY l.id '
        . 'ORDER BY l.created_at DESC '
        . 'LIMIT 6';

    check_db_connection($conn);

    set_charset($conn, "utf8");
    return fetch_from_db($conn, $sql);
}
