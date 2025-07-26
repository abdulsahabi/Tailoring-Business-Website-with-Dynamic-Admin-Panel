<?php
// api/gallery-create.php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json');

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $title   = sanitize($_POST['title'] ?? '');
    $caption = sanitize($_POST['caption'] ?? '');
    $tag     = sanitize($_POST['tag'] ?? '');
    $file    = $_FILES['image'] ?? null;

    // === Basic Validation ===
    if (empty($title) || empty($caption) || empty($tag) || !$file) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    // === File Upload Handling ===
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Only JPG, PNG, or WEBP files allowed.']);
        exit;
    }

    if ($file['size'] > 2 * 1024 * 1024) { // 2MB limit
        echo json_encode(['success' => false, 'message' => 'File is too large. Max 2MB allowed.']);
        exit;
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $uniqueName = 'gallery_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
    $uploadDir = __DIR__ . '/../uploads/gallery/';
    $uploadPath = $uploadDir . $uniqueName;

    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        echo json_encode(['success' => false, 'message' => 'Failed to upload image.']);
        exit;
    }

    // === Save to DB ===
    $stmt = $pdo->prepare("INSERT INTO images (image_name, alt, caption, tag, created_at)
                           VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$uniqueName, $title, $caption, $tag]);
    
    logActivity("New image uploaded to gallery");

    echo json_encode(['success' => true, 'message' => 'Image uploaded successfully.']);

} catch (Exception $e) {
    logError($e);
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again.']);
}
