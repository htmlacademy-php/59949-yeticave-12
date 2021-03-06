<?php

/**
 * @param mysqli $conn
 * @param int $items_per_page
 * @param int $offset
 * @return array|false
 */
function getLots(mysqli $conn, int $items_per_page, int $offset)
{
    $sql = "SELECT
       l.id, l.title, c.title category_title, expiry_dt, initial_price, img_path
    FROM lots l
    JOIN categories c ON l.category_id = c.id
    WHERE l.expiry_dt > NOW()
    ORDER BY l.created_at DESC
    LIMIT $items_per_page OFFSET $offset";

    return fetchFromDb($conn, $sql);
}
