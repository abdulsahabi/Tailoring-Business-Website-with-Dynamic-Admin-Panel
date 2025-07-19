<?php // admin-dashboard.php ?>
<?php $activePage = 'dashboard'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("./components/head.php") ?>
  <title>Admin Dashboard – AU Idris</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <style>
    :root {
      --sidebar-width: 240px;
    }

    html, body {
      height: 100%;
      width: 100%;
      overflow-x: hidden;
      touch-action: manipulation;
    }

    body {
      font-family: 'Poppins', sans-serif;
      transition: padding-left 0.3s ease;
    }

    body.sidebar-open {
      position: fixed;
      overflow: hidden;
      width: 100%;
      height: 100%;
    }

    #adminSidebar {
      width: var(--sidebar-width);
      transition: transform 0.3s ease-in-out;
      z-index: 40;
      transform: translateX(-100%);
    }

    #adminSidebar.open {
      transform: translateX(0);
    }

    #mainContent {
      transition: all 0.3s ease-in-out;
    }

    #mainContent.shifted {
      margin-left: var(--sidebar-width);
    }

    #mainContent.blurred {
      filter: blur(3px);
      pointer-events: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      #adminSidebar {
        transform: translateX(0) !important;
      }

      #mainContent {
        margin-left: var(--sidebar-width);
      }

      #sidebarBackdrop {
        display: none !important;
      }
    }
  </style>
</head>

<body class="bg-gray-100 text-gray-800 flex min-h-screen relative">

  <!-- Sidebar -->
  <?php include("./components/sidebar.php") ?>

  <!-- Backdrop -->
  <div id="sidebarBackdrop" class="fixed inset-0 bg-black bg-opacity-30 z-30 hidden"></div>

  <!-- Main Content -->
  <div id="mainContent" class="flex-1">

    <!-- Header -->
   <header class="bg-white p-4 flex justify-between items-center shadow-sm fixed top-0 z-50 w-full">
  <button id="toggleSidebar" class="md:hidden text-gray-800 p-2 rounded focus:outline-none z-50">
    <i data-lucide="menu" class="w-6 h-6"></i>
  </button>
  <h1 class="text-xl font-semibold">Admin Dashboard</h1>
</header>

    <!-- Page Body -->
   <main class="p-6 space-y-10">

  <!-- Welcome -->
  <div class="mt-[70px]">
    <h2 class="text-2xl font-semibold text-gray-800">Dashboard</h2>
    <p class="text-sm text-gray-500">Welcome back. Here's the latest insight.</p>
  </div>

  <!-- Summary Cards -->
  <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
      <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
        <i data-lucide="eye" class="w-6 h-6"></i>
      </div>
      <div>
        <h2 class="text-lg font-semibold"><?= $siteViews ?? '1,240' ?></h2>
        <p class="text-sm text-gray-500">Site Views</p>
      </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
      <div class="p-3 rounded-full bg-blue-100 text-blue-500">
        <i data-lucide="image" class="w-6 h-6"></i>
      </div>
      <div>
        <h2 class="text-lg font-semibold"><?= $galleryCount ?? '82' ?></h2>
        <p class="text-sm text-gray-500">Gallery Items</p>
      </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
      <div class="p-3 rounded-full bg-green-100 text-green-500">
        <i data-lucide="key-round" class="w-6 h-6"></i>
      </div>
      <div>
        <h2 class="text-lg font-semibold"><?= $tokenCount ?? '14' ?></h2>
        <p class="text-sm text-gray-500">Moderator Tokens</p>
      </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
      <div class="p-3 rounded-full bg-red-100 text-red-500">
        <i data-lucide="users" class="w-6 h-6"></i>
      </div>
      <div>
        <h2 class="text-lg font-semibold"><?= $userCount ?? '6' ?></h2>
        <p class="text-sm text-gray-500">System Users</p>
      </div>
    </div>
  </section>

  <!-- Insights -->
  <section class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <div class="bg-white p-6 rounded-xl shadow-sm">
      <h3 class="text-sm font-medium text-gray-500 mb-1">Peak Traffic Time</h3>
      <div class="mt-4">
        <p class="text-lg font-bold text-gray-800">8PM – 10PM</p>
        <p class="text-xs text-gray-400 mt-1">Average 340 visitors</p>
      </div>
    </div>
  </section>

  <!-- Charts -->
  <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-xl shadow-sm">
      <h3 class="text-base font-medium text-gray-800 mb-4">Image Uploads (Last 7 days)</h3>
      <canvas id="uploadChart" height="180"></canvas>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm">
      <h3 class="text-base font-medium text-gray-800 mb-4">Visitor Types</h3>
      <canvas id="visitorChart" height="180"></canvas>
    </div>
  </section>

  <!-- Activities -->
  <section>
    <div class="bg-white p-6 rounded-xl shadow-sm mb-[60px]">
      <h3 class="text-lg font-semibold mb-4">Recent Activities</h3>
      <ul class="divide-y divide-gray-100 text-sm">
        <li class="py-3 flex justify-between items-center">
          <span>New image uploaded to Bridal gallery</span>
          <span class="text-xs text-gray-400">3 hours ago</span>
        </li>
        <li class="py-3 flex justify-between items-center">
          <span>Token created for new moderator</span>
          <span class="text-xs text-gray-400">Yesterday</span>
        </li>
        <li class="py-3 flex justify-between items-center">
          <span>User profile updated</span>
          <span class="text-xs text-gray-400">2 days ago</span>
        </li>
      </ul>
    </div>
  </section>

