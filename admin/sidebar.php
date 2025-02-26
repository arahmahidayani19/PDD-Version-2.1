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


            <li class="nav-item">
              <p class="nav-link font-weight-bold text-dark">Main Menu</p>
            </li>

            <!-- User List -->
            <li class="nav-item">
              <a href="../user_list/user.php" class="nav-link <?php echo isActive('user.php'); ?>">
                <i class="nav-icon fas fa-users"></i>
                <p>User List</p>
              </a>
            </li>

            <!-- Daily Transaction -->
            <li class="nav-item">
              <a href="../daily/daily_tr.php" class="nav-link <?php echo isActive('daily_tr.php'); ?>">
                <i class="nav-icon fas fa-clipboard-list"></i>
                <p>Daily Transaction</p>
              </a>
            </li>

            <!-- Information File -->
            <li class="nav-item menu-open">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-folder-open"></i>
                <p>Information File<i class="fas fa-angle-left right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item" style="padding-left: 30px;">
                  <a href="../product_doc/file.php" class="nav-link <?php echo isActive('file.php'); ?>">
                    <i class="fas fa-file-alt nav-icon"></i>
                    <p>Part Number Document</p>
                  </a>
                </li>
                <li class="nav-item" style="padding-left: 30px;">
                  <a href="../machine_doc/machine_document.php" class="nav-link <?php echo isActive('machine_document.php'); ?>">
                    <i class="fas fa-tools nav-icon"></i>
                    <p>Machine Document</p>
                  </a>
                </li>
              </ul>
            </li>

            <!-- Document Maintenance -->
            <li class="nav-item">
              <a href="../doc_maintenc/maintenance.php" class="nav-link <?php echo isActive('maintenance.php'); ?>">
                <i class="fa fa-solid fa-file-signature nav-icon"></i>
                <p>Document Maintenance</p>
              </a>
            </li>

            <!-- Machine Master -->
            <li class="nav-item">
              <a href="../machine_master/machine_master.php" class="nav-link <?php echo isActive('machine_master.php'); ?>">
                <i class="nav-icon fas fa-cogs"></i>
                <p>Machine Master</p>
              </a>
            </li>

            <!-- Part Number Master -->
            <li class="nav-item">
              <a href="../product_master/partno_master.php" class="nav-link <?php echo isActive('partno_master.php'); ?>">
                <i class="nav-icon fas fa-layer-group"></i>
                <p>Part Number Master</p>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </aside>