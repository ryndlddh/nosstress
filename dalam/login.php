
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
    <title>Login RyeGallery</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="shortcut icon" href="../asset/ryegallery.png" type="image/x-icon">
</head>
<body class="bg-gray-200">
    <?php include 'nav.php' ?>

    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Login RyeGallery
                </h2>
            </div>
            <?php if (!empty($success_message)): ?>
                <div class="text-center text-green-500 mt-4"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="mt-8 space-y-6">
                <input type="hidden" name="remember" value="true">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="username" class="sr-only">Username</label>
                        <input id="username" name="username" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Username">
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password">
                            <input type="checkbox" id="showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                            <label for="showPassword" class="text-sm text-gray-500">Tampilkan sandi</label>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Login
                    </button>
                </div>
            </form>
            <div class="text-center text-gray-500 text-xs">
                <p>Belim punya akun? <a href="daftar.php" class="text-blue-500 hover:text-blue-800">Daftar Akun</a>.</p>
            </div>
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

    <?php include '../footer.php' ?>
</body>
</html>
