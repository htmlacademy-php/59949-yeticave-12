<?php

/**
 * @param mysqli $conn
 * @return null|string
 */
function getLotsCount(mysqli $conn): ?string
{
    $sql = "SELECT COUNT(*) as lots_count FROM lots l WHERE l.expiry_dt > NOW()";

    $result = fetchOneFromDb($conn, $sql);

    if (isset($result) && isset($result['lots_count'])) {
        return $result['lots_count'];
    }

    return null;
}
