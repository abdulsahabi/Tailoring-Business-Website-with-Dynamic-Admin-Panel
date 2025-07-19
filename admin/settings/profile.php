<?php // admin-dashboard.php ?><?php $activePage = 'profile'; ?><!DOCTYPE html><html lang="en">
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
  <h1 class="text-xl font-semibold">Profile</h1>
</header>
<main class="p-6 mt-16 flex justify-center">
  <div class="w-full max-w-2xl space-y-8">

    <!-- Avatar -->
    <div class="flex items-center gap-4">
      <div class="w-14 h-14 bg-yellow-500 text-white flex items-center justify-center rounded-full text-xl font-semibold">
        A
      </div>
      <div>
        <h2 class="text-xl font-semibold text-gray-800">Admin Profile</h2>
        <p class="text-sm text-gray-500">Manage your account information securely.</p>
      </div>
    </div>

    <!-- Profile Form -->
    <form id="adminProfileForm" class="flex flex-col gap-5 mt-4">
      <!-- Full Name -->
      <div class="input-group relative">
        <label class="absolute top-[6px] label">FULL NAME</label>
        <input type="text" id="full_name" name="full_name" value="Admin User"
          class="input w-full border border-gray-300 p-[10px] pt-[16px] rounded-sm h-[56px] outline-none text-[13px] focus:ring-yellow-400 focus:ring-2" autocomplete="off">
        <div id="full_error" class="error text-xs text-red-600 mt-1 hidden"></div>
      </div>

      <!-- Email -->
      <div class="input-group relative">
        <label class="absolute top-[6px] label">EMAIL ADDRESS</label>
        <input type="email" id="email" name="email" value="admin@example.com"
          class="input w-full border border-gray-300 p-[10px] pt-[16px] rounded-sm h-[56px] outline-none text-[13px] focus:ring-yellow-400 focus:ring-2" autocomplete="off">
        <div id="email_error" class="error text-xs text-red-600 mt-1 hidden"></div>
      </div>

      <!-- New Password -->
      <div class="input-group relative">
        <label class="absolute top-[6px] label">NEW PASSWORD</label>
        <input type="password" id="password" name="password"
          class="input w-full border border-gray-300 p-[10px] pt-[16px] pr-[50px] rounded-sm h-[56px] outline-none text-[13px] focus:ring-yellow-400 focus:ring-2" autocomplete="off">
        <button type="button" class="absolute right-4 top-5 toggle-pass" data-target="password">
          <i data-lucide="eye" class="w-5 h-5 text-gray-400"></i>
        </button>
        <div id="password_error" class="error text-xs text-red-600 mt-1 hidden"></div>
      </div>

      <!-- Confirm Password -->
      <div class="input-group relative">
        <label class="absolute top-[6px] label">CONFIRM PASSWORD</label>
        <input type="password" id="cpassword" name="cpassword"
          class="input w-full border border-gray-300 p-[10px] pt-[16px] pr-[50px] rounded-sm h-[56px] outline-none text-[13px] focus:ring-yellow-400 focus:ring-2" autocomplete="off">
        <button type="button" class="absolute right-4 top-5 toggle-pass" data-target="cpassword">
          <i data-lucide="eye" class="w-5 h-5 text-gray-400"></i>
        </button>
        <div id="cpassword_error" class="error text-xs text-red-600 mt-1 hidden"></div>
      </div>

      <!-- Submit -->
      <button type="submit"
        class="w-full bg-yellow-400 text-black shadow-sm p-[10px] pt-[16px] rounded-sm h-[56px] text-sm font-medium hover:bg-yellow-500 transition-all">
        Save Changes
      </button>
    </form>

    <!-- Delete Account Card -->
    <div class="bg-white border border-red-200 text-red-700 rounded-xl shadow-sm p-6">
      <div class="flex items-start justify-between gap-4">
        <div>
          <h3 class="text-lg font-semibold">Delete Admin Account</h3>
          <p class="text-sm mt-1">Permanently remove this profile and all its associated data. This action cannot be undone.</p>
        </div>
        <button id="triggerDelete" class="inline-flex items-center gap-2 px-4 py-2 border border-red-400 hover:bg-red-100 rounded-md text-sm font-medium transition">
          <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
        </button>
      </div>
    </div>
  </div>

  <!-- Modal (outside the centered container to cover full screen properly) -->
  <div id="deleteModal" class="fixed inset-0 z-50 hidden flex items-center justify-center backdrop-blur-sm bg-black/40">
    <div class="bg-white p-6 rounded-xl w-[90vw] max-w-md shadow-xl space-y-4">
      <h2 class="text-lg font-bold text-red-600 flex items-center gap-2">
        <i data-lucide="alert-triangle" class="w-5 h-5"></i> Confirm Deletion
      </h2>
      <p class="text-sm text-gray-600">Are you absolutely sure you want to delete this admin account? This action is irreversible.</p>
      <div class="flex justify-end gap-4 pt-4">
        <button id="cancelDelete" class="text-sm px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md">Cancel</button>
        <button id="confirmDelete" class="text-sm px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-md">Yes, Delete</button>
      </div>
    </div>
  </div>
