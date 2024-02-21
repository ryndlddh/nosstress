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

// Periksa apakah user_id dikirim melalui parameter URL
if (!isset($_GET['user_id'])) {
    header("Location: halaman_admin.php");
    exit();
}

// Ambil user_id dari parameter URL
$user_id = $_GET['user_id'];

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "album");

// Periksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Periksa apakah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input dan lakukan update data pengguna
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $sql = "UPDATE users SET name='$name', username='$username', password='$password', email='$email' WHERE user_id='$user_id'";

    if (mysqli_query($conn, $sql)) {
        // Jika berhasil mengupdate, redirect ke halaman admin
        header("Location: halaman_admin.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

// Query untuk membaca data pengguna berdasarkan user_id
$sql_select_user = "SELECT * FROM users WHERE user_id='$user_id'";
$result_select_user = mysqli_query($conn, $sql_select_user);
$user_data = mysqli_fetch_assoc($result_select_user);

// Tutup koneksi ke database
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="style/slfa.css">
    <style>
        /* CSS untuk tampilan umum */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        /* CSS untuk konten utama */
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
        }

        /* CSS untuk form */
        form {
            margin-top: 20px;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #3498db; /* Warna biru */
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        button[type="submit"]:hover {
            background-color: #2980b9; /* Warna biru yang sedikit lebih gelap saat dihover */
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="dasboard.php">Home</a>
        <?php if (isset($_SESSION['name']) && $_SESSION['name'] !== '') : ?>
            <a href="create_album.php"><?php echo $_SESSION['name']; ?></a>
        <?php endif; ?>

        <?php if (isset($_SESSION['access_level']) && $_SESSION['access_level'] === 'admin') : ?>
            <a href="halaman_admin.php">admin</a>
        <?php endif; ?>
        <a href="upload.php">upload</a>
        <div style="float: right;">
            <a href="dalam/logout.php">Logout</a>
        </div>
    </div>
    <div class="container">
        <h2>Edit User</h2>
        <form method="post" onsubmit="return validateForm()" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?user_id=$user_id"; ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $user_data['name']; ?>" required>
            <span id="name-error"></span>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $user_data['username']; ?>" required>
            <span id="username-error"></span>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?php echo $user_data['password']; ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $user_data['email']; ?>" required>

            <button type="submit">Update User</button>
        </form>
    </div>
    <script>
        function validateForm() {
            var nameInput = document.getElementById("name").value;
            var regex = /^[A-Za-z\s]+$/; // Hanya huruf dan spasi diizinkan

            if (!regex.test(nameInput)) {
                document.getElementById("name-error").innerHTML = "Nama tidak boleh mengandung angka!";
                return false;
            } else {
                document.getElementById("name-error").innerHTML = "";
            }

            // Periksa keunikan username
            var username = document.getElementById("username").value;
            if (!checkUniqueUsername(username)) {
                document.getElementById("username-error").innerHTML = "Username sudah digunakan, silakan ganti username.";
                return false;
            } else {
                document.getElementById("username-error").innerHTML = "";
            }

            return true;
        }

        function checkUniqueUsername(username) {
            var isUnique = false;
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "check_username.php", false); // Menggunakan sinkron untuk memastikan respon diterima sebelum melanjutkan
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    if (xhr.responseText === "unique") {
                        isUnique = true;
                    }
                }
            };
            xhr.send("username=" + encodeURIComponent(username));
            return isUnique;
        }
    </script>
</body>
</html>