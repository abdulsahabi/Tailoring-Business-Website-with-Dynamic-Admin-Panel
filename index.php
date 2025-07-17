<?php ?><!DOCTYPE html><html lang="en">
<head>
  <!-- Meta Basics -->
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- SEO Meta -->
  <meta name="description" content="AU Idris Fashion – Trendy, elegant, and modest Northern Nigerian fashion for every occasion." />
  <meta name="keywords" content="AU Idris, Northern fashion, kaftans, abayas, men’s styles, bridal outfits, Kebbi Nigeria, custom tailoring" />
  <meta name="author" content="AU Idris Fashion" />
  <meta name="robots" content="index, follow" />
  <link rel="canonical" href="https://afcbeewhy.com/" />
  
  <!-- Page Title -->
  <title>AU Idris Fashion Center</title>

    <!-- Open Graph (Facebook, WhatsApp, etc.) -->
  <meta property="og:type" content="website" />
  <meta property="og:title" content="AU Idris Fashion Center" />
  <meta property="og:description" content="Explore trendy, elegant, and modest designs inspired by Northern culture." />
  <meta property="og:image" content="https://afcbeewhy.com/assets/images/cover.jpg" />
  <meta property="og:url" content="https://afcbeewhy.com" />
  <meta property="og:site_name" content="AU Idris Fashion" />

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="AU Idris Fashion Center" />
  <meta name="twitter:description" content="Elegant Northern styles tailored to perfection." />
  <meta name="twitter:image" content="https://afcbeewhy.com/assets/images/cover.jpg" />

  
  <!-- Favicon -->
  <link rel="icon" href="./assets/images/favicon.ico" type="image/x-icon" />

  
  <!-- TailwindCSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script> 
  <!-- Lucide Icons -->  
  <script src="https://unpkg.com/lucide@latest" defer></script>  
  <!-- Custom CSS & Favicon --> 
  <link rel="stylesheet" href="/assets/css/custom.css" />
  <link rel="icon" type="image/png" href="/assets/images/favicon.png" /> 
  <link href="https://unpkg.com/aos@next/dist/aos.css" rel="stylesheet" />
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>

  <style>
    html { 
      overflow-x: hidden; 
      font-size: 14px; 
    }
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
   .dot {
    width: 20px;
    height: 4px;
    background: rgba(255, 255, 255, 0.2);
    margin: 0 4px;
    border-radius: 2px;
    overflow: hidden;
    position: relative;
  }
  .dot.active {
    background: rgba(250, 204, 21, 0.2);
    box-shadow: 0 0 6px #facc15aa;
  }
  .dot .progress {
    position: absolute;
    height: 100%;
    width: 0%;
    background: #facc15;
    animation: none;
  }
  .dot.active .progress {
    animation: progress 5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
  }
  @keyframes progress {
    from { width: 0%; }
    to { width: 100%; }
  }

  /* Slide and text transitions */
  #slideImage {
    transition: opacity 0.7s ease-in-out, transform 0.7s ease-in-out;
    transform: scale(1.02);
  }
  #slideImage.active {
    opacity: 1;
    transform: scale(1);
  }
  .slide-text {
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.5s ease;
  }
  .slide-text.show {
    opacity: 1;
    transform: translateY(0);
  }
  
  .like-btn svg {
    transition: transform 0.2s ease, color 0.2s ease;
  }
  .like-btn.liked svg {
    color: #ef4444 !important;
  }
  
  @keyframes spin-slow {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.animate-spin-slow {
  animation: spin-slow 2.5s linear infinite;
}

 .scrollbar-hide::-webkit-scrollbar {
    display: none;
  }
  .scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }
  
  @media (max-width: 768px) {
    html {
      font-size: 14px; /* Even smaller on mobile for better scaling */
    }
  }

  body {
    font-size: 0.95rem; /* Slightly smaller than Tailwind's default 1rem (text-base) */
    line-height: 1.6;
  }

  h1, h2, h3, h4 {
    line-height: 1.3;
  }

  h1 {
    font-size: 1.8rem;
  }

  h2 {
    font-size: 1.5rem;
  }

  h3 {
    font-size: 1.25rem;
  }

  p, li {
    font-size: 0.95rem;
  }
  </style>

  </head>
<body class="text-[var(--text-color)] font-[PoppinsRegular] bg-[var(--background-color)]">
  <div class="bg-black/80 backdrop-blur-sm text-white text-sm text-center py-2 flex justify-center items-center gap-2 shadow-sm">
  <i data-lucide="phone-call" class="w-4 h-4 text-yellow-400 animate-pulse"></i>
  <span class="tracking-wide">Call or WhatsApp Us: <strong class="text-yellow-400">+234 703 697 3849</strong></span>
</div>

  <div id="mainContent" class="transition-transform duration-300 relative z-10">
   <?php include("./views/components/header.php")
   ?>
