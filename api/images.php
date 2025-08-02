<?php
require_once '../includes/db.php';
header('Content-Type: application/json');

try {
    $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 4;
    $offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;

    $stmt = $pdo->prepare("SELECT image_name AS src, alt, caption, tag FROM images ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->execute();
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $images]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch images.']);
}