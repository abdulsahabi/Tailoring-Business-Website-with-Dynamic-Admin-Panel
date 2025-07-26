<?php
// delete-user.php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json');

try {
    // Only allow POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendResponse(false, ['method' => 'Only POST requests are allowed.'], 405);
    }

    // Get JSON payload
    $data = json_decode(file_get_contents("php://input"), true);
    $id = isset($data['id']) ? (int)$data['id'] : 0;

    if ($id <= 0) {
        sendResponse(false, ['id' => 'Invalid or missing user ID.'], 422);
    }

    // Optional: Protect certain roles like Super Admin from being deleted
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        sendResponse(false, ['user' => 'User not found.'], 404);
    }

    if ($user['role'] === 'Admin') {
        sendResponse(false, ['user' => 'Admin users cannot be deleted.'], 403);
    }

    // Proceed to delete
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    sendResponse(true, ['message' => 'User deleted successfully.']);

} catch (Exception $e) {
    sendResponse(false, ['error' => 'Server error. Try again later.', 'details' => $e->getMessage()], 500);
}