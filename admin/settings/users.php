<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';

$activePage = 'users';

$stmt = $pdo->query("SELECT id, full_name, email, role, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?><!DOCTYPE html><html lang="en">
<head>
  <?php include("../components/head.php"); ?>
  <title>System Users – Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <style>
    :root { --sidebar-width: 240px; }
    html, body { height: 100%; width: 100%; overflow-x: hidden; touch-action: manipulation; }
    body { font-family: 'Poppins', sans-serif; transition: padding-left 0.3s ease; }
    body.sidebar-open { position: fixed; overflow: hidden; width: 100%; height: 100%; }
    #adminSidebar { width: var(--sidebar-width); transition: transform 0.2s ease-in-out; z-index: 40; transform: translateX(-100%); }
    #adminSidebar.open { transform: translateX(0); }
    #mainContent { transition: all 0.2s ease-in-out; }
    #mainContent.shifted { margin-left: var(--sidebar-width); }
    #mainContent.blurred { filter: blur(3px); pointer-events: none; user-select: none; }
    @media (min-width: 768px) {
      #adminSidebar { transform: translateX(0) !important; }
      #mainContent { margin-left: var(--sidebar-width); }
      #sidebarBackdrop { display: none !important; }
    }
    body.modal-open { overflow: hidden; height: 100vh; }
    
    .fade-in { animation: fadeIn 0.4s ease forwards; opacity: 0; }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(4px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fade-in {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}

.animate-fade-in {
  animation: fade-in 0.2s ease-out;
}
  </style>
</head>
<body class="bg-gray-100 text-gray-800 flex min-h-screen relative">
  <?php include("../components/sidebar.php"); ?>
  <div id="sidebarBackdrop" class="fixed inset-0 bg-black bg-opacity-30 z-30 hidden"></div>  <div id="mainContent" class="flex-1">
    <header class="bg-white p-4 flex justify-between items-center shadow-sm fixed top-0 z-50 w-full">
      <button id="toggleSidebar" class="md:hidden text-gray-800 p-2 rounded focus:outline-none z-50">
        <i data-lucide="menu" class="w-6 h-6"></i>
      </button>
      <h1 class="text-xl font-semibold">System Users</h1>
    </header><main class="p-6 mt-16 max-w-6xl mx-auto space-y-6">
  <div class="flex justify-between items-center">
    <h2 class="text-2xl font-semibold">System Users</h2>
   
  </div>

  <!-- Users Table -->
<div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-200">
  <table class="min-w-full text-sm text-left">
    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
      <tr>
        <th class="px-4 py-3">Full Name</th>
        <th class="px-4 py-3 hidden sm:table-cell">Email</th>
        <th class="px-4 py-3">Role</th>
        <th class="px-4 py-3 hidden md:table-cell">Joined</th>
        <th class="px-4 py-3 text-right">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 text-gray-800">
      <?php foreach ($users as $user): ?>
        <tr class="hover:bg-gray-50 transition-all duration-150 ease-in-out">
          <td class="px-4 py-3"><?= htmlspecialchars($user['full_name']) ?></td>
          <td class="px-4 py-3 hidden sm:table-cell"><?= htmlspecialchars($user['email']) ?></td>
          <td class="px-4 py-3">
            <span class="px-2 py-1 rounded-md bg-green-100 text-green-700 text-xs font-semibold">
              <?= htmlspecialchars($user['role']) ?>
            </span>
          </td>
          <td class="px-4 py-3 hidden md:table-cell">
            <div class="flex justify-end gap-2">
            <?= date('Y-m-d', strtotime($user['created_at'])) ?>
          </td>
          <td class="px-4 py-3 text-right">
             <a href="#" 
   class="text-red-600 hover:underline text-xs delete-user-btn" 
   data-id="<?= $user['id'] ?>" 
   data-name="<?= htmlspecialchars($user['full_name']) ?>">
  Delete
</a>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Delete Modal (Single Copy) -->
<div id="deleteUserModal" class="fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center hidden">
  <div class="bg-white w-full max-w-sm mx-auto rounded-lg shadow-lg p-6 space-y-4 animate-fade-in">
    <h3 class="text-lg font-semibold text-gray-800">Delete User</h3>
    <p class="text-sm text-gray-600">
      Are you sure you want to delete <span id="deleteUserName" class="font-medium text-gray-900"></span>?
    </p>
    <div class="flex justify-end gap-3 pt-4">
      <button id="cancelDeleteUser" class="px-4 py-2 text-sm rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
        Cancel
      </button>
      <button id="confirmDeleteUser" class="px-4 py-2 text-sm rounded-md bg-red-600 text-white hover:bg-red-700">
        Delete
      </button>
    </div>
  </div>
</div>
</main>
</div>

<!-- Scripts -->
<script src="https://unpkg.com/lucide@latest" defer></script>


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
    
      // Delete User Modal Logic
  let deleteUserId = null;
  let deleteRowEl = null;

  const deleteUserModal = document.getElementById("deleteUserModal");
  const deleteUserName = document.getElementById("deleteUserName");
  const confirmDeleteUser = document.getElementById("confirmDeleteUser");
  const cancelDeleteUser = document.getElementById("cancelDeleteUser");

  document.querySelector("tbody").addEventListener("click", (e) => {
    const btn = e.target.closest(".delete-user-btn");
    if (!btn) return;

    deleteUserId = btn.dataset.id;
    deleteRowEl = btn.closest("tr");
    deleteUserName.textContent = btn.dataset.name || "this user";
    deleteUserModal.classList.remove("hidden");
    document.body.classList.add("modal-open");
  });

  cancelDeleteUser.addEventListener("click", () => {
    deleteUserModal.classList.add("hidden");
    document.body.classList.remove("modal-open");
  });

  confirmDeleteUser.addEventListener("click", async () => {
    try {
      const res = await fetch("/api/delete-user.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: deleteUserId })
      });

      const data = await res.json();
      if (data.success) {
        deleteRowEl.remove();
        deleteUserModal.classList.add("hidden");
        document.body.classList.remove("modal-open");
      } else {
        alert(data.errors?.user || data.message || "Failed to delete user.");
      }
    } catch (err) {
      alert("Error: " + err.message);
    }
  });
  });
</script>
</body>
</html>