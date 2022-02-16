<?php

/**
 * @param mysqli $conn
 * @return bool|mysqli_result
 */
function setWinners(mysqli $conn)
{
    $sql = "UPDATE lots l
    JOIN (
        SELECT bt.id as bet_id, user_id, SQ2.lot_id, SQ2.bet_created
        FROM bets bt
        JOIN (
        SELECT b.lot_id, MAX(created_at) AS bet_created
        FROM (
            SELECT id AS lot_id, @var := CONCAT_WS(',', id, @var)
            FROM lots
            WHERE winner IS NULL AND expiry_dt < NOW()
        ) AS SQ1
        JOIN bets b ON b.lot_id = SQ1.lot_id
        GROUP BY b.lot_id
        ) AS SQ2 ON bt.lot_id = SQ2.lot_id AND bt.created_at = SQ2.bet_created
    ) AS SQ3 ON l.id = SQ3.lot_id
    SET winner = SQ3.user_id, updated_at = NOW()";

    return mysqli_query($conn, $sql);
}
