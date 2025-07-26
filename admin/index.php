<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
trackPageView(); // Auto-detects route// Auto-detects route and device type
 header('location: /admin/dashboard.php');
?>