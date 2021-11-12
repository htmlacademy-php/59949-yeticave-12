<?php

require_once('db-config.php');

if (!$db_conn) {
    $error = mysqli_connect_error();
    print(include_template('error.php', ['error' => $error]));
    exit();
}

$sql = 'SELECT * FROM categories';
$result = mysqli_query($db_conn, $sql);

if(!$result) {
    print(include_template('error.php', ['error' => mysqli_error($link)]));
    exit();
}

$categories_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
