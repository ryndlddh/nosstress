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

// Ambil user_id dari sesi
$user_id = $_SESSION['user_id'];

// Periksa apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data form yang disubmit
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Validasi data form jika diperlukan

    // Simpan data album ke database
    $sql = "INSERT INTO albums (user_id, title, description, created_at) VALUES ('$user_id', '$title', '$description', NOW())";

    if (mysqli_query($conn, $sql)) {
        echo "Album berhasil dibuat!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Tutup koneksi ke database
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Album</title>
    <link rel="stylesheet" href="style/slfa.css">
    <link rel="stylesheet" href="style/komen.css">
</head>
<body>
    <div class="navbar">
        <a href="dasboard.php">Home</a>
        <?php if (isset($_SESSION['name']) && $_SESSION['name'] !== '') : ?>
            <a href="create_album.php"><?php echo $_SESSION['name']; ?></a>
        <?php endif; ?>

        <?php if (isset($_SESSION['access_level']) && $_SESSION['access_level'] === 'admin') : ?>
            <a href="halaman_admin.phpadmin">admin</a>
        <?php endif; ?>
        <a href="upload.php">upload</a>
        <div style="float: right;">
            <a href="dalam/logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <!-- Form to create new album -->
        <h2>Create New Album</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="title">Judul:</label><br>
            <input type="text" id="title" name="title"><br>
            <label for="description">Deskirpsi:</label><br>
            <textarea id="description" name="description"></textarea><br>
            <button type="submit" value="Create">buat</button>
        </form>
    </div>
</body>
</html>
