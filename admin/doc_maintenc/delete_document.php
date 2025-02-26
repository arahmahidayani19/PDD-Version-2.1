// delete_document.php
<?php
include '../koneksi.php';

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

?>