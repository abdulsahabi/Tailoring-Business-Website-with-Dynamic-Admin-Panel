<?php
// includes/response.php

/**
 * Sends a standardized JSON response and halts script execution.
 *
 * @param bool  $success Whether the request was successful.
 * @param array $data    Data to send back (can be errors or payload).
 * @param int   $status  HTTP status code.
 */
function sendResponse(bool $success, array $data = [], int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        $success ? 'data' : 'errors' => $data
    ]);
    exit;
}



/**
 * Logs error messages to a secure error log file.
 *
 * @param Exception|string $error The error or exception to log.
 */
function logError($error): void {
    $message = is_object($error) && method_exists($error, 'getMessage')
        ? $error->getMessage()
        : (string) $error;

    $logMessage = "[" . date('Y-m-d H:i:s') . "] ERROR: $message\n";
    $logFile = __DIR__ . '/../logs/errors.log';

    // Ensure the logs directory exists
    if (!file_exists(dirname($logFile))) {
        mkdir(dirname($logFile), 0755, true);
    }

    error_log($logMessage, 3, $logFile);
}