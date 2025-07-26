<?php
// helpers/response.php



require_once __DIR__ . '/logger.php';

/**
 * Send a JSON success response.
 *
 * @param string $message
 * @param array  $data
 * @param int    $status HTTP status code
 */
function json_success(string $message = "Success", array $data = [], int $status = 200): void {
  http_response_code($status);
  header('Content-Type: application/json');
  echo json_encode([
    "status" => "success",
    "message" => $message,
    "data" => $data
  ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
  exit;
}

/**
 * Send a JSON error response.
 *
 * @param string $message
 * @param int    $status HTTP status code
 * @param array  $details Optional additional error details
 */
function json_error(string $message = "An error occurred", int $status = 400, array $details = []): void {
  http_response_code($status);
  header('Content-Type: application/json');

  // Log the error for debugging
  log_error("API ERROR: $message", $details);

  echo json_encode([
    "status" => "error",
    "message" => $message,
    "details" => !empty($details) ? $details : null
  ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
  exit;
}

/**
 * Send a JSON validation error response.
 *
 * @param array  $errors Associative array of field => error message
 * @param string $message
 * @param int    $status HTTP status code
 */
function json_validation(array $errors, string $message = "Validation failed", int $status = 422): void {
  http_response_code($status);
  header('Content-Type: application/json');

  log_error("VALIDATION ERROR", $errors);

  echo json_encode([
    "status" => "error",
    "message" => $message,
    "errors" => $errors
  ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
  exit;
}