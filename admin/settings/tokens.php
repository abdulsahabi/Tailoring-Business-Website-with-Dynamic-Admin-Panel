<?php // admin-dashboard.php ?>
<?php $activePage = 'tokens'; ?>
<!DOCTYPE html><html lang="en">
<head>
  <?php include("../components/head.php") ?>
  <title>Admin Dashboard – AU Idris</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <style>
    :root {
      --sidebar-width: 240px;
    }html, body {
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

body.modal-open {
  overflow: hidden;
  height: 100vh;
}

  </style>
</head><body class="bg-gray-100 text-gray-800 flex min-h-screen relative">  <!-- Sidebar -->  <?php include("../components/sidebar.php") ?>  <!-- Backdrop -->  <div id="sidebarBackdrop" class="fixed inset-0 bg-black bg-opacity-30 z-30 hidden"></div>  <!-- Main Content -->  <div id="mainContent" class="flex-1"><!-- Header -->
<header class="bg-white p-4 flex justify-between items-center shadow-sm fixed top-0 z-50 w-full">
  <button id="toggleSidebar" class="md:hidden text-gray-800 p-2 rounded focus:outline-none z-50">
    <i data-lucide="menu" class="w-6 h-6"></i>
  </button>
  <h1 class="text-xl font-semibold">Manage tokens</h1>
</header>
 <!-- Main -->
  <main class="p-6 mt-16 max-w-5xl mx-auto space-y-6">
    
    <!-- Section Header -->
    <div class="flex items-center justify-between">
      <h2 class="text-2xl font-semibold">Signup Tokens</h2>
      <form class="flex items-center gap-2">
        <input type="number" min="1" class="border border-gray-300 px-3 py-2 rounded-md text-sm w-32 focus:ring-yellow-400 focus:ring-2 outline-none" placeholder="Qty">
        <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black font-medium px-4 py-2 rounded-md text-sm transition">
          + Generate
        </button>
      </form>
    </div>

    <!-- Tokens Table -->
   <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-200">
  <table class="min-w-full text-sm text-left">
    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
      <tr>
        <th class="px-4 py-3">Token</th>
        <th class="px-4 py-3">Status</th>
        <th class="px-4 py-3">Created At</th>
      
        <th class="px-4 py-3 text-right">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 text-gray-800">
      <?php for ($i = 1; $i <= 5; $i++): ?>
      <tr>
        <td class="px-4 py-3 font-mono text-xs">TOKN<?= 1000 + $i ?>XYZ</td>
        <td class="px-4 py-3">
          <span class="inline-block text-xs font-semibold px-2 py-1 rounded-md 
          <?= $i % 2 === 0 ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
            <?= $i % 2 === 0 ? 'Used' : 'Unused' ?>
          </span>
        </td>
        <td class="px-4 py-3 text-xs">2025-07-<?= 15 + $i ?></td>
      
        <td class="px-4 py-3 text-right">
          <a href="#" class="text-red-600 hover:underline text-xs">Delete</a>
        </td>
      </tr>
      <?php endfor; ?>
    </tbody>
  </table>
</div>

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
  </div>
</body>
</html>