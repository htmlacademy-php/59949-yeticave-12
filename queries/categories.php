<?php

/**
 * @param mysqli $conn
 * @return array|false
 */
function getCategories(mysqli $conn)
{
    $sql = 'SELECT * FROM categories';

    return fetchFromDb($conn, $sql);
}
