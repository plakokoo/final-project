<?php
session_start();

if(!isset($_SESSION['user_id'])) {
  header("Location: ../public/login.html");
  exit();
}


?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SHIELDNET</title>
    <link rel="icon" type="image/x-icon" href="../assets/2.png" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../css/profile.css">
  </head>

  <body>
    <nav class="navbar navbar-expand-lg bg-white px-3 shadow-sm">
      <div class="container-fluid">
        <button class="btn me-2">
          <i class="bi bi-list fs-3"></i>
        </button>

        <a class="navbar-brand d-flex align-items-center" href="index.php">
          <img src="../assets/1.png" alt="Logo" height="80" class="me-2" />
          <span class="fw-bold text-dark">SHIELDNET</span>
        </a>

        <div class="dropdown ms-auto">
          <button
            class="btn btn-light rounded-circle d-flex justify-content-center align-items-center"
            type="button"
            data-bs-toggle="dropdown"
            style="width: 40px; height: 40px"
            id="profileBtn"
          >
            <i class="bi bi-person fs-5"></i>
          </button>

          <ul class="dropdown-menu dropdown-menu-end shadow" id="profileMenu">
            <li>
              <a class="dropdown-item" href="index.php"
                ><i class="bi bi-person me-2"></i>Profile</a
              >
            </li>
            <li><hr class="dropdown-divider" /></li>
            <li>
              <a class="dropdown-item text-danger" href="../backend/api/logout.php" id="logoutBtn"
                ><i class="bi bi-box-arrow-right me-2"></i>Logout</a
              >
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/profile.js"></script>
</html>
