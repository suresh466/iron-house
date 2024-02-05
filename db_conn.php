<?php

define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');
define('DB_NAME', 'iron_house');

// get database connection
$conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    or die('Could not connect to MySQL: ' . mysqli_connect_error());
mysqli_set_charset($conn, 'utf8');

// sanitize input
function prepare_string($dbc, $string)
{
    $string_trimmed = trim($string);
    $string = mysqli_real_escape_string($dbc, $string_trimmed);
    return $string;
}