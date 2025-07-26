<?php
// api/verify-code.php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../includes/functions.php';

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    sendResponse(false, ['message' => 'Only POST requests are allowed.'], 405);
  }

  $input = json_decode(file_get_contents("php://input"), true);
  $email = strtolower(trim($input['email'] ?? ''));
  $code  = trim($input['code'] ?? '');

  // Input validation
  if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendResponse(false, ['message' => 'A valid email is required.'], 422);
  }

  if (!preg_match('/^\d{6}$/', $code)) {
    sendResponse(false, ['message' => 'Invalid verification code format.'], 422);
  }

  // Fetch user
  $stmt = $pdo->prepare("SELECT id, is_verified FROM users WHERE email = ? LIMIT 1");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    sendResponse(false, ['message' => 'User not found.'], 404);
  }

  if ((int)$user['is_verified'] === 1) {
    sendResponse(true, ['message' => 'Your email is already verified.', 'redirect' => '/admin/dashboar.php']);
  }

  // Fetch raw OTP without filtering
  $stmt = $pdo->prepare("SELECT * FROM user_codes WHERE user_id = ? AND code = ? ORDER BY id DESC LIMIT 1");
  $stmt->execute([$user['id'], $code]);
  $otp = $stmt->fetch(PDO::FETCH_ASSOC);

  // Check existence
  if (!$otp) {
    sendResponse(false, ['message' => 'Verification code not found.'], 400);
  }

  // Specific failure checks
  if ($otp['purpose'] !== 'verify') {
    sendResponse(false, ['message' => 'Verification code has wrong purpose.'], 400);
  }

  if ((int)$otp['is_used'] === 1) {
    sendResponse(false, ['message' => 'Verification code has already been used.',
    ], 400);
  }

  $now = new DateTime();
  $expiresAt = new DateTime($otp['expires_at']);
  if ($expiresAt < $now) {
    sendResponse(false, ['message' => 'Verification code has expired.'], 400);
  }

  // ✅ Update user and OTP
  $stmt = $pdo->prepare("UPDATE users SET is_verified = 1 WHERE id = ?");
  $stmt->execute([$user['id']]);

  $stmt = $pdo->prepare("UPDATE user_codes SET is_used = 1 WHERE id = ?");
  $stmt->execute([$otp['id']]);

  // ✅ Respond with success
  sendResponse(true, [
    'message' => 'Verification successful.',
    'redirect' => '/admin/dashboar.php'
  ]);

} catch (Exception $e) {
  logError($e);
  sendResponse(false, ['message' => 'Server error. Please try again later.'], 500);
}