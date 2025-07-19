<?php // admin-dashboard.php ?>
<?php $activePage = 'gallery'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("../components/head.php") ?>
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
  <?php include("../components/sidebar.php") ?>

  <!-- Backdrop -->
  <div id="sidebarBackdrop" class="fixed inset-0 bg-black bg-opacity-30 z-30 hidden"></div>

  <!-- Main Content -->
  <div id="mainContent" class="flex-1">
  
    <!-- Header -->
   <header class="bg-white p-4 flex justify-between items-center shadow-sm fixed top-0 z-50 w-full">
  <button id="toggleSidebar" class="md:hidden text-gray-800 p-2 rounded focus:outline-none z-50">
    <i data-lucide="menu" class="w-6 h-6"></i>
  </button>
  <h1 class="text-xl font-semibold">Manage Gallery</h1>
</header>

<main class="p-6 space-y-8 mt-16">
  <!-- 🔘 Heading + Add New Button -->
  <div class="flex justify-between items-center flex-wrap gap-4">
    <div>
      <h2 class="text-2xl font-semibold text-gray-800">Manage Gallery</h2>
      <p class="text-sm text-gray-500">All uploaded images in tabular form.</p>
    </div>
    <a href="/admin/gallery/create.php"
       class="inline-flex items-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded-md shadow-sm text-sm">
      <i data-lucide="plus" class="w-4 h-4"></i> Add New
    </a>
  </div>

  <!-- 🔍 Search Form -->
  <div class="sticky top-0 z-20 bg-gray-100 py-2 flex justify-between items-center gap-4">
    <div class="relative w-full max-w-sm">
      <input id="searchInput" type="text" placeholder="Search by title..."
             class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-400" />
      <button id="clearSearch" class="absolute right-2 top-2.5 text-gray-400 hover:text-red-500 hidden">
        <i data-lucide="x-circle" class="w-5 h-5"></i>
      </button>
    </div>
  </div>

  <!-- 📸 Table -->
  <div class="overflow-x-auto bg-white rounded-xl shadow-sm">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
        <tr>
          <th class="px-4 py-3">#</th>
          <th class="px-4 py-3">Preview</th>
          <th class="px-4 py-3">Title</th>
          <th class="px-4 py-3 hidden md:table-cell">Date Uploaded</th>
          <th class="px-4 py-3">Actions</th>
        </tr>
      </thead>
      <tbody id="galleryTableBody" class="bg-white divide-y divide-gray-100 text-sm">
        <!-- JS will populate rows here -->
      </tbody>
    </table>
  </div>

  <!-- 📄 Pagination -->
  <div id="pagination" class="mt-6 flex justify-center items-center space-x-4 text-sm">
    <!-- JS handles pagination buttons -->
  </div>
</main>

<!-- ✅ JavaScript -->
<script>
  const API_URL = '../../api/gallery-api.php'; // You’ll replace this with your real endpoint

  const searchInput = document.getElementById('searchInput');
  const clearBtn = document.getElementById('clearSearch');
  const tbody = document.getElementById('galleryTableBody');
  const pagination = document.getElementById('pagination');

  let currentPage = 1;
  let totalItems = 0;
  let perPage = 5;
  let searchQuery = '';

  const showSkeleton = () => {
    tbody.innerHTML = '';
    for (let i = 0; i < 5; i++) {
      tbody.innerHTML += `
        <tr class="animate-pulse">
          <td class="px-4 py-3"><div class="h-4 bg-gray-200 rounded w-6"></div></td>
          <td class="px-4 py-3"><div class="h-14 w-14 bg-gray-200 rounded-md"></div></td>
          <td class="px-4 py-3"><div class="h-4 bg-gray-200 rounded w-32"></div></td>
          <td class="px-4 py-3 hidden md:table-cell"><div class="h-4 bg-gray-200 rounded w-20"></div></td>
          <td class="px-4 py-3"><div class="h-4 bg-gray-200 rounded w-16"></div></td>
        </tr>`;
    }
  };

  const fetchGallery = async () => {
    showSkeleton();
    try {
      const response = await fetch(`${API_URL}?page=${currentPage}&search=${encodeURIComponent(searchQuery)}`);
      const { items, total } = await response.json();
      totalItems = total;
      renderTable(items);
      renderPagination();
    } catch (err) {
      tbody.innerHTML = `<tr><td colspan="5" class="text-center text-red-500 py-6">Failed to load data.</td></tr>`;
    }
  };

  const renderTable = (items) => {
    if (!items.length) {
      tbody.innerHTML = `<tr><td colspan="5" class="text-center py-6 text-gray-500">No images found.</td></tr>`;
      return;
    }
    tbody.innerHTML = '';
    items.forEach((item, i) => {
      const index = (currentPage - 1) * perPage + i + 1;
      tbody.innerHTML += `
        <tr>
          <td class="px-4 py-3">${index}</td>
          <td class="px-4 py-3">
            <img src="${item.src}" alt="${item.title}" class="w-14 h-14 rounded-md object-cover border">
          </td>
          <td class="px-4 py-3 font-medium text-gray-800">${item.title}</td>
          <td class="px-4 py-3 hidden md:table-cell text-gray-500">${item.date}</td>
          <td class="px-4 py-3">
            <div class="flex gap-3 text-sm">
              <a href="#" class="text-blue-500 hover:underline">Edit</a>
              <a href="#" class="text-red-500 hover:underline">Delete</a>
            </div>
          </td>
        </tr>`;
    });
  };

  const renderPagination = () => {
    const totalPages = Math.ceil(totalItems / perPage);
    pagination.innerHTML = '';

    if (totalPages <= 1) return;

    if (currentPage > 1) {
      const prev = document.createElement('button');
      prev.textContent = 'Previous';
      prev.className = 'px-3 py-2 bg-gray-200 rounded hover:bg-gray-300';
      prev.onclick = () => {
        currentPage--;
        fetchGallery();
      };
      pagination.appendChild(prev);
    }

    const status = document.createElement('span');
    status.textContent = `Page ${currentPage} of ${totalPages}`;
    pagination.appendChild(status);

    if (currentPage < totalPages) {
      const next = document.createElement('button');
      next.textContent = 'Next';
      next.className = 'px-3 py-2 bg-gray-200 rounded hover:bg-gray-300';
      next.onclick = () => {
        currentPage++;
        fetchGallery();
      };
      pagination.appendChild(next);
    }
  };

  // 🔍 Search + Debounce
  let debounceTimer;
  searchInput.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
      searchQuery = searchInput.value.trim();
      currentPage = 1;
      fetchGallery();
      clearBtn.classList.toggle('hidden', !searchQuery);
    }, 300);
  });

  clearBtn.addEventListener('click', () => {
    searchInput.value = '';
    searchQuery = '';
    currentPage = 1;
    fetchGallery();
    clearBtn.classList.add('hidden');
  });

  // 🟡 Init
  fetchGallery();
</script>

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

  </div>
</body>
</html>