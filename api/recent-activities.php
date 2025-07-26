<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("SELECT message, created_at FROM activities ORDER BY created_at DESC LIMIT 5");
    $stmt->execute();
    $activities = $stmt->fetchAll();

   $now = new DateTime('now', new DateTimeZone('Africa/Lagos'));

foreach ($activities as &$activity) {
    $created = new DateTime($activity['created_at'], new DateTimeZone('Africa/Lagos'));
    $diff = $now->diff($created);

    if ($diff->days > 1) {
        $activity['time_ago'] = $diff->days . " days ago";
    } elseif ($diff->days === 1) {
        $activity['time_ago'] = "Yesterday";
    } elseif ($diff->h >= 1) {
        $activity['time_ago'] = $diff->h . " hours ago";
    } elseif ($diff->i >= 1) {
        $activity['time_ago'] = $diff->i . " minutes ago";
    } else {
        $activity['time_ago'] = "Just now";
    }
}
    echo json_encode([
        'success' => true,
        'data' => $activities
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch activities'
    ]);
}