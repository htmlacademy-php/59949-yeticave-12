<?php

/**
 * @param mysqli $conn
 * @param string $category_id
 * @return array|false
 */
function get_lots_by_category(mysqli $conn, string $category_id) {
    $sql = "SELECT
       l.id, l.title, img_path, expiry_dt, initial_price, c.title category_title
    FROM lots l
    LEFT JOIN categories c ON c.id = l.category_id
    WHERE l.category_id = ?
    AND l.expiry_dt > NOW()
    GROUP BY l.id
    ORDER BY l.created_at DESC";

    return get_by_search_from_db($conn, $sql, [$category_id]);
}