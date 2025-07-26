<?php
function log_error(string $message, array $context = []): void {
  $logDir = __DIR__ . '/../logs';
  $logFile = $logDir . '/error.log';

  if (!is_dir($logDir)) {
    mkdir($logDir, 0775, true);
  }

  $timestamp = date("Y-m-d H:i:s");
  $contextStr = !empty($context) ? json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : '';
  $logMessage = "[$timestamp] $message " . ($contextStr ? "- Context: $contextStr" : '') . PHP_EOL;

  error_log($logMessage, 3, $logFile);
}