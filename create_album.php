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
    $access = $_POST['access'];

    // Validasi data form jika diperlukan

    // Simpan data album ke database
    // Simpan data album ke database
$sql = "INSERT INTO albums (user_id, title, description, created_at, access) VALUES ('$user_id', '$title', '$description', NOW(), '$access')";

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
    <title>Buat Album</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="shortcut icon" href="asset/ryegallery.png" type="image/x-icon">
</head>
<body class="bg-gray-200">
<?php include 'navbar.php'; ?>

    <div class="container mx-auto p-4" style="margin-top: 72px;">
        <h2 class="text-2xl font-bold text-center mb-4">Buat Album Baru</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Judul:</label>
            <input type="text" id="title" name="title" class="mt-1 block w-full p-2 border border-gray-300 rounded">

            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi:</label>
            <textarea id="description" name="description" class="mt-1 block w-full p-2 border border-gray-300 rounded"></textarea>

            <label for="access" class="block text-sm font-medium text-gray-700">Akses:</label>
            <div class="mt-1">
                <label class="inline-flex items-center">
                    <input type="radio" class="form-radio" name="access" value="PUBLIC" checked>
                    <span class="ml-2">Publik</span>
                </label>
                <label class="inline-flex items-center ml-6">
                    <input type="radio" class="form-radio" name="access" value="PRIVATE">
                    <span class="ml-2">Privat</span>
                </label>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Buat</button>
        </form>
    </div>
    <?php include 'footer.php'?>
</body>
</html>

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
