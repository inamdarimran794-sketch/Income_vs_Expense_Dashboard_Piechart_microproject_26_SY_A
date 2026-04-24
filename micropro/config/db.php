<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'income_expense');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$db_connection_error = null;

if ($conn->connect_error) {
    $db_connection_error = 'Connection failed: ' . $conn->connect_error;
    $conn = null;
} else {
    $conn->set_charset('utf8mb4');
}
?>