<!-- templates/otp-email-template.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>One-Time Password (OTP)</title>
</head>
<body style="background-color: #f3f4f6; font-family: 'Segoe UI', sans-serif; margin: 0; padding: 40px 0; color: #1f2937;">
  <div style="max-width: 540px; margin: 0 auto; background-color: #ffffff; padding: 32px 24px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); border-top: 4px solid #facc15;">

    <!-- Logo -->
    <div style="text-align: center; margin-bottom: 24px;">
      <img src="https://yourdomain.com/assets/logo-dark.png" alt="AU Idris Logo" style="height: 42px;">
    </div>

    <!-- Greeting -->
    <p style="font-size: 18px; font-weight: 600; margin-bottom: 12px; color: #111827;">
      Hello <?= htmlspecialchars($userName ?? 'User') ?>,
    </p>

    <!-- Message -->
    <p style="font-size: 15px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
      Use the OTP below to complete your recent action securely.
    </p>

    <!-- OTP Code -->
    <div style="font-size: 28px; font-weight: bold; letter-spacing: 8px; background-color: #fef3c7; color: #92400e; padding: 14px 0; border-radius: 10px; text-align: center; font-family: 'Courier New', monospace; max-width: 280px; margin: 0 auto 20px;">
      <?= htmlspecialchars($otpCode ?? '123456') ?>
    </div>

    <!-- Expiry Note -->
    <p style="font-size: 14px; text-align: center; color: #6b7280;">
      This code will expire in <strong>10 minutes</strong>.
    </p>

    <!-- Disclaimer -->
    <p style="font-size: 14px; margin-top: 24px; color: #4b5563; text-align: center;">
      If you didn’t request this OTP, you can safely ignore this message.
    </p>

    <!-- Footer -->
    <div style="margin-top: 36px; font-size: 12px; color: #9ca3af; text-align: center;">
      &copy; <?= date('Y') ?> AU Idris. All rights reserved.
    </div>
  </div>
</body>
</html>