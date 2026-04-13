<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

if ($db_connection_error !== null) {
    echo json_encode(['error' => $db_connection_error]);
    exit;
}

$income = 0;
$expense = 0;

$q = $conn->query("SELECT type, SUM(amount) AS total FROM transactions GROUP BY type");
if ($q) {
    while ($row = $q->fetch_assoc()) {
        if ($row['type'] === 'income') $income = (float) $row['total'];
        if ($row['type'] === 'expense') $expense = (float) $row['total'];
    }
} else {
    echo json_encode(['error' => 'Table error: ' . $conn->error]);
    exit;
}

echo json_encode([
    'income' => round($income, 2),
    'expense' => round($expense, 2),
    'total_income' => round($income, 2),
    'total_expense' => round($expense, 2),
]);
?>