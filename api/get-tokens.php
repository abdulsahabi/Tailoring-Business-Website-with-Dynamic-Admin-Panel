<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/response.php';
require_once __DIR__ . '/../includes/auth.php';

$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$limit = isset($_GET['limit']) ? max((int)$_GET['limit'], 1) : 10;
$offset = ($page - 1) * $limit;

try {
    // Total count using PDO
    $stmtTotal = $pdo->query("SELECT COUNT(*) AS total FROM tokens");
    $totalRow = $stmtTotal->fetch(PDO::FETCH_ASSOC);
    $totalTokens = $totalRow['total'];
    $totalPages = ceil($totalTokens / $limit);

    // Get paginated tokens using PDO
    $stmt = $pdo->prepare("SELECT id, token, status, created_at FROM tokens ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $tokens = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(true, [
        'tokens' => $tokens,
        'page' => $page,
        'pages' => $totalPages
    ]);

} catch (PDOException $e) {
    sendResponse(false, ['error' => 'Database error: ' . $e->getMessage()], 500);
}