<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
trackPageView(); // Auto-detects route
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>405 – Method Not Allowed</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      text-align: center;
      padding: 80px;
      background-color: #f3f4f6;
      color: #1f2937;
    }
    h1 {
      font-size: 48px;
      margin-bottom: 16px;
      color: #dc2626;
    }
    p {
      font-size: 18px;
      color: #374151;
    }
  </style>
</head>
<body>
  <h1>405 – Method Not Allowed</h1>
  <p>This endpoint only accepts POST requests. Please do not access it directly.</p>
</body>
</html>