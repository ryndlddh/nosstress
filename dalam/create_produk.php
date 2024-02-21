<?php
// Koneksi database
$conn = mysqli_connect("localhost", "root", "", "album");

// Fungsi untuk mencegah SQL injection
function sanitize($data) {
  global $conn;
  $data = mysqli_real_escape_string($conn, $data);
  return $data;
}

// Cek ketersediaan username
if (isset($_POST['check_username'])) {
  $username = sanitize($_POST['username']);
  $sql = "SELECT * FROM users WHERE username = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "s", $username);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  if (mysqli_num_rows($result) > 0) {
    echo "username_taken";
  } else {
    echo "username_available";
  }
  mysqli_stmt_close($stmt);
  exit;
}

// Proses data yang dikirimkan melalui form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  $name = sanitize($_POST['name']);
  $username = sanitize($_POST['username']);
  $password = sanitize($_POST['password']);
  $email = sanitize($_POST['email']);
  $create_at = date('Y-m-d H:i:s');

  // Query insert dengan prepared statement
  $sql = "INSERT INTO `users`(`name`, `username`, `password`, `email`, `create_at`) VALUES (?, ?, ?, ?, ?)";
  if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "sssss", $name, $username, $password, $email, $create_at);
    if (mysqli_stmt_execute($stmt)) {
      // Set pesan sukses ke dalam session
      session_start();
      $_SESSION['success_message'] = "Akun berhasil dibuat";
      header("Location: login.php");
      exit();
    } else {
      echo "Error: " . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);
  } else {
    echo "Error: " . mysqli_error($conn);
  }
}

// Tutup koneksi
mysqli_close($conn);
?>