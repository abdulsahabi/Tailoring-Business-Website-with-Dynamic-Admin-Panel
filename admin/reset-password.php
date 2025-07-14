<!DOCTYPE html>
<html lang="en">
<?php require("../views/components/head.php") ?>
<body class="bg-[var(--background-color)] text-[var(--text-color)] font-[PoppinsRegular] relative">

  <div class="w-full max-w-sm sm:max-w-md m-auto my-10 px-4">

    <!-- Header -->
    <h1 class="mb-[18px] text-[22px] text-center font-[PoppinsSemiBold]">Reset Your Password</h1>
    <p class="text-center text-[13px] mb-5 text-[var(--muted-text)]">
      Enter and confirm your new password.
    </p>

    <form id="resetForm" class="flex flex-col gap-5">
      <div class="status w-full"></div>
      <input type="hidden" id="email">
      <input type="hidden" id="code">

      <!-- New Password -->
      <div class="input-group relative">
        <label class="absolute top-[6px] label">NEW PASSWORD</label>
        <input type="password" id="password" class="input w-full border border-[var(--border-color)] p-[10px] pt-[16px] pr-[50px] rounded-sm h-[56px] text-[13px] outline-none focus:ring-2 focus:ring-[var(--primary-yellow)] ring-offset-2" autocomplete="off">
        <div class="absolute right-5 top-5 cursor-pointer toggle-pass" data-target="password"></div>
        <div id="password_error" class="error text-[12px] text-red-600 mt-1 hidden"></div>

        <!-- Strength Meter -->
        <div id="password_strength_bar" class="h-[6px] w-full bg-gray-200 rounded mt-2 overflow-hidden hidden">
          <div id="password_strength_fill" class="h-full transition-all duration-300 ease-in-out"></div>
        </div>
        <div class="text-[11px] mt-2 text-[var(--muted-text)]">
          Must be at least 8 characters, include uppercase, lowercase, number, and symbol.
        </div>
      </div>

      <!-- Confirm Password -->
      <div class="input-group relative">
        <label class="absolute top-[6px] label">CONFIRM PASSWORD</label>
        <input type="password" id="cpassword" class="input w-full border border-[var(--border-color)] p-[10px] pt-[16px] pr-[50px] rounded-sm h-[56px] text-[13px] outline-none focus:ring-2 focus:ring-[var(--primary-yellow)] ring-offset-2" autocomplete="off">
        <div class="absolute right-5 top-5 cursor-pointer toggle-pass" data-target="cpassword"></div>
        <div id="cpassword_error" class="error text-[12px] text-red-600 mt-1 hidden"></div>
      </div>

      <button type="submit" id="submitBtn" class="button w-full bg-[var(--primary-yellow)] text-black shadow-sm p-[10px] pt-[16px] rounded-sm h-[56px] text-[13px] hover:bg-[#FFB300] transition-all">
        Reset Password
      </button>
    </form>
  </div>

  <!-- Toast Container -->
  <div class="toast-container fixed bottom-5 left-1/2 transform -translate-x-1/2 z-50"></div>

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


    // URL Params
    const emailInput = document.getElementById("email");
    const codeInput = document.getElementById("code");

    const passwordInput = document.getElementById("password");
    const cpasswordInput = document.getElementById("cpassword");

    const password_error = document.getElementById("password_error");
    const cpassword_error = document.getElementById("cpassword_error");

    const strengthBar = document.getElementById("password_strength_bar");
    const strengthFill = document.getElementById("password_strength_fill");

    const params = new URLSearchParams(window.location.search);
    const email = params.get("email");
    const code = params.get("code");

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const codeRegex = /^\d{6}$/;

    if (!email || !code || !emailRegex.test(email) || !codeRegex.test(code)) {
      window.location.href = "/admin/forgot-password.php";
    } else {
      emailInput.value = email;
      codeInput.value = code;
    }

    // Show/hide password
    document.querySelectorAll(".toggle-pass").forEach(toggle => {
      toggle.innerHTML = eyeSvg;
      toggle.addEventListener("click", () => {
        const input = document.getElementById(toggle.dataset.target);
        const isPass = input.type === "password";
        input.type = isPass ? "text" : "password";
        toggle.innerHTML = isPass ? eyeSlashSvg : eyeSvg;
      });
    });

    // Password strength
    passwordInput.addEventListener("input", () => {
      const val = passwordInput.value;
      let score = 0;
      if (val.length >= 8) score++;
      if (/[A-Z]/.test(val)) score++;
      if (/[a-z]/.test(val)) score++;
      if (/\d/.test(val)) score++;
      if (/[^A-Za-z0-9]/.test(val)) score++;

      strengthBar.classList.remove("hidden");
      strengthFill.className = score <= 2
        ? "h-full w-1/3 bg-red-500"
        : score <= 4
          ? "h-full w-2/3 bg-yellow-500"
          : "h-full w-full bg-green-500";
    });

    // Toast
    function showToast(message, success = false) {
      const toast = document.createElement("div");
      toast.className = `my-2 px-4 py-2 rounded text-sm text-white shadow-md transition-all duration-300 ${success ? 'bg-green-600' : 'bg-red-600'}`;
      toast.textContent = message;
      document.querySelector('.toast-container').innerHTML = ""
      document.querySelector('.toast-container').appendChild(toast);
      setTimeout(() => toast.remove(), 4000);
    }

    // Loader delay
    function delay(ms) {
      return new Promise(resolve => setTimeout(resolve, ms));
    }

    // Submit handler
    document.getElementById("resetForm").addEventListener("submit", async function (e) {
      e.preventDefault();

      const password = passwordInput.value;
      const cpassword = cpasswordInput.value;

      password_error.classList.add("hidden");
      cpassword_error.classList.add("hidden");
      password_error.textContent = "";
      cpassword_error.textContent = "";

      let hasError = false;
      let focusField = null;

      const strongPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}$/;

      if (!strongPassword.test(password)) {
        password_error.textContent = "Password must meet complexity requirements.";
        password_error.classList.remove("hidden");
        focusField = passwordInput;
        hasError = true;
      }

      if (password !== cpassword) {
        cpassword_error.textContent = "Passwords do not match";
        cpassword_error.classList.remove("hidden");
        focusField ??= cpasswordInput;
        hasError = true;
      }

      if (hasError) {
        focusField?.focus();
        return;
      }

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

      try {
        const res = await fetch("/api/reset-password.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ email, code, password })
        });

        await delay(1000);
        const result = await res.json();
        loader.classList.add("hidden");

        if (!res.ok || !result.success) {
          showToast(result.message || "Failed to reset password");
          return;
        }

        showToast("Password reset successful!", true);
        setTimeout(() => {
          window.location.href = "/admin/login.php";
        }, 1500);
      } catch (err) {
        loader.classList.add("hidden");
        showToast("Something went wrong");
        console.error(err);
      }
    });
  </script>
</body>
</html>