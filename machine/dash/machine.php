<?php
// Database connection
$servername = "localhost";
$dbusername = "root";
$password = "";
$dbname = "pdd";

$conn = new mysqli($servername, $dbusername, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get URL parameters
$line = isset($_GET['line']) ? htmlspecialchars($_GET['line']) : 'Unknown Line';
$machine = isset($_GET['machine']) ? htmlspecialchars($_GET['machine']) : 'Unknown Machine';

// Inisialisasi array untuk menyimpan data
$display_data = array();

// Variables to store form_data information
$part_number = 'N/A';
$shift = 'N/A';
$machineStatus = 'N/A';
$transaction_date = 'N/A';
$customerID = 'Unknown Customer';
$productName = 'Unknown Product';

// Step 1: Get data from form_data
$sql = "SELECT * FROM form_data WHERE machineType = ? AND machineNumber = ? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ss", $line, $machine);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Store all necessary form_data information
        $part_number = isset($row['part_number']) ? htmlspecialchars($row['part_number']) : 'N/A';
        $shift = isset($row['shift']) ? htmlspecialchars($row['shift']) : 'N/A';
        $machineStatus = isset($row['machineStatus']) ? htmlspecialchars($row['machineStatus']) : 'N/A';
        $transaction_date = isset($row['created_at']) ? htmlspecialchars($row['created_at']) : 'N/A';

        if ($part_number != 'N/A') {
            // Query untuk mengambil data dari products
            // Ambil daftar kolom dari tabel products
            $columns_query = "SHOW COLUMNS FROM products";
            $result_columns = $conn->query($columns_query);

            $document_columns = [];

            if ($result_columns->num_rows > 0) {
                while ($column = $result_columns->fetch_assoc()) {
                    if (preg_match('/_path$/', $column['Field'])) { // Cari kolom yang diakhiri dengan "_path"
                        $document_columns[] = $column['Field'];
                    }
                }
            }

            // Ambil data berdasarkan part_number
            $sql_products = "SELECT * FROM products WHERE productID = ?";
            $stmt_products = $conn->prepare($sql_products);

            if ($stmt_products) {
                $stmt_products->bind_param("s", $part_number);
                $stmt_products->execute();
                $result_products = $stmt_products->get_result();

                if ($result_products->num_rows > 0) {
                    $products_row = $result_products->fetch_assoc();

                    $customerID = isset($products_row['customerID']) ? htmlspecialchars($products_row['customerID']) : 'Unknown Customer';
                    $productName = isset($products_row['productName']) ? htmlspecialchars($products_row['productName']) : 'Unknown Product';

                    // Ambil semua data yang berkahiran "_path"
                    foreach ($document_columns as $column) {
                        if (isset($products_row[$column])) {
                            $display_data[$column] = $products_row[$column];
                        }
                    }
                }
                $stmt_products->close();
            }
        }
    }
}

// Debug: Print data yang akan ditampilkan
// echo "<pre>Display Data: "; print_r($display_data); echo "</pre>";
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDD</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../../plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="../../plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
    <link rel="stylesheet" href="../../plugins/bs-stepper/css/bs-stepper.min.css">
    <link rel="stylesheet" href="../../plugins/dropzone/min/dropzone.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../dist/css/nav.css">
    <link rel="stylesheet" href="../../dist/sweetalert2/sweetalert2.min.css">
</head>

