<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

try {
    $stmt = $pdo->query("
        SELECT device_type, SUM(count) as total
        FROM views_summary
        GROUP BY device_type
    ");

    $labels = [];
    $data = [];

    while ($row = $stmt->fetch()) {
        $labels[] = $row['device_type'];
        $data[] = (int) $row['total'];
    }

    echo json_encode([
        'success' => true,
        'labels' => $labels,
        'data' => $data
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error loading device view data'
    ]);
}

