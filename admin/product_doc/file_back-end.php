<?php
// Koneksi ke database
$servername = "localhost";
$username_db = "root";
$password_db = ""; // Ganti dengan password database Anda jika ada
$dbname = "pdd";

// Buat koneksi ke database
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data dari tabel 'products'
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Kolom wajib yang selalu ada (dimodifikasi untuk menghilangkan id dan customerID)
$required_columns = ['productID'];

// Kolom yang ingin disembunyikan
$hidden_columns = ['id', 'customerID', 'productName', 'entry_date'];
