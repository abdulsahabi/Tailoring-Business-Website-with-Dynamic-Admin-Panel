<?php
// api/reset-password.php

session_start();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php'; // for sanitize(), hashPassword()
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, ['message' => 'Method not allowed.'], 405);
}

$data = json_decode(file_get_contents("php://input"), true);

// Check session for verified reset
$email = $_SESSION['verified_reset_email'] ?? '';
$sessionExpiry = $_SESSION['verified_reset_expiry'] ?? 0;

if (!$email || time() > $sessionExpiry) {
    sendResponse(false, ['message' => 'Unauthorized or session expired. Please verify again.'], 401);
}

$password = trim($data['password'] ?? '');

if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}$/', $password)) {
    sendResponse(false, ['message' => 'Password does not meet security requirements.'], 422);
}

try {
    // Hash the new password
    $hashed = hashPassword($password);

    // Update the user's password
    $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE email = ?");
    $stmt->execute([$hashed, $email]);

    // Clean up any leftover reset codes (optional safety)
    $pdo->prepare("UPDATE user_codes SET is_used = 1 WHERE purpose = 'reset' AND user_id = (SELECT id FROM users WHERE email = ?)")->execute([$email]);

    // Clear session
    unset($_SESSION['verified_reset_email'], $_SESSION['verified_reset_expiry']);

    sendResponse(true, ['message' => 'Password reset successful!']);
} catch (Exception $e) {
    logError($e); // Optional error logging
    sendResponse(false, ['message' => 'An error occurred. Please try again.'], 500);
}