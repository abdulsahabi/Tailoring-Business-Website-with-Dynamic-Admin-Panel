<?php
// functions.php

require_once __DIR__ . '/response.php';

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// === Generate OTP ===
function generateOtpCode($length = 6): string {
  return str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
}

function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }

    // Trim whitespace and remove invisible characters
    $input = trim($input);
    $input = filter_var($input, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_BACKTICK);

    // Strip tags and convert HTML characters
    $input = strip_tags($input);                      // Remove HTML
    $input = htmlspecialchars($input, ENT_QUOTES);    // Escape quotes etc.

    return $input;
}

// === Send OTP Email ===
function send_otp_email($toEmail, $subject, $otpCode, $userName = 'User', $purpose = 'verify') {
  $templateFile = $purpose === 'login'
    ? __DIR__ . '/../admin/otp.php'
    : __DIR__ . '/../admin/email_verification_template.php';

    if (!file_exists($templateFile)) {
        error_log("Email template not found: $templateFile", 3, __DIR__ . '/../logs/errors.log');
        return false;
    }

    // Load the email template with variables
    ob_start();
    include $templateFile;
    $emailBody = ob_get_clean();

    // Get configured mailer
    require_once __DIR__ . '/mailer_config.php';
    $mail = getMailer();

    try {
        // Recipients
        $mail->addAddress($toEmail, $userName);

        // Content
        $mail->Subject = $subject;
        $mail->Body    = $emailBody;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("PHPMailer Error: {$mail->ErrorInfo}", 3, __DIR__ . '/../logs/errors.log');
        return false;
    }
}
function logActivity($message) {
    global $pdo;

    // Use PHP's current time with timezone for consistency
    $now = (new DateTime('now', new DateTimeZone('Africa/Lagos')))->format('Y-m-d H:i:s');

    $stmt = $pdo->prepare("INSERT INTO activities (message, created_at) VALUES (?, ?)");
    $stmt->execute([$message, $now]);
}

