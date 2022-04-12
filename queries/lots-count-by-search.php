<?php

/**
 * @param mysqli $conn
 * @param string $search_str
 * @return null|string
 */
function getLotsCountBySearch(mysqli $conn, string $search_str): ?string
{
    $sql = "SELECT COUNT(*) as lots_count
    FROM lots l
    WHERE MATCH(l.title, description) AGAINST('$search_str' IN BOOLEAN MODE)
    AND l.expiry_dt > NOW()";

    $result = fetchOneFromDb($conn, $sql);

    if (isset($result) && isset($result['lots_count'])) {
        return $result['lots_count'];
    }

    return null;
}
