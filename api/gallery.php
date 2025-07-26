<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Only GET allowed
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Only GET method is allowed.']);
    exit;
}

// Input sanitation
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$perPage = 5;
$offset = ($page - 1) * $perPage;

try {
    // Count total rows
    if ($search !== '') {
        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM images WHERE caption LIKE :search OR tag LIKE :search");
        $countStmt->execute(['search' => "%$search%"]);
    } else {
        $countStmt = $pdo->query("SELECT COUNT(*) FROM images");
    }
    $total = (int) $countStmt->fetchColumn();

    // Fetch paginated data
    if ($search !== '') {
        $stmt = $pdo->prepare("SELECT id, image_name, caption, created_at FROM images 
                               WHERE caption LIKE :search OR tag LIKE :search
                               ORDER BY created_at DESC LIMIT :offset, :limit");
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare("SELECT id, image_name, caption, created_at FROM images 
                               ORDER BY created_at DESC LIMIT :offset, :limit");
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->execute();
    }

    $items = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $items[] = [
            'id' => $row['id'],
            'title' => $row['caption'],
            'src' => '/uploads/gallery/' . $row['image_name'],
            'date' => date('M d, Y', strtotime($row['created_at']))
        ];
    }

    header('Content-Type: application/json');
    echo json_encode([
        'items' => $items,
        'total' => $total
    ]);

} catch (Exception $e) {
    logError($e); // Your custom logger if available
    http_response_code(500);
    echo json_encode(['error' => 'Something went wrong.']);
}