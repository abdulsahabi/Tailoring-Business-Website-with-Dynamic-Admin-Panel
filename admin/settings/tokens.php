<?php 
  require_once __DIR__ . '/../../includes/auth.php';
?>


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

body.modal-open {
  overflow: hidden;
  position: fixed;
  inset: 0;
}

#deleteTokenModal .p-6 {
  transform: scale(0.9);
  transition: transform 0.2s ease;
}
  </style>
</head>
<body class="bg-gray-100 text-gray-800 flex min-h-screen relative">  <!-- Sidebar -->  <?php include("../components/sidebar.php") ?>  <!-- Backdrop -->  <div id="sidebarBackdrop" class="fixed inset-0 bg-black bg-opacity-30 z-30 hidden"></div>  <!-- Main Content -->  <div id="mainContent" class="flex-1"><!-- Header -->
<header class="bg-white p-4 flex justify-between items-center shadow-sm fixed top-0 z-50 w-full">
  <button id="toggleSidebar" class="md:hidden text-gray-800 p-2 rounded focus:outline-none z-50">
    <i data-lucide="menu" class="w-6 h-6"></i>
  </button>
  <h1 class="text-xl font-semibold">Manage tokens</h1>
</header>
 <!-- Main -->
  <main class="p-6 mt-16 max-w-5xl mx-auto space-y-6">
    
    <div id="statusMessage"></div>
    
    <!-- Section Header -->
    <div class="flex items-center justify-between">
      <h2 class="text-2xl font-semibold">Signup Tokens</h2>
     <form id="generateForm" class="flex items-center gap-2">
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
    <tbody id="tokensTableBody" class="divide-y divide-gray-200 text-gray-800">
      <!-- Tokens will be loaded here dynamically -->
    </tbody>
  </table>
 
</div>
 <div id="paginationControls" class="flex justify-between items-center mt-10 text-sm">
  <button id="prevPage" class="px-3 py-1 bg-gray-200 rounded disabled:opacity-50">Previous</button>
  <span id="pageInfo"></span>
  <button id="nextPage" class="px-3 py-1 bg-gray-200 rounded disabled:opacity-50">Next</button>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteTokenModal"
     class="fixed inset-0 z-[1000] bg-black bg-opacity-50 hidden flex items-center justify-center">
  <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full relative z-[1001]">
    <h3 class="text-lg font-semibold text-gray-800">Delete Token?</h3>
    <p class="text-sm text-gray-600 mt-2 mb-4">
      This will permanently delete the token:
      <span id="tokenText" class="font-mono"></span>
    </p>
    <div class="flex justify-end gap-2">
      <button id="cancelDeleteToken" class="px-4 py-2 rounded bg-gray-200 text-gray-800 hover:bg-gray-300">Cancel</button>
      <button id="confirmDeleteToken" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Yes, Delete</button>
    </div>
  </div>
</div>

</main>
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
  });
</script>
 <script>
