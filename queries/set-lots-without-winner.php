<?php

/**
 * @param mysqli $conn
 * @return bool|mysqli_result
 */
function setExpiredLotsWithoutWinner(mysqli $conn)
{
    $sql = "UPDATE lots l
    JOIN (
        SELECT SQ1.lot_id
        FROM (
            SELECT id AS lot_id
            FROM lots
            WHERE winner IS NULL AND expiry_dt < NOW()
        ) AS SQ1
        LEFT JOIN bets b ON b.lot_id = SQ1.lot_id
        WHERE b.lot_id IS NULL
    ) AS SQ2 ON l.id = SQ2.lot_id
    SET winner = -1, updated_at = NOW()";

    return mysqli_query($conn, $sql);
}
