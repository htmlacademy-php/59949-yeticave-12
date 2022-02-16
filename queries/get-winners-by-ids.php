<?php

/**
 * @param mysqli $conn
 * @param string $data
 * @return array|false
 */
function getWinnersInfoByIds(mysqli $conn, string $data)
{
    $sql = "SELECT SQ.id AS lot_id, u.id AS user_id, title, name, email
    FROM (
	    SELECT id, title, winner FROM lots WHERE id IN ($data)
    ) AS SQ
    JOIN users u ON u.id = SQ.winner";

    return fetchFromDb($conn, $sql);
}
