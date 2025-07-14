<!DOCTYPE html>
<html lang="en">
<?php require("../views/components/head.php") ?>
<body class="bg-[var(--background-color)] text-[var(--text-color)] font-[PoppinsRegular]">
  <div class="w-full max-w-sm sm:max-w-md m-auto my-[80px] px-4">

    <!-- Header -->
    <h1 class="mb-[18px] text-[22px] text-center font-[PoppinsSemiBold]">
      Enter Verification Code
    </h1>
    <p class="text-center text-[13px] mb-5 text-[var(--muted-text)]">
      A 6-digit code was sent to your email.
    </p>

    <!-- Form -->
    <form id="verifyForm" class="flex flex-col gap-5">
      <div class="status w-full"></div>
      <input type="hidden" id="email">
      <input type="hidden" id="code">

      <!-- OTP Inputs -->
      <div id="otp-inputs" class="flex justify-between gap-2">
        <input type="text" inputmode="numeric" maxlength="1" class="otp-box" />
        <input type="text" inputmode="numeric" maxlength="1" class="otp-box" />
        <input type="text" inputmode="numeric" maxlength="1" class="otp-box" />
        <input type="text" inputmode="numeric" maxlength="1" class="otp-box" />
        <input type="text" inputmode="numeric" maxlength="1" class="otp-box" />
        <input type="text" inputmode="numeric" maxlength="1" class="otp-box" />
      </div>
      <div id="code_error" class="error text-[12px] text-red-600 mt-1 hidden"></div>

      <!-- Submit Button -->
      <button type="submit" id="submitBtn" class="button w-full bg-[var(--primary-yellow)] text-black shadow-sm p-[10px] pt-[16px] rounded-sm h-[56px] text-[13px] hover:bg-[#FFB300] transition-all">
        Verify Code
      </button>

      <!-- Resend -->
      <p class="text-[13px] mt-3 text-center text-[var(--muted-text)]">
        Didn’t get the code? 
        <button type="button" id="resendBtn" class="text-[var(--primary-yellow)] font-medium hover:underline" disabled>
          Resend in <span id="timer">30</span>s
        </button>
      </p>
    </form>
  </div>

  <style>
    .otp-box {
      width: 42px;
      height: 56px;
      text-align: center;
      font-size: 18px;
      border-radius: 0.25rem;
      border: 1px solid var(--border-color);
      outline: none;
    }

    .otp-box:focus {
      border-color: var(--primary-yellow);
      box-shadow: 0 0 0 2px var(--primary-yellow);
    }
  </style>

  <script>
    const delay = ms => new Promise(resolve => setTimeout(resolve, ms));

    // Toast System
    let toastContainer = document.querySelector(".toast-container");
    if (!toastContainer) {
      toastContainer = document.createElement("div");
      toastContainer.className = "toast-container fixed bottom-5 left-1/2 transform -translate-x-1/2 z-50";
      document.body.appendChild(toastContainer);
    }

    function showToast(message, success = false) {
      toastContainer.innerHTML = ""; // Clear previous toasts
      const toast = document.createElement("div");
      toast.className = `my-1 px-4 py-2 rounded text-sm text-white shadow-md transition-opacity duration-300 ${success ? 'bg-green-600' : 'bg-red-600'}`;
      toast.textContent = message;
      toastContainer.appendChild(toast);
      setTimeout(() => toast.remove(), 4000);
    }

    // Email check
    const params = new URLSearchParams(window.location.search);
    const emailParam = params.get("email");
    const emailInput = document.getElementById("email");
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailParam || !emailRegex.test(emailParam)) {
      window.location.href = "/admin/forgot-password.php";
    } else {
      emailInput.value = emailParam;
    }

    // OTP Input Logic
    const otpBoxes = document.querySelectorAll('.otp-box');
    const hiddenCodeInput = document.getElementById("code");

    otpBoxes.forEach((box, idx) => {
      box.addEventListener('input', () => {
        if (box.value.length === 1 && idx < otpBoxes.length - 1) {
          otpBoxes[idx + 1].focus();
        }
        updateHiddenCode();
      });

      box.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !box.value && idx > 0) {
          otpBoxes[idx - 1].focus();
        }
      });
    });

    function updateHiddenCode() {
  hiddenCodeInput.value = [...otpBoxes].map(b => b.value).join('');
  if (hiddenCodeInput.value.length === 6 && /^\d{6}$/.test(hiddenCodeInput.value)) {
    document.getElementById("verifyForm").requestSubmit(); // triggers submit event
  }
}

    // Paste full 6-digit code
    otpBoxes[0].parentElement.addEventListener("paste", (e) => {
      e.preventDefault();
      const pasted = (e.clipboardData || window.clipboardData).getData("text").trim();
      if (!/^\d{6}$/.test(pasted)) return;

      [...pasted].forEach((char, idx) => {
        if (otpBoxes[idx]) otpBoxes[idx].value = char;
      });

      updateHiddenCode();
      otpBoxes[5].focus();
      showToast("Code pasted from clipboard", true);
    });

    // Resend Timer
    function startResendTimer(duration = 30) {
      const resendBtn = document.getElementById("resendBtn");
      const timerSpan = document.getElementById("timer");
      resendBtn.disabled = true;
      let timeLeft = duration;

      const interval = setInterval(() => {
        timeLeft--;
        timerSpan.textContent = timeLeft;
        if (timeLeft <= 0) {
          clearInterval(interval);
          resendBtn.disabled = false;
          resendBtn.innerHTML = "Resend Code";
        }
      }, 1000);
    }

    startResendTimer();

    document.getElementById("resendBtn").addEventListener("click", async () => {
      try {
        const res = await fetch("/api/resend-code.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ email: emailInput.value })
        });

        const result = await res.json();
        if (!res.ok || !result.success) {
          showToast(result.message || "Failed to resend code");
          return;
        }

        showToast("Verification code resent!", true);
        document.getElementById("resendBtn").innerHTML = `Resend in <span id="timer">30</span>s`;
        startResendTimer();
      } catch (err) {
        showToast("Error resending code");
        console.error(err);
      }
    });

    // Form submission
    document.getElementById("verifyForm").addEventListener("submit", async function (e) {
      e.preventDefault();

      const code = hiddenCodeInput.value;
      const email = emailInput.value;
      const codeError = document.getElementById("code_error");

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

      codeError.textContent = "";
      codeError.classList.add("hidden");

      if (!/^\d{6}$/.test(code)) {
        codeError.textContent = "Please enter a valid 6-digit code";
        codeError.classList.remove("hidden");
        otpBoxes[0].focus();
        loader.classList.add("hidden");
        return;
      }

      try {
        const res = await fetch("/api/verify-code.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ email, code })
        });

        await delay(1000);
        const result = await res.json();
        loader.classList.add("hidden");

        if (!res.ok || !result.success) {
          codeError.textContent = result.message || "Invalid or expired code";
          codeError.classList.remove("hidden");
          otpBoxes[0].focus();
          return;
        }

        showToast("Code verified successfully!", true);

        setTimeout(() => {
          window.location.href = `/admin/reset-password.php?email=${encodeURIComponent(email)}&code=${encodeURIComponent(code)}`;
        }, 1000);

      } catch (err) {
        loader.classList.add("hidden");
        showToast("Something went wrong.");
        console.error(err);
      }
    });
  </script>
</body>
</html>