<?php
// api/resend-code.php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../includes/functions.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: POST');
        sendResponse(false, ['method' => 'Only POST requests are allowed.'], 405);
    }

    // Get and sanitize input
    $input = json_decode(file_get_contents("php://input"), true);
    $email = strtolower(trim($input['email'] ?? ''));

    // Validate email
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendResponse(false, ['message' => 'A valid email is required.'], 422);
    }

    // Fetch user by email
    $stmt = $pdo->prepare("SELECT id, full_name FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        sendResponse(false, ['message' => 'User not found.'], 404);
    }

    $userId = $user['id'];
    $userName = $user['full_name'];

    // Check for recent unexpired OTP for the same purpose
    $stmt = $pdo->prepare("
        SELECT code, expires_at 
        FROM user_codes 
        WHERE user_id = ? AND purpose = 'verify' AND is_used = 0 
        ORDER BY created_at DESC LIMIT 1
    ");
    $stmt->execute([$userId]);
    $existingCode = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingCode) {
        $now = new DateTime();
        $expiresAt = new DateTime($existingCode['expires_at']);

        if ($expiresAt > $now) {
            // Still within cooldown
            sendResponse(false, ['message' => 'Please wait before requesting a new code.'], 429);
        }
    }

    // Optional: expire old unused codes for same purpose
    $pdo->prepare("UPDATE user_codes SET is_used = 1 WHERE user_id = ? AND purpose = 'verify' AND is_used = 0")->execute([$userId]);

    // Generate new OTP
    $otpCode = generateOtpCode(); // From functions.php
    $newExpiresAt = (new DateTime())->modify('+5 minutes')->format('Y-m-d H:i:s');

    // Store OTP
    $stmt = $pdo->prepare("INSERT INTO user_codes (user_id, code, purpose, expires_at) VALUES (?, ?, 'verify', ?)");
    $stmt->execute([$userId, $otpCode, $newExpiresAt]);

    // Send OTP via email
    $emailSent = send_otp_email(
        $toEmail = $email,
        $subject = "Your Email Verification Code",
        $otpCode,
        $userName,
        'verify'
    );

    if (!$emailSent) {
        sendResponse(false, ['message' => 'Failed to send email. Please try again.'], 500);
    }

    // Respond with expiration data
    logActivity("Verification code resent");
    sendResponse(true, [
        'message' => 'Verification code resent successfully.',
        'response' => [
            'expires_at' => $newExpiresAt,
            'server_time' => (new DateTime())->format('Y-m-d H:i:s')
        ]
    ]);

} catch (Exception $e) {
    logError($e);
    sendResponse(false, ['message' => 'Server error. Please try again.'], 500);
}