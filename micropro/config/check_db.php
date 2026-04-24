<?php
/**
 * Open this file in your browser to verify database connection.
 * Example: http://localhost/Test/check_db.php
 */
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Connection Test</title>
    <style>
        body { font-family: sans-serif; max-width: 500px; margin: 2rem auto; padding: 1rem; }
        .ok { color: #00c853; font-weight: bold; }
        .err { color: #c62828; font-weight: bold; }
        pre { background: #f5f5f5; padding: 1rem; overflow: auto; }
    </style>
</head>
<body>
    <h1>Database connection test</h1>
<?php
require_once __DIR__ . '/config/db.php';

if ($db_connection_error !== null || $conn === null) {
    echo '<p class="err">✗ ' . htmlspecialchars($db_connection_error) . '</p>';
    echo '<p>Fix: Edit config/db.php and set DB_USER, DB_PASS. Ensure MySQL is running.</p>';
} else {
    echo '<p class="ok">✓ Connection successful.</p>';

    $q = $conn->query("SELECT COUNT(*) AS n FROM transactions");
    if ($q) {
        $row = $q->fetch_assoc();
        echo '<p class="ok">✓ Table "transactions" exists. Rows: ' . (int)$row['n'] . '</p>';
    } else {
        echo '<p class="err">✗ Table "transactions" missing or error. Run setup.sql in phpMyAdmin.</p>';
        echo '<pre>' . htmlspecialchars($conn->error) . '</pre>';
    }
}
?>
    <p><a href="index.php">← Back to Dashboard</a></p>
</body>
</html>