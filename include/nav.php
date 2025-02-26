<nav class="main-header navbar navbar-expand navbar-white navbar-light shadow-sm">
  <!-- Left navbar links -->
  <ul class="navbar-nav align-items-center">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="dash.php" class="nav-link  text-dark">Dashboard</a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto align-items-center">
    <!-- Navbar Search -->
    <li class="nav-item">
      <a class="nav-link" data-widget="navbar-search" href="#" role="button">
        <i class="fas fa-search text-secondary"></i>
      </a>
      <div class="navbar-search-block p-2 rounded shadow">
        <form class="form-inline">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar border-primary" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-primary" type="submit">
                <i class="fas fa-search"></i>
              </button>
              <button class="btn btn-danger" type="button" data-widget="navbar-search">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </li>

    <!-- Fullscreen -->
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt text-secondary"></i>
      </a>
    </li>

    <!-- User Profile and Logout -->
    <li class="nav-item dropdown">
      <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#">
        <img src="<?php echo isset($_SESSION['profile_picture']) && file_exists($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : '../../dist/img/profile.png'; ?>" class="img-circle border border-primary" alt="User Avatar" style="width: 35px; height: 35px; object-fit: cover; margin-right: 8px;">
        <span id="user-name" class="text-dark font-weight"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
      </a>

      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow-lg border-0 rounded">
        <div class="dropdown-header text-center bg-primary text-white font-weight-bold py-2">
          Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
        </div>
        <a href="profile.php" class="dropdown-item">
          <i class="fas fa-user mr-2 text-primary"></i> Profile
        </a>
        <a href="change_password.php" class="dropdown-item">
          <i class="fas fa-key mr-2 text-warning"></i> Change Password
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item text-danger font-weight-bold" onclick="confirmLogout()">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
      </div>
    </li>
  </ul>
</nav>

<script>
  function confirmLogout() {
    Swal.fire({
      title: 'Are you sure you want to log out?',
      text: "Make sure you have saved your work.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: '<i class="fas fa-check-circle"></i> Yes, log me out!',
      cancelButtonText: '<i class="fas fa-times-circle"></i> Cancel',
      showClass: {
        popup: 'animate__animated animate__fadeInDown'
      },
      hideClass: {
        popup: 'animate__animated animate__fadeOutUp'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Logging out...',
          text: 'Redirecting you to login page.',
          icon: 'success',
          timer: 2000,
          showConfirmButton: false
        });
        setTimeout(() => {
          window.location.href = '../../login/logout.php';
        }, 2000);
      }
    });
  }
</script>