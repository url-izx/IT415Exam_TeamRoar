<?php
// api/get_transaction.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

$txn = $_GET['txn'] ?? '';
$id  = $_GET['id'] ?? '';

if (empty($txn) && empty($id)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Transaction ID or number required']);
    exit;
}

try {
    if (!empty($txn)) {
        $stmt = $pdo->prepare("SELECT * FROM transactions WHERE txn_number = ?");
        $stmt->execute([$txn]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ?");
        $stmt->execute([$id]);
    }
    $transaction = $stmt->fetch();

    if (!$transaction) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Transaction not found']);
        exit;
    }

    $stmtItems = $pdo->prepare("SELECT * FROM transaction_items WHERE transaction_id = ?");
    $stmtItems->execute([$transaction['id']]);
    $items = $stmtItems->fetchAll();

    echo json_encode([
        'status' => 'success',
        'data' => [
            'transaction' => $transaction,
            'items' => $items,
            'formatted_date' => date('F j, Y · h:i A', strtotime($transaction['created_at']))
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
