<?php
// api/verify-reset.php

session_start();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, ['message' => 'Only POST requests are allowed.'], 405);
}

$data  = json_decode(file_get_contents("php://input"), true);
$email = strtolower(trim($data['email'] ?? ''));
$code  = trim($data['code'] ?? '');

// Validate input
if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^\d{6}$/', $code)) {
    sendResponse(false, ['message' => 'Invalid email or code.'], 422);
}

try {
    // Fetch user
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        sendResponse(false, ['message' => 'User not found.'], 404);
    }

    $userId = $user['id'];

    // Fetch latest reset code
    $stmt = $pdo->prepare("
        SELECT id, expires_at, is_used 
        FROM user_codes 
        WHERE user_id = ? AND code = ? AND purpose = 'reset' 
        ORDER BY created_at DESC LIMIT 1
    ");
    $stmt->execute([$userId, $code]);
    $otpData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$otpData) {
        sendResponse(false, ['message' => 'Code not found.'], 404);
    }

    if ((int)$otpData['is_used'] === 1) {
        sendResponse(false, ['message' => 'Code has already been used.'], 410);
    }

    $now = new DateTime();
    $expiresAt = new DateTime($otpData['expires_at']);

    if ($now > $expiresAt) {
        sendResponse(false, ['message' => 'Code has expired.'], 410);
    }

    // Mark OTP as used
    $pdo->prepare("UPDATE user_codes SET is_used = 1 WHERE id = ?")->execute([$otpData['id']]);

    // ✅ Save verified session
    $_SESSION['verified_reset_email']  = $email;
    $_SESSION['verified_reset_expiry'] = time() + 300; // 5 mins access

    // Success
    sendResponse(true, [
        'message' => 'Code verified successfully.',
        'redirect' => '/admin/reset-password.php'
    ]);
} catch (Exception $e) {
    logError($e);
    sendResponse(false, ['message' => 'Server error. Please try again.'], 500);
}