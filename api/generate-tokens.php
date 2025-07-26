<?php
// api/generate-tokens.php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../includes/auth.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: POST');
        sendResponse(false, ['method' => 'Only POST requests are allowed.'], 405);
    }

    // Support JSON or x-www-form-urlencoded
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($contentType, 'application/json') !== false) {
        $input = json_decode(file_get_contents('php://input'), true);
    } else {
        $input = $_POST;
    }

    // Sanitize input
    $qty = isset($input['qty']) ? intval($input['qty']) : 0;

    if ($qty < 1 || $qty > 20) {
        sendResponse(false, ['qty' => 'Please enter a valid number between 1 and 20.'], 422);
    }

    $stmt = $pdo->prepare("INSERT INTO tokens (token, status, created_at, updated_at, is_used) VALUES (?, ?, ?, ?, ?)");

    $now = date('Y-m-d H:i:s');
    $created = 0;
    $tokens = [];

    function generate8DigitToken(): string {
        return str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
    }

    for ($i = 0; $i < $qty; $i++) {
        $token = generate8DigitToken(); // strictly 8-digit number
        $status = 'Unused';
        $is_used = 0;

        $stmt->execute([$token, $status, $now, $now, $is_used]);
        $tokens[] = $token;
        $created++;
    }
    logActivity("Moderator token created");
    sendResponse(true, [
        'message' => "$created token(s) generated successfully.",
        'tokens' => $tokens
    ]);

} catch (Exception $e) {
    error_log($e->getMessage());
    sendResponse(false, ['error' => 'An error occurred while generating tokens.'], 500);
}