<main class="space-y-16 py-6 px-4 max-w-6xl mx-auto">

  <!-- Hero Section -->
  <section class="relative w-full max-w-[600px] mx-auto aspect-square overflow-hidden rounded-2xl shadow-xl">
    <div id="slider" class="w-full h-full relative">
      <div class="absolute inset-0 bg-black/30 z-10 rounded-2xl"></div>
      <img id="slideImage" src="/assets/images/style1.jpg" alt="Elegant Wear" class="w-full h-full object-cover transition-opacity duration-700 ease-in-out rounded-2xl">
      <div class="absolute bottom-10 w-full z-20 flex flex-col items-center justify-end text-white text-center px-4">
        <h1 id="slideTitle" class="text-xl md:text-2xl font-semibold slide-text">Elegant Wear</h1>
        <p id="slideSubtitle" class="text-sm md:text-base slide-text">Made for graceful moments</p>
      </div>
    </div>
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-30 flex gap-2" id="dots"></div>
  </section>

  <!-- Gallery Section -->
<section class="py-16 px-4 bg-white">
  <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-800 mb-10">
    A Glimpse of Our Style
  </h2>

<div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 max-w-6xl mx-auto items-stretch">
  
    <?php
    $gallery = [
      ['src' => '/assets/images/gallery4.jpg', 'alt' => 'Elegant Brown Kaftan', 'caption' => 'Classic Elegance', 'tag' => 'Men'],
      ['src' => '/assets/images/gallery3.jpg', 'alt' => 'Bridal Embroidery Gown', 'caption' => 'Bridal Kaftan Set', 'tag' => 'Bridal'],
      ['src' => '/assets/images/gallery1.jpg', 'alt' => 'White Gentleman Attire', 'caption' => 'Royal Simplicity', 'tag' => 'Classic'],
      ['src' => '/assets/images/gallery2.jpg', 'alt' => 'Blue Hausa Outfit', 'caption' => 'Northern Touch', 'tag' => 'Cultural'],
    ];
    foreach ($gallery as $index => $item):
    ?>
      <div class="relative group h-[220px] overflow-hidden rounded-xl shadow hover:shadow-lg transition-all duration-300" data-aos="fade-up" data-aos-delay="<?= 100 + ($index * 50) ?>">
        
        <!-- Category Tag -->
        <div class="absolute top-2 left-2 bg-yellow-400 text-black text-xs px-2 py-1 rounded-full font-semibold z-10">
          <?= htmlspecialchars($item['tag']) ?>
        </div>

        <!-- Image -->
        <img loading="lazy" src="<?= htmlspecialchars($item['src']) ?>" alt="<?= htmlspecialchars($item['alt']) ?>"
          class="hover:scale-[1.04] transition-transform duration-500 ease-in-out w-full h-full object-cover" />
        
        <!-- Caption Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-3 text-white font-medium text-sm">
          <?= htmlspecialchars($item['caption']) ?>
        </div>

        <!-- Like Button -->
    <button aria-label="Like this design"
  class="like-btn absolute top-2 right-2 text-white/90 bg-black/40 rounded-full p-1 w-7 h-7 transition">
  <i data-lucide="heart" class="lucide-heart transition duration-300 w-5 h-5"></i>
</button>
</div>
    <?php endforeach; ?>
  </div>

  <div class="text-center mt-10">
    <a href="/views/gallery.php"
      class="inline-flex items-center gap-2 bg-yellow-400 text-black font-semibold py-2 px-6 rounded-full hover:scale-105 hover:bg-yellow-500 transition duration-300 shadow-md">
      Explore More Designs
      <i data-lucide="arrow-right" class="w-4 h-4"></i>
    </a>
  </div>
