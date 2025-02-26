<?php
include '../koneksi.php';

$swal_script = "";

// Fetch all document types
$query = "SELECT * FROM document_types";
$result = mysqli_query($conn, $query);

// Handle Delete
if (isset($_POST['delete_document'])) {
    $document_id = $_POST['document_id'];
    $category = $_POST['category'];
    $document_name = $_POST['document_name'];

    // Start transaction
    mysqli_begin_transaction($conn);
    try {
        // Delete from document_types
        $delete_query = "DELETE FROM document_types WHERE id = ?";
        $stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($stmt, "i", $document_id);
        mysqli_stmt_execute($stmt);

        // Delete column from respective table based on category
        $column_name = strtolower(str_replace(" ", "_", $document_name)) . "_path";
        if ($category == 'machine') {
            $alter_query = "ALTER TABLE machine_documents DROP COLUMN `$column_name`";
        } else if ($category == 'product') {
            $alter_query = "ALTER TABLE products DROP COLUMN `$column_name`";
        }
        mysqli_query($conn, $alter_query);

        mysqli_commit($conn);
        $swal_script = "Swal.fire({icon: 'success', title: 'Document deleted successfully!', timer: 1500, showConfirmButton: false});";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $swal_script = "Swal.fire({icon: 'error', title: 'Error deleting document', text: '" . addslashes($e->getMessage()) . "'});";
    }
}

// Handle Edit
if (isset($_POST['edit_document'])) {
    $document_id = $_POST['document_id'];
    $old_category = $_POST['old_category'];
    $old_name = $_POST['old_name'];
    $new_category = $_POST['category'];
    $new_name = trim($_POST['document_name']);
    $new_description = trim($_POST['description']);

    // Add "machine" prefix if needed
    if ($new_category == 'machine' && stripos($new_name, 'machine') !== 0) {
        $new_name = "machine " . $new_name;
    }

    // Start transaction
    mysqli_begin_transaction($conn);
    try {
        // Update document_types
        $update_query = "UPDATE document_types SET category = ?, document_name = ?, description = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "sssi", $new_category, $new_name, $new_description, $document_id);
        mysqli_stmt_execute($stmt);

        // Handle column rename in respective tables
        $old_column = strtolower(str_replace(" ", "_", $old_name)) . "_path";
        $new_column = strtolower(str_replace(" ", "_", $new_name)) . "_path";

        if ($old_category == $new_category) {
            // Same category, just rename column
            if ($new_category == 'machine') {
                $alter_query = "ALTER TABLE machine_documents CHANGE `$old_column` `$new_column` VARCHAR(255)";
            } else if ($new_category == 'product') {
                $alter_query = "ALTER TABLE products CHANGE `$old_column` `$new_column` VARCHAR(255)";
            }
            mysqli_query($conn, $alter_query);
        } else {
            // Category changed, drop column from old table and add to new table
            if ($old_category == 'machine') {
                mysqli_query($conn, "ALTER TABLE machine_documents DROP COLUMN `$old_column`");
            } else if ($old_category == 'product') {
                mysqli_query($conn, "ALTER TABLE products DROP COLUMN `$old_column`");
            }

            if ($new_category == 'machine') {
                mysqli_query($conn, "ALTER TABLE machine_documents ADD COLUMN `$new_column` VARCHAR(255)");
            } else if ($new_category == 'product') {
                mysqli_query($conn, "ALTER TABLE products ADD COLUMN `$new_column` VARCHAR(255)");
            }
        }

        mysqli_commit($conn);
        $swal_script = "Swal.fire({icon: 'success', title: 'Document updated successfully!', timer: 1500, showConfirmButton: false});";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $swal_script = "Swal.fire({icon: 'error', title: 'Error updating document', text: '" . addslashes($e->getMessage()) . "'});";
    }
}

// Handle Add (Upload Document)
if (isset($_POST['upload_document'])) {
    $category      = trim($_POST['category']);
    $document_name = trim($_POST['document_name']);
    $description   = trim($_POST['description']);

    // Add "machine" prefix if needed
    if ($category == 'machine' && stripos($document_name, 'machine') !== 0) {
        $document_name = "machine " . $document_name;
    }

    // Insert document data into document_types table
    $sql = "INSERT INTO document_types (category, document_name, description) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $category, $document_name, $description);

    if (mysqli_stmt_execute($stmt)) {
        $swal_script .= "Swal.fire({icon: 'success', title: 'Document type added successfully!', timer: 1500, showConfirmButton: false});\n";

        // Add new column to the respective table based on category

        // If category is "machine"
        if ($category == 'machine') {
            $column_name = strtolower(str_replace(" ", "_", $document_name)) . "_path";

            // Check if column already exists in machine_documents table
            $checkQuery = "SHOW COLUMNS FROM machine_documents LIKE '$column_name'";
            $checkResult = mysqli_query($conn, $checkQuery);

            if (mysqli_num_rows($checkResult) == 0) {
                // If column doesn't exist, add new column
                $alterQuery = "ALTER TABLE machine_documents ADD COLUMN `$column_name` VARCHAR(255) NOT NULL";
                if (mysqli_query($conn, $alterQuery)) {
                    $swal_script .= "Swal.fire({icon: 'success', title: 'Column " . addslashes($column_name) . " added to machine_documents table!', timer: 1500, showConfirmButton: false});\n";
                } else {
                    $swal_script .= "Swal.fire({icon: 'error', title: 'Failed to add column', text: '" . addslashes(mysqli_error($conn)) . "'});\n";
                }
            } else {
                $swal_script .= "Swal.fire({icon: 'info', title: 'Column " . addslashes($column_name) . " already exists.'});\n";
            }
        }

        // If category is "product"
        if ($category == 'product') {
            $column_name = strtolower(str_replace(" ", "_", $document_name)) . "_path";

            // Check if column already exists in products table
            $checkQuery = "SHOW COLUMNS FROM products LIKE '$column_name'";
            $checkResult = mysqli_query($conn, $checkQuery);

            if (mysqli_num_rows($checkResult) == 0) {
                // If column doesn't exist, add new column
                $alterQuery = "ALTER TABLE products ADD COLUMN `$column_name` VARCHAR(255) NOT NULL";
                if (mysqli_query($conn, $alterQuery)) {
                    $swal_script .= "Swal.fire({icon: 'success', title: 'Column " . addslashes($column_name) . " added to products table!', timer: 1500, showConfirmButton: false});\n";
                } else {
                    $swal_script .= "Swal.fire({icon: 'error', title: 'Failed to add column', text: '" . addslashes(mysqli_error($conn)) . "'});\n";
                }
            } else {
                $swal_script .= "Swal.fire({icon: 'info', title: 'Column " . addslashes($column_name) . " already exists.'});\n";
            }
        }
    } else {
        $swal_script .= "Swal.fire({icon: 'error', title: 'Failed to add document type', text: '" . addslashes(mysqli_error($conn)) . "'});\n";
    }
}
?>


