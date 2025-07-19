
<?php // admin-dashboard.php ?>
<?php $activePage = 'add_gallery'; ?>
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
  <h1 class="text-xl font-semibold">Create Gallery</h1>
</header>

<main class="p-6 space-y-8 mt-16">
  <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow-sm space-y-6">
    <!-- Image Preview -->
    <div id="previewContainer" class="hidden">
      <img id="imagePreview" src="" alt="Preview" class="w-full h-64 object-cover rounded-md border" />
      <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
        <i data-lucide="image" class="w-4 h-4 text-yellow-500"></i> Preview of selected image
      </p>
    </div>

    <h2 class="text-lg font-semibold text-gray-800">Upload New Gallery Image</h2>

    <form id="uploadForm" novalidate>
      <!-- Title Field -->
      <div>
        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Image Title</label>
        <input type="text" id="title" name="title"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-400" />
        <p class="text-xs text-red-500 mt-1 hidden" id="titleError">Title is required.</p>
      </div>

      <!-- Tag Field -->
      <div>
        <label for="tag" class="block text-sm font-medium text-gray-700 mb-1">Category Tag</label>
        <select id="tag" name="tag"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-400">
          <option value="">-- Select Tag --</option>
          <option value="Men">Men</option>
          <option value="Women">Women</option>
          <option value="Bridal">Bridal</option>
          <option value="Kids">Kids</option>
        </select>
        <p class="text-xs text-red-500 mt-1 hidden" id="tagError">Please select a category tag.</p>
      </div>

      <!-- File Upload Field -->
      <div>
        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Select Image</label>
        <input type="file" id="image" name="image" accept="image/*"
          class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white file:mr-4 file:px-4 file:py-2 file:border-0 file:bg-yellow-400 file:text-white file:rounded file:cursor-pointer hover:file:bg-yellow-500" />
        <p class="text-xs text-red-500 mt-1 hidden" id="imageError">Please select a valid image.</p>
      </div>

      <!-- Submit Button -->
      <button type="submit"
        class="mt-4 flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-md text-sm font-medium shadow-sm">
        <i data-lucide="upload-cloud" class="w-4 h-4"></i> Upload Image
      </button>
    </form>
  </div>
</main>


  <!-- Scripts -->
  <script src="https://unpkg.com/lucide@latest" defer></script>
  <script>
  window.addEventListener("DOMContentLoaded", () => {
    lucide.createIcons();

    const form = document.getElementById("uploadForm");
    const imageInput = document.getElementById("image");
    const imagePreview = document.getElementById("imagePreview");
    const previewContainer = document.getElementById("previewContainer");

    const titleInput = document.getElementById("title");
    const tagSelect = document.getElementById("tag");

    const titleError = document.getElementById("titleError");
    const tagError = document.getElementById("tagError");
    const imageError = document.getElementById("imageError");

    // Image preview
    imageInput.addEventListener("change", () => {
      const file = imageInput.files[0];
      if (file && file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = (e) => {
          imagePreview.src = e.target.result;
          previewContainer.classList.remove("hidden");
        };
        reader.readAsDataURL(file);
        imageError.classList.add("hidden");
      } else {
        imageError.textContent = "Please select a valid image.";
        imageError.classList.remove("hidden");
        previewContainer.classList.add("hidden");
        imagePreview.src = "";
      }
    });

    // Validation before submit
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      let isValid = true;

      // Title validation
      if (!titleInput.value.trim()) {
        titleError.classList.remove("hidden");
        isValid = false;
      } else {
        titleError.classList.add("hidden");
      }

      // Tag validation
      if (!tagSelect.value) {
        tagError.classList.remove("hidden");
        isValid = false;
      } else {
        tagError.classList.add("hidden");
      }

      // Image validation
      const file = imageInput.files[0];
      if (!file || !file.type.startsWith("image/")) {
        imageError.textContent = "Please select a valid image.";
        imageError.classList.remove("hidden");
        isValid = false;
      } else {
        imageError.classList.add("hidden");
      }

      // Submit form if valid
      if (isValid) {
        // Submit with form.submit(); OR AJAX later
        alert("✅ All fields valid! You can now proceed to upload.");
        // form.submit(); // uncomment to submit normally
      }
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
  
<!-- Chart Scripts (place at bottom of body) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  </div>
</body>
</html>