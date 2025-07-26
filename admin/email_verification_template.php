<?php
// This file is loaded by send_otp_email() and expects:
// $userName (string), $otpCode (string)
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Email Verification</title>
  <style>
    body {
      font-family: 'Segoe UI', 'Helvetica Neue', sans-serif;
      background-color: #f1f5f9;
      padding: 0;
      margin: 0;
      color: #1f2937;
    }

    .email-wrapper {
      width: 100%;
      padding: 40px 0;
    }

    .email-content {
      max-width: 500px;
      background-color: #ffffff;
      margin: 0 auto;
      padding: 32px 24px;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
      border-top: 4px solid #facc15; /* Tailwind yellow-400 */
    }

    .logo {
      text-align: center;
      margin-bottom: 24px;
    }

    .logo img {
      height: 40px;
    }

    .greeting {
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 12px;
      color: #111827;
    }

    .message {
      font-size: 15px;
      margin-bottom: 24px;
      color: #374151;
    }

    .otp-box {
      font-size: 28px;
      font-weight: 700;
      letter-spacing: 8px;
      background-color: #fef3c7; /* Tailwind yellow-100 */
      color: #92400e; /* Tailwind yellow-900 */
      padding: 14px 0;
      border-radius: 8px;
      text-align: center;
      width: 100%;
      max-width: 280px;
      margin: 0 auto 20px auto;
      font-family: 'Courier New', monospace;
    }

    .expiry-note {
      font-size: 14px;
      color: #6b7280; /* Tailwind gray-500 */
      text-align: center;
    }

    .footer {
      margin-top: 32px;
      font-size: 12px;
      color: #9ca3af; /* Tailwind gray-400 */
      text-align: center;
    }

    @media (max-width: 540px) {
      .email-content {
        margin: 0 20px;
        padding: 24px 16px;
      }
    }
  </style>
</head>
<body>
  <div class="email-wrapper">
    <div class="email-content">
      <div class="logo">
        <img src="https://yourdomain.com/assets/logo-dark.png" alt="AU Idris">
      </div>

      <p class="greeting">Hello <?= htmlspecialchars($userName ?? 'User') ?>,</p>

      <p class="message">
        Welcome to <strong>AU Idris</strong>! To complete your registration, please use the verification code below.
      </p>

      <div class="otp-box"><?= htmlspecialchars($otpCode ?? '123456') ?></div>

      <p class="expiry-note">
        This code is valid for the next <strong>10 minutes</strong>.
      </p>

      <p class="message" style="margin-top: 24px;">
        If you did not request this, please disregard this message.
      </p>

      <div class="footer">
        &copy; <?= date('Y') ?> AU Idris. All rights reserved.
      </div>
    </div>
  </div>
</body>
</html>