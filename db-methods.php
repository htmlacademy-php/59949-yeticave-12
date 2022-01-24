<?php

/**
 * @return mysqli|false|null
 */
function get_db_connect()
{
    $config = require('db-config.php');
    $conn = mysqli_connect($config['host'], $config['username'], $config['password'], $config['db_name']);

    mysqli_set_charset($conn, "utf8");

    return $conn;
}

/**
 * @return string|null
 */
function get_db_connection_error(): ?string
{
    return mysqli_connect_error();
}

/**
 * @param mysqli $conn
 * @return string
 */
function get_db_error(mysqli $conn): string
{
    return mysqli_error($conn);
}

/**
 * @param mysqli $conn
 * @param string $sql
 * @return array|false
 */
function fetch_from_db(mysqli $conn, string $sql)
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
function fetch_one_from_db(mysqli $conn, string $sql)
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
function fetch_from_db_by_params(mysqli $conn, string $sql, array $params)
{
    $stmt = db_get_prepare_stmt($conn, $sql, $params);
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
function db_insert(mysqli $conn, string $sql, array $data)
{
    $stmt = db_get_prepare_stmt($conn, $sql, $data);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        return false;
    }

    return mysqli_insert_id($conn);
}
