<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
trackPageView(); // Auto-detects route
logView()
?>


<!DOCTYPE html>
<html lang="en">
<?php include("./components/head.php") ?>
<body class="bg-white text-gray-800 font-[PoppinsRegular]">

<div id="mainContent" class="py-8 px-4 max-w-6xl mx-auto space-y-8">

  <!-- Back Button -->
  <button onclick="history.back()"
          class="inline-flex items-center gap-2 text-yellow-500 hover:text-yellow-600 font-semibold mb-4">
    <i data-lucide="arrow-left" class="w-5 h-5"></i> Back
  </button>

  <!-- Page Title -->
  <section class="text-center mb-8">
    <h1 class="text-4xl font-bold mb-2">A Glimpse of Our Style</h1>
    <p class="text-gray-600">Every piece tells a story of elegance, culture, and craftsmanship.</p>
  </section>

  <!-- Gallery Container -->
  <section>
    <div id="galleryGrid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 items-stretch">
      <!-- Skeletons + Images will be injected here -->
    </div>

    <div class="text-center mt-8">
      <button id="loadMoreBtn"
              class="bg-yellow-400 text-black font-semibold py-2 px-6 rounded-full hover:bg-yellow-500 transition duration-300 shadow-md">
        Load More
      </button>
    </div>
  </section>

</div>

<?php include("./components/footer.php") ?>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
  lucide.createIcons();

  const galleryGrid = document.getElementById('galleryGrid');
  const loadMoreBtn = document.getElementById('loadMoreBtn');
  const batchSize = 4;
  let currentIndex = 0;
  let skeletonRefs = [];

  function showSkeletons(count) {
    return new Promise(resolve => {
      let added = 0;
      const interval = setInterval(() => {
        const skeleton = document.createElement('div');
        skeleton.className = "skeleton h-[220px] w-full rounded-xl";
        galleryGrid.appendChild(skeleton);
        skeletonRefs.push(skeleton);
        added++;
        if (added === count) {
          clearInterval(interval);
          resolve();
        }
      }, 80);
    });
  }

  function removeSkeletons() {
    return new Promise(resolve => {
      let removed = 0;
      const total = skeletonRefs.length;
      const interval = setInterval(() => {
        const skeleton = skeletonRefs.shift();
        if (skeleton && skeleton.parentNode) {
          galleryGrid.removeChild(skeleton);
        }
        removed++;
        if (removed === total) {
          clearInterval(interval);
          resolve();
        }
      }, 60);
    });
  }

  async function renderBatch() {
    const url = `/api/images.php?limit=${batchSize}&offset=${currentIndex}`;

    await showSkeletons(batchSize);
    await new Promise(res => setTimeout(res, 300));

    const res = await fetch(url);
    const json = await res.json();
    await removeSkeletons();

    if (!json.success || !json.data.length) {
      loadMoreBtn.style.display = 'none';
      return;
    }

    const data = json.data;
    data.forEach(item => {
      const card = document.createElement('div');
      card.className = "relative group h-[220px] overflow-hidden rounded-xl shadow hover:shadow-lg transition-all duration-300";

      card.innerHTML = `
        <div class="absolute top-2 left-2 bg-yellow-400 text-black text-xs px-2 py-1 rounded-full font-semibold z-10">${item.tag}</div>
        <img loading="lazy" src="/uploads/gallery/${item.src}" alt="${item.alt}" class="w-full h-full object-cover hover:scale-[1.04] transition-transform duration-500" />
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-3 text-white font-medium text-sm">
          ${item.caption}
        </div>
        <button aria-label="Like this design" class="like-btn absolute top-2 right-2 text-white/90 bg-black/40 rounded-full p-1 w-7 h-7 transition">
          <i data-lucide="heart" class="lucide-heart w-5 h-5 transition duration-300"></i>
        </button>
      `;
      galleryGrid.appendChild(card);
    });

    lucide.createIcons();

    document.querySelectorAll('.like-btn').forEach(button => {
      button.onclick = () => {
        const icon = button.querySelector('svg');
        icon.classList.toggle('text-red-500');
        icon.classList.toggle('fill-current');
        icon.classList.add('scale-110');
        button.classList.toggle('liked');
        setTimeout(() => icon.classList.remove('scale-110'), 200);
      };
    });

    currentIndex += data.length;

    if (data.length < batchSize) {
      loadMoreBtn.style.display = 'none';
    }
  }

  renderBatch();
  loadMoreBtn.addEventListener('click', () => renderBatch());
</script>

<!-- Skeleton shimmer CSS -->
<style>
  .skeleton {
    position: relative;
    overflow: hidden;
    background-color: #e5e7eb;
  }

  .skeleton::after {
    content: "";
    position: absolute;
    top: 0;
    left: -150px;
    height: 100%;
    width: 150px;
    background: linear-gradient(to right, transparent 0%, #f3f4f6 50%, transparent 100%);
    animation: shimmer 1.2s infinite;
  }

  @keyframes shimmer {
    0% {
      left: -150px;
    }
    100% {
      left: 100%;
    }
  }
</style>
</body>
</html>