</main>


  <!-- Scripts -->
  <script src="https://unpkg.com/lucide@latest" defer></script>
  <script>
    window.addEventListener("DOMContentLoaded", () => {
      lucide.createIcons();
      
      const toggleBtn = document.getElementById('toggleSidebar');
toggleBtn?.addEventListener('click', () => {
  if (sidebar.classList.contains('open')) {
    closeSidebar();
  } else {
    openSidebar();
  }
});

      const sidebar = document.getElementById('adminSidebar');
      const closeBtn = document.getElementById('closeSidebarBtn');
      const mainContent = document.getElementById('mainContent');
      const backdrop = document.getElementById('sidebarBackdrop');

      const isMobile = () => window.innerWidth < 768;

      const openSidebar = () => {
        sidebar.classList.add('open');
        mainContent.classList.add('shifted', 'blurred');
        backdrop.classList.remove('hidden');
        if (isMobile()) document.body.classList.add('sidebar-open');
        localStorage.setItem('sidebarState', 'open');
      };

      const closeSidebar = () => {
        sidebar.classList.remove('open');
        mainContent.classList.remove('shifted', 'blurred');
        backdrop.classList.add('hidden');
        document.body.classList.remove('sidebar-open');
        localStorage.setItem('sidebarState', 'closed');
      };

      closeBtn?.addEventListener('click', closeSidebar);
      backdrop?.addEventListener('click', closeSidebar);

      const savedState = localStorage.getItem('sidebarState');
      const isDesktop = window.innerWidth >= 768;

      if (isDesktop) {
        sidebar.classList.add('open');
        mainContent.classList.add('shifted');
        backdrop.classList.add('hidden');
        document.body.classList.remove('sidebar-open');
      }

      window.addEventListener('resize', () => {
        const nowDesktop = window.innerWidth >= 768;
        if (nowDesktop) {
          sidebar.classList.add('open');
          mainContent.classList.add('shifted');
          mainContent.classList.remove('blurred');
          backdrop.classList.add('hidden');
          document.body.classList.remove('sidebar-open');
        } else {
          const state = localStorage.getItem('sidebarState');
          state === 'open' ? openSidebar() : closeSidebar();
        }
      });
    });
  </script>
  
<!-- Chart Scripts (place at bottom of body) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx1 = document.getElementById('uploadChart').getContext('2d');
  new Chart(ctx1, {
    type: 'line',
    data: {
      labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
      datasets: [{
        label: 'Uploads',
        data: [3, 7, 4, 5, 9, 6, 4],
        borderColor: '#3b82f6',
        backgroundColor: 'rgba(59,130,246,0.1)',
        tension: 0.3
      }]
    }
  });

  const ctx2 = document.getElementById('visitorChart').getContext('2d');
  new Chart(ctx2, {
    type: 'doughnut',
    data: {
      labels: ['Mobile', 'Desktop', 'Tablet'],
      datasets: [{
        data: [60, 30, 10],
        backgroundColor: ['#facc15', '#60a5fa', '#34d399']
      }]
    }
  });
</script>
  </div>
</body>
</html>