<body>

    <div class="wrapper">
        <!-- Navbar -->
        <?php include('line_back.php'); ?>
        <?php include('../../include/nav.php'); ?>


        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Line - <?php echo htmlspecialchars($line) . ' Machine ' . htmlspecialchars($machine); ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Machine</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            </section>

            <?php include('machine_detail.php'); ?>


            <div class="btn" id="btn-back">
                <a href="javascript:history.back()" class="btn-primary btn-outline-white btn-sm mt-3 px-2 py-6 rounded">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>

            <!-- Main Content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row mt-3">
                        <?php if ($machineDetails): ?>
                            <div class="col-lg-12">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Machine Details - <?php echo htmlspecialchars($machine); ?></h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-info"><i class="fas fa-industry"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Brand</span>
                                                        <span class="info-box-number"><?php echo htmlspecialchars($machineDetails['brand']); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-primary"><i class="fas fa-cogs"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Model</span>
                                                        <span class="info-box-number"><?php echo htmlspecialchars($machineDetails['model']); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-success"><i class="fas fa-barcode"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Serial No</span>
                                                        <span class="info-box-number"><?php echo htmlspecialchars($machineDetails['serial_no']); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-danger"><i class="fas fa-weight-hanging"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Tonnage</span>
                                                        <span class="info-box-number"><?php echo htmlspecialchars($machineDetails['tonnage']); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-warning"><i class="fas fa-tag"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Asset No</span>
                                                        <span class="info-box-number"><?php echo htmlspecialchars($machineDetails['asset_no']); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-secondary"><i class="fas fa-calendar-alt"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Date of Machine</span>
                                                        <span class="info-box-number"><?php echo htmlspecialchars($machineDetails['date']); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="col-lg-12">
                                <div class="card card-danger">
                                    <div class="card-body">
                                        <p>No details available for this machine.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="container-fluid">
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-cogs"></i> Machine Running Details</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Part Number Currently Running -->
                                            <div class="col-md-6 mb-3">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-info"><i class="fas fa-cogs"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Part Number Currently Running</span>
                                                        <span class="info-box-number"><?php echo htmlspecialchars($part_number); ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-success"><i class="fas fa-box-open"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Part Name</span>
                                                        <span class="info-box-number"><?php echo htmlspecialchars($productName); ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-warning"><i class="fas fa-user-tie"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Customer</span>
                                                        <span class="info-box-number"><?php echo htmlspecialchars($customerID); ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Machine Status -->
                                            <div class="col-md-6 mb-3">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-primary"><i class="fas fa-tachometer-alt"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Machine Status</span>
                                                        <span class="info-box-number"><?php echo htmlspecialchars($machineStatus); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Shift -->
                                            <div class="col-md-6 mb-3">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-success"><i class="fas fa-clock"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Shift</span>
                                                        <span class="info-box-number"><?php echo htmlspecialchars($shift); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Date Start Part Number -->
                                            <div class="col-md-6 mb-3">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-warning"><i class="fas fa-calendar-alt"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Date Start Part Number</span>
                                                        <span class="info-box-number"><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($transaction_date))); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    // Database connection
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "pdd";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Get URL parameters
                    $line = isset($_GET['line']) ? htmlspecialchars($_GET['line']) : 'Unknown Line';
                    $machine = isset($_GET['machine']) ? htmlspecialchars($_GET['machine']) : 'Unknown Machine';

                    // Initialize arrays for document data
                    $display_data = array();
                    $machine_documents = array();

                    // Get product-related data
                    $part_number = 'N/A';
                    $sql = "SELECT * FROM form_data WHERE machineType = ? AND machineNumber = ? ORDER BY created_at DESC LIMIT 1";
                    $stmt = $conn->prepare($sql);

                    if ($stmt) {
                        $stmt->bind_param("ss", $line, $machine);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $part_number = isset($row['part_number']) ? htmlspecialchars($row['part_number']) : 'N/A';

                            if ($part_number != 'N/A') {
                                // Get product document paths
                                $sql_products = "SELECT * FROM products WHERE productID = ?";
                                $stmt_products = $conn->prepare($sql_products);

                                if ($stmt_products) {
                                    $stmt_products->bind_param("s", $part_number);
                                    $stmt_products->execute();
                                    $result_products = $stmt_products->get_result();

                                    if ($result_products->num_rows > 0) {
                                        $products_row = $result_products->fetch_assoc();
                                        foreach ($products_row as $key => $value) {
                                            if (strpos($key, '_path') !== false) {
                                                $display_data[$key] = $value;
                                            }
                                        }
                                    }
                                    $stmt_products->close();
                                }
                            }
                        }
                        $stmt->close();
                    }

                    // Get machine document paths
                    $sql_machine = "SELECT * FROM machine_documents WHERE machine_name = ?";
                    $stmt_machine = $conn->prepare($sql_machine);

                    if ($stmt_machine) {
                        $stmt_machine->bind_param("s", $machine);
                        $stmt_machine->execute();
                        $result_machine = $stmt_machine->get_result();

                        if ($result_machine->num_rows > 0) {
                            $machine_row = $result_machine->fetch_assoc();
                            foreach ($machine_row as $key => $value) {
                                if (strpos($key, '_path') !== false) {
                                    $machine_documents[$key] = $value;
                                }
                            }
                        }
                        $stmt_machine->close();
                    }

                    // Get document types and descriptions
                    $documentTypes = [];
                    $sql = "SELECT document_name, description, category FROM document_types";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $columnName = strtolower(str_replace(" ", "_", $row['document_name'])) . "_path";
                        $documentTypes[$columnName] = [
                            'description' => $row['description'],
                            'category' => $row['category']
                        ];
                    }

                    // Icon mapping
                    $icon_mapping = [
                        'instruction' => 'fas fa-file-alt',
                        'parameter' => 'fas fa-cogs',
                        'packaging' => 'fas fa-box-open',
                        'quality' => 'fas fa-check-circle',
                        'document' => 'fas fa-file-pdf',
                        'specification' => 'fas fa-ruler',
                        'drawing' => 'fas fa-drafting-compass'
                    ];
                    ?>

                    <!-- Documents Based on Products -->
                    <div class="container-fluid mb-4">
                        <div class="row">
                            <div class="col-12">
                                <h3 class="text-primary mb-3">Documents Based on Products</h3>
                                <p class="text-muted">Part Number: <?php echo $part_number; ?></p>
                            </div>
                            <?php
                            foreach ($display_data as $field => $value) {
                                $icon = 'fas fa-file-alt';
                                foreach ($icon_mapping as $key => $icon_class) {
                                    if (stripos($field, $key) !== false) {
                                        $icon = $icon_class;
                                        break;
                                    }
                                }

                                $display_name = ucwords(str_replace('_', ' ', str_replace('_path', '', $field)));
                                $description = isset($documentTypes[$field]) ? $documentTypes[$field]['description'] :
                                    "View or download " . strtolower($display_name) . " document.";
                                $file_available = !empty($value) && $value !== '#';
                            ?>
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                    <div class="card card-outline card-primary shadow-lg hover-shadow">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h3 class="card-title"><i class="<?php echo $icon; ?>"></i> <?php echo $display_name; ?></h3>
                                        </div>
                                        <div class="card-body">
                                            <p><?php echo $description; ?></p>
                                            <?php if ($file_available) { ?>
                                                <button class="btn btn-primary btn-block" onclick="openPDFInNewTab('<?php echo htmlspecialchars($value); ?>')">
                                                    <i class="fas fa-eye"></i> View File
                                                </button>
                                            <?php } else { ?>
                                                <button class="btn btn-primary btn-block" disabled>
                                                    <i class="fas fa-eye"></i> No File Available
                                                </button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Documents Based on Machines -->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <h3 class="text-primary mb-3">Documents Based on Machines</h3>
                                <p class="text-muted">Machine: <?php echo $machine; ?></p>
                            </div>
                            <?php
                            foreach ($machine_documents as $field => $value) {
                                $icon = 'fas fa-file-alt';
                                foreach ($icon_mapping as $key => $icon_class) {
                                    if (stripos($field, $key) !== false) {
                                        $icon = $icon_class;
                                        break;
                                    }
                                }

                                $display_name = ucwords(str_replace('_', ' ', str_replace('_path', '', $field)));
                                $description = isset($documentTypes[$field]) ? $documentTypes[$field]['description'] :
                                    "View or download " . strtolower($display_name) . " document.";
                                $file_available = !empty($value) && $value !== '#';
                            ?>
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                    <div class="card card-outline card-primary shadow-lg hover-shadow">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h3 class="card-title"><i class="<?php echo $icon; ?>"></i> <?php echo $display_name; ?></h3>
                                        </div>
                                        <div class="card-body">
                                            <p><?php echo $description; ?></p>
                                            <?php if ($file_available) { ?>
                                                <button class="btn btn-primary btn-block" onclick="openPDFInNewTab('<?php echo htmlspecialchars($value); ?>')">
                                                    <i class="fas fa-eye"></i> View File
                                                </button>
                                            <?php } else { ?>
                                                <button class="btn btn-primary btn-block" disabled>
                                                    <i class="fas fa-eye"></i> No File Available
                                                </button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>


                </div>
            </section>
        </div>
    </div>

    </div>
    </section>
    </div>
    <?php include('../../include/footer.php'); ?>

    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../dist/js/adminlte.min.js"></script>
    <script src="../../dist/js/dark_buton.js"></script>
    <script src="../../dist/sweetalert2/sweetalert2.min.js"></script>
    <script src="../../dist/sweetalert2/sweetalert2.js"></script>

    <script>
        function openPDFInNewTab(filePath) {
            window.open('pdf_viewer.php?file=' + filePath, '_blank');
        }
    </script>



</html>