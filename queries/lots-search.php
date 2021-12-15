<?php

/**
 * @param mysqli $conn
 * @param string $search
 * @return array|false
 */
function search_lots(mysqli $conn, string $search) {
    $sql = "SELECT l.id, l.title, description, c.title category_title, expiry_dt, initial_price, img_path
    FROM lots l
    LEFT JOIN bets b ON l.id = b.lot_id
    JOIN categories c ON l.category_id = c.id
    WHERE MATCH(l.title, description) AGAINST(? IN BOOLEAN MODE)
    GROUP BY l.id
    ORDER BY l.created_at DESC";

    return get_by_search_from_db($conn, $sql, [$search]);
}
