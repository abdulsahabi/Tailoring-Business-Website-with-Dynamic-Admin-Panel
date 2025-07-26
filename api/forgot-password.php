<?php
// api/forgot-password.php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/mailer_config.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: POST');
        sendResponse(false, ['method' => 'Only POST requests allowed.'], 405);
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $email = strtolower(trim($data['email'] ?? ''));

    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendResponse(false, ['email' => 'A valid email is required.'], 422);
    }

    // Check if user exists
    $stmt = $pdo->prepare("SELECT id, full_name FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        sendResponse(false, ['email' => 'No account found with this email.'], 404);
    }

    $userId = $user['id'];
    $userName = $user['full_name'];

    // Expire all previous reset codes
    $pdo->prepare("UPDATE user_codes SET is_used = 1 WHERE user_id = ? AND purpose = 'reset' AND is_used = 0")
        ->execute([$userId]);

    // Generate new OTP
    $otp = generateOtpCode(); // 6-digit or any format in functions.php
    $expiresAt = (new DateTime())->modify('+5 minutes')->format('Y-m-d H:i:s');

    // Insert new OTP
    $stmt = $pdo->prepare("
        INSERT INTO user_codes (user_id, code, purpose, expires_at)
        VALUES (?, ?, 'reset', ?)
    ");
    $stmt->execute([$userId, $otp, $expiresAt]);

    // Send OTP email
    $mail = getMailer();
    $mail->addAddress($email);
    $mail->Subject = 'Your Password Reset Code';
    $mail->Body = "Hi <b>$userName</b>,<br><br>Your password reset code is: <b>$otp</b>.<br>This code will expire in 5 minutes.";
    $mail->isHTML(true);

    if (!$mail->send()) {
        sendResponse(false, ['mail' => 'Failed to send email. Please try again.'], 500);
    }

    // Success response
    sendResponse(true, [
        'message' => 'Password reset code sent successfully.',
        'response' => [
            'expires_at' => $expiresAt,
            'server_time' => (new DateTime())->format('Y-m-d H:i:s')
        ]
    ]);
} catch (Exception $e) {
    logError($e); // Optional: log for debug
    sendResponse(false, ['message' => 'Server error. Please try again.'], 500);
}