function trackPageView($path = null) {
    global $pdo;

    if (!$path) {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Auto-detect current route
    }

    $now = date('Y-m-d H:i:s');

    try {
        $stmt = $pdo->prepare("
            INSERT INTO page_views (path, count, last_viewed_at)
            VALUES (?, 1, ?)
            ON DUPLICATE KEY UPDATE count = count + 1, last_viewed_at = ?
        ");
        $stmt->execute([$path, $now, $now]);
    } catch (Exception $e) {
        logError($e); // Optional logging
    }
}

function getPeakTrafficStats(PDO $pdo) {
    $stmt = $pdo->query("
        SELECT HOUR(last_viewed_at) AS hour, SUM(count) AS total_views
        FROM page_views
        WHERE last_viewed_at >= NOW() - INTERVAL 7 DAY
        GROUP BY hour
        ORDER BY total_views DESC
        LIMIT 1
    ");

    $row = $stmt->fetch();

    if (!$row) {
        return [
            'time_range' => 'No data',
            'average_visitors' => 0
        ];
    }

    $hour = (int)$row['hour'];
    $nextHour = ($hour + 1) % 24;

    $range = date("gA", strtotime("$hour:00")) . ' – ' . date("gA", strtotime("$nextHour:00"));

    return [
        'time_range' => $range,
        'average_visitors' => number_format($row['total_views'])
    ];
}

function get_ip_location(string $ip): string {
    // Detect private/reserved ranges
    if (
        $ip === '127.0.0.1' || $ip === '::1' ||
        preg_match('/^10\./', $ip) ||
        preg_match('/^192\.168\./', $ip) ||
        preg_match('/^172\.(1[6-9]|2[0-9]|3[0-1])\./', $ip)
    ) {
        return 'Private/Local Network';
    }

    // Try external geolocation
    $url = "https://ipwho.is/$ip";
    $response = @file_get_contents($url);
    if ($response) {
        $data = json_decode($response, true);
        if ($data['success']) {
            return "{$data['city']}, {$data['region']}, {$data['country']}";
        }
    }

    return 'Unknown';
}

function detectDeviceType() {
    $agent = $_SERVER['HTTP_USER_AGENT'];

    if (preg_match('/mobile/i', $agent)) return 'Mobile';
    if (preg_match('/tablet|ipad/i', $agent)) return 'Tablet';
    return 'Desktop';
}

function logView($route = null) {
    global $pdo;

    if (!$route) {
        $route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    $device = detectDeviceType();
    $today = date('Y-m-d');

    try {
        $stmt = $pdo->prepare("
            INSERT INTO views_summary (route, device_type, view_date, count)
            VALUES (?, ?, ?, 1)
            ON DUPLICATE KEY UPDATE count = count + 1, updated_at = NOW()
        ");
        $stmt->execute([$route, $device, $today]);
    } catch (Exception $e) {
        logError($e); // Optional error logging
    }
}

function send_security_alert_email(
    string $toEmail,
    string $attemptedEmail,
    string $userName = 'Admin',
    string $ipAddress = '',
    string $attemptTime = '',
    string $location = 'Unknown'
): bool {
    $templateFile = __DIR__ . '/../admin/security_alert_template.php';

    if (!file_exists($templateFile)) {
        error_log("Security alert template not found: $templateFile", 3, __DIR__ . '/../logs/errors.log');
        return false;
    }

    // Fallbacks
    $ipAddress = $ipAddress ?: ($_SERVER['REMOTE_ADDR'] ?? 'Unknown IP');
    $attemptTime = $attemptTime ?: date('Y-m-d H:i:s');

    // Render email body with variables
    ob_start();
    include $templateFile; // expects: $userName, $attemptedEmail, $ipAddress, $location, $attemptTime
    $emailBody = ob_get_clean();

    // Load configured PHPMailer
    require_once __DIR__ . '/mailer_config.php';
    $mail = getMailer();

    try {
        $mail->addAddress($toEmail, $userName);
        $mail->Subject = "🚨 Security Alert: Failed Login Attempts Detected";
        $mail->Body    = $emailBody;
        $mail->isHTML(true);

        return $mail->send();
    } catch (Exception $e) {
        error_log("Security Email Error: {$mail->ErrorInfo}", 3, __DIR__ . '/../logs/errors.log');
        return false;
    }
}

// === Validate Email Format ===
function isValidEmail(string $email): bool {
  return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// === Validate Password Strength ===
function isStrongPassword(string $password): bool {
  return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}$/', $password);
}

// === Hash Password Securely ===
function hashPassword(string $password): string {
  return password_hash($password, PASSWORD_DEFAULT);
}

// === Verify Password ===
function verifyPassword(string $inputPassword, string $hashedPassword): bool {
  return password_verify($inputPassword, $hashedPassword);
}

// === Generate Secure Token (if needed) ===
function generateToken(int $length = 32): string {
  return bin2hex(random_bytes($length));
}

// === Check if Token Exists and Is Valid ===
function getValidToken(PDO $pdo, string $token): ?array {
  $stmt = $pdo->prepare("SELECT * FROM tokens WHERE token = ? AND is_used = 0");
  $stmt->execute([$token]);
  return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

// === Mark Token as Used ===
function markTokenAsUsed(PDO $pdo, int $tokenId): void {
  $stmt = $pdo->prepare("UPDATE tokens SET is_used = 1 WHERE id = ?");
  $stmt->execute([$tokenId]);
}

// === Create OTP Record in DB ===
function storeOtp(PDO $pdo, int $userId, string $otp, string $purpose = 'verify'): void {
  $stmt = $pdo->prepare("INSERT INTO otps (user_id, code, purpose, expires_at) VALUES (?, ?, ?, ?)");
  $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));
  $stmt->execute([$userId, $otp, $purpose, $expiresAt]);
}