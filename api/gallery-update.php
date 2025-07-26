<?php
// api/gallery-update.php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $id      = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $title   = sanitize($_POST['title'] ?? '');
    $caption = sanitize($_POST['caption'] ?? '');
    $tag     = sanitize($_POST['tag'] ?? '');
    $file    = $_FILES['image'] ?? null;

    if (!$id || empty($title) || empty($tag)) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
        exit;
    }

    // === Get previous image from DB ===
    $stmt = $pdo->prepare("SELECT image_name FROM images WHERE id = ?");
    $stmt->execute([$id]);
    $imageData = $stmt->fetch();

    if (!$imageData) {
        echo json_encode(['success' => false, 'message' => 'Image not found.']);
        exit;
    }

    $oldImageName = $imageData['image_name'];
    $newImageName = $oldImageName;

    // === Handle new image upload ===
    if ($file && $file['tmp_name']) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($file['type'], $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => 'Only JPG, PNG, or WEBP allowed.']);
            exit;
        }

        if ($file['size'] > 2 * 1024 * 1024) {
            echo json_encode(['success' => false, 'message' => 'File too large. Max 2MB.']);
            exit;
        }

        // Generate new file name
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newImageName = 'gallery_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        $uploadDir = __DIR__ . '/../uploads/gallery/';
        $uploadPath = $uploadDir . $newImageName;

        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image.']);
            exit;
        }

        // Delete old image
        $oldPath = $uploadDir . $oldImageName;
        if (file_exists($oldPath)) {
            unlink($oldPath);
        }
    }

    // === Update the record ===
    $stmt = $pdo->prepare("UPDATE images SET image_name = ?, alt = ?, caption = ?, tag = ? WHERE id = ?");
    $stmt->execute([$newImageName, $title, $caption, $tag, $id]);
    
    logActivity("Gallery image updated");

    echo json_encode(['success' => true, 'message' => 'Image updated successfully.']);

} catch (Exception $e) {
    logError($e);
    echo json_encode(['success' => false, 'message' => 'Server error.']);
}