<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../includes/auth.php';

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, ['method' => 'Only POST allowed'], 405);
  }

  $input = json_decode(file_get_contents('php://input'), true);
  $id = isset($input['id']) ? intval($input['id']) : 0;

  

  $stmt = $pdo->prepare("DELETE FROM tokens WHERE id = ?");
  $stmt->execute([$id]);
  logActivity("Moderator token deleted");
  sendResponse(true, ['message' => 'Token deleted successfully.']);
} catch (Exception $e) {
  error_log($e->getMessage());
  sendResponse(false, ['error' => 'Server error.'], 500);
}