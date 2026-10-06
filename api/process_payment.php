<?php
// api/process_payment.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    exit;
}

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON payload']);
    exit;
}

$cart = $input['cart'] ?? [];
$paymentMethod = trim($input['payment_method'] ?? '');
$amountPaid = floatval($input['amount_paid'] ?? 0);
$referenceNo = trim($input['reference_no'] ?? '');

if (empty($cart) || !is_array($cart)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Cart cannot be empty']);
    exit;
}

$validMethods = ['Cash', 'QR Payment', 'Credit / Debit Card'];
if (!in_array($paymentMethod, $validMethods)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid payment method']);
    exit;
}

// Compute and verify totals on the server side to ensure integrity
$computedTotal = 0;
foreach ($cart as $item) {
    $qty = intval($item['quantity'] ?? 0);
    $price = floatval($item['price'] ?? 0);
    if ($qty <= 0 || $price < 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid cart item quantity or price']);
        exit;
    }
    $computedTotal += ($qty * $price);
}

// Check payment amount for Cash
if ($paymentMethod === 'Cash') {
    if ($amountPaid < $computedTotal) {
        $shortage = $computedTotal - $amountPaid;
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'code' => 'INSUFFICIENT_PAYMENT',
            'message' => 'Insufficient payment.',
            'shortage' => $shortage,
            'required' => $computedTotal
        ]);
        exit;
    }
    $changeAmount = $amountPaid - $computedTotal;
} else {
    // For QR and Card, simulated payment has paid == computedTotal and 0 change
    $amountPaid = $computedTotal;
    $changeAmount = 0.00;
}

try {
    $pdo->beginTransaction();

    // Determine next sequential transaction number
    $year = date('Y');
    // Count transactions from this year to give realistic numbering (e.g. TXN-2026-00125)
    $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM transactions WHERE txn_number LIKE ?");
    $stmtCount->execute(["TXN-{$year}-%"]);
    $currentCount = $stmtCount->fetchColumn();
    $nextSequence = 125 + $currentCount; // Base offset to start with TXN-2026-00125 like sample UI
    $txnNumber = sprintf("TXN-%s-%05d", $year, $nextSequence);

    // If reference_no is empty for QR/Card, generate one
    if (empty($referenceNo)) {
        if ($paymentMethod === 'QR Payment') {
            $referenceNo = sprintf("QR-TXN-%s-%05d", $year, $nextSequence);
        } else if ($paymentMethod === 'Credit / Debit Card') {
            $referenceNo = 'AUTH-' . strtoupper(bin2hex(random_bytes(4)));
        }
    }

    $stmtTxn = $pdo->prepare("
        INSERT INTO transactions (txn_number, subtotal, total_amount, payment_method, amount_paid, change_amount, reference_no, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'Payment Successful')
    ");
    $stmtTxn->execute([
        $txnNumber,
        $computedTotal,
        $computedTotal,
        $paymentMethod,
        $amountPaid,
        $changeAmount,
        $referenceNo
    ]);
    $transactionId = $pdo->lastInsertId();

    // Insert items
    $stmtItem = $pdo->prepare("
        INSERT INTO transaction_items (transaction_id, product_id, product_name, quantity, unit_price, subtotal)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    foreach ($cart as $item) {
        $qty = intval($item['quantity']);
        $price = floatval($item['price']);
        $subtotal = $qty * $price;
        $prodId = isset($item['id']) ? intval($item['id']) : null;
        $name = trim($item['name']);

        $stmtItem->execute([
            $transactionId,
            $prodId,
            $name,
            $qty,
            $price,
            $subtotal
        ]);
    }

    $pdo->commit();

    // Fetch created record with items
    $stmtFetch = $pdo->prepare("SELECT * FROM transactions WHERE id = ?");
    $stmtFetch->execute([$transactionId]);
    $transaction = $stmtFetch->fetch();

    $stmtFetchItems = $pdo->prepare("SELECT * FROM transaction_items WHERE transaction_id = ?");
    $stmtFetchItems->execute([$transactionId]);
    $items = $stmtFetchItems->fetchAll();

    echo json_encode([
        'status' => 'success',
        'message' => 'Transaction completed successfully',
        'data' => [
            'transaction' => $transaction,
            'items' => $items,
            'formatted_date' => date('F j, Y · h:i A', strtotime($transaction['created_at']))
        ]
    ]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Transaction failed: ' . $e->getMessage()
    ]);
}
