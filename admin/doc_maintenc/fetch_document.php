<?php
include '../koneksi.php';

function fetchDocuments()
{
    global $conn;
    $query = "SELECT * FROM document_types";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        throw new Exception(mysqli_error($conn));
    }

    return $result;
}