</main>
<!-- Scripts -->
<script src="https://unpkg.com/lucide@latest" defer></script>
<!-- All related script sections here (omitted for brevity) -->
 <script>
  document.addEventListener("DOMContentLoaded", () => {
    lucide.createIcons();

    const form = document.getElementById("adminProfileForm");
    const fullNameInput = document.getElementById("full_name");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const cpasswordInput = document.getElementById("cpassword");

    const avatarBox = document.querySelector(".w-14.h-14");

    // Update avatar letter on name change
    fullNameInput.addEventListener("input", () => {
      const firstLetter = fullNameInput.value.trim().charAt(0).toUpperCase();
      avatarBox.textContent = firstLetter || 'A';
    });

    // Toggle password visibility
    document.querySelectorAll(".toggle-pass").forEach((btn) => {
      btn.addEventListener("click", () => {
        const inputId = btn.getAttribute("data-target");
        const input = document.getElementById(inputId);
        const isVisible = input.type === "text";
        input.type = isVisible ? "password" : "text";
        btn.innerHTML = isVisible ? '<i data-lucide="eye"></i>' : '<i data-lucide="eye-off"></i>';
        lucide.createIcons();
      });
    });

    form.addEventListener("submit", (e) => {
      e.preventDefault();
      let isValid = true;

      const showError = (id, message) => {
        const el = document.getElementById(id + "_error");
        el.textContent = message;
        el.classList.remove("hidden");
        isValid = false;
      };

      // Clear errors
      ["full", "email", "password", "cpassword"].forEach(id => {
        document.getElementById(id + "_error").classList.add("hidden");
      });

      if (!fullNameInput.value.trim()) showError("full", "Full name is required.");
      if (!emailInput.value.match(/^\S+@\S+\.\S+$/)) showError("email", "Invalid email address.");
      if (passwordInput.value && passwordInput.value.length < 8) showError("password", "Password must be at least 8 characters.");
      if (passwordInput.value !== cpasswordInput.value) showError("cpassword", "Passwords do not match.");

      if (isValid) {
        alert("✅ Profile updated (form can now submit via AJAX).");
        // Send data via fetch() or other API request...
      }
    });
  });
</script>
<script>
  document.addEventListener("DOMContentLoaded", () => {
    lucide.createIcons();

    const deleteBtn = document.getElementById("triggerDelete");
    const modal = document.getElementById("deleteModal");
    const cancelBtn = document.getElementById("cancelDelete");
    const confirmBtn = document.getElementById("confirmDelete");

   // Open modal
deleteBtn?.addEventListener("click", () => {
  modal.classList.remove("hidden");
  document.body.classList.add("modal-open");  // Prevent scrolling
});

// Close modal
cancelBtn?.addEventListener("click", () => {
  modal.classList.add("hidden");
  document.body.classList.remove("modal-open");  // Re-enable scrolling
});

// Confirm Deletion
confirmBtn?.addEventListener("click", () => {
  modal.classList.add("hidden");
  document.body.classList.remove("modal-open");  // Re-enable scrolling
  alert("✅ Account deleted. [Connect to backend for real action]");
});
  });
</script>
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