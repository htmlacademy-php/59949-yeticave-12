<?php

/**
 * @param mysqli $conn
 * @param string $search_str
 * @return array|false
 */
function getLotsBySearchStr(mysqli $conn, string $search_str)
{
    $sql = "SELECT l.id, l.title, description, c.title category_title, expiry_dt, initial_price, img_path
    FROM lots l
    JOIN categories c ON l.category_id = c.id
    WHERE MATCH(l.title, description) AGAINST(? IN BOOLEAN MODE)
    ORDER BY l.created_at DESC";

    return fetchFromDbByParams($conn, $sql, [$search_str]);
}
