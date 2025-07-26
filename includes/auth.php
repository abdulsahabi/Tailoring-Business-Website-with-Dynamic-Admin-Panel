<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Required dependencies
require_once __DIR__ . '/db.php';         // Database (PDO)
require_once __DIR__ . '/functions.php';  // Optional utilities (e.g. logError())

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: /admin/login.php");
    exit;
}

// === Session Expiry Checks ===
define('SESSION_IDLE_TIMEOUT', 3 * 60 * 60); // 3 hours
$currentTime = time();

if (isset($_SESSION['last_activity']) && ($currentTime - $_SESSION['last_activity']) > SESSION_IDLE_TIMEOUT) {
    $flash = [
        'type' => 'warning',
        'message' => 'Your session has expired due to inactivity. Please log in again.'
    ];

    session_unset();
    session_destroy();
    session_start();
    $_SESSION['flash'] = $flash;

    header("Location: /admin/login.php");
    exit;
}

$_SESSION['last_activity'] = $currentTime;

$userId = $_SESSION['user_id'];

try {
    // Fetch user info including verification status
    $stmt = $pdo->prepare("SELECT id, full_name, email, is_verified FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$userId]);
    $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$currentUser) {
        session_unset();
        session_destroy();
        header("Location: /admin/login.php");
        exit;
    }

    // === Redirect if user is not verified ===
    if ((int) $currentUser['is_verified'] !== 1) {
        session_unset();
        session_destroy();
        header('Location: /admin/verify-code.php?email=' . urlencode($currentUser['email']));
        exit;
    }

    
    $stmt = $pdo->query("SELECT * FROM tokens ORDER BY created_at DESC");
    $tokens = $stmt->fetchAll();

} catch (Exception $e) {
    logError($e);
    session_unset();
    session_destroy();
    header("Location: /admin/login.php");
    exit;
}