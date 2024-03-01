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

// Ambil comment_id dari parameter URL
if (isset($_GET['comment_id'])) {
    $comment_id = $_GET['comment_id'];
} else {
    // Jika parameter comment_id tidak tersedia, arahkan pengguna kembali ke halaman sebelumnya
    header("Location: view_comments.php?photo_id=" . $_GET['photo_id']);
    exit();
}

// Query untuk menghapus komentar dari database
$sql_delete_comment = "DELETE FROM comments WHERE comment_id = '$comment_id'";
if (mysqli_query($conn, $sql_delete_comment)) {
    // Komentar berhasil dihapus, kembali ke halaman view_comments.php
    header("Location: view_comments.php?photo_id=" . $_GET['photo_id']);
    exit();
} else {
    // Jika terjadi kesalahan saat menghapus komentar, tampilkan pesan error
    echo "Error: " . mysqli_error($conn);
}

// Tutup koneksi ke database
mysqli_close($conn);
?>
