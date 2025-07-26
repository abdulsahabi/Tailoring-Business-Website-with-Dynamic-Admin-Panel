<?php
// api/request-code.php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../includes/functions.php';

try {
  $input = json_decode(file_get_contents("php://input"), true);
  $email = strtolower(trim($input['email'] ?? ''));

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

  // Get latest verification code
  $stmt = $pdo->prepare("
    SELECT id, code, expires_at, is_used 
    FROM user_codes 
    WHERE user_id = ? AND purpose = 'verify' 
    ORDER BY id DESC 
    LIMIT 1
  ");
  $stmt->execute([$user['id']]);
  $code = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$code) {
    sendResponse(false, ['message' => 'No code found.'], 404);
  }

  $now = new DateTime();
  $expiresAt = new DateTime($code['expires_at']);

  $isExpired = $now > $expiresAt;
  $isUsed = (bool)$code['is_used'];

  if ($isUsed) {
    sendResponse(true, [
      'expired' => true,
      'message' => 'This code has already been used. Please resend a new one.',
      'response' => [
        'expires_at' => $code['expires_at'],
        'server_time' => $now->format('Y-m-d H:i:s')
      ]
    ]);
  }

  if ($isExpired) {
    sendResponse(true, [
      'expired' => true,
      'message' => 'Your code has expired. Please request a new one.',
      'response' => [
        'expires_at' => $code['expires_at'],
        'server_time' => $now->format('Y-m-d H:i:s')
      ]
    ]);
    
    return;
  }

  // ✅ Code is still valid and not used
  sendResponse(true, [
    'expired' => false,
    'message' => 'Code is still valid.',
    'response' => [
      'expires_at' => $code['expires_at'],
      'server_time' => $now->format('Y-m-d H:i:s')
    ]
  ]);

} catch (Exception $e) {
  logError($e);
  sendResponse(false, ['message' => 'Server error.'], 500);
}