window.addEventListener("DOMContentLoaded", () => {
  lucide.createIcons();

  const form = document.querySelector("form");
  const qtyInput = form.querySelector("input[type='number']");
  const tableBody = document.querySelector("#tokensTableBody");

  const deleteModal = document.getElementById("deleteTokenModal");
  const tokenText = document.getElementById("tokenText");
  const confirmDelete = document.getElementById("confirmDeleteToken");
  const cancelDelete = document.getElementById("cancelDeleteToken");
  let selectedId = null;

  // Generate Tokens
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const qty = parseInt(qtyInput.value.trim());
    if (!qty || qty < 1 || qty > 20) {
      alert("Please enter a valid quantity (1–20)");
      return;
    }

    if (!confirm(`Are you sure you want to generate ${qty} token(s)?`)) return;

    const btn = form.querySelector("button");
    const originalText = btn.textContent;
    btn.textContent = "Generating...";
    btn.disabled = true;

    try {
      const res = await fetch("/api/generate-tokens.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({ qty })
      });

      const data = await res.json();

      if (data.success && Array.isArray(data.data.tokens)) {
        alert(data.data.message);
        const now = new Date().toISOString().split("T")[0];

        data.data.tokens.forEach(token => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td class="px-4 py-3 font-mono text-xs">${token}</td>
            <td class="px-4 py-3">
              <span class="inline-block text-xs font-semibold px-2 py-1 rounded-md bg-yellow-100 text-yellow-700">Unused</span>
            </td>
            <td class="px-4 py-3 text-xs">${now}</td>
            <td class="px-4 py-3 text-right">
              <button class="text-red-600 hover:underline text-xs delete-token-btn"
                data-id="new" data-token="${token}">Delete</button>
            </td>
          `;
          tableBody.prepend(tr);
        });

        qtyInput.value = "";
        attachDeleteListeners(); // ✅ Re-attach modal events
      } else {
        alert(data?.data?.error || data.message || "Failed to generate tokens.");
      }

    } catch (err) {
      
      const statusBox = document.getElementById("statusMessage");
      if (statusBox) {
        statusBox.innerHTML = `
          <div class="bg-red-50 text-red-700 p-4 rounded-md border border-red-200 mt-4">
            <strong>Error:</strong> ${err.message}
          </div>
        `;
        statusBox.classList.remove("hidden");
      }
    } finally {
      btn.textContent = originalText;
      btn.disabled = false;
    }
  });

  // Delete Modal Event Delegation (same function used in pagination script)
  function attachDeleteListeners() {
    document.querySelectorAll(".delete-token-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        selectedId = btn.getAttribute("data-id");
        tokenText.textContent = btn.getAttribute("data-token");
        deleteModal.classList.remove("hidden");
        document.body.classList.add("modal-open");
      });
    });
  }

  cancelDelete.addEventListener("click", () => {
    deleteModal.classList.add("hidden");
    document.body.classList.remove("modal-open");
  });

  confirmDelete.addEventListener("click", async () => {
    try {
      const res = await fetch(`/api/delete-token.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: selectedId })
      });

      const data = await res.json();
    
      if (data.success) {
        const row = document.querySelector(`button[data-id="${selectedId}"]`)?.closest("tr");
        if (row) {
          row.style.transition = 'opacity 0.3s';
          row.style.opacity = '0';
          setTimeout(() => row.remove(), 300);
        }

        deleteModal.classList.add("hidden");
        document.body.classList.remove("modal-open");
      } else {
        alert();
        
        const statusBox = document.getElementById("statusMessage");
      if (statusBox) {
        statusBox.innerHTML = data.message || "Failed to delete token."
        statusBox.classList.remove("hidden");
      }
      }
    } catch (err) {
      alert("Error deleting token: " + err.message);
    }
  });

  window.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && !deleteModal.classList.contains("hidden")) {
      deleteModal.classList.add("hidden");
      document.body.classList.remove("modal-open");
    }
  });

  attachDeleteListeners(); // Initial call for static tokens
});
</script>
  <script>
let currentPage = 1;
const limit = 10;

const deleteModal = document.getElementById("deleteTokenModal");
const tokenText = document.getElementById("tokenText");
const confirmDelete = document.getElementById("confirmDeleteToken");
const cancelDelete = document.getElementById("cancelDeleteToken");
const tableBody = document.getElementById("tokensTableBody");
let selectedId = null;

// Fade in effect
function fadeIn(el, duration = 300) {
  el.style.opacity = 0;
  el.style.transition = `opacity ${duration}ms ease`;
  requestAnimationFrame(() => {
    el.style.opacity = 1;
  });
}

// Fade out effect
function fadeOut(el, duration = 300, callback) {
  el.style.transition = `opacity ${duration}ms ease`;
  el.style.opacity = 0;
  setTimeout(() => {
    callback?.();
  }, duration);
}

  function fadeIn(element, duration = 300, yOffset = 10) {
  element.style.opacity = 0;
  element.style.transform = `translateY(${yOffset}px)`;
  element.style.transition = `opacity ${duration}ms ease, transform ${duration}ms ease`;
  requestAnimationFrame(() => {
    element.style.opacity = 1;
    element.style.transform = `translateY(0)`;
  });
}

