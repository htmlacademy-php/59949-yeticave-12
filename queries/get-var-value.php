<?php

/**
 * @param mysqli $conn
 * @return false|mixed|string
 */
function getVarValue(mysqli $conn)
{
    $sql = "SELECT @var";

    return fetchOneFromDb($conn, $sql);
}
