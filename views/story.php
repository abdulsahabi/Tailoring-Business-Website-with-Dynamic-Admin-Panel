<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("./components/head.php") ?>

  <!-- Clean and classy Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Inter:wght@400;500&display=swap" rel="stylesheet">
</head>

<body class="bg-white text-gray-800 font-['Inter']">

  <div id="mainContent" class="py-16 px-6 max-w-4xl mx-auto space-y-14">

    <!-- Back Button -->
    <button onclick="history.back()" 
            class="inline-flex items-center gap-2 text-yellow-600 hover:text-yellow-700 font-medium mb-6 transition">
      <i data-lucide="arrow-left" class="w-5 h-5"></i> Back
    </button>

    <!-- Hero Image -->
    <section class="rounded-xl overflow-hidden shadow-md flex items-center justify-center">
      <img src="/assets/images/new_logo.jpg" alt="Tailored fashion banner" class="h-60 w-[150px] object-cover">
    </section>

    <!-- Page Title -->
    <section class="text-center">
      <h1 class="text-4xl font-['Playfair Display'] font-bold mb-4">Our Story</h1>
      <p class="text-gray-600 max-w-3xl mx-auto leading-relaxed">
        At AU Idris Fashion Center, every outfit is made with care and purpose. From small beginnings to a name trusted for quality, we create clothing that blends tradition with modern style.
      </p>
    </section>

    <!-- Highlighted Quote -->
    <blockquote class="border-l-4 border-yellow-500 pl-4 italic text-gray-600">
      "Every stitch tells a story of pride, culture, and beauty."
    </blockquote>

    <!-- Story Content -->
    <section class="prose prose-lg max-w-none text-gray-700">
      <p>
        We started with a simple idea: to make clothing that respects tradition and feels good to wear. Our focus is on modest fashion, especially kaftans, abayas, and other classic styles from Northern Nigeria.
      </p>

      <p>
        Each piece is handmade by skilled tailors who care deeply about their craft. Whether you're dressing for a special day or everyday life, our clothes are made to help you feel confident and respected.
      </p>

      <p>
        As we grow, our goal stays the same—offering high-quality, stylish, and authentic clothing that honors your culture and personality.
      </p>

      <p class="mt-8 font-semibold">
        Thank you for being part of our journey.
      </p>
    </section>

    <!-- Meet the Tailors -->
    <section class="text-center pt-10">
      <h2 class="text-2xl font-semibold mb-4">Made by Experts</h2>
      <p class="text-gray-600 leading-relaxed max-w-2xl mx-auto">
        Our tailors have years of experience. They put heart and skill into every outfit. You’ll feel the difference in every detail.
      </p>
    </section>

  </div>

  <?php include("./components/footer.php") ?>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    lucide.createIcons();
  </script>
</body>
</html>