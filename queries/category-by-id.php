<?php

/**
 * @param mysqli $conn
 * @param string $category_id
 * @return array|false
 */
function get_category_by_id(mysqli $conn, string $category_id)
{
    $sql = "SELECT id, title FROM categories c WHERE c.id = ?";

    return fetch_from_db_by_params($conn, $sql, [$category_id])[0];
}
