<?php
include '../koneksi.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define base upload directory
define('BASE_UPLOAD_DIR', "../../PDD/product/");

// Start output buffering
ob_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debug output
    error_log("POST data: " . print_r($_POST, true));
    error_log("FILES data: " . print_r($_FILES, true));

    $productID = $_POST['part_number'];

    // Get database columns dynamically
    $columns_result = $conn->query("SHOW COLUMNS FROM products");
    $updateFields = [];
    $updateValues = [];
    $fieldNames = []; // Array to store clean field names
    $types = "";

    // Function to check if field needs file processing
    function needsFileUpload($fieldName)
    {
        $fileRelatedTerms = ['path', 'file', 'document', 'attachment', 'upload', 'parameter', 'instruction', 'packaging'];
        $fieldNameLower = strtolower($fieldName);

        foreach ($fileRelatedTerms as $term) {
            if (strpos($fieldNameLower, $term) !== false) {
                return true;
            }
        }
        return false;
    }

    // Process each field dynamically
    while ($column = $columns_result->fetch_assoc()) {
        $field = $column['Field'];

        // Skip productID as it's our WHERE condition
        if ($field === 'productID') {
            continue;
        }

        if (needsFileUpload($field)) {
            // Handle file upload fields
            $path_value = null;

            // Check for path input
            if (!empty($_POST["{$field}_path"])) {
                $path_value = $_POST["{$field}_path"];
            }
            // Check for file upload
            elseif (isset($_FILES["{$field}_file"]) && $_FILES["{$field}_file"]['error'] == 0) {
                $path_value = processFile($_FILES["{$field}_file"], $field);
                error_log("$field file path: " . $path_value);
            }

            if ($path_value !== null) {
                $updateFields[] = "$field = COALESCE(?, $field)";
                $updateValues[] = $path_value;
                $fieldNames[] = $field; // Store the clean field name
                $types .= "s";
            }
        }
        // Handle regular text fields
        elseif (isset($_POST[$field]) && $_POST[$field] !== '') {
            $updateFields[] = "$field = COALESCE(?, $field)";
            $updateValues[] = $_POST[$field];
            $fieldNames[] = $field; // Store the clean field name
            $types .= "s";
        }
    }

    // Add productID for WHERE clause
    $updateValues[] = $productID;
    $types .= "s";

    // Construct and execute update query if we have fields to update
    if (!empty($updateFields)) {
        $sql = "UPDATE products SET " . implode(", ", $updateFields) . " WHERE productID = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Create parameter array for bind_param
            $params = array_merge([$types], $updateValues);
            $tmp = [];
            foreach ($params as $key => $value) $tmp[$key] = &$params[$key];
            call_user_func_array([$stmt, 'bind_param'], $tmp);

            error_log("Executing query: " . $sql);
            error_log("With values: " . print_r($updateValues, true));

            if ($stmt->execute()) {
                // Create updated_fields array using only the values that were actually updated
                $updatedFieldsArray = array_combine(
                    $fieldNames,
                    array_slice($updateValues, 0, count($fieldNames))
                );

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Product updated successfully!',
                    'updated_fields' => $updatedFieldsArray
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to update product: ' . $stmt->error
                ]);
            }
            $stmt->close();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Database error: ' . $conn->error
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'warning',
            'message' => 'No fields to update'
        ]);
    }

    $conn->close();
}
function processFile($file, $type)
{
    // Convert field name to folder name dynamically
    $folderName = str_replace('_', '', ucwords($type, '_')); // Convert field_name to FieldName
    $uploadDir = BASE_UPLOAD_DIR . "Product/$folderName/";

    // Create directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            throw new Exception("Failed to create directory: " . $uploadDir);
        }
    }

    $fileName = basename($file['name']);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $filePath = $uploadDir . $fileName;

    error_log("Processing file: " . $file['name']);
    error_log("Target path: " . $filePath);

    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        error_log("File uploaded successfully to: " . $filePath);

        // Handle file conversion if needed
        if (in_array($fileExt, ['xls', 'xlsx', 'csv', 'docx'])) {
            try {
                if (in_array($fileExt, ['xls', 'xlsx', 'csv'])) {
                    $pdfPath = convertToPDF($filePath);
                } elseif ($fileExt === 'docx') {
                    $pdfPath = convertWordToPDF($filePath);
                }
                unlink($filePath); // Remove original file after conversion
                return $pdfPath;
            } catch (Exception $e) {
                error_log("Conversion error: " . $e->getMessage());
                return $filePath; // Return original file path if conversion fails
            }
        }
        return $filePath;
    }

    error_log("Failed to upload file: " . $file['name']);
    return null;
}

ob_end_flush();
