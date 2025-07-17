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

  <!-- TailwindCSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Custom CSS -->
  <link rel="stylesheet" href="/assets/css/custom.css" />
  
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