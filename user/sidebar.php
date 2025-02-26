<?php include('../../include/session.php'); ?>

<?php
$current_page = basename($_SERVER['PHP_SELF']);

function isActive($pages)
{
  $current_page = basename($_SERVER['PHP_SELF']);
  return in_array($current_page, (array)$pages) ? 'active' : '';
}
?>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <aside class="main-sidebar sidebar-light-primary elevation-4">
      <a href="#" class="brand-link" style="text-align: center; display: flex; align-items: center; justify-content: center;">
        <span class="brand-text" style="font-size: 1.25rem; font-weight: bold; line-height: 1.5; margin-right: 10px;">PDD</span>
        <div class="custom-control custom-switch">
          <input type="checkbox" class="custom-control-input" id="themeSwitch">
          <label class="custom-control-label" for="themeSwitch"></label>
        </div>
      </a>

      <div class="sidebar">
        <div class="user-panel d-flex flex-column align-items-center my-3">
          <div class="info text-center">
            <img src="../../dist/img/sanwa.png" alt="Logo" class="mb-2" style="width: 60px; height: 35px;">
            <a href="#" class="d-block" style="color:  rgba(0, 51, 102, 0.95);">Production Display System</a>
          </div>
        </div>

        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
            <li class="nav-item">
              <a href="../dash/index.php" class="nav-link <?php echo isActive(['index.php', 'line.php', 'machine.php']); ?>">
                <i class="nav-icon fas fa-home"></i>
                <p>Dashboard</p>
              </a>
            </li>

            <!-- Daily Transaction -->
            <li class="nav-item">
              <a href="../daily/daily_tr.php" class="nav-link <?php echo isActive('daily_tr.php'); ?>">
                <i class="nav-icon fas fa-clipboard-list"></i>
                <p>Daily Transaction</p>
              </a>
            </li>

          </ul>
        </nav>
      </div>
    </aside>