<?php

/**
 * @return mysqli|false|null
 */
function getDbConnect()
{
    $config = require('db-config.php');
    $conn = mysqli_connect($config['host'], $config['username'], $config['password'], $config['db_name']);

    mysqli_set_charset($conn, "utf8");

    return $conn;
}

/**
 * @return string|null
 */
function getDbConnectionError(): ?string
{
    return mysqli_connect_error();
}

/**
 * @param mysqli $conn
 * @return string
 */
function getDbError(mysqli $conn): string
{
    return mysqli_error($conn);
}

/**
 * @param mysqli $conn
 * @param string $sql
 * @return array|false
 */
function fetchFromDb(mysqli $conn, string $sql)
{
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        return false;
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * @param mysqli $conn
 * @param string $sql
 * @return false|mixed|string
 */
function fetchOneFromDb(mysqli $conn, string $sql)
{
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        return false;
    }

    return mysqli_fetch_assoc($result);
}

/**
 * @param mysqli $conn
 * @param string $sql
 * @param array $params
 * @return array|false
 */
function fetchFromDbByParams(mysqli $conn, string $sql, array $params)
{
    $stmt = dbGetPrepareStmt($conn, $sql, $params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        return false;
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * @param mysqli $conn
 * @param string $sql
 * @param array $data
 * @return false|int|string
 */
function dbInsert(mysqli $conn, string $sql, array $data)
{
    $stmt = dbGetPrepareStmt($conn, $sql, $data);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        return false;
    }

    return mysqli_insert_id($conn);
}
