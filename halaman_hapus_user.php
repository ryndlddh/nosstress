<?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "album");

// Periksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Ambil user_id dari parameter id dalam URL
$user_id = $_GET['user_id'];

// Periksa apakah pengguna yang dihapus bukan admin
$check_query = "SELECT access_level FROM users WHERE user_id = $user_id";
$check_result = mysqli_query($conn, $check_query);
$check_row = mysqli_fetch_assoc($check_result);

if ($check_row['access_level'] === 'admin') {
    // Jika pengguna yang dihapus adalah admin, tampilkan pesan error
    $pesan = "Anda tidak dapat menghapus akun admin.";
} else {
    // Query untuk menghapus data pengguna
    $sql = "DELETE FROM users WHERE user_id = $user_id";

    if (mysqli_query($conn, $sql)) {
        $pesan = "Akun sudah berhasil terhapus";
    } else {
        $pesan = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Tutup koneksi ke database
mysqli_close($conn);

// Redirect ke halaman_admin.php dan kirim pesan sebagai parameter
header("Location: halaman_admin.php?pesan=" . urlencode($pesan));
exit();
?>