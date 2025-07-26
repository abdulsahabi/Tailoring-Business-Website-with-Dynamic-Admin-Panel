<aside id="adminSidebar"
       class="admin-sidebar fixed top-0 left-0 h-full w-[240px] bg-gray-900 text-white p-6 z-40 overflow-y-auto shadow-lg transition-transform duration-300 ease-in-out"
       aria-label="Admin Sidebar">

  <!-- Mobile Close Button -->
  <button id="closeSidebarBtn" aria-label="Close sidebar"
          class="md:hidden absolute top-4 right-4 p-2 rounded hover:bg-gray-800 text-white hover:text-yellow-300 z-50">
    <i data-lucide="x" class="w-6 h-6"></i>
  </button>

  <!-- Logo / Title -->
  <a href="/admin/dashboard.php" class="block text-2xl font-bold mb-8 text-yellow-400 hover:text-yellow-300">
    Admin Panel
  </a>

  <!-- Navigation -->
  <nav aria-label="Main Navigation" class="space-y-6 pb-24">
    
    <!-- Dashboard -->
    <a href="/admin/dashboard.php"
       class="flex items-center gap-3 hover:text-yellow-300 <?= ($activePage === 'dashboard') ? 'text-yellow-300 font-semibold sidebar-active' : '' ?>">
      <i data-lucide="layout-dashboard"></i> <span>Dashboard</span>
    </a>

    <!-- Gallery Section -->
    <div class="space-y-2">
      <p class="text-xs uppercase text-gray-400 tracking-wider">Gallery</p>
      <a href="/admin/gallery/index.php"
         class="flex items-center gap-3 pl-3 hover:text-yellow-300 <?= ($activePage === 'gallery') ? 'text-yellow-300 font-semibold sidebar-active' : '' ?>">
        <i data-lucide="image"></i> <span>Manage Images</span>
      </a>
      <a href="/admin/gallery/create.php"
         class="flex items-center gap-3 pl-3 hover:text-yellow-300 <?= ($activePage === 'add_gallery') ? 'text-yellow-300 font-semibold sidebar-active' : '' ?>">
        <i data-lucide="plus-square"></i> <span>Add New</span>
      </a>
    </div>

    <!-- Settings Section -->
    <div class="space-y-2">
      <p class="text-xs uppercase text-gray-400 tracking-wider">Settings</p>
      <a href="/admin/settings/tokens.php"
         class="flex items-center gap-3 pl-3 hover:text-yellow-300 <?= ($activePage === 'tokens') ? 'text-yellow-300 font-semibold sidebar-active' : '' ?>">
        <i data-lucide="key-round"></i> <span>Tokens</span>
      </a>
      <a href="/admin/settings/users.php"
         class="flex items-center gap-3 pl-3 hover:text-yellow-300 <?= ($activePage === 'users') ? 'text-yellow-300 font-semibold sidebar-active' : '' ?>">
        <i data-lucide="users"></i> <span>System Users</span>
      </a>
      <a href="/admin/settings/profile.php"
         class="flex items-center gap-3 pl-3 hover:text-yellow-300 <?= ($activePage === 'profile') ? 'text-yellow-300 font-semibold sidebar-active' : '' ?>">
        <i data-lucide="user"></i> <span>Profile</span>
      </a>
    </div>
  </nav>

  <!-- Logout (Sticky on small screens) -->
  <div class="absolute bottom-6 left-6 right-6 md:static md:mt-8">
    <a href="/api/logout.php"
       class="flex items-center gap-3 text-red-400 hover:text-red-600">
      <i data-lucide="log-out"></i> <span>Logout</span>
    </a>
  </div>
</aside>