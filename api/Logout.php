<?php
session_start();

// Optional: Determine the reason for logout
$reason = $_GET['reason'] ?? 'manual'; // e.g., ?reason=timeout or ?reason=force

// Prepare flash message based on reason
switch ($reason) {
    case 'timeout':
        $flash = ['type' => 'warning', 'message' => 'You have been logged out due to inactivity.'];
        break;
    case 'force':
        $flash = ['type' => 'error', 'message' => 'You were forcibly logged out for security reasons.'];
        break;
    default:
        $flash = ['type' => 'success', 'message' => 'You have been logged out successfully.'];
        break;
}

// Clear and restart session
session_unset();
session_destroy();
session_start();

$_SESSION['flash'] = $flash;

// Redirect to login page
header("Location: /admin/login.php");
exit;