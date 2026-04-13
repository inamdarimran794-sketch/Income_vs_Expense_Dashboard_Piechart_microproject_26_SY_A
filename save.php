<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

require_once __DIR__ . '/../config/db.php';

if ($db_connection_error !== null || $conn === null) {
    echo json_encode(['status' => 'error', 'message' => $db_connection_error]);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true) ?? [];

$type = isset($data['type']) ? strtolower(trim((string) $data['type'])) : '';
$amount = isset($data['amount']) ? (float) $data['amount'] : 0;
$description = isset($data['description']) ? trim((string) $data['description']) : 'Added from form';

if ($type !== 'income' && $type !== 'expense') {
    echo json_encode(['status' => 'error', 'message' => 'Select type: Income or Expense']);
    exit;
}
if ($amount <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Enter a valid amount']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO transactions (type, amount, description) VALUES (?, ?, ?)");
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
    exit;
}
$stmt->bind_param("sds", $type, $amount, $description);
$ok = $stmt->execute();
$stmt->close();

if ($ok) {
    echo json_encode(['status' => 'success', 'message' => 'Data saved successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save']);
}
?>
