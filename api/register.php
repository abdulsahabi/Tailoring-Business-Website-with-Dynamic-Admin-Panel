<?php
// api/register.php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../includes/functions.php';

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  // Show a nice error page for browser visits
  if (php_sapi_name() !== 'cli' && strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'text/html') !== false) {
    include __DIR__ . '/../views/405-method-not-allowed.php';
  } else {
    // Fallback for API/JSON clients
    header('Allow: POST');
    sendResponse(false, ['method' => 'Only POST requests are allowed.'], 405);
  }
  exit;
}



  $input = json_decode(file_get_contents("php://input"), true);

  $full_name = trim($input['full_name'] ?? '');
  $email     = strtolower(trim($input['email'] ?? ''));
  $password  = $input['password'] ?? '';
  $cpassword = $input['cpassword'] ?? '';
  $token     = trim($input['token'] ?? '');

  $errors = [];

  if (!$full_name) $errors['full'] = "Full name is required.";
  if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Valid email is required.";
  if (!$password) $errors['password'] = "Password is required.";
  if ($password !== $cpassword) $errors['cpassword'] = "Passwords do not match.";
  if (!$token || strlen($token) < 6) $errors['token'] = "Valid token is required.";

  if (!empty($errors)) sendResponse(false, $errors, 422);

  // Check if email already exists
  $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->execute([$email]);
  if ($stmt->rowCount() > 0) {
    sendResponse(false, ['email' => 'Email is already in use.'], 422);
  }

  // Check if token is valid and unused
  $stmt = $pdo->prepare("SELECT * FROM tokens WHERE token = ? AND is_used = 0 LIMIT 1");
  $stmt->execute([$token]);
  $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$tokenData) {
    sendResponse(false, ['token' => 'Invalid or already used token.'], 422);
  }

  // Create new user
  $passwordHash = password_hash($password, PASSWORD_BCRYPT);
  $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
  $stmt->execute([$full_name, $email, $passwordHash]);
  $user_id = $pdo->lastInsertId();

  // Mark token as used
  $stmt = $pdo->prepare("UPDATE tokens SET is_used = 1, updated_at = NOW() WHERE id = ?");
  $stmt->execute([$tokenData['id']]);

  // Generate and insert OTP for verification
  $otpCode  = generateOtpCode();
  $expires  = date('Y-m-d H:i:s', strtotime('+5 minutes'));
  $stmt = $pdo->prepare("INSERT INTO user_codes (user_id, code, purpose, expires_at) VALUES (?, ?, 'verify', ?)");
  $stmt->execute([$user_id, $otpCode, $expires]);

  // Send email verification code
  send_otp_email($email, "Verify Your AU Idris Account", $otpCode, $full_name, 'verify');
  logActivity("New user account created");
  // Respond with success and redirect URL
  sendResponse(true, [
    'message' => 'Registration successful. Please verify your email address.',
    'redirect' => '/admin/verify-code.php?email=' . urlencode($email)
  ]);

} catch (Exception $e) {
  logError($e);
  sendResponse(false, ['server' => 'Something went wrong. Please try again later.'], 500);
}