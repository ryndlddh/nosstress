<?php
// Mulai sesi
session_start();

// Fungsi untuk mengarahkan pengguna ke halaman login jika belum login
function redirect_to_login() {
    header("Location: dalam/login.php");
    exit();
}

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    redirect_to_login();
}

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "album");

// Periksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Ambil data dari form
$album_id = $_POST['album_id'];
$title = $_POST['title'];
$description = $_POST['description'];
$access = $_POST['access']; // Ambil nilai akses dari form

// Query untuk memperbarui detail album
$sql = "UPDATE albums SET title = ?, description = ?, access = ? WHERE album_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sssi", $title, $description, $access, $album_id);

// Eksekusi query
if (mysqli_stmt_execute($stmt)) {
    // Redirect ke halaman album setelah berhasil memperbarui album
    header("Location: album_user.php");
    exit();
} else {
    echo "Terjadi kesalahan saat memperbarui album.";
}

// Tutup koneksi
mysqli_close($conn);
?>