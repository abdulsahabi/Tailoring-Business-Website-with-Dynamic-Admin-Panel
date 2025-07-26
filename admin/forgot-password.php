<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
trackPageView(); // Auto-detects route

?>



<!DOCTYPE html><html lang="en">
<?php require("../views/components/head.php") ?>
<body class="bg-[var(--background-color)] text-[var(--text-color)] font-[PoppinsRegular]">
  <div class="w-full max-w-sm sm:max-w-md m-auto my-10 px-4">

<!-- Header Navigation -->
<div class="flex flex-row justify-between items-center mb-4">
  <a href="/admin/login.php" class="flex flex-row gap-2 items-center text-[13px] text-[var(--muted-text)] hover:text-[var(--primary-brown)]">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
      <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
    </svg>
    <span>BACK</span>
  </a>
</div>

<h1 class="mb-[18px] text-[22px] text-center font-[PoppinsSemiBold]">
  Forgot Password 
</h1>

<p class="text-center text-[13px] mb-5 text-[var(--muted-text)]">Enter your email and we'll send you a verification code</p>

<!-- Form Starts -->
<form id="forgotForm" class="flex flex-col gap-5">
  <div class="status w-full"></div>

  <!-- Email -->
  <div class="input-group relative">
    <label class="absolute top-[6px] label">EMAIL ADDRESS</label>
    <input type="email" name="email" id="email" autofocus class="input w-[90vw] max-w-full sm:w-full border border-[var(--border-color)] p-[10px] pt-[16px] rounded-sm h-[56px] outline-none text-[13px] focus:ring-[var(--primary-yellow)] focus:ring-offset-2 focus:ring-2" autocomplete="off">
    <div id="email_error" class="error text-[12px] text-red-600 mt-1 hidden"></div>
  </div>

  <!-- Submit Button -->
  <button type="submit" id="submitBtn" class="button w-[90vw] max-w-full sm:w-full bg-[var(--primary-yellow)] text-black shadow-sm p-[10px] pt-[16px] rounded-sm h-[56px] text-[13px] hover:bg-[#FFB300] transition-all">
    Send Code
  </button>
</form>

</div>

<!-- Scripts -->
<script>
const delay = (ms) => new Promise(resolve => setTimeout(resolve, ms));

function showToast(message, success = false) {
  const toast = document.createElement("div");
  toast.className = `bottom-5 px-4 py-2 rounded text-sm z-50 text-white shadow-md transition-all duration-300 ${success ? 'bg-green-600' : 'bg-red-600'}`;
  toast.textContent = message;
  const statusDiv = document.querySelector('.status');
  statusDiv.innerHTML = "";
  statusDiv.appendChild(toast);
  setTimeout(() => toast.remove(), 4000);
}

document.getElementById("forgotForm").addEventListener("submit", async function (e) {
  e.preventDefault();

  const email = document.getElementById("email");
  const emailError = document.getElementById("email_error");
  emailError.textContent = "";
  emailError.classList.add("hidden");

  let loader = document.getElementById("loader");
  if (!loader) {
    loader = document.createElement("div");
    loader.id = "loader";
    loader.className = "fixed inset-0 bg-black/60 flex items-center justify-center z-50";
    loader.innerHTML = `<div class="w-12 h-12 border-4 border-yellow-400 border-t-transparent rounded-full animate-spin"></div>`;
    document.body.appendChild(loader);
  } else {
    loader.classList.remove("hidden");
  }

  const data = { email: email.value.trim() };

  if (!data.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email)) {
    emailError.textContent = "Enter a valid email";
    emailError.classList.remove("hidden");
    email.focus();
    loader.classList.add("hidden");
    return;
  }

  try {
    const res = await fetch("/api/forgot-password.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });

    const result = await res.json();
    loader.classList.add("hidden");

    if (!res.ok || !result.success) {
      if (result.errors?.email) {
        emailError.textContent = result.errors.email;
        emailError.classList.remove("hidden");
        email.focus();
      }
      
      return;
    }

    showToast("Verification code sent!", true);

    // Redirect to verification page (code is not included in URL!)
    window.location.href = `/admin/verify-reset.php?email=${encodeURIComponent(data.email)}`;

  } catch (err) {
    loader.classList.add("hidden");
    showToast("Something went wrong. Please try again.");
    console.error(err);
  }
});
</script>
</body>
</html>