<?php

/**
 * @param mysqli $conn
 * @param string $category_id
 * @return array|false
 */
function getCategoryById(mysqli $conn, string $category_id)
{
    $sql = "SELECT id, title FROM categories c WHERE c.id = ?";

    return fetchFromDbByParams($conn, $sql, [$category_id])[0];
}
