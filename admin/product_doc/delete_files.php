<?php
include '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $productID = isset($_GET['id']) ? $_GET['id'] : null;

    if ($productID) {
        // Periksa apakah productID ada di database
        $checkSql = "SELECT COUNT(*) FROM products WHERE productID = ?";
        if ($stmt = $conn->prepare($checkSql)) {
            $stmt->bind_param("s", $productID);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count == 0) {
                echo json_encode(['status' => 'error', 'message' => 'Product ID not found in the database!']);
                exit();
            }
        }

        // Ambil daftar kolom yang memiliki "_path" di dalamnya
        $columns = [];
        $result = $conn->query("SHOW COLUMNS FROM products");
        while ($row = $result->fetch_assoc()) {
            if (strpos($row['Field'], '_path') !== false) {
                $columns[] = $row['Field'];
            }
        }

        if (!empty($columns)) {
            // Buat query UPDATE dinamis
            $setClause = implode(" = ?, ", $columns) . " = ?";
            $sql = "UPDATE products SET $setClause WHERE productID = ?";
            if ($stmt = $conn->prepare($sql)) {
                // Buat array parameter untuk bind_param
                $params = array_fill(0, count($columns), '');
                $params[] = $productID; // Tambahkan productID di akhir

                // Buat parameter bind
                $types = str_repeat("s", count($params));
                $stmt->bind_param($types, ...$params);

                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Product files deleted successfully!']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to delete product files.']);
                }
                $stmt->close();
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No file paths found to delete.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Product ID is required.']);
    }

    $conn->close();
}
