<?php

/**
 * @param mysqli $conn
 * @param string $search_str
 * @return array|false
 */
function get_lots_by_search_str(mysqli $conn, string $search_str) {
    $sql = "SELECT l.id, l.title, description, c.title category_title, expiry_dt, initial_price, img_path
    FROM lots l
    JOIN categories c ON l.category_id = c.id
    WHERE MATCH(l.title, description) AGAINST(? IN BOOLEAN MODE)
    GROUP BY l.id
    ORDER BY l.created_at DESC";

    return fetch_from_db_by_params($conn, $sql, [$search_str]);
}
