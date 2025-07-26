<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Set response headers
header('Content-Type: application/json; charset=utf-8');

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed. Use POST.'
    ]);
    exit;
}

// Validate content type
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($contentType, 'application/json') === false) {
    http_response_code(415); // Unsupported Media Type
    echo json_encode([
        'success' => false,
        'message' => 'Content-Type must be application/json.'
    ]);
    exit;
}

// Parse and sanitize JSON input
$input = json_decode(file_get_contents("php://input"), true);

$imageId = isset($input['id']) ? (int)$input['id'] : 0;
$imageName = isset($input['image']) ? basename(trim($input['image'])) : '';

if ($imageId <= 0 || empty($imageName)) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'success' => false,
        'message' => 'Invalid image ID or filename.'
    ]);
    exit;
}

// Fetch image from DB
$stmt = $pdo->prepare("SELECT * FROM images WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $imageId]);
$image = $stmt->fetch();

if (!$image) {
    http_response_code(404); // Not Found
    echo json_encode([
        'success' => false,
        'message' => 'Image not found.'
    ]);
    exit;
}

// Prevent mismatch or manipulation
if ($image['image_name'] !== $imageName) {
    http_response_code(403); // Forbidden
    echo json_encode([
        'success' => false,
        'message' => 'Image data does not match the record.'
    ]);
    exit;
}

// Absolute path to the image
$imagePath = realpath(__DIR__ . '/../uploads/gallery/' . $imageName);

// Ensure file is within allowed folder (avoid deleting anything else)
$uploadDir = realpath(__DIR__ . '/../uploads/gallery');
if ($imagePath === false || strpos($imagePath, $uploadDir) !== 0) {
    http_response_code(403); // Forbidden
    echo json_encode([
        'success' => false,
        'message' => 'Invalid file path.'
    ]);
    exit;
}

// Attempt to delete file
if (file_exists($imagePath)) {
    if (!unlink($imagePath)) {
        http_response_code(500); // Internal Server Error
        echo json_encode([
            'success' => false,
            'message' => 'Failed to delete image file.'
        ]);
        exit;
    }
}

// Delete DB record
$stmt = $pdo->prepare("DELETE FROM images WHERE id = :id");
$success = $stmt->execute(['id' => $imageId]);

if ($success) {
    logActivity("Gallery image deleted");
    echo json_encode([
        'success' => true,
        'message' => 'Image deleted successfully.'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to delete database record.'
    ]);
}