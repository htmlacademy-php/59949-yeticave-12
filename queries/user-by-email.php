<?php

/**
 * @param mysqli $conn
 * @param string $email
 * @return array|false
 */
function getUserByEmail(mysqli $conn, string $email)
{
    $email_escaped_str = mysqli_real_escape_string($conn, $email);
    $sql = "SELECT * FROM users WHERE email = '$email_escaped_str'";

    return fetchFromDb($conn, $sql);
}
