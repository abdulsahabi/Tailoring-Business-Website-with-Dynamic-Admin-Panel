<?php
// api/request-reset-code.php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../includes/functions.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: POST');
        sendResponse(false, ['message' => 'Only POST requests are allowed.'], 405);
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $email = strtolower(trim($data['email'] ?? ''));

    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendResponse(false, ['message' => 'A valid email is required.'], 422);
    }

    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        sendResponse(false, ['message' => 'User not found.'], 404);
    }

    $userId = $user['id'];

    // Fetch latest unused reset code
    $stmt = $pdo->prepare("
        SELECT code, expires_at 
        FROM user_codes 
        WHERE user_id = ? AND purpose = 'reset' AND is_used = 0 
        ORDER BY created_at DESC 
        LIMIT 1
    ");
    $stmt->execute([$userId]);
    $reset = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reset) {
        sendResponse(false, ['message' => 'No reset code found. Please request a new one.'], 404);
    }

    $now = new DateTime();
    $expiresAt = new DateTime($reset['expires_at']);

    if ($expiresAt < $now) {
        sendResponse(true, [
            'expired' => true,
            'message' => 'Code has expired.',
            'response' => [
                'server_time' => $now->format('Y-m-d H:i:s'),
                'expires_at' => $expiresAt->format('Y-m-d H:i:s')
            ]
        ]);
    }

    sendResponse(true, [
        'expired' => false,
        'response' => [
            'server_time' => $now->format('Y-m-d H:i:s'),
            'expires_at' => $expiresAt->format('Y-m-d H:i:s')
        ]
    ]);

} catch (Exception $e) {
    logError($e); // Optional: log for debugging
    sendResponse(false, ['message' => 'Server error. Please try again.'], 500);
}