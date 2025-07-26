<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $input = json_decode(file_get_contents("php://input"), true);
    $email = strtolower(trim($input['email'] ?? ''));
    $password = $input['password'] ?? '';

    $errors = [];

    // Basic input validation
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if (!$password) {
        $errors['password'] = 'Password is required.';
    }

    if (!empty($errors)) {
        sendResponse(false, $errors, 422);
    }

    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $now = new DateTime();
    $attemptTime = $now->format('Y-m-d H:i:s');
    $location = get_ip_location($ip); // Placeholder: You can replace with IP geolocation logic

    // Rate limiting: Check failed login attempts in last 15 minutes
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM login_attempts 
        WHERE email = ? AND ip_address = ? AND success = 0 
        AND created_at >= (NOW() - INTERVAL 15 MINUTE)
    ");
    $stmt->execute([$email, $ip]);
    $attemptCount = (int) $stmt->fetchColumn();

    if ($attemptCount >= 5) {
        sendResponse(false, [
            'message' => 'Too many failed login attempts. Please try again after 15 minutes.'
        ], 429);
    }

    // Look up user
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $isValid = $user && password_verify($password, $user['password']);

    // Log the attempt
    $logStmt = $pdo->prepare("
        INSERT INTO login_attempts (email, ip_address, success, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    $logStmt->execute([$email, $ip, $isValid ? 1 : 0]);
    
    
    if (!$isValid) {
        // On 5th failure, send security alert email
      
        if ($attemptCount + 1 === 5) {
          $adminEmail = "afcbeewhy@gmail.com";
            send_security_alert_email(
                toEmail: $adminEmail,  // Define this constant in your config
                userName: $email,
                attemptedEmail: $email,
                ipAddress: $ip,
                attemptTime: $attemptTime,
                location: $location
            );
        }

        sendResponse(false, ['email' => 'Invalid email or password.'], 401);
    }

    // Login success – regenerate session ID
    // After successful login and session creation
    session_regenerate_id(true);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['login_time'] = time();          // 🕒 Login timestamp
    $_SESSION['last_activity'] = time();       // 🕓 Last active timestamp (for idle timeout)
    logActivity("Admin logged in");
   
    sendResponse(true, [
    'message' => 'Login successful.',
    'redirect' => '/admin/dashboard.php'
]);

} catch (Exception $e) {
    logError($e);
    sendResponse(false, [
        'message' => 'An unexpected server error occurred. Please try again later.'
    ], 500);
}