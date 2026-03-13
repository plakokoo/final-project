<?php
session_set_cookie_params(0, '/');
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
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
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


<div class="container mt-4">
  <div class="row">

    <div class="col-md-7">
      <div class="card p-3">

        <h5 class="text-center mb-3">
        <span style="display:inline-block;width:18px;height:12px;background:#e0a04b;border-radius:3px;margin-right:8px;"></span>
        <b>Door Status:</b> Loading...
        </h5>
      <div class="d-flex gap-3 justify-content-center mb-4  ">
        <button class="btn btn-success flex-fill py-3 ">Unlock Door</button>
        <button class="btn btn-danger flex-fill py-3">Lock Door</button>
      </div>
        <h5 class="mt-4">Access History</h5>
        <table class="table">
          <tr>
            <th>Time</th>
            <th>Action</th>
          </tr>
          <tr>
            <td colspan="2" style="text-align: center;">No access history yet</td>
          </tr>
        </table>

      </div>
    </div>

    <div class="col-md-5">
      <div class="card p-3">
       <h5><i class="bi bi-person">  Profile </i></h5>
        <p><b>Name: <?php echo htmlspecialchars($_SESSION['user_name']);?></b></p>
        <p><b>Card ID: <?php echo htmlspecialchars($_SESSION['card_id']);?></b></p>

        <hr>

        <p>
          Welcome! Manage access, monitor activity,
          and control your smart lock system.
        </p>
      </div>
    </div>

  </div>
</div>
  </body>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../js/profile.js"></script>
</html>
