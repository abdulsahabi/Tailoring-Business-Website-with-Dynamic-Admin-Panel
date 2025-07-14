<!DOCTYPE html><html lang="en">
<?php require("../views/components/head.php") ?>
<body class="bg-[var(--background-color)] text-[var(--text-color)] font-[PoppinsRegular]">
  <div class="w-full max-w-sm sm:max-w-md m-auto my-10 px-4"><!-- Header Navigation -->
<div class="flex flex-row justify-between items-center mb-4">
  <a href="/" class="flex flex-row gap-2 items-center text-[13px] text-[var(--muted-text)] hover:text-[var(--primary-brown)]">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
      <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
    </svg>
    <span>BACK</span>
  </a>
  <a href="/admin/login.php" class="text-[13px] text-[var(--primary-brown)] font-[PoppinsMedium] hover:underline">LOGIN</a>
</div>


<div class="w-full flex items-center justify-center mb-3">
  <img src="../assets/images/logo.png" alt="AU Beewhy logo" class="w-[60px] h-[60px]">
</div>


<h1 class="mb-[18px] text-[22px] text-center font-[PoppinsSemiBold]">
  Create Admin Account
</h1>
<!-- Form Starts -->
<form id="registerForm" class="flex flex-col gap-5">
  
<!-- Server status -->
<div class="status w-full"></div>
  <!-- Full Name -->
  <div class="input-group relative">
    <label class="absolute top-[6px] label">FULL NAME</label>
    <input type="text" name="full_name" id="full_name" autofocus class="input w-[90vw] max-w-full sm:w-full border border-[var(--border-color)] p-[10px] pt-[16px] rounded-sm h-[56px] outline-none text-[13px] focus:ring-[var(--primary-yellow)] focus:ring-offset-2 focus:ring-2" autocomplete="off">
    <div id="full_error" class="error text-[12px] text-red-600 mt-1 hidden"></div>
  </div>

  <!-- Email -->
  <div class="input-group relative">
    <label class="absolute top-[6px] label">EMAIL ADDRESS</label>
    <input type="text" name="email" id="email" class="input w-[90vw] max-w-full sm:w-full border border-[var(--border-color)] p-[10px] pt-[16px] rounded-sm h-[56px] outline-none text-[13px] focus:ring-[var(--primary-yellow)] focus:ring-offset-2 focus:ring-2" autocomplete="off">
    <div id="email_error" class="error text-[12px] text-red-600 mt-1 hidden"></div>
  </div>

  <!-- Password -->
  <div class="input-group relative">
    <label class="absolute top-[6px] label">PASSWORD</label>
    <input type="password" name="password" id="password" class="input w-[90vw] max-w-full sm:w-full border border-[var(--border-color)] p-[10px] pt-[16px] pr-[50px] rounded-sm h-[56px] outline-none text-[13px] focus:ring-[var(--primary-yellow)] focus:ring-offset-2 focus:ring-2" autocomplete="off">
    <div class="absolute right-5 top-5 cursor-pointer toggle-pass" data-target="password"></div>
    <div id="password_error" class="error text-[12px] text-red-600 mt-1 hidden"></div>

    <!-- Strength bar -->
    <div id="password_strength_bar" class="h-[6px] w-full bg-gray-200 rounded mt-2 overflow-hidden hidden">
      <div id="password_strength_fill" class="h-full transition-all duration-300 ease-in-out"></div>
    </div>
    <div class="text-[11px] mt-2 text-[var(--muted-text)] leading-tight">
      Must be at least 8 characters, include uppercase, lowercase, number, and symbol.
    </div>
  </div>

  <!-- Confirm Password -->
  <div class="input-group relative">
    <label class="absolute top-[6px] label">CONFIRM PASSWORD</label>
    <input type="password" name="cpassword" id="cpassword" class="input w-[90vw] max-w-full sm:w-full border border-[var(--border-color)] p-[10px] pt-[16px] pr-[50px] rounded-sm h-[56px] outline-none text-[13px] focus:ring-[var(--primary-yellow)] focus:ring-offset-2 focus:ring-2" autocomplete="off">
    <div class="absolute right-5 top-5 cursor-pointer toggle-pass" data-target="cpassword"></div>
    <div id="cpassword_error" class="error text-[12px] text-red-600 mt-1 hidden"></div>
  </div>

  <!-- Token -->
  <div class="input-group relative">
    <label class="absolute top-[6px] label">TOKEN</label>
    <input type="text" name="token" id="token" class="input w-[90vw] max-w-full sm:w-full border border-[var(--border-color)] p-[10px] pt-[16px] rounded-sm h-[56px] outline-none text-[13px] focus:ring-[var(--primary-yellow)] focus:ring-offset-2 focus:ring-2" autocomplete="off">
    <div id="token_error" class="error text-[12px] text-red-600 mt-1 hidden"></div>
  </div>

  <!-- Submit Button -->
  <button type="submit" id="submitBtn" class="button w-[90vw] max-w-full sm:w-full bg-[var(--primary-yellow)] text-black shadow-sm p-[10px] pt-[16px] rounded-sm h-[56px] text-[13px] hover:bg-[#FFB300] transition-all">
    Sign up
  </button>