async function loadTokens(page = 1) {
  try {
    const res = await fetch(`/api/get-tokens.php?page=${page}&limit=${limit}`);
    if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);

    const data = await res.json();
    if (!data.success) {
      alert("Failed to load tokens: " + (data.message || "Unknown error"));
      return;
    }

    tableBody.innerHTML = "";

    data.data.tokens.forEach((token, index) => {
      const tr = document.createElement("tr");
      tr.style.opacity = 0;
      tr.style.transform = `translateY(10px)`; // Slide from bottom
      tr.style.transition = `opacity 300ms ease, transform 300ms ease`;
      
      tr.innerHTML = `
        <td class="px-4 py-3 font-mono text-xs">${token.token}</td>
        <td class="px-4 py-3">
          <span class="inline-block text-xs font-semibold px-2 py-1 rounded-md 
            ${token.status === 'Used' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'}">
            ${token.status}
          </span>
        </td>
        <td class="px-4 py-3 text-xs">${token.created_at.split(' ')[0]}</td>
        <td class="px-4 py-3 text-right">
          <button class="text-red-600 hover:underline text-xs delete-token-btn"
            data-id="${token.id}" data-token="${token.token}">
            Delete
          </button>
        </td>
      `;
      tableBody.appendChild(tr);

      // Stagger animation
      setTimeout(() => {
        tr.style.opacity = 1;
        tr.style.transform = `translateY(0)`;
      }, index * 30); // 30ms delay per row
    });

    currentPage = data.data.page;
    const totalPages = data.data.pages;

    document.getElementById("pageInfo").textContent = `Page ${currentPage} of ${totalPages}`;
    document.getElementById("prevPage").disabled = currentPage === 1;
    document.getElementById("nextPage").disabled = currentPage === totalPages;

  } catch (err) {
    console.error("Failed to fetch tokens:", err);
    alert("An error occurred while loading tokens.");
  }
}


// Pagination controls
document.getElementById("prevPage").addEventListener("click", () => {
  if (currentPage > 1) loadTokens(currentPage - 1);
});
document.getElementById("nextPage").addEventListener("click", () => {
  loadTokens(currentPage + 1);
});

// Handle delete modal via event delegation
tableBody.addEventListener("click", (e) => {
  const btn = e.target.closest(".delete-token-btn");
  if (!btn) return;

  selectedId = btn.getAttribute("data-id");
  tokenText.textContent = btn.getAttribute("data-token");
  deleteModal.classList.remove("hidden");
  deleteModal.querySelector(".p-6").style.transform = "scale(0.9)";
  setTimeout(() => {
    deleteModal.querySelector(".p-6").style.transition = "transform 0.2s ease";
    deleteModal.querySelector(".p-6").style.transform = "scale(1)";
  }, 10);
  document.body.classList.add("modal-open");
});

// Confirm delete
confirmDelete.addEventListener("click", async () => {
  try {
    const res = await fetch(`/api/delete-token.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: selectedId })
    });

    const data = await res.json();
    if (data.success) {
      const row = document.querySelector(`button[data-id="${selectedId}"]`)?.closest("tr");
      if (row) {
        fadeOut(row, 300, () => row.remove());
      }
      deleteModal.classList.add("hidden");
      document.body.classList.remove("modal-open");
    } else {
      alert(data.message || "Failed to delete token.");
    }
  } catch (err) {
    alert("Error deleting token: " + err.message);
  }
});

// Cancel delete
cancelDelete.addEventListener("click", () => {
  deleteModal.classList.add("hidden");
  document.body.classList.remove("modal-open");
});

// Escape key closes modal
window.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && !deleteModal.classList.contains("hidden")) {
    deleteModal.classList.add("hidden");
    document.body.classList.remove("modal-open");
  }
});

// On page load
window.addEventListener("DOMContentLoaded", () => {
  loadTokens();
});
</script>
  </div>
</body>
</html>