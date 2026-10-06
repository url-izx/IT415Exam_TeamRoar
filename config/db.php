<?php
// config/db.php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

/**
 * ENVIRONMENT DETECTION (Local vs. Hosted)
 * Automatically detects whether CampusGo is running locally on XAMPP 
 * or hosted on InfinityFree (campusgo.site.je / live server).
 */
$isLocal = false;

if (php_sapi_name() === 'cli') {
    // PHP command-line testing on local machine
    $isLocal = true;
} elseif (isset($_SERVER['HTTP_HOST'])) {
    $hostHeader = strtolower($_SERVER['HTTP_HOST']);
    if (
        str_contains($hostHeader, 'localhost') ||
        str_contains($hostHeader, '127.0.0.1') ||
        str_contains($hostHeader, '::1')
    ) {
        $isLocal = true;
    }
} elseif (isset($_SERVER['SERVER_NAME'])) {
    $serverName = strtolower($_SERVER['SERVER_NAME']);
    if (
        str_contains($serverName, 'localhost') ||
        str_contains($serverName, '127.0.0.1')
    ) {
        $isLocal = true;
    }
}

if ($isLocal) {
    // ==========================================
    // 1. LOCAL XAMPP CONFIGURATION
    // ==========================================
    $host = 'localhost';
    $db   = 'campusgo_db';
    $user = 'root';
    $pass = '';
} else {
    // ==========================================
    // 2. INFINITYFREE HOSTED CONFIGURATION
    // ==========================================
    // Note: InfinityFree requires their specific MySQL Hostname
    // (Found in your InfinityFree Control Panel -> MySQL Databases or Account Overview,
    // e.g. sql106.infinityfree.com, sql200.epizy.com, sql300.infinityfree.com).
    // If yours is different from 'sql300.infinityfree.com', simply update $hostedHost below!
    $hostedHost = 'sql300.infinityfree.com';

    $host = getenv('DB_HOST') ?: $hostedHost;
    $db   = 'if0_43102692_campusgo_db';
    $user = 'if0_43102692';
    $pass = 'mRNcyFDWvy';
}

$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::ATTR_TIMEOUT            => 5,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection failed: ' . $e->getMessage(),
        'environment' => $isLocal ? 'local' : 'hosted',
        'host_used' => $host
    ]);
    exit;
}
