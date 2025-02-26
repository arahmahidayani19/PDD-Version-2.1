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

    $machineName = isset($_POST['machine_name']) ? mysqli_real_escape_string($conn, trim($_POST['machine_name'])) : '';

    // Check for duplicate machine name
    $checkQuery = "SELECT id FROM machine_documents WHERE machine_name = '$machineName'";
    $checkResult = mysqli_query($conn, $checkQuery);
    if ($checkResult && mysqli_num_rows($checkResult) > 0) {
        $response['message'] = "A machine with the name <strong>" . htmlspecialchars($machineName) . "</strong> already exists.";
        echo json_encode($response);
        exit;
    }

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

    $data = array();
    foreach ($docColumns as $col) {
        $displayLabel = ucwords(str_replace('_', ' ', str_replace('_path', '', $col)));
        $folderLabel = str_replace(' ', '', $displayLabel);
        $uploadFolder = $baseUploadDir . $folderLabel . '/';
        if (!is_dir($uploadFolder)) {
            mkdir($uploadFolder, 0777, true);
        }

        $fileInputName = $col . '_file';
        $textInput = isset($_POST[$col]) ? trim($_POST[$col]) : '';

        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES[$fileInputName]['tmp_name'];
            $originalName = $_FILES[$fileInputName]['name'];
            $destPath = $uploadFolder . $originalName;

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
                $data[$col] = mysqli_real_escape_string($conn, $finalPath);
            } else {
                throw new Exception("Error uploading file for " . $displayLabel);
            }
        } else {
            $data[$col] = mysqli_real_escape_string($conn, $textInput);
        }
    }

    $columns = "machine_name";
    $values  = "'" . $machineName . "'";
    foreach ($docColumns as $col) {
        $columns .= ", " . $col;
        $value = isset($data[$col]) ? $data[$col] : '';
        $values .= ", '" . $value . "'";
    }

    $sql = "INSERT INTO machine_documents ($columns) VALUES ($values)";
    if (mysqli_query($conn, $sql)) {
        $response['status'] = 'success';
        $response['message'] = 'Data has been successfully saved!';
        $response['redirect'] = 'machine_document.php';
    } else {
        throw new Exception(mysqli_error($conn));
    }
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
} finally {
    mysqli_close($conn);
    echo json_encode($response);
}