</section>

  <!-- Why Choose Us -->
  <section class="py-16 px-4 bg-white relative overflow-hidden">
    <h2 class="text-3xl font-bold text-center mb-12">Why Choose Us</h2>
    <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto relative z-10">
      <div class="bg-[#fffdf7] p-6 rounded-2xl shadow-sm hover:shadow-xl transition-shadow duration-300" data-aos="fade-up" data-aos-delay="100">
        <div class="flex justify-center mb-4">
          <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center group transition-transform duration-300">
            <i data-lucide="scissors" class="w-6 h-6 text-yellow-500 group-hover:animate-spin-slow"></i>
          </div>
        </div>
        <h3 class="text-xl font-semibold mb-2 text-center">Expert Tailoring</h3>
        <p class="text-gray-600 text-center">We blend precision and tradition to craft outfits that fit flawlessly and elevate your appearance.</p>
      </div>
      <div class="bg-[#fffdf7] p-6 rounded-2xl shadow-sm hover:shadow-xl transition-shadow duration-300" data-aos="fade-up" data-aos-delay="200">
        <div class="flex justify-center mb-4">
          <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center group transition-transform duration-300">
            <i data-lucide="star" class="w-6 h-6 text-yellow-500 group-hover:animate-spin-slow"></i>
          </div>
        </div>
        <h3 class="text-xl font-semibold mb-2 text-center">Premium Fabrics</h3>
        <p class="text-gray-600 text-center">Only the finest, most elegant fabrics are selected to ensure both comfort and class in every stitch.</p>
      </div>
      <div class="bg-[#fffdf7] p-6 rounded-2xl shadow-sm hover:shadow-xl transition-shadow duration-300" data-aos="fade-up" data-aos-delay="300">
        <div class="flex justify-center mb-4">
          <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center group transition-transform duration-300">
            <i data-lucide="clock" class="w-6 h-6 text-yellow-500 group-hover:animate-spin-slow"></i>
          </div>
        </div>
        <h3 class="text-xl font-semibold mb-2 text-center">Timely Delivery</h3>
        <p class="text-gray-600 text-center">Fast, reliable delivery without compromising craftsmanship — your outfit arrives ready to impress.</p>
      </div>
    </div>
  </section>

  <!-- Testimonials -->
  <section class="bg-white py-16 px-4 md:px-8 lg:px-12 relative">
    <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-800 mb-10">What Our Customers Are Saying</h2>
    <div id="testimonial-slider" class="overflow-x-auto flex space-x-6 snap-x snap-mandatory px-2 md:px-0 scroll-smooth scrollbar-hide">
      <?php
     $testimonials = [
    ['name' => 'Musa K., Abuja', 'text' => 'I’ve never worn a kaftan that fit this perfectly. AU Idris is now my go-to.'],
    ['name' => 'Halima S., Kaduna', 'text' => 'Their styles are modern yet preserve tradition. Beautiful work!'],
    ['name' => 'Aisha Y., Kano', 'text' => 'Exceptional craftsmanship! I feel confident and proud in every outfit.'],
    ['name' => 'Zainab A., Sokoto', 'text' => 'Great service and timely delivery. I’ll definitely order again.'],
    ['name' => 'Ahmed U., Katsina', 'text' => 'The attention to detail is amazing. I highly recommend them.'],
    ['name' => 'Fatima B., Zaria', 'text' => 'I received so many compliments. The design was simply elegant.'],
    ['name' => 'Nasir M., Bauchi', 'text' => 'Comfortable, classy, and well-made. I love their work!'],
    ['name' => 'Maryam S., Minna', 'text' => 'From fabric to finishing, everything was top-notch. Thank you!'],
];
      foreach ($testimonials as $t):
      ?>
        <blockquote class="snap-start shrink-0 w-full sm:w-[22rem] md:w-[28rem] bg-white p-6 rounded-xl shadow-lg border border-gray-200">
          <p class="text-base md:text-lg text-gray-700 italic leading-relaxed mb-4">“<?= htmlspecialchars($t['text']) ?>”</p>
          <footer class="flex items-center justify-end text-sm text-gray-600 font-medium gap-2">
            <i data-lucide="user-round" class="w-4 h-4 text-yellow-500"></i>
            <?= htmlspecialchars($t['name']) ?>
          </footer>
        </blockquote>
      <?php endforeach; ?>
    </div>
    <div class="flex justify-center mt-6 space-x-3" id="testimonial-dots"></div>
  </section>

</main>

<script>
  function toggleLike(button) {
    const icon = button.querySelector('i');
    icon.classList.toggle('text-red-500');
    icon.classList.add('scale-125');
    setTimeout(() => icon.classList.remove('scale-125'), 200);
  }
</script>

  </div>  
 <!-- Footer Section -->
