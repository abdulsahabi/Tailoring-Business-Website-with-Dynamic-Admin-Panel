<?php
require_once __DIR__ . '/../../includes/auth.php';

// Get the image ID from URL
$imageId = isset($_GET['id']) ? intval($_GET['id']) : 0;

require_once __DIR__ . '/../../includes/db.php';
$stmt = $pdo->prepare("SELECT * FROM images WHERE id = ?");
$stmt->execute([$imageId]);
$gallery = $stmt->fetch();

if (!$gallery) {
  die("Image not found");
}

trackPageView(); // Auto-detects route
logView(); // Auto-detects route and device type
?>

<?php $activePage = 'add_gallery'; ?><!DOCTYPE html><html lang="en">
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

@keyframes fadeIn {
  0% {
    opacity: 0;
    transform: scale(0.95);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.25s ease-out;
}

  </style>
</head><body class="bg-gray-100 text-gray-800 flex min-h-screen relative overflow-x-hidden">
  <?php include("../components/sidebar.php") ?>
  <div id="sidebarBackdrop" class="fixed inset-0 bg-black bg-opacity-30 z-30 hidden"></div>
  <div id="mainContent" class="flex-1">
    <header class="bg-white p-4 flex justify-between items-center shadow-sm fixed top-0 z-50 w-full">
      <button id="toggleSidebar" class="md:hidden text-gray-800 p-2 rounded focus:outline-none z-50">
        <i data-lucide="menu" class="w-6 h-6"></i>
      </button>
      <h1 class="text-xl font-semibold">Updating image</h1>
    </header>
    <main class="p-6 space-y-8 mt-16">
      <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow-sm space-y-6">
        <h2 class="text-lg font-semibold text-gray-800">Update Gallery Image</h2>
        <div id="statusMessage" class="text-sm hidden"></div>
        <form id="uploadForm" novalidate>
          <input type="hidden" id="galleryId" name="id" value="<?= $gallery['id'] ?>">
          <input type="hidden" id="oldImage" value="<?= htmlspecialchars($gallery['image_name']) ?>">
          <input type="hidden" id="oldImageName" name="old_image" value="<?= htmlspecialchars($gallery['image_name']) ?>">
          <div id="previewContainer" class="mb-4">
            <img id="imagePreview" src="/uploads/gallery/<?= htmlspecialchars($gallery['image_name']) ?>" alt="Preview" class="w-full h-64 object-cover rounded-md border" />
            <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
              <i data-lucide="image" class="w-4 h-4 text-yellow-500"></i> Current image preview
            </p>
          </div>
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Image Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($gallery['alt']) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-400" />
            <p class="text-xs text-red-500 mt-1 hidden" id="titleError">Title is required.</p>
          </div>
          <div>
            <label for="caption" class="block text-sm font-medium text-gray-700 mb-1">Image Caption</label>
            <input type="text" id="caption" name="caption" value="<?= htmlspecialchars($gallery['caption']) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-400" />
          </div>
          <div>
            <label for="tag" class="block text-sm font-medium text-gray-700 mb-1">Category Tag</label>
            <select id="tag" name="tag" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-400">
              <option value="">-- Select Tag --</option>
              <option value="Men" <?= $gallery['tag'] === 'Men' ? 'selected' : '' ?>>Men</option>
              <option value="Women" <?= $gallery['tag'] === 'Women' ? 'selected' : '' ?>>Women</option>
              <option value="Bridal" <?= $gallery['tag'] === 'Bridal' ? 'selected' : '' ?>>Bridal</option>
              <option value="Kids" <?= $gallery['tag'] === 'Kids' ? 'selected' : '' ?>>Kids</option>
              <option value="fashion" <?= $gallery['tag'] === 'fashion' ? 'selected' : '' ?>>Fashion</option>
            </select>
            <p class="text-xs text-red-500 mt-1 hidden" id="tagError">Please select a category tag.</p>
          </div>
          <div>
            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Change Image (Optional)</label>
            <input type="file" id="image" name="image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white file:mr-4 file:px-4 file:py-2 file:border-0 file:bg-yellow-400 file:text-white file:rounded file:cursor-pointer hover:file:bg-yellow-500" />
            <p class="text-xs text-gray-500 mt-1">Only choose a file if you want to replace the image.</p>
            <p class="text-xs text-red-500 mt-1 hidden" id="imageError">Please select a valid image.</p>
            <p id="fileName" class="text-xs text-gray-600 mt-1 hidden"></p>
          </div>
          <button type="submit" id="submitBtn" class="mt-4 inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-md text-sm font-medium shadow-sm transition-all">
            <span id="submitText">Update Image</span>
            <svg id="submitSpinner" class="w-4 h-4 animate-spin hidden" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="white" stroke-width="4"></circle>
              <path class="opacity-75" fill="white" d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
          </button>
        </form>
        <button type="button" id="deleteBtn" class="mt-4 inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-md text-sm font-medium shadow-sm transition">
          <i data-lucide="trash-2" class="w-4 h-4"></i>
          Delete Image
        </button>
      </div>
    </main><!-- Confirm Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
  <div class="flex items-center justify-center min-h-screen p-4">
    <div class="bg-white w-full max-w-md rounded-xl shadow-xl p-6 animate-fadeIn">
      <h2 class="text-xl font-semibold text-gray-800 mb-2">Confirm Deletion</h2>
      <p class="text-sm text-gray-600 mb-6">Are you sure you want to permanently delete this image? This action cannot be undone.</p>
      <div class="flex justify-end gap-3">
        <button id="cancelDelete" class="px-4 py-2 text-sm rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 transition">Cancel</button>
        <button id="confirmDelete" class="px-6 flex py-2 text-sm bg-red-500 hover:bg-red-600 text-white rounded-md transition">
          <i data-lucide="trash" class="w-4 h-4 mr-1"></i> Yes, Delete
        </button>
      </div>
    </div>
  </div>
</div>

  </div>
  <script src="https://unpkg.com/lucide@latest" defer></script>
  <script>
    
    
window.addEventListener("DOMContentLoaded", () => {
  lucide.createIcons();

  const form = document.getElementById("uploadForm");

  const galleryId = document.getElementById("galleryId").value;
  const oldImageName = document.getElementById("oldImageName").value;
  const imageIdInput = document.getElementById("imageId");
  const oldImageInput = document.getElementById("oldImage");

  const titleInput = document.getElementById("title");
  const captionInput = document.getElementById("caption");
  const tagSelect = document.getElementById("tag");
  const imageInput = document.getElementById("image");

  const imagePreview = document.getElementById("imagePreview");
  const previewContainer = document.getElementById("previewContainer");

  const titleError = document.getElementById("titleError");
  const tagError = document.getElementById("tagError");
  const imageError = document.getElementById("imageError");
  const statusMessage = document.getElementById("statusMessage");

  const submitBtn = document.getElementById("submitBtn");
  const submitText = document.getElementById("submitText");
  const submitSpinner = document.getElementById("submitSpinner");

  // Show image preview if changed
const fileNameDisplay = document.getElementById("fileName");

imageInput.addEventListener("change", () => {
  const file = imageInput.files[0];

  if (file) {
    if (!file.type.startsWith("image/")) {
      imageError.textContent = "Please select a valid image.";
      imageError.classList.remove("hidden");
      imageInput.value = "";
      fileNameDisplay.classList.add("hidden");
      fileNameDisplay.textContent = "";
      return;
    }

    if (file.size > 2 * 1024 * 1024) {
      imageError.textContent = "File too large. Max 2MB allowed.";
      imageError.classList.remove("hidden");
      imageInput.value = "";
      previewContainer.classList.add("hidden");
      fileNameDisplay.classList.add("hidden");
      fileNameDisplay.textContent = "";
      return;
    }

    // Show preview
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.src = e.target.result;
      previewContainer.classList.remove("hidden");
    };
    reader.readAsDataURL(file);

    // ✅ Display selected file name
    fileNameDisplay.textContent = `Selected file: ${file.name}`;
    fileNameDisplay.classList.remove("hidden");

    imageError.classList.add("hidden");
  } else {
    fileNameDisplay.classList.add("hidden");
    fileNameDisplay.textContent = "";
  }
});

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    let isValid = true;
    statusMessage.textContent = '';
    statusMessage.classList.add("hidden");

    // Validate title
    if (!titleInput.value.trim()) {
      titleError.classList.remove("hidden");
      isValid = false;
    } else {
      titleError.classList.add("hidden");
    }

    // Validate tag
    if (!tagSelect.value) {
      tagError.classList.remove("hidden");
      isValid = false;
    } else {
      tagError.classList.add("hidden");
    }

    // Validate image only if file is chosen
    const file = imageInput.files[0];
    if (file && !file.type.startsWith("image/")) {
      imageError.classList.remove("hidden");
      isValid = false;
    } else {
      imageError.classList.add("hidden");
    }

    if (!isValid) return;

    // Prepare form data
    const formData = new FormData();
    formData.append("id", galleryId);
    formData.append("title", titleInput.value.trim());
    formData.append("caption", captionInput.value.trim());
    formData.append("tag", tagSelect.value);
    formData.append("old_image", oldImageName);
    if (file) {
      formData.append("image", file);
    }

    // Show loading state
    submitBtn.disabled = true;
    submitSpinner.classList.remove("hidden");
    submitText.textContent = "Updating...";

    try {
      const res = await fetch("../../api/gallery-update.php", {
        method: "POST",
        body: formData
      });

      const raw = await res.text();
      let data;

      try {
        data = JSON.parse(raw);
      } catch (jsonErr) {
        statusMessage.innerHTML = `
          <div class="text-red-600 font-medium mt-4">
            ❌ Invalid response from server.
            <pre class="text-xs mt-2 bg-red-50 p-2 border border-red-200 rounded text-red-700 overflow-auto">${raw}</pre>
          </div>
        `;
        statusMessage.classList.remove("hidden");
        return;
      }

      if (data.success) {
        statusMessage.textContent = "✅ Gallery updated successfully.";
        statusMessage.className = "text-green-600 font-medium mt-4";
         window.location.href = './index.php'
      } else {
        statusMessage.textContent = "❌ " + (data.message || "Update failed.");
        statusMessage.className = "text-red-600 font-medium mt-4";
      }

    } catch (err) {
      statusMessage.innerHTML = `
        <div class="text-red-600 font-medium mt-4">
          ❌ Error occurred during update.
          <pre class="text-xs mt-2 bg-red-50 p-2 border border-red-200 rounded text-red-700 overflow-auto">${err.message}</pre>
        </div>
      `;
    } finally {
      statusMessage.classList.remove("hidden");
      submitSpinner.classList.add("hidden");
      submitText.textContent = "Update Image";
      submitBtn.disabled = false;
    }
  });
  
  const deleteBtn = document.getElementById("deleteBtn");
  const deleteModal = document.getElementById("deleteModal");
  const cancelDelete = document.getElementById("cancelDelete");
  const confirmDelete = document.getElementById("confirmDelete");

  // Show modal
  deleteBtn?.addEventListener("click", () => {
    deleteModal.classList.remove("hidden");
  });

  // Cancel delete
  cancelDelete?.addEventListener("click", () => {
    deleteModal.classList.add("hidden");
  });

  // Confirm delete
  confirmDelete?.addEventListener("click", async () => {
    try {
      confirmDelete.disabled = true;
      confirmDelete.textContent = "Deleting...";

      const res = await fetch("../../api/gallery-delete.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id: galleryId, image: oldImageName })
      });

      const data = await res.json();

      if (data.success) {
        window.location.href = "./index.php?deleted=true";
      } else {
        alert("❌ Failed to delete image.");
      }
    } catch (err) {
      alert("❌ Error deleting image.");
      console.error(err);
    } finally {
      confirmDelete.disabled = false;
      confirmDelete.textContent = "Yes, Delete";
      deleteModal.classList.add("hidden");
    }
  });
});
</script>
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
</body>
</html>