<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->query("
        SELECT 
            DAYNAME(created_at) AS day,
            COUNT(*) AS total
        FROM images
        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
        GROUP BY DAYOFWEEK(created_at)
        ORDER BY FIELD(DAYNAME(created_at), 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
    ");

    $result = $stmt->fetchAll();

    // Build array for chart
    $dayMap = [
        'Monday'    => 0, 'Tuesday'   => 0, 'Wednesday' => 0,
        'Thursday'  => 0, 'Friday'    => 0, 'Saturday'  => 0,
        'Sunday'    => 0
        
    ];

    foreach ($result as $row) {
        $dayMap[$row['day']] = (int)$row['total'];
    }

    sendResponse(true, ['data' => array_values($dayMap)]);
} catch (Exception $e) {
    logError($e);
    sendResponse(false, ['message' => 'Error loading chart.']);
}