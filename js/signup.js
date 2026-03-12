document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("#signup-form");
  if (!form) return;

  const submitBtn = document.querySelector("#submitBtn");
  const errorMsg = document.querySelector("#error-message");

  function showError(msg) {
    errorMsg.textContent = msg;
    errorMsg.style.display = "block";
  }

  function clearError() {
    errorMsg.textContent = "";
    errorMsg.style.display = "none";
  }

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    clearError();

    errorMsg.style.display = "none";
    errorMsg.textContent = "";

    const fullname = form.fullname.value.trim();
    const username = form.username.value.trim();
    const password = form.password.value.trim();
    const confirmpassword = form.confirmpassword.value.trim();
    const originalText = submitBtn.textContent;

    if (!fullname || !username || !password || !confirmpassword) {
      showError("All fields are required");
      submitBtn.disabled = false;
      submitBtn.textContent = originalText;
      return;
    }

    if (password !== confirmpassword) {
      showError("Password do not match.");
      submitBtn.disabled = false;
      submitBtn.textContent = originalText;
      return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(username)) {
      showError("Invalid email address.");
      return;
    }

    submitBtn.disabled = true;

    submitBtn.textContent = "Signing up...";

    try {
      const response = await fetch("/Finals/backend/api/signup.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ fullname, username, password, confirmpassword }),
        credentials: "include",
      });

      let result;

      try {
        result = await response.json();
      } catch (e) {
        result = {};
      }

      if (!response.ok || result.success === false) {
        showError(result.message || "Signup failed. Please try again.");
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
        return;
      }

      const toastEl = document.getElementById("successToast");
      const toast = new bootstrap.Toast(toastEl);
      toast.show();

      setTimeout(() => {
        window.location.href = "/Finals/public/login.html";
      }, 1500);
    } catch (err) {
      showError("Network error. Please try again.");
      submitBtn.disabled = false;
      submitBtn.textContent = originalText;
    }
  });
});
