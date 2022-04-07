<?php

/**
 * @param mysqli $conn
 * @param string $search_str
 * @return mixed|string
 */
function getLotsCountBySearch(mysqli $conn, string $search_str)
{
    $sql = "SELECT COUNT(*) as lots_count
    FROM lots l
    WHERE MATCH(l.title, description) AGAINST('$search_str' IN BOOLEAN MODE)
    AND l.expiry_dt > NOW()";

    return fetchOneFromDb($conn, $sql)['lots_count'];
}
