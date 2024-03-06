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

// Ambil photo_id dari parameter URL
if (isset($_GET['photo_id'])) {
    $photo_id = $_GET['photo_id'];
} else {
    // Jika parameter photo_id tidak tersedia, arahkan pengguna kembali ke halaman sebelumnya
    header("Location: dasboard.php");
    exit();
}

// Query untuk mendapatkan daftar album yang dimiliki oleh pengguna
$sql_albums = "SELECT album_id, title FROM albums WHERE user_id = '$user_id'";
$result_albums = mysqli_query($conn, $sql_albums);

// Proses form ketika disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil album_id yang dipilih dari form
    $album_id = $_POST['album_id'];

    // Memperbarui album_id di tabel photos
    $sql_update_album_id = "UPDATE photos SET album_id = '$album_id' WHERE photo_id = '$photo_id'";
    if (mysqli_query($conn, $sql_update_album_id)) {
        echo "Album ID berhasil diperbarui pada tabel photos!";
    } else {
        echo "Error: " . $sql_update_album_id . "<br>" . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambahkan Foto ke Album</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="shortcut icon" href="asset/ryegallery.png" type="image/x-icon">
</head>
<body class="bg-gray-200">
<?php include'navbar.php'; ?>

    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold text-center mb-4">Tambahkan Foto ke Album</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?photo_id=$photo_id"; ?>" class="space-y-4">
            <label for="album_id" class="block text-sm font-medium text-gray-700">Pilih Album:</label>
            <select id="album_id" name="album_id" required class="mt-1 block w-full p-2 border border-gray-300 rounded">
                <option value="" disabled selected>Pilih Album</option>
                <?php
                // Tampilkan daftar album yang dimiliki oleh pengguna
                if (mysqli_num_rows($result_albums) > 0) {
                    while ($row = mysqli_fetch_assoc($result_albums)) {
                        echo "<option value='" . $row['album_id'] . "'>" . $row['title'] . "</option>";
                    }
                } else {
                    echo "<option value='' disabled>Tidak ada album</option>";
                }
                ?>
            </select>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Tambahkan Ke Album</button>
        </form>
    </div>
    <?php include 'footer.php' ?>
</body>
</html>


<?php
// Tutup koneksi ke database
mysqli_close($conn);
?>
<script>
function showConfirmation() {
    // Tampilkan notifikasi konfirmasi
    var confirmation = confirm("Apakah Anda yakin ingin logout?");
    
    // Jika pengguna menekan tombol "OK" pada notifikasi konfirmasi
    if (confirmation) {
        // Lakukan perintah logout atau tindakan lainnya
        window.location.href = "dalam/logout.php"; // Ganti dengan URL logout atau tindakan lainnya
    } else {
        // Jika pengguna memilih "Tidak" atau menutup notifikasi, tidak ada tindakan yang diambil
    }
}
</script>