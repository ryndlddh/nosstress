<!-- check_username.php -->
<?php
// Koneksi database
$conn = mysqli_connect("localhost", "root", "", "album");

// Fungsi untuk mencegah SQL injection
function sanitize($data) {
    global $conn;
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

$username = sanitize($_POST['username']);
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) > 0) {
    // Username sudah digunakan, redirect ke daftar.php dengan pesan
    header("Location: daftar.php?error=Username sudah digunakan, silakan ganti username.");
    exit();
} else {
    echo "username_available";
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>