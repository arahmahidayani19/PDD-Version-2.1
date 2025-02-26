<?php
include '../koneksi.php';

$response = array(
    'status' => 'error',
    'message' => ''
);

try {
    if (!isset($_POST['id'])) {
        throw new Exception('ID not found');
    }

    $id = mysqli_real_escape_string($conn, $_POST['id']);

    // Get file paths before deleting the record
    $query = "SELECT * FROM machine_documents WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        // Get all columns that end with '_path'
        $columns = array_filter(array_keys($row), function ($key) {
            return substr($key, -5) === '_path';
        });

        // Delete associated files
        foreach ($columns as $column) {
            $filePath = $row[$column];
            if (!empty($filePath) && file_exists($filePath)) {
                unlink($filePath);

                // Delete PDF version if it exists
                $pdfPath = pathinfo($filePath, PATHINFO_DIRNAME) . '/' .
                    pathinfo($filePath, PATHINFO_FILENAME) . '.pdf';
                if (file_exists($pdfPath)) {
                    unlink($pdfPath);
                }
            }
        }

        // Delete record from database
        $deleteQuery = "DELETE FROM machine_documents WHERE id = '$id'";
        if (mysqli_query($conn, $deleteQuery)) {
            $response['status'] = 'success';
            $response['message'] = 'Data successfully deleted';
        } else {
            throw new Exception(mysqli_error($conn));
        }
    } else {
        throw new Exception('Data not found');
    }
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
} finally {
    mysqli_close($conn);
    echo json_encode($response);
}
