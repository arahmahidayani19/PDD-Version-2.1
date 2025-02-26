<?php
session_start();

// Get the logged-in username from the session
$username = $_SESSION['username'];

// Database connection
$servername = "localhost";
$username_db = "root";
$password = "";
$dbname = "pdd";
$conn = new mysqli($servername, $username_db, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // First check if the arrays exist in $_POST
    if (
        !isset($_POST['partNumber']) || !isset($_POST['shift']) ||
        !isset($_POST['machineType']) || !isset($_POST['machineNumber']) ||
        !isset($_POST['machineStatus']) || !isset($_POST['transactionDatetime']) ||
        !isset($_POST['jobsiteno'])
    ) {
        die("Missing required form data");
    }

    // Get the form data arrays
    $part_numbers = $_POST['partNumber'];
    $shifts = $_POST['shift'];
    $machineTypes = $_POST['machineType'];
    $machineNumbers = $_POST['machineNumber'];
    $machineStatuses = $_POST['machineStatus'];
    $transactionDatetimes = $_POST['transactionDatetime'];
    $jobsitenos = $_POST['jobsiteno'];

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO form_data (username, part_number, shift, 
        machineType, machineNumber, machineStatus, transaction_datetime, jobsiteno) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Loop through each entry
    for ($i = 0; $i < count($part_numbers); $i++) {
        // Check if this entry has all required fields filled
        if (
            !empty($part_numbers[$i]) && !empty($shifts[$i]) &&
            !empty($machineTypes[$i]) && !empty($machineNumbers[$i]) &&
            !empty($machineStatuses[$i]) && !empty($transactionDatetimes[$i]) &&
            !empty($jobsitenos[$i])
        ) {

            // Bind parameters and execute
            $stmt->bind_param(
                "ssssssss",
                $username,
                $part_numbers[$i],
                $shifts[$i],
                $machineTypes[$i],
                $machineNumbers[$i],
                $machineStatuses[$i],
                $transactionDatetimes[$i],
                $jobsitenos[$i]
            );

            if (!$stmt->execute()) {
                echo "Error executing query: " . $stmt->error;
                exit();
            }
        }
    }

    $stmt->close();
    $conn->close();

    // Redirect on success
    header("Location: daily_tr.php");
    exit();
} else {
    echo "Invalid request method";
    exit();
}
