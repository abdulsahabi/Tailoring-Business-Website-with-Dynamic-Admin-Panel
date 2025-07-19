<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin | AU Idris Fashion</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <!-- Custom Fonts and Theme -->
  <link rel="stylesheet" href="/assets/css/custom.css" />

  <!-- Admin-only Styles (if any overrides) -->
  <link rel="stylesheet" href="../../assets/css/admin.css" />

  <style>
    html {
      font-size: 14px;
    }

    body {
      font-family: 'PoppinsRegular', sans-serif;
      font-size: 15px;
      background-color: var(--background-color);
      color: var(--text-color);
    }

    h1, h2, h3 {
      font-family: 'PoppinsSemiBold', sans-serif;
    }

    .admin-layout {
      display: grid;
      grid-template-columns: 260px 1fr;
      min-height: 100vh;
    }

    .sidebar-link.active {
      background-color: rgba(250, 204, 21, 0.15);
      border-left: 4px solid var(--primary-yellow);
      padding-left: 1rem;
      font-family: 'PoppinsMedium';
    }

    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }

    .scrollbar-hide {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>
</head>