<?php include("./views/components/footer.php") ?>

   <?php include("./views/components/sidebar.php")
   ?>
   
   <script>
  // Auto-update copyright year
  document.getElementById('year').textContent = new Date().getFullYear();
  


  window.addEventListener('DOMContentLoaded', () => {
    // 🔧 Sidebar logic
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
    if (toggleSidebar) toggleSidebar.onclick = openMenu;
    if (closeSidebar) closeSidebar.onclick = closeMenu;
    if (sidebarWrapper) {
      sidebarWrapper.onclick = (e) => {
        if (!sidebarPanel.contains(e.target)) closeMenu();
      };
    }
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeMenu();
    });

    // 🧠 Lucide + AOS
    lucide.createIcons();
    AOS.init({ once: true, duration: 300, easing: 'ease-in-out', offset: 50 });

    // ❤️ Like Button Logic
    document.querySelectorAll('.like-btn').forEach(button => {
      button.addEventListener('click', () => {
        const icon = button.querySelector('i');
        icon.classList.toggle('text-red-500');
        icon.classList.toggle('fill-current');
        button.classList.toggle('liked');
        icon.classList.add('scale-110');
        setTimeout(() => icon.classList.remove('scale-110'), 200);
      });
    });

    // 🎞️ Hero Slide Show
    const slides = [
      { title: "Elegant Wear", subtitle: "Made for graceful moments", image: "/assets/images/style1.jpg" },
      { title: "Classic Kaftan", subtitle: "Timeless and gentle", image: "/assets/images/style2.jpg" },
      { title: "Northern Touch", subtitle: "Simplicity with class", image: "/assets/images/style3.jpg" },
      { title: "Everyday Style", subtitle: "Neat and modest look", image: "/assets/images/style4.jpg" },
    ];
    let currentHero = 0;
    const imageEl = document.getElementById("slideImage");
    const titleEl = document.getElementById("slideTitle");
    const subtitleEl = document.getElementById("slideSubtitle");
    const dotsContainer = document.getElementById("dots");
    const titleText = [titleEl, subtitleEl];

    titleText.forEach(el => el.classList.add("slide-text"));

    slides.forEach((_, i) => {
      const dot = document.createElement("div");
      dot.className = "dot";
      dot.innerHTML = `<div class='progress'></div>`;
      dotsContainer.appendChild(dot);
    });

    const updateHeroSlide = () => {
      const slide = slides[currentHero];
      imageEl.classList.remove("active");
      imageEl.classList.add("opacity-0");
      titleText.forEach(el => el.classList.remove("show"));
      setTimeout(() => {
        imageEl.src = slide.image;
        imageEl.alt = slide.title;
        titleEl.textContent = slide.title;
        subtitleEl.textContent = slide.subtitle;
        imageEl.classList.remove("opacity-0");
        imageEl.classList.add("active");
        setTimeout(() => {
          titleText.forEach((el, idx) =>
            setTimeout(() => el.classList.add("show"), idx * 100));
        }, 100);
      }, 400);

      [...dotsContainer.children].forEach((dot, i) => {
        dot.classList.remove("active");
        const progress = dot.querySelector(".progress");
        progress.style.animation = "none";
        progress.offsetHeight;
        if (i === currentHero) {
          dot.classList.add("active");
          progress.style.animation = "progress 5s cubic-bezier(0.4, 0, 0.2, 1) forwards";
        }
      });
    };

    setInterval(() => {
      currentHero = (currentHero + 1) % slides.length;
      updateHeroSlide();
    }, 5000);
    updateHeroSlide();

    // 💬 Testimonials
    const testimonialSlider = document.getElementById('testimonial-slider');
    const testimonialSlides = testimonialSlider.querySelectorAll('blockquote');
    const testimonialDotsContainer = document.getElementById('testimonial-dots');
    let currentTestimonial = 0;
    let manualScroll = false;

    testimonialDotsContainer.innerHTML = '';
    testimonialSlides.forEach((_, i) => {
      const dot = document.createElement('span');
      dot.className = 'dot w-3 h-3 rounded-full transition-all duration-300 cursor-pointer ' + (i === 0 ? 'bg-gray-400' : 'bg-gray-300');
      dot.addEventListener('click', () => {
        testimonialSlider.scrollTo({
          left: testimonialSlides[i].offsetLeft,
          behavior: 'smooth'
        });
        manualScroll = true;
        setTimeout(() => manualScroll = false, 800);
      });
      testimonialDotsContainer.appendChild(dot);
    });

    function highlightTestimonialDot() {
      const scrollLeft = testimonialSlider.scrollLeft;
      let index = 0;
      testimonialSlides.forEach((slide, i) => {
        if (scrollLeft >= slide.offsetLeft - 50) {
          index = i;
        }
      });

      testimonialDotsContainer.querySelectorAll('span').forEach((dot, i) => {
        dot.classList.toggle('bg-gray-400', i === index);
        dot.classList.toggle('bg-gray-300', i !== index);
      });
      currentTestimonial = index;
    }

    testimonialSlider.addEventListener('scroll', highlightTestimonialDot);
    highlightTestimonialDot();

    setInterval(() => {
      if (manualScroll) return;
      currentTestimonial = (currentTestimonial + 1) % testimonialSlides.length;
      testimonialSlider.scrollTo({
        left: testimonialSlides[currentTestimonial].offsetLeft,
        behavior: 'smooth'
      });
    }, 5000);
    
  // Re-run after Lucide renders
lucide.createIcons();

document.querySelectorAll('.like-btn').forEach(button => {
  button.addEventListener('click', () => {
    const icon = button.querySelector('svg'); // Not <i>, now it's <svg>

    if (!icon) return;

    icon.classList.toggle('text-red-500');
    icon.classList.toggle('fill-current');
    icon.classList.add('scale-110');
    button.classList.toggle('liked');
    setTimeout(() => icon.classList.remove('scale-110'), 200);
  });
});
  });
  
</script>

  </body>
</html>