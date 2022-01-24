<?php

/**
 * @param mysqli $conn
 * @param string $category_id
 * @return array|false
 */
function getLotsByCategory(mysqli $conn, string $category_id)
{
    $sql = "SELECT
       l.id, l.title, img_path, expiry_dt, initial_price, c.title category_title
    FROM lots l
    JOIN categories c ON c.id = l.category_id
    WHERE l.category_id = ?
    AND l.expiry_dt > NOW()
    ORDER BY l.created_at DESC";

    return fetchFromDbByParams($conn, $sql, [$category_id]);
}
