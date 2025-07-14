<!DOCTYPE html>
<html lang="en">
<?php require("./views/components/head.php") ?>
<body class="text-[var(--text-color)] font-[PoppinsRegular] bg-[var(--background-color)]">

  <!-- Header 1.1: Top Info Bar -->
  <div class="bg-black text-[12px] text-center text-white py-2 advert">
    Nigeria phone/WhatsApp: <strong>08126421552</strong>
  </div>

  <!-- Header 1.2: Navigation + Hero Image -->
  <header id="mainHeader" class="relative z-40">
    <!-- Navbar -->
    <div id="navbar" class="flex items-center justify-between px-4 sm:px-10 py-4 transition-all absolute top-0 w-full z-50">
      
      <!-- Hamburger -->
      <button id="menuToggle" class="sm:hidden text-white">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>

      <!-- Company Name -->
      <h1 class="text-lg font-semibold text-white ml-[40px]">AU Beewhy</h1>

      <!-- Spacer -->
      <div class="w-6 h-6 sm:hidden"></div>
    </div>

    <!-- Hero Image with Overlay -->
    <div class="relative h-[90vh] overflow-hidden">
      <!-- Slider Background -->
      <div id="heroImage" class="absolute inset-0 bg-cover bg-center transition-all duration-1000"></div>
      <!-- Overlay -->
      <div class="absolute inset-0 bg-gradient-to-br from-[var(--primary-brown)]/60 to-[var(--primary-yellow)]/50"></div>

      <!-- Hero Content -->
      <div class="relative z-10 flex flex-col justify-center items-center h-full px-4 text-center text-white">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-semibold mb-4">Elegant Northern Kaftans</h1>
        <p class="text-sm sm:text-base max-w-md mb-6">Crafted with Culture, Styled with Excellence</p>
        <a href="#collections" class="bg-[var(--primary-yellow)] hover:bg-[#FFB300] text-black px-6 py-3 rounded text-sm font-medium shadow transition">
          View Collections
        </a>
      </div>
    </div>
  </header>

     <div>
      <div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eius consequatur, odio, hic molestiae minus blanditiis incidunt quis doloremque molestias similique quas dolorum illum omnis repudiandae nobis libero consectetur alias! Dolores!</div>
      <div>Aspernatur dolorem vero inventore dolorum maiores ipsam harum? Pariatur eligendi optio excepturi, delectus dolorum voluptatem ipsam placeat sit architecto, sunt rem quod ipsa soluta accusamus modi blanditiis laudantium quaerat doloremque.</div>
      <div>Ea alias, veniam suscipit labore quasi consectetur repellat dolorem voluptatibus, voluptates rem, ratione nemo? Libero fuga sapiente distinctio tempore laudantium unde magni, provident iste doloremque, eos, minus et nihil quas!</div>
      <div>Sunt natus fugit mollitia doloribus, adipisci aliquam quam eos voluptas ducimus provident, quo molestiae laudantium maiores animi ab obcaecati ratione dicta, culpa sed. Eveniet architecto ab hic, iste suscipit, facere?</div>
      <div>Dolores voluptas itaque similique vitae. Deleniti, sit quod nulla necessitatibus molestias velit voluptates quos dicta aperiam, quisquam omnis earum, aliquid autem animi officiis quibusdam commodi. Doloremque expedita, deleniti laboriosam suscipit!</div>
      <div>Veniam quo, reprehenderit architecto dignissimos consequatur rem recusandae dolorum odio praesentium natus atque quam sunt aliquid, odit totam quia inventore rerum quod voluptas dicta consectetur dolor nostrum necessitatibus perspiciatis dolores.</div>
      <div>Voluptatem perferendis ratione quibusdam accusantium, nam error dolorem impedit. Quam, praesentium quasi consectetur quidem alias dolore at totam voluptate expedita, quae, odio inventore nemo quaerat. Nihil laboriosam, beatae perspiciatis minus.</div>
      <div>Molestias perferendis asperiores, explicabo ex illum non quam ipsam minus consequuntur dolorem distinctio, deserunt modi consequatur, debitis est expedita, commodi ipsa nostrum dolore aspernatur! Optio illum ut, aliquid dolore cumque!</div>
      <div>Officia aspernatur, illum ut iusto placeat quas tempore, impedit, magni sunt doloribus fugiat nam ratione cupiditate! Tempore iusto, odit laboriosam, aliquid labore in nesciunt, perferendis molestias, nam at odio iure?</div>
      <div>Odit blanditiis nemo accusantium quod facere delectus iste eveniet atque cum odio? Blanditiis aliquam minima dignissimos voluptatum nam eligendi, quisquam omnis, assumenda cum commodi in architecto ducimus, sequi totam cupiditate!</div>
    </div>
    
  <!-- Sticky Navbar Logic -->
  <script>
    const navbar = document.getElementById("navbar");
    window.addEventListener("scroll", () => {
      if (window.scrollY > 80) {
        navbar.classList.add("sticky", "top-0", "bg-white", "shadow", "text-black");
        navbar.classList.remove("text-white");
      } else {
        navbar.classList.remove("sticky", "top-0", "bg-white", "shadow", "text-black");
        navbar.classList.add("text-white");
      }
    });
  </script>

  <!-- Image Slider Logic -->
  <script>
    const heroImage = document.getElementById("heroImage");
    const heroSlides = [
      "./assets/images/slide1.png",
      "./assets/images/slide2.jpg",
      "./assets/images/slide1.png",
    ];
    let heroIndex = 0;

    function updateHero() {
      heroImage.style.backgroundImage = `url('${heroSlides[heroIndex]}')`;
      heroIndex = (heroIndex + 1) % heroSlides.length;
    }

    updateHero(); // Show first image
    setInterval(updateHero, 3000); // Rotate every 3s
  </script>
</body>
</html>