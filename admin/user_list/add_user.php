<?php
include '../koneksi.php';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Check if the username already exists
    $checkSql = "SELECT id FROM users WHERE username=?";
    if ($stmt = $conn->prepare($checkSql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Username already exists!']);
            exit();
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error, please try again later.']);
        exit();
    }

    // Proceed with inserting the new user if the username is unique
    $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {

        $stmt->bind_param("sss", $username, $password, $role);

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'User added successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add user.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error, please try again later.']);
    }
}

$conn->close();
