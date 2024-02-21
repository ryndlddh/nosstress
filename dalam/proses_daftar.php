<!-- proses_daftar.php -->
<?php
// Koneksi database
$conn = mysqli_connect("localhost", "root", "", "album");

// Fungsi untuk mencegah SQL injection
function sanitize($data) {
    global $conn;
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

// Ambil data input
$name = sanitize($_POST['name']);
$username = sanitize($_POST['username']);
$password = sanitize($_POST['password']);
$email = sanitize($_POST['email']);
$access_level = 'user';
$create_at = date('Y-m-d H:i:s'); // Mengisi create_at dengan waktu saat ini

// Query insert dengan prepared statement
$sql = "INSERT INTO `users`(`name`, `username`, `password`, `email`, `access_level`, `create_at`) VALUES (?, ?, ?, ?, ?, ?)";

if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "ssssss", $name, $username, $password, $email, $access_level, $create_at);

    if (mysqli_stmt_execute($stmt)) {
        // Pendaftaran berhasil, redirect ke daftar.php dengan pesan
        header("Location: daftar.php?success=Akun berhasil dibuat");
        exit();
    } else {
        $error = "Error: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
} else {
    $error = "Error: " . mysqli_error($conn);
}

// Tutup koneksi
mysqli_close($conn);

// Arahkan kembali ke halaman daftar.php jika terjadi error
if (isset($error)) {
    header("Location: daftar.php?error=" . urlencode($error));
    exit();
}
?>