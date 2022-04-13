<?php

/**
 * @param mysqli $conn
 * @param string $category_id
 * @return null|string
 */
function getLotsCountByCategory(mysqli $conn, string $category_id): ?string
{
    $sql = "SELECT COUNT(*) as lots_count FROM lots l WHERE l.category_id = $category_id AND l.expiry_dt > NOW()";

    $result = fetchOneFromDb($conn, $sql);

    if (isset($result) && isset($result['lots_count'])) {
        return $result['lots_count'];
    }

    return null;
}
