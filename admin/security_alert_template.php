<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Security Alert</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #f9fafb; /* Tailwind: bg-gray-50 */
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      font-size: 14px;
      color: #1f2937; /* Tailwind: text-gray-800 */
    }

    .container {
      max-width: 600px;
      margin: 40px auto;
      background-color: #ffffff; /* Tailwind: bg-white */
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.06); /* Tailwind-like shadow */
      overflow: hidden;
    }

    .header {
      background-color: #facc15; /* Tailwind: bg-yellow-400 */
      padding: 20px;
      text-align: center;
    }

    .header h2 {
      margin: 0;
      font-size: 20px;
      font-weight: 600;
      color: #1f2937;
    }

    .body {
      padding: 30px;
    }

    .body p {
      margin-bottom: 16px;
      line-height: 1.6;
    }

    .body ul {
      margin-top: 10px;
      padding-left: 18px;
    }

    .body li {
      margin-bottom: 6px;
    }

    .highlight {
      margin-top: 24px;
      padding: 14px;
      background-color: #fef9c3; /* Tailwind: bg-yellow-100 */
      border-left: 4px solid #facc15; /* Tailwind: border-yellow-400 */
      color: #92400e; /* Tailwind: text-yellow-800 */
    }

    .footer {
      background-color: #f3f4f6; /* Tailwind: bg-gray-100 */
      padding: 20px;
      font-size: 12px;
      text-align: center;
      color: #6b7280; /* Tailwind: text-gray-500 */
    }

    .warning {
      color: #b91c1c; /* Tailwind: text-red-700 */
      font-weight: 600;
    }

    .bold {
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>Security Alert</h2>
    </div>
    <div class="body">
      <p>Dear <span class="bold">A U Idris</span>,</p>

      <p class="warning">⚠️ We detected multiple failed login attempts to the admin system.</p>

      <ul>
        <li><strong>Attempted Email:</strong> <?= htmlspecialchars($attemptedEmail ?? 'N/A') ?></li>
        <li><strong>IP Address:</strong> <?= htmlspecialchars($ipAddress ?? 'Unknown') ?></li>
        <li><strong>Time:</strong> <?= htmlspecialchars($attemptTime ?? date('Y-m-d H:i:s')) ?></li>
        <li><strong>Location:</strong> <?= htmlspecialchars($location ?? 'Unknown') ?></li>
      </ul>

      <div class="highlight">
        If this wasn’t authorized or looks suspicious, please investigate the account and consider resetting the password or blocking the IP.
      </div>

      <p>If this alert was expected, no action is required.</p>

      <p>Stay safe,<br><strong>AU Beewhy Security Team</strong></p>
    </div>
    <div class="footer">
      &copy; <?= date('Y') ?> AU Beewhy. This is an automated alert — please do not reply.
    </div>
  </div>
</body>
</html>