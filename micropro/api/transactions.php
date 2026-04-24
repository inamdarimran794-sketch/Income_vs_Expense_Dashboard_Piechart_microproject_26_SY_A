<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

if ($db_connection_error !== null || $conn === null) {
    echo json_encode(['error' => $db_connection_error]);
    exit;
}

$transactions = [];
$query = "SELECT id, type, amount, description FROM transactions ORDER BY id DESC";
$result = $conn->query($query);

if (!$result) {
    echo json_encode(['error' => 'Table error: ' . $conn->error]);
    exit;
}

while ($row = $result->fetch_assoc()) {
    $transactions[] = [
        'id' => (int) $row['id'],
        'type' => $row['type'],
        'amount' => round((float) $row['amount'], 2),
        'description' => $row['description'] !== '' ? $row['description'] : '-',
    ];
}

echo json_encode(['transactions' => $transactions]);
?>
