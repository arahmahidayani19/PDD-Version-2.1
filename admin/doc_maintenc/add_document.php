<?php
include '../koneksi.php';

if (isset($_POST['upload_document'])) {
    $category      = trim($_POST['category']);
    $document_name = trim($_POST['document_name']);
    $description   = trim($_POST['description']);
    $swal_script = "";

    // Add "machine" prefix if needed
    if ($category == 'machine' && stripos($document_name, 'machine') !== 0) {
        $document_name = "machine " . $document_name;
    }

    // Insert document data into document_types table
    $sql = "INSERT INTO document_types (category, document_name, description) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $category, $document_name, $description);

    if (mysqli_stmt_execute($stmt)) {
        $swal_script = "Swal.fire({icon: 'success', title: 'Document type added successfully!', timer: 1500, showConfirmButton: false});\n";

        // Add new column to the respective table based on category
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
        $swal_script = "Swal.fire({icon: 'error', title: 'Failed to add document type', text: '" . addslashes(mysqli_error($conn)) . "'});\n";
    }

    // Output the SweetAlert script
    echo "<script>" . $swal_script . "</script>";
}
