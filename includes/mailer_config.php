<?php
// includes/mailer_config.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Returns a configured PHPMailer instance for Gmail SMTP
 *
 * @return PHPMailer
 */
 
 $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();


function getMailer(): PHPMailer {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = $_ENV["EMAIL_HOST"];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV["EMAIL"];         // Replace with your Gmail
        $mail->Password   = $_ENV["EMAIL_PASS"];            // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Optional debugging (for development)
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'error_log';

        // Sender info
        $mail->setFrom($_ENV["EMAIL"], 'BEEWHY ENTERPRISE');

        // Content settings
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

    } catch (Exception $e) {
        error_log("Mailer config error: {$mail->ErrorInfo}", 3, __DIR__ . '/../logs/errors.log');
    }

    return $mail;
}