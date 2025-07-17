<?php ?><!DOCTYPE html><html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta name="description" content="AU Idris Fashion - Trendy, elegant, and modest fashion styles for every occasion." />
  <meta name="keywords" content="AU Idris, fashion, clothing, kaftans, abayas, styles, Nigeria fashion" />
  <meta name="author" content="AU Idris Fashion" />
  <title>AU Idris Fashion Center</title>  <!-- TailwindCSS CDN -->  <script src="https://cdn.tailwindcss.com"></script>  <!-- Lucide Icons -->  <script src="https://unpkg.com/lucide@latest" defer></script>  <!-- Custom CSS & Favicon -->  <link rel="stylesheet" href="/assets/css/custom.css" />
  <link rel="icon" type="image/png" href="/assets/images/favicon.png" />  <style>
    html { overflow-x: hidden; }
    body.no-scroll {
      height: 100vh;
      overflow: hidden !important;
      position: fixed;
      width: 100%;
      top: 0;
      left: 0;
      touch-action: none;
    }
    @keyframes fadeInLink {
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }
    .sidebar-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 0.5rem 0;
      transition: all 0.3s ease;
      opacity: 0;
      transform: translateX(-10px);
      animation: fadeInLink 0.4s ease forwards;
      animation-delay: calc(var(--i) * 0.1s);
    }
    .sidebar-link:hover {
      color: #facc15;
    }
    .sidebar-link svg {
      width: 20px;
      height: 20px;
    }
    .sidebar-link.active {
      background-color: rgba(255, 255, 255, 0.1);
      border-left: 4px solid #facc15;
      padding-left: 1rem;
      border-radius: 0.375rem;
    }
    .sidebar-logo, .sidebar-footer {
      opacity: 0;
      transform: translateY(10px);
      animation: fadeInLink 0.5s ease forwards;
      animation-delay: 0.3s;
    }
    #sidebarWrapper > div {
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    #sidebarWrapper:not(.hidden) > div {
      opacity: 1;
    }
  </style></head><body class="text-[var(--text-color)] font-[PoppinsRegular] bg-[var(--background-color)]">
  <div class="bg-black/80 backdrop-blur-sm text-white text-sm text-center py-2 flex justify-center items-center gap-2 shadow-sm">
  <i data-lucide="phone-call" class="w-4 h-4 text-yellow-400 animate-pulse"></i>
  <span class="tracking-wide">Call or WhatsApp Us: <strong class="text-yellow-400">+234 812 642 1552</strong></span>
</div>
  <div id="mainContent" class="transition-transform duration-300 relative z-10">
<header class="sticky top-0 z-30 bg-white/30 backdrop-blur-md border-b border-gray-200 shadow-sm px-4 py-3 flex items-center justify-between">
  <!-- Mobile Menu Button -->
  <button id="toggleSidebar" class="md:hidden" aria-label="Toggle Sidebar">
    <i data-lucide="menu" class="w-6 h-6 text-gray-700 hover:text-yellow-400 transition-all"></i>
  </button>

  <!-- Logo & Brand Name -->
  <div class="flex items-center gap-2">
    <img src="./assets/images/logo.png" alt="AU Idris Logo"
         class="h-10 w-10 hover:rotate-12 hover:scale-105 transition-transform duration-300">
    <span class="text-lg font-semibold text-gray-800 tracking-wide">AU IDRIS FASHION</span>
  </div>

  <!-- Contact Icons (Hidden on Mobile) -->
  <div class="hidden md:flex items-center gap-4 text-gray-600 text-[18px]">
    <a href="tel:+2348126421552" class="hover:text-blue-600 transition-all" aria-label="Call">
      <i data-lucide="phone-call"></i>
    </a>
    <a href="https://wa.me/2348126421552" target="_blank" class="hover:text-green-600 transition-all" aria-label="WhatsApp">
      <i data-lucide="message-circle"></i>
    </a>
    <a href="https://instagram.com/yourpage" target="_blank" class="hover:text-pink-600 transition-all" aria-label="Instagram">
      <i data-lucide="instagram"></i>
    </a>
  </div>
