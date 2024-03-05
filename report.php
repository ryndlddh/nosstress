<?php
// Mulai sesi
session_start();

// Fungsi untuk mengarahkan pengguna ke halaman login jika belum login
function redirect_to_login() {
    header("Location: login.php");
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

// Ambil photo_id dari parameter URL
if (isset($_GET['photo_id'])) {
    $photo_id = $_GET['photo_id'];
} else {
    // Jika parameter photo_id tidak tersedia, arahkan pengguna kembali ke halaman sebelumnya
    header("Location: dasboard.php");
    exit();
}

// Proses form jika dikirimkan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $report_text = $_POST['report_text'];
    $created_at = date('Y-m-d H:i:s');

    // Menyiapkan query SQL untuk menyimpan laporan
    $sql = "INSERT INTO `report` (`user_id`, `photo_id`, `report`, `created_at`) VALUES ('$user_id', '$photo_id', '$report_text', '$created_at')";

    // Mengeksekusi query SQL
    if (mysqli_query($conn, $sql)) {
        // Jika berhasil, arahkan pengguna kembali ke halaman sebelumnya
        header("Location: view_comments.php?photo_id=$photo_id");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error
        $error_message = "Terjadi kesalahan saat menyimpan laporan: " . mysqli_error($conn);
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
    <title>Laporkan Foto</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body class="bg-gray-200">
<?php include 'navbar.php'; ?>

<div class="container mx-auto p-4">
    <div class="bg-white rounded shadow-md overflow-hidden p-4">
        <h2 class="text-2xl font-bold mb-4">Laporkan Foto</h2>
        <?php if (isset($error_message)) : ?>
            <p class="text-red-500 mb-4"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?photo_id=' . $photo_id); ?>">
            <div class="mb-4">
                <label for="report_text" class="block text-gray-700 font-bold mb-2">Laporan</label>
                <textarea name="report_text" id="report_text" rows="4" class="w-full p-2 border border-gray-300 rounded" required></textarea>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Kirim Laporan</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    function showConfirmation() {
    var confirmation = confirm("Apakah Anda yakin ingin logout?");
    if (confirmation) {
        window.location.href = "dalam/logout.php";
    }
}
</script>
</body>
</html>