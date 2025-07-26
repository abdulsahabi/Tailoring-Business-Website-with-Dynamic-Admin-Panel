 <div id="sidebarWrapper" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    <aside id="sidebarPanel" class="absolute left-0 top-0 h-full w-[90%] sm:w-[320px] bg-gradient-to-b from-black/80 via-black/60 to-black/40 text-white p-6 shadow-lg transform -translate-x-full transition-transform duration-300 rounded-tr-2xl rounded-br-2xl backdrop-blur-lg border-r border-white/10 overflow-y-auto">
      <div class="sidebar-logo text-center mb-8 mt-4 sticky top-0 bg-black/60 py-4">
        <img src="./assets/images/logo.jpg" class="h-16 w-16 mx-auto p-1 bg-white rounded-full" alt="AU Idris Fashion Logo">
        <h3 class="text-lg font-semibold mt-2">BEEWHY ENTERPRISE</h3>
        <p class="text-sm text-gray-300 italic">"Tailored to Perfection"</p>
        <button id="closeSidebar" class="absolute top-4 right-4 p-2 rounded-full hover:bg-white/10 transition animate-pulse" aria-label="Close Sidebar">
          <i data-lucide="x" class="text-white w-6 h-6"></i>
        </button>
      </div><nav class="flex flex-col gap-3 text-white text-base mt-4">
    <a href="/" class="sidebar-link <?php echo $_SERVER['REQUEST_URI'] == '/' ? 'active' : '' ?>" style="--i: 0"><i data-lucide="home"></i>Home</a>
    <a href="/views/story.php" class="sidebar-link <?php echo $_SERVER['REQUEST_URI'] == '/views/story.php' ? 'active' : '' ?>" style="--i: 1"><i data-lucide="file-text"></i>The AU Story</a>
    <a href="/views/gallery.php" class="sidebar-link <?php echo $_SERVER['REQUEST_URI'] == '/gallery' ? 'active' : '' ?>" style="--i: 2"><i data-lucide="image"></i>Style Showcase</a>
    <a href="tel:+2347036973849" class="sidebar-link" style="--i: 3">
  <i data-lucide="phone"></i> Tailor With Us
</a>
  </nav>

  <div class="sidebar-footer absolute bottom-6 left-6 text-sm text-gray-400 space-y-2">
    <p>&copy; <?= date('Y') ?> BEEWHY ENTERPRISE</p>
    <div class="flex gap-3">
      <a href="https://wa.me/2347036973849" target="_blank" aria-label="WhatsApp" class="hover:text-green-400"><i data-lucide="message-circle"></i></a>
      <a href="https://www.facebook.com/profile.php?id=61578764211055" target="_blank" aria-label="Instagram" class="hover:text-pink-400"><i data-lucide="instagram"></i></a>
    </div>
  </div>
</aside>

  </div>