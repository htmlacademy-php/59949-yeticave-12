<?php
require_once('mailer.php');

require_once('queries/set-lots-without-winner.php');
require_once('queries/set-var.php');
require_once('queries/get-var-value.php');
require_once('queries/set-winners.php');
require_once('queries/get-winners-by-ids.php');

setExpiredLotsWithoutWinner($db_conn);

mysqli_query($db_conn, "START TRANSACTION");

$result1 = setEmptyVar($db_conn);
$result2 = setWinners($db_conn);
$result3 = getVarValue($db_conn);

if ($result1 && $result2 && $result3 && isset($result3['@var'])) {
    mysqli_query($db_conn, "COMMIT");

    $winnersInfo = getWinnersInfoByIds($db_conn, $result3['@var']);

    sendLettersToTheWinners($winnersInfo);
}
else {
    mysqli_query($db_conn, "ROLLBACK");
}
