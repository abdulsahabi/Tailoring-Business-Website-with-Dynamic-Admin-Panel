<?php 
  require_once __DIR__ . '/../includes/auth.php';
?>
<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
trackPageView(); // Auto-detects route
//logView(); // Auto-detects route and device type

// Count site views
$siteViews = $pdo->query("SELECT SUM(count) FROM page_views")->fetchColumn() ?? 0;

// Count gallery items
$galleryCount = $pdo->query("SELECT COUNT(*) FROM images")->fetchColumn() ?? 0;

// Count tokens
$tokenCount = $pdo->query("SELECT COUNT(*) FROM tokens")->fetchColumn() ?? 0;

// Count users
$userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() ?? 0;

$peakStats = getPeakTrafficStats($pdo);


?>



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
      transition: transform 0.2s ease-in-out;
      z-index: 40;
      transform: translateX(-100%);
    }

    #adminSidebar.open {
      transform: translateX(0);
    }

    #mainContent {
      transition: all 0.2s ease-in-out;
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
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
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
       <p class="text-lg font-bold text-gray-800"><?= $peakStats['time_range'] ?></p>
      <p class="text-xs text-gray-400 mt-1">Average <?= $peakStats['average_visitors'] ?> visitors</p>
      </div>
    </div>
  </section>

  <!-- Charts -->
  <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-xl shadow-sm">
      <h3 class="text-base font-medium text-gray-800 mb-4">Image Uploads (Last 7 days)</h3>
      <div class="relative h-[200px]">
  <canvas id="uploadChart" class="absolute inset-0 w-full h-full"></canvas>
</div>
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
     <ul id="activityList" class="divide-y divide-gray-100 text-sm"></ul>
    </div>
  </section>

</main>


  <!-- Scripts -->
  <script src="https://unpkg.com/lucide@latest" defer></script>
  <!-- Chart Scripts (place at bottom of body) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
  window.addEventListener("DOMContentLoaded", () => {
    lucide.createIcons();

    const sidebar = document.getElementById('adminSidebar');
    const toggleBtn = document.getElementById('toggleSidebar');
    const closeBtn = document.getElementById('closeSidebarBtn');
    const mainContent = document.getElementById('mainContent');
    const backdrop = document.getElementById('sidebarBackdrop');

    const isMobile = () => window.innerWidth < 768;

    const openSidebar = () => {
      sidebar.classList.add('open');
      mainContent.classList.add('shifted', 'blurred');
      backdrop.classList.remove('hidden');
      if (isMobile()) document.body.classList.add('sidebar-open');
    };

    const closeSidebar = () => {
      sidebar.classList.remove('open');
      mainContent.classList.remove('shifted', 'blurred');
      backdrop.classList.add('hidden');
      document.body.classList.remove('sidebar-open');
    };

    // Toggle button
    toggleBtn?.addEventListener('click', () => {
      sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });

    closeBtn?.addEventListener('click', closeSidebar);
    backdrop?.addEventListener('click', closeSidebar);

    const isDesktop = window.innerWidth >= 768;

    // Default state on load
    if (isDesktop) {
      sidebar.classList.add('open');
      mainContent.classList.add('shifted');
      backdrop.classList.add('hidden');
      document.body.classList.remove('sidebar-open');
    } else {
      closeSidebar();
    }

    // Update on screen resize
    window.addEventListener('resize', () => {
      const nowDesktop = window.innerWidth >= 768;
      if (nowDesktop) {
        sidebar.classList.add('open');
        mainContent.classList.add('shifted');
        mainContent.classList.remove('blurred');
        backdrop.classList.add('hidden');
        document.body.classList.remove('sidebar-open');
      } else {
        closeSidebar();
      }
    });
  });
  </script>
  

<script>
async function renderLineChart(canvasId, apiUrl, chartLabel, lineColor = '#1d4ed8') {
  const canvas = document.getElementById(canvasId);
  if (!canvas) return;

  const ctx = canvas.getContext('2d');
  try {
    const res = await fetch(apiUrl);
    const json = await res.json();
    
    

    if (!json.success) throw new Error(json.message);
    
    
  
    const values = Array.isArray(json.data.data) ? json.data.data : [0, 0, 0, 0, 0, 0, 0];
    const labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

    // Destroy old chart if exists
    if (window.uploadChartInstance) window.uploadChartInstance.destroy();

    window.uploadChartInstance = new Chart(ctx, {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: chartLabel,
          data: values,
          borderColor: lineColor,
          borderWidth: 2,
          backgroundColor: 'rgba(29,78,216,0.1)',
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            suggestedMax: 5, // Helps give room above small numbers
            ticks: {
              stepSize: 1
            }
          }
        }
      }
    });

  } catch (err) {
    console.error(err);
    ctx.font = "16px Arial";
    ctx.fillText("Failed to load chart data", 10, 50);
  }
}
// Usage:
renderLineChart('uploadChart', '/api/weeks_upload.php', 'Uploads');

  (async () => {
  const canvas = document.getElementById('visitorChart');
  if (!canvas) return;
  
  const ctx = canvas.getContext('2d');

  try {
    const res = await fetch('/api/visitor-types.php');
    const json = await res.json();

    if (!json.success) {
      throw new Error(json.message || 'Failed to load visitor data');
    }

    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: json.labels,
        datasets: [{
          data: json.data,
          backgroundColor: ['#facc15', '#60a5fa', '#34d399']
        }]
      },
      options: {
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              font: {
                size: 14
              }
            }
          }
        }
      }
    });
  } catch (err) {
    ctx.font = "16px Arial";
    ctx.fillText("Failed to load visitor data", 10, 50);
    console.error(err);
  }
})();
</script>
<script>
  async function loadRecentActivities() {
    const container = document.getElementById('activityList');
    if (!container) return;

    try {
      const res = await fetch('/api/recent-activities.php');
      const json = await res.json();

      if (!json.success) throw new Error(json.message);

      if (json.data.length === 0) {
        container.innerHTML = `<li class="py-3 text-gray-400">No recent activity</li>`;
        return;
      }

      container.innerHTML = json.data.map(act => `
        <li class="py-3 flex justify-between items-center">
          <span>${act.message}</span>
          <span class="text-xs text-gray-400">${act.time_ago}</span>
        </li>
      `).join('');

    } catch (err) {
      console.error('Activity log error:', err);
      container.innerHTML = `<li class="py-3 text-red-400">Failed to load activities</li>`;
    }
  }

  // Call it when DOM is ready
  window.addEventListener('DOMContentLoaded', loadRecentActivities);
</script>
  </div>
</body>
</html>