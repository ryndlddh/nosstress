<?php
session_start();

// Periksa apakah pengguna adalah admin
if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] !== 'admin') {
    // Jika bukan admin, arahkan ke index.php
    header("Location: index.php");
    exit();
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
    
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</head>
<body class="bg-gray-200">
<?php include 'navbar.php'; ?>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold text-center mb-4">Edit User</h2>
        <form method="post" onsubmit="return validateForm()" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?user_id=$user_id"; ?>" class="space-y-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $user_data['name']; ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded">
            <span id="name-error" class="text-red-500"></span>

            <label for="username" class="block text-sm font-medium text-gray-700">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $user_data['username']; ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded">
            <span id="username-error" class="text-red-500"></span>

            <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
            <input type="password" id="password" name="password" value="<?php echo $user_data['password']; ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded">

            <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $user_data['email']; ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded">

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update User</button>
        </form>
    </div>
    <?php include 'footer.php'?>
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
    </script>
</body>
</html>
