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
    localStorage.setItem('sidebarState', 'open');
  };

  const closeSidebar = () => {
    sidebar.classList.remove('open');
    mainContent.classList.remove('shifted', 'blurred');
    backdrop.classList.add('hidden');
    document.body.classList.remove('sidebar-open');
    localStorage.setItem('sidebarState', 'closed');
  };

  // Toggle button
  toggleBtn?.addEventListener('click', () => {
    sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
  });

  closeBtn?.addEventListener('click', closeSidebar);
  backdrop?.addEventListener('click', closeSidebar);

  const savedState = localStorage.getItem('sidebarState');
  const isDesktop = window.innerWidth >= 768;

  if (isDesktop) {
    sidebar.classList.add('open');
    mainContent.classList.add('shifted');
    backdrop.classList.add('hidden');
    document.body.classList.remove('sidebar-open');
  } else {
    savedState === 'open' ? openSidebar() : closeSidebar();
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