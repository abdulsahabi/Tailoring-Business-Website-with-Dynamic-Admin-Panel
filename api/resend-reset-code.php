<?php
// api/resend-reset-code.php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../includes/mailer_config.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: POST');
        sendResponse(false, ['message' => 'Only POST requests are allowed.'], 405);
    }

    // Sanitize and validate email
    $input = json_decode(file_get_contents("php://input"), true);
    $email = strtolower(trim($input['email'] ?? ''));

    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendResponse(false, ['message' => 'A valid email is required.'], 422);
    }

    // Lookup user
    $stmt = $pdo->prepare("SELECT id, full_name FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        sendResponse(false, ['message' => 'User not found.'], 404);
    }

    $userId = $user['id'];
    $userName = $user['full_name'];

    // Check for recent unused OTP
    $stmt = $pdo->prepare("
        SELECT code, expires_at 
        FROM user_codes 
        WHERE user_id = ? AND purpose = 'reset' AND is_used = 0 
        ORDER BY created_at DESC LIMIT 1
    ");
    $stmt->execute([$userId]);
    $existingCode = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingCode) {
        $now = new DateTime();
        $expiresAt = new DateTime($existingCode['expires_at']);

        if ($expiresAt > $now) {
            sendResponse(false, ['message' => 'Please wait before requesting a new code.'], 429);
        }
    }

    // Optional: expire all old reset codes
    $pdo->prepare("UPDATE user_codes SET is_used = 1 WHERE user_id = ? AND purpose = 'reset' AND is_used = 0")->execute([$userId]);

    // Generate new OTP
    $otpCode = generateOtpCode();
    $newExpiresAt = (new DateTime())->modify('+5 minutes')->format('Y-m-d H:i:s');

    // Store OTP
    $stmt = $pdo->prepare("INSERT INTO user_codes (user_id, code, purpose, expires_at) VALUES (?, ?, 'reset', ?)");
    $stmt->execute([$userId, $otpCode, $newExpiresAt]);

    // Send OTP Email (uses shared helper)
    $emailSent = send_otp_email(
        $toEmail = $email,
        $subject = "Your Password Reset Code",
        $otpCode,
        $userName,
        'reset'
    );

    if (!$emailSent) {
        sendResponse(false, ['message' => 'Failed to send email. Please try again.'], 500);
    }

    // Final response
    sendResponse(true, [
        'message' => 'Reset code sent successfully.',
        'response' => [
            'expires_at' => $newExpiresAt,
            'server_time' => (new DateTime())->format('Y-m-d H:i:s')
        ]
    ]);

} catch (Exception $e) {
    logError($e); // Optional logging
    sendResponse(false, ['message' => 'Server error. Please try again.'], 500);
}