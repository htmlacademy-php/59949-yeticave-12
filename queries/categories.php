<?php

/**
 * @param mysqli $conn
 * @return array|false
 */
function get_categories(mysqli $conn)
{
    $sql = 'SELECT * FROM categories';

    return fetch_from_db($conn, $sql);
}
