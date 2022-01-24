<?php

/**
 * @param mysqli $conn
 * @return mixed|string
 */
function getLotsCount(mysqli $conn)
{
    $sql = "SELECT COUNT(*) as lots_count FROM lots l WHERE l.expiry_dt > NOW()";

    return fetchOneFromDb($conn, $sql)['lots_count'];
}
