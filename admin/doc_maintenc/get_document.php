<?php
include '../../koneksi.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $document_id = $_GET['id'];

    $query = "SELECT * FROM document_types WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $document_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($document = mysqli_fetch_assoc($result)) {
        echo json_encode($document);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Document not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
