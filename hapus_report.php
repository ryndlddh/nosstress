<?php
// Mulai sesi
session_start();

// Fungsi untuk mengarahkan pengguna ke halaman login jika belum login
function redirect_to_login() {
    header("Location: login.php");
    exit();
}

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    redirect_to_login();
}

// Cek apakah ada report_id yang dikirimkan
if (!isset($_GET['report_id'])) {
    die("Tidak ada report_id yang diberikan.");
}

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "album");

// Periksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Ambil report_id dari URL
$report_id = mysqli_real_escape_string($conn, $_GET['report_id']);

// Query untuk menghapus laporan
$sql = "DELETE FROM `report` WHERE `report_id` = '$report_id'";

// Eksekusi query
if (mysqli_query($conn, $sql)) {
    // Redirect ke halaman daftar laporan
    header("Location: read_report.php");
} else {
    echo "Terjadi kesalahan saat menghapus laporan: " . mysqli_error($conn);
}

// Tutup koneksi ke database
mysqli_close($conn);
?>
