<?php
include '../koneksi.php';

// Initialize response array
$response = array(
    'status' => 'error',
    'message' => '',
    'redirect' => ''
);

try {
    $baseUploadDir = '../../PDD/Machine/';
    if (!is_dir($baseUploadDir)) {
        mkdir($baseUploadDir, 0777, true);
    }

    // Get the machine ID and name
    $machineId = isset($_POST['machine_id']) ? mysqli_real_escape_string($conn, trim($_POST['machine_id'])) : '';
    $machineName = isset($_POST['machine_name']) ? mysqli_real_escape_string($conn, trim($_POST['machine_name'])) : '';

    // Check if the machine already exists (excluding the current record)
    $checkQuery = "SELECT id FROM machine_documents WHERE machine_name = '$machineName' AND id != '$machineId'";
    $checkResult = mysqli_query($conn, $checkQuery);
    if ($checkResult && mysqli_num_rows($checkResult) > 0) {
        $response['message'] = "A machine with the name <strong>" . htmlspecialchars($machineName) . "</strong> already exists.";
        echo json_encode($response);
        exit;
    }

    // Get document columns
    $docColumns = array();
    $sqlColumns = "SHOW COLUMNS FROM machine_documents";
    $resultColumns = mysqli_query($conn, $sqlColumns);
    if ($resultColumns) {
        while ($col = mysqli_fetch_assoc($resultColumns)) {
            $field = $col['Field'];
            if ($field != 'id' && $field != 'machine_name' && substr($field, -5) === '_path') {
                $docColumns[] = $field;
            }
        }
    }

    // Get current file paths
    $currentDataQuery = "SELECT * FROM machine_documents WHERE id = '$machineId'";
    $currentDataResult = mysqli_query($conn, $currentDataQuery);
    $currentData = mysqli_fetch_assoc($currentDataResult);

    $updateFields = array();
    foreach ($docColumns as $col) {
        $displayLabel = ucwords(str_replace('_', ' ', str_replace('_path', '', $col)));
        $folderLabel = str_replace(' ', '', $displayLabel);
        $uploadFolder = $baseUploadDir . $folderLabel . '/';
        if (!is_dir($uploadFolder)) {
            mkdir($uploadFolder, 0777, true);
        }

        $fileInputName = $col . '_file';
        $textInput = isset($_POST[$col]) ? trim($_POST[$col]) : '';

        // Check if a new file was uploaded
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES[$fileInputName]['tmp_name'];
            $originalName = $_FILES[$fileInputName]['name'];
            $destPath = $uploadFolder . $originalName;

            // Delete old file if it exists
            if (!empty($currentData[$col]) && file_exists($currentData[$col])) {
                unlink($currentData[$col]);
                // Delete corresponding PDF if it exists
                $oldPdfPath = pathinfo($currentData[$col], PATHINFO_DIRNAME) . '/' .
                    pathinfo($currentData[$col], PATHINFO_FILENAME) . '.pdf';
                if (file_exists($oldPdfPath)) {
                    unlink($oldPdfPath);
                }
            }

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                if (in_array($fileExtension, array('doc', 'docx', 'xls', 'xlsx'))) {
                    $command = 'soffice --headless --convert-to pdf --outdir ' . escapeshellarg($uploadFolder) . ' ' . escapeshellarg($destPath);
                    shell_exec($command);
                    $pdfFile = $uploadFolder . pathinfo($originalName, PATHINFO_FILENAME) . '.pdf';
                    $finalPath = file_exists($pdfFile) ? $pdfFile : $destPath;
                } else {
                    $finalPath = $destPath;
                }
                $updateFields[] = $col . " = '" . mysqli_real_escape_string($conn, $finalPath) . "'";
            } else {
                throw new Exception("Error uploading file for " . $displayLabel);
            }
        } elseif (!empty($textInput)) {
            // If text input is provided, update the path
            $updateFields[] = $col . " = '" . mysqli_real_escape_string($conn, $textInput) . "'";
        }
        // If neither file nor text is provided, keep existing value
    }

    // Add machine name to update fields
    $updateFields[] = "machine_name = '" . $machineName . "'";

    // Construct and execute update query
    if (!empty($updateFields)) {
        $sql = "UPDATE machine_documents SET " . implode(", ", $updateFields) . " WHERE id = '$machineId'";
        if (mysqli_query($conn, $sql)) {
            $response['status'] = 'success';
            $response['message'] = 'Data successfully updated!';
            $response['redirect'] = 'machine_document.php';
        } else {
            throw new Exception(mysqli_error($conn));
        }
    } else {
        $response['status'] = 'success';
        $response['message'] = 'No changes were made';
        $response['redirect'] = 'machine_document.php';
    }
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
} finally {
    mysqli_close($conn);
    echo json_encode($response);
}
