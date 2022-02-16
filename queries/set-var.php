<?php

/**
 * @param mysqli $conn
 * @return bool|mysqli_result
 */
function setEmptyVar(mysqli $conn)
{
    $sql = "SET @var := NULL";

    return mysqli_query($conn, $sql);
}