</form>

  </div>  
  
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

  // Password strength logic
  const passwordInput = document.getElementById("password");
  const strengthBar = document.getElementById("password_strength_bar");
  const strengthFill = document.getElementById("password_strength_fill");

  passwordInput.addEventListener("input", () => {
    const val = passwordInput.value;
    let score = 0;

    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[a-z]/.test(val)) score++;
    if (/\d/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    strengthBar.classList.remove("hidden");

    if (score <= 2) {
      strengthFill.className = "h-full w-1/3 bg-red-500";
    } else if (score === 3 || score === 4) {
      strengthFill.className = "h-full w-2/3 bg-yellow-500";
    } else {
      strengthFill.className = "h-full w-full bg-green-500";
    }
  });

  const delay = (ms) => new Promise(resolve => setTimeout(resolve, ms));

  // Toast message creator
  function showToast(message, success = false) {
    const toast = document.createElement("div");
    toast.className = ` bottom-5 px-4 py-2 rounded text-sm z-50 text-white shadow-md transition-all duration-300 ${success ? 'bg-green-600' : 'bg-red-600'}`;
    toast.textContent = message;
    document.querySelector('.status').appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
  }

  document.getElementById("registerForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const full_name = document.getElementById("full_name");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const cpassword = document.getElementById("cpassword");
    const token = document.getElementById("token");
    const submitBtn = document.getElementById("submitBtn");

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
      full_name: full_name.value.trim(),
      email: email.value.trim(),
      password: password.value,
      cpassword: cpassword.value,
      token: token.value.trim(),
    };

    ["full", "email", "password", "cpassword", "token"].forEach(id => {
      const el = document.getElementById(`${id}_error`);
      if (el) {
        el.textContent = "";
        el.classList.add("hidden");
      }
    });

    let hasError = false;
    let focusField = null;

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const strongPasswordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}$/;

    if (!data.full_name) {
      full_error.textContent = "Full name is required";
      full_error.classList.remove("hidden");
      focusField ??= full_name;
      hasError = true;
    }
    if (!data.email || !emailRegex.test(data.email)) {
      email_error.textContent = "Valid email is required";
      email_error.classList.remove("hidden");
      focusField ??= email;
      hasError = true;
    }
    if (!data.password) {
      password_error.textContent = "Password is required";
      password_error.classList.remove("hidden");
      focusField ??= password;
      hasError = true;
    } else if (!strongPasswordRegex.test(data.password)) {
      password_error.textContent = "Password must include uppercase, lowercase, number & special character";
      password_error.classList.remove("hidden");
      focusField ??= password;
      hasError = true;
    }
    if (data.password !== data.cpassword) {
      cpassword_error.textContent = "Passwords do not match";
      cpassword_error.classList.remove("hidden");
      focusField ??= cpassword;
      hasError = true;
    }
    if (!data.token || data.token.length < 6) {
      token_error.textContent = "Valid token is required";
      token_error.classList.remove("hidden");
      focusField ??= token;
      hasError = true;
    }

    if (hasError) {
      loader.classList.add("hidden");
      if (focusField) focusField.focus();
      return;
    }

    try {
      const res = await fetch("/api/register.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });

      await delay(1500);
      const result = await res.json();
      loader.classList.add("hidden");

      if (!res.ok && result.errors) {
        for (let key in result.errors) {
          const el = document.getElementById(`${key}_error`);
          if (el) {
            el.textContent = result.errors[key];
            el.classList.remove("hidden");
          }
        }
        showToast("Registration failed. Check inputs again.");
        return;
      }

      showToast("Account created successfully!", true);
      setTimeout(() => {
        window.location.href = "/admin/login.php";
      }, 1500);
    } catch (err) {
      loader.classList.add("hidden");
      showToast("Failed to register. Please try again later.");
      console.error(err);
    }
  });
</script>
  </body>
</html>