<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DEFAULT_DB_NAME', 'primestore');
$con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);
if ($con->connect_error) {
    echo "Couldn't connect. Error No: " .
        $con->connect_errno .
        ' : ' .
        $con->connect_error .
        '<br/>';
    die('Error connecting database');
} else {
}

$con->select_db(DEFAULT_DB_NAME);