<?php include('../sidebar.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDD</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../dist/css/nav.css">
    <link rel="stylesheet" href="../../dist/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
</head>

<body>
    <div class="wrapper">
        <?php include('../../include/nav.php'); ?>

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Document Maintenance</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Document Maintenance</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Select Category</label>
                                    <select name="category" class="form-control" required>
                                        <option value="">-- Select --</option>
                                        <option value="product">Product</option>
                                        <option value="machine">Machine</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Document Name</label>
                                    <input type="text" name="document_name" class="form-control" placeholder="Enter document name" required>
                                </div>

                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control" rows="3" placeholder="Enter document description"></textarea>
                                </div>

                                <button type="submit" name="upload_document" class="btn btn-primary">Add Data</button>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <table id="documentTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Category</th>
                                        <th>Document Name</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['document_name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                                        echo "<td>
                                        <button type='button' class='btn btn-warning btn-sm' onclick='editDocument(" .
                                            json_encode([
                                                "id" => $row['id'],
                                                "category" => $row['category'],
                                                "document_name" => $row['document_name'],
                                                "description" => $row['description']
                                            ]) . ")'>
                                            <i class='fas fa-edit'></i> Edit
                                        </button>
                                        <button type='button' class='btn btn-danger btn-sm' onclick='deleteDocument(" .
                                            json_encode([
                                                "id" => $row['id'],
                                                "category" => $row['category'],
                                                "document_name" => $row['document_name']
                                            ]) . ")'>
                                            <i class='fas fa-trash'></i> Delete
                                        </button>
                                    </td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Edit Document</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="document_id" id="edit_document_id">
                                                <input type="hidden" name="old_category" id="old_category">
                                                <input type="hidden" name="old_name" id="old_name">

                                                <div class="form-group">
                                                    <label>Category</label>
                                                    <select name="category" id="edit_category" class="form-control" required>
                                                        <option value="">-- Select --</option>
                                                        <option value="product">Product</option>
                                                        <option value="machine">Machine</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Document Name</label>
                                                    <input type="text" name="document_name" id="edit_document_name" class="form-control" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="edit_document" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <form id="deleteForm" method="POST" style="display: none;">
                                <input type="hidden" name="document_id" id="delete_document_id">
                                <input type="hidden" name="category" id="delete_category">
                                <input type="hidden" name="document_name" id="delete_document_name">
                                <input type="hidden" name="delete_document" value="1">
                            </form>

                        </div>
                    </div>
            </section>
        </div>

        <?php include('../../include/footer.php'); ?>
    </div>



    <!-- Load script JS -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/select2/js/select2.full.min.js"></script>
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../../dist/js/adminlte.min.js"></script>
    <script src="../../dist/sweetalert2/sweetalert2.min.js"></script>
    <script src="../../dist/sweetalert2/sweetalert2.js"></script>
    <script src="../../dist/js/dark_buton.js"></script>

    <script>
        function editDocument(data) {
            $('#edit_document_id').val(data.id);
            $('#old_category').val(data.category);
            $('#old_name').val(data.document_name);
            $('#edit_category').val(data.category);
            $('#edit_document_name').val(data.document_name);
            $('#edit_description').val(data.description);
            $('#editModal').modal('show');
        }

        function deleteDocument(data) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the document and its associated column. This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#delete_document_id').val(data.id);
                    $('#delete_category').val(data.category);
                    $('#delete_document_name').val(data.document_name);
                    $('#deleteForm').submit();
                }
            });
        }

        // Initialize DataTable
        $(document).ready(function() {
            $('#documentTable').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#documentTable_wrapper .col-md-6:eq(0)');
        });

        <?php
        if (!empty($swal_script)) {
            echo "document.addEventListener('DOMContentLoaded', function() {
        $swal_script
    });";
        }
        ?>
    </script>
</body>

</html>