</header>

  </div>  <div id="sidebarWrapper" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    <aside id="sidebarPanel" class="absolute left-0 top-0 h-full w-[85%] sm:w-[300px] bg-gradient-to-b from-black/80 via-black/60 to-black/40 text-white p-6 shadow-lg transform -translate-x-full transition-transform duration-300 rounded-tr-2xl rounded-br-2xl backdrop-blur-lg border-r border-white/10 overflow-y-auto">
      <div class="sidebar-logo text-center mb-8 mt-4 sticky top-0 bg-black/60 py-4">
        <img src="./assets/images/logo.png" class="h-16 w-16 mx-auto p-1 bg-white rounded-full" alt="AU Idris Fashion Logo">
        <h3 class="text-lg font-semibold mt-2">AU Idris Fashion</h3>
        <p class="text-sm text-gray-300 italic">"Elegance Redefined"</p>
        <button id="closeSidebar" class="absolute top-4 right-4 p-2 rounded-full hover:bg-white/10 transition animate-pulse" aria-label="Close Sidebar">
          <i data-lucide="x" class="text-white w-6 h-6"></i>
        </button>
      </div><nav class="flex flex-col gap-3 text-white text-base mt-4">
    <a href="/" class="sidebar-link <?php echo $_SERVER['REQUEST_URI'] == '/' ? 'active' : '' ?>" style="--i: 0"><i data-lucide="home"></i>Home</a>
    <a href="/about" class="sidebar-link <?php echo $_SERVER['REQUEST_URI'] == '/about' ? 'active' : '' ?>" style="--i: 1"><i data-lucide="file-text"></i>The AU Story</a>
    <a href="/gallery" class="sidebar-link <?php echo $_SERVER['REQUEST_URI'] == '/gallery' ? 'active' : '' ?>" style="--i: 2"><i data-lucide="image"></i>Style Showcase</a>
    <a href="/contact" class="sidebar-link <?php echo $_SERVER['REQUEST_URI'] == '/contact' ? 'active' : '' ?>" style="--i: 3"><i data-lucide="phone"></i>Tailor With Us</a>
  </nav>

  <a href="/contact" class="mt-8 inline-block bg-yellow-400 text-black py-2 px-4 rounded-lg font-semibold hover:bg-yellow-500 transition text-center w-full">Place an Order</a>

  <div class="sidebar-footer absolute bottom-6 left-6 text-sm text-gray-400 space-y-2">
    <p>&copy; <?= date('Y') ?> AU Idris</p>
    <div class="flex gap-3">
      <a href="https://wa.me/2348126421552" target="_blank" aria-label="WhatsApp" class="hover:text-green-400"><i data-lucide="message-circle"></i></a>
      <a href="https://instagram.com/yourpage" target="_blank" aria-label="Instagram" class="hover:text-pink-400"><i data-lucide="instagram"></i></a>
    </div>
  </div>
</aside>

  </div>  <script>
    const sidebarWrapper = document.getElementById('sidebarWrapper');
    const sidebarPanel = document.getElementById('sidebarPanel');
    const mainContent = document.getElementById('mainContent');
    const toggleSidebar = document.getElementById('toggleSidebar');
    const closeSidebar = document.getElementById('closeSidebar');

    let scrollY = 0;

    const openMenu = () => {
      scrollY = window.scrollY;
      document.body.style.top = `-${scrollY}px`;
      document.body.classList.add('no-scroll');
      sidebarWrapper.classList.remove('hidden');
      requestAnimationFrame(() => {
        sidebarPanel.classList.remove('-translate-x-full');
        mainContent.classList.add('translate-x-[80%]', 'md:translate-x-[300px]');
      });
    };

    const closeMenu = () => {
      sidebarPanel.classList.add('-translate-x-full');
      mainContent.classList.remove('translate-x-[80%]', 'md:translate-x-[300px]');
      setTimeout(() => {
        sidebarWrapper.classList.add('hidden');
        document.body.classList.remove('no-scroll');
        document.body.style.top = '';
        window.scrollTo(0, scrollY);
      }, 300);
    };

    toggleSidebar.onclick = openMenu;
    closeSidebar.onclick = closeMenu;
    sidebarWrapper.onclick = (e) => {
      if (!sidebarPanel.contains(e.target)) closeMenu();
    };
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeMenu();
    });

    window.addEventListener('DOMContentLoaded', () => {
      lucide.createIcons();
    });
    
    
     const slides = [
      {
        title: "Zanƙe Royal",
        subtitle: "Elegance fit for Emirs",
        button: "Shop Now",
        image: "/assets/images/style1.jpg"
      },
      {
        title: "Kaftan Sarki",
        subtitle: "Tailored Nobility in Every Stitch",
        button: "Explore Designs",
        image: "/assets/images/style2.jpg"
      },
      {
        title: "Arewa Luxe",
        subtitle: "Where Culture Meets Modern Class",
        button: "View Collection",
        image: "/assets/images/style3.jpg"
      },
      {
        title: "Dan Malam Styles",
        subtitle: "Refined Looks for Distinguished Men",
        button: "See Styles",
        image: "/assets/images/style4.jpg"
      }
    ];

    let current = 0;
    const imageEl = document.getElementById("slideImage");
    const titleEl = document.getElementById("slideTitle");
    const subtitleEl = document.getElementById("slideSubtitle");
    const buttonEl = document.getElementById("slideButton");

    setInterval(() => {
      current = (current + 1) % slides.length;
      const slide = slides[current];

      imageEl.classList.add("opacity-0");
      setTimeout(() => {
        imageEl.src = slide.image;
        imageEl.alt = slide.title;
        titleEl.textContent = slide.title;
        subtitleEl.textContent = slide.subtitle;
        buttonEl.textContent = slide.button;
        imageEl.classList.remove("opacity-0");
      }, 500);
    }, 5000);
  </script></body>
</html>