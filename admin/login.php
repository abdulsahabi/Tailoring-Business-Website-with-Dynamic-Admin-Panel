<?php
session_start();

if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']); // Only show once
}
?>

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
  <a href="/" class="flex flex-row gap-2 items-center text-[13px] text-[var(--muted-text)] hover:text-[var(--primary-brown)]">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
      <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
    </svg>
    <span>BACK</span>
  </a>
  <a href="/admin/register.php" class="text-[13px] text-[var(--primary-brown)] font-[PoppinsMedium] hover:underline">SIGN UP</a>
</div>

<!-- Logo -->
<div class="w-full flex items-center justify-center mb-3">
  <img src="../assets/images/logo.jpg" alt="AU Beewhy logo" class="w-[80px] h-[80px] rounded-full">
</div>

<h1 class="mb-[18px] text-[22px] text-center font-[PoppinsSemiBold]">
  Admin Login
</h1>

<!-- Form Starts -->
<form id="loginForm" class="flex flex-col gap-5">
  <div class="status w-full"></div>
  <?php if (!empty($flash)): ?>
    <div class="mb-4 px-4 py-3 rounded text-sm text-white 
        <?= $flash['type'] === 'success' ? 'bg-green-600' : ($flash['type'] === 'warning' ? 'bg-yellow-500' : 'bg-red-600') ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>
  <!-- Email -->
  <div class="input-group relative">
    <label class="absolute top-[6px] label">EMAIL ADDRESS</label>
    <input type="email" name="email" id="email" autofocus class="input w-[90vw] max-w-full sm:w-full border border-[var(--border-color)] p-[10px] pt-[16px] rounded-sm h-[56px] outline-none text-[13px] focus:ring-[var(--primary-yellow)] focus:ring-offset-2 focus:ring-2" autocomplete="off">
    <div id="email_error" class="error text-[12px] text-red-600 mt-1 hidden"></div>
  </div>

  <!-- Password -->
  <div class="input-group relative">
    <label class="absolute top-[6px] label">PASSWORD</label>
    <input type="password" name="password" id="password" class="input w-[90vw] max-w-full sm:w-full border border-[var(--border-color)] p-[10px] pt-[16px] pr-[50px] rounded-sm h-[56px] outline-none text-[13px] focus:ring-[var(--primary-yellow)] focus:ring-offset-2 focus:ring-2" autocomplete="off">
    <div class="absolute right-5 top-5 cursor-pointer toggle-pass" data-target="password"></div>
    <div id="password_error" class="error text-[12px] text-red-600 mt-1 hidden"></div>
  </div>

  <!-- Submit Button -->
  <button type="submit" id="submitBtn" class="button w-[90vw] max-w-full sm:w-full bg-[var(--primary-yellow)] text-black shadow-sm p-[10px] pt-[16px] rounded-sm h-[56px] text-[13px] hover:bg-[#FFB300] transition-all">
    Login
  </button>
</form>

<div class="flex items-center justify-center">
  <a href="/admin/forgot-password.php" class="link text-[13px] my-[28px] ">Forgot Password?</a>
</div>

</div>

<!-- Scripts -->
<script>
const eyeSvg = `
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
    <path d="M10 12.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" />
    <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 0 1 0-1.186A10.004 10.004 0 0 1 10 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0 1 10 17c-4.257 0-7.893-2.66-9.336-6.41ZM14 10a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" clip-rule="evenodd" />
  </svg>
`;

const eyeSlashSvg = `
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
    <path fill-rule="evenodd" d="M3.28 2.22a.75.75 0 0 0-1.06 1.06l14.5 14.5a.75.75 0 1 0 1.06-1.06l-1.745-1.745a10.029 10.029 0 0 0 3.3-4.38 1.651 1.651 0 0 0 0-1.185A10.004 10.004 0 0 0 9.999 3a9.956 9.956 0 0 0-4.744 1.194L3.28 2.22ZM7.752 6.69l1.092 1.092a2.5 2.5 0 0 1 3.374 3.373l1.091 1.092a4 4 0 0 0-5.557-5.557Z" clip-rule="evenodd" />
    <path d="m10.748 13.93 2.523 2.523a9.987 9.987 0 0 1-3.27.547c-4.258 0-7.894-2.66-9.337-6.41a1.651 1.651 0 0 1 0-1.186A10.007 10.007 0 0 1 2.839 6.02L6.07 9.252a4 4 0 0 0 4.678 4.678Z" />
  </svg>
`;

document.querySelectorAll(".toggle-pass").forEach(toggle => {
  toggle.innerHTML = eyeSvg;
  toggle.addEventListener("click", () => {
    const input = document.getElementById(toggle.dataset.target);
    const isPassword = input.type === "password";
    input.type = isPassword ? "text" : "password";
    toggle.innerHTML = isPassword ? eyeSlashSvg : eyeSvg;
  });
});

const delay = (ms) => new Promise(resolve => setTimeout(resolve, ms));

function showToast(message, success = false) {
  const toast = document.createElement("div");
  toast.className = `bottom-5 px-4 py-2 rounded text-sm z-50 text-white shadow-md transition-all duration-300 text-center ${success ? 'bg-green-600' : 'bg-red-600'}`;
  toast.textContent = message;
  let status = document.querySelector('.status')
  status.innerHTML = "";
  status.appendChild(toast)
  setTimeout(() => toast.remove(), 4000);
}

document.getElementById("loginForm").addEventListener("submit", async function (e) {
  e.preventDefault();

  const email = document.getElementById("email");
  const password = document.getElementById("password");
  const submitBtn = document.getElementById("submitBtn");

  // Disable submit button to prevent double clicks
  submitBtn.disabled = true;

  // Show loader
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

  const data = {
    email: email.value.trim(),
    password: password.value,
  };

  // Reset previous errors
  ["email", "password"].forEach(id => {
    const el = document.getElementById(`${id}_error`);
    if (el) {
      el.textContent = "";
      el.classList.add("hidden");
    }
  });

  let hasError = false;
  let focusField = null;

  if (!data.email) {
    email_error.textContent = "Email is required";
    email_error.classList.remove("hidden");
    focusField ??= email;
    hasError = true;
  }

  if (!data.password) {
    password_error.textContent = "Password is required";
    password_error.classList.remove("hidden");
    focusField ??= password;
    hasError = true;
  }

  if (hasError) {
    loader.classList.add("hidden");
    submitBtn.disabled = false;
    if (focusField) focusField.focus();
    return;
  }

  try {
    const res = await fetch("/api/login.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });

    const result = await res.json();
    loader.classList.add("hidden");

    if (!res.ok || !result.success) {
      if (result.errors && typeof result.errors === "object") {
        for (let key in result.errors) {
          const el = document.getElementById(`${key}_error`);
          if (el) {
            el.textContent = result.errors[key] === "Invalid email or password." ? "" : result.errors[key];
            el.classList.remove("hidden");
          }
        }
      }
      
      

      const msg = result.errors?.email || result.errors?.message
      showToast(msg, false);
      submitBtn.disabled = false;
      return;
    }

    // Success
    showToast(result.data?.message || "Login successful!", true);
    setTimeout(() => {
      window.location.href = result.data?.redirect || "/admin/dashboard.php";
    }, 1200);

  } catch (err) {
    console.error("Login Error:", err);
    showToast("Something went wrong. Please try again...", false);
    loader.classList.add("hidden");
    submitBtn.disabled = false;
  } finally {
    // Safety fallback
    loader.classList.add("hidden");
  }
});
</script>
</body>
</html>