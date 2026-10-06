<?php
// api/get_products.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
    $stmt->execute();
    $products = $stmt->fetchAll();

    // Get unique categories for filtering
    $categoriesStmt = $pdo->query("SELECT DISTINCT category FROM products WHERE is_active = 1 ORDER BY category ASC");
    $categories = $categoriesStmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode([
        'status' => 'success',
        'data' => [
            'products' => $products,
            'categories' => array_merge(['All'], $categories)
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to fetch products: ' . $e->getMessage()
    ]);
}
