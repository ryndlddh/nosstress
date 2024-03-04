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

// Ambil ID album dari URL
$album_id = $_GET['album_id'];

// Query untuk menghapus semua foto dalam album
$sql_delete_photos = "DELETE FROM photos WHERE album_id = ?";
$stmt_delete_photos = mysqli_prepare($conn, $sql_delete_photos);
mysqli_stmt_bind_param($stmt_delete_photos, "i", $album_id);
mysqli_stmt_execute($stmt_delete_photos);

// Query untuk menghapus album berdasarkan ID
$sql_delete_album = "DELETE FROM albums WHERE album_id = ?";
$stmt_delete_album = mysqli_prepare($conn, $sql_delete_album);
mysqli_stmt_bind_param($stmt_delete_album, "i", $album_id);
mysqli_stmt_execute($stmt_delete_album);

// Redirect ke halaman album_user.php setelah berhasil menghapus album dan foto
header("Location: album_user.php");
exit();

// Tutup koneksi
mysqli_close($conn);
?>
