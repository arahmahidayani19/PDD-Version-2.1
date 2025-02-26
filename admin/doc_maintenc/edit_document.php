// edit_document.php
<?php
include '../koneksi.php';

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

?>