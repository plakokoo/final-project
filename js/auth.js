document.addEventListener("DOMContentLoaded", () => {
  const rmCheck = document.getElementById("rememberMe");
  const emailInput = document.getElementById("username");
  const form = document.querySelector("#login-form");
  if (!form) return;

  const savedUsername = localStorage.getItem("username");
  const savedCheckBox = localStorage.getItem("checkbox");

  if (savedCheckBox == "true" && savedUsername) {
    rmCheck.checked = true;
    emailInput.value = savedUsername;
  } else {
    rmCheck.checked = false;
    emailInput.value = "";
  }

  const submitBtn = document.querySelector("#submitBtn");
  const errorMsg = document.querySelector("#error-message");
  let lockoutTimer = null;

  function formatTime(seconds) {
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    return `${m}m ${s}s`;
  }

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    errorMsg.style.display = "none";
    submitBtn.disabled = true;
    submitBtn.textContent = "Logging in...";

    if (rmCheck.checked && emailInput.value !== "") {
      localStorage.setItem("username", emailInput.value);
      localStorage.setItem("checkbox", "true");
    } else {
      localStorage.removeItem("username");
      localStorage.setItem("checkbox", "false");
    }
    const data = {
      username: form.username.value.trim(),
      password: form.password.value.trim(),
    };

    try {
      const response = await fetch("/Finals/backend/api/login.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
        credentials: "include",
      });

      if (!response.ok) {
        throw new Error("Server error: " + response.status);
      }

      const result = await response.json();

      if (result.success) {
        window.location.href = "/Finals/pages/index.php";
        return;
      }

      if (result.lockout && result.remaining_seconds > 0) {
        let time = result.remaining_seconds;

        errorMsg.textContent = `Too many login attempts. Try again in ${formatTime(time)}.`;
        errorMsg.style.display = "block";

        submitBtn.disabled = true;

        if (lockoutTimer) clearInterval(lockoutTimer);

        lockoutTimer = setInterval(() => {
          time--;

          if (time <= 0) {
            clearInterval(lockoutTimer);
            submitBtn.disabled = false;
            submitBtn.textContent = "SIGN IN";
            errorMsg.style.display = "none";
          } else {
            submitBtn.textContent = `Locked (${formatTime(time)})`;
          }
        }, 1000);

        return;
      }

      errorMsg.textContent = result.message || "Login failed";
      errorMsg.style.display = "block";
      submitBtn.disabled = false;
      submitBtn.textContent = "SIGN IN";
    } catch (error) {
      errorMsg.textContent = error.message;
      errorMsg.style.display = "block";
      submitBtn.disabled = false;
      submitBtn.textContent = "SIGN IN";
    }
  });
});
