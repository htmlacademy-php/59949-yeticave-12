<?php

function fetch_categories(mysqli $conn) {
    $sql = 'SELECT * FROM categories';

    return fetch_from_db($conn, $sql);
}
