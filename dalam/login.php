
<?php
session_start(); // Memulai session

$success_message = "";
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai input dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Koneksi ke database
    $conn = mysqli_connect("localhost", "root", "", "album");

    // Cek koneksi
    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    // Query untuk mencari pengguna berdasarkan username dan password
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";

    $result = mysqli_query($conn, $sql);

    // Cek apakah pengguna ditemukan
    if (mysqli_num_rows($result) == 1) {
        // Pengguna ditemukan, simpan informasi pengguna ke dalam session
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['access_level'] = $row['access_level'];

        // Redirect ke halaman dashboard atau halaman lain yang sesuai
        header("Location: ../dasboard.php");
    } else {
        // Jika pengguna tidak ditemukan, tampilkan pesan error
        $error = "Username atau password salah.";
    }

    // Tutup koneksi ke database
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>body {
    font-family: Arial, sans-serif;
    background-color: #f0f2f5;
}

.container {
    max-width: 400px;
    margin: 100px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

label {
    font-weight: bold;
    color: #1c1e21;
}

input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #dddfe2;
    border-radius: 5px;
    box-sizing: border-box;
}

input[type="checkbox"] {
    margin-top: 10px;
}

input[type="submit"] {
    width: 100%;
    padding: 12px;
    background-color: #1877f2; /* Warna biru Facebook */
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #166fe5; /* Warna biru tua saat dihover */
}

.signup-link {
    text-align: center;
    margin-top: 15px;
}

.signup-link a {
    color: #1877f2; /* Warna biru Facebook */
    text-decoration: none;
}

.signup-link a:hover {
    text-decoration: underline;
}

.success-message {
  text-align: center;
  color: rgb(10, 224, 10);
}

/* Gaya untuk tampilan responsif */
@media (max-width: 600px) {
    .container {
        width: 90%;
        margin: 50px auto;
    }
    
    input[type="text"],
    input[type="password"] {
        width: calc(100% - 20px);
    }
}
</style>
<link rel="stylesheet" href="../style/slfa.css">
</head>
<body>
    <div class="navbar">
        <a href="../index.php">Home</a>
        </div>
    </div>
    


<div class="container">
    <h2>Login</h2>
<?php if (!empty($success_message)): ?>
  
  <div class="success-message"><?php echo $success_message; ?></div>
<?php endif; ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <div style="position: relative;">
                <input type="password" id="password" name="password" required>
                <input type="checkbox" id="showPassword"> <label for="showPassword">Show Password</label>
            </div>
            <input type="submit" value="Login">
        </form>
        <div class="signup-link">
            <a href="daftar.php">Daftar Akun</a>.
        </div>
    </div>
    <script>
        document.getElementById("showPassword").addEventListener("change", function() {
            var passwordField = document.getElementById("password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        });
    </script>
</div>
</body>
</html>
