<?php

/**
 * @param mysqli $conn
 * @param string $category_id
 * @return mixed|string
 */
function getLotsCountByCategory(mysqli $conn, string $category_id)
{
    $sql = "SELECT COUNT(*) as lots_count FROM lots l WHERE l.category_id = $category_id AND l.expiry_dt > NOW()";

    return fetchOneFromDb($conn, $sql)['lots_count'];
}
