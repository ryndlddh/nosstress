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

// Query untuk mengambil semua album beserta foto-fotonya
$sql = "SELECT albums.*, users.name as user_name, GROUP_CONCAT(photos.image_path) as photo_paths
        FROM albums
        INNER JOIN users ON albums.user_id = users.user_id
        INNER JOIN photos ON albums.album_id = photos.album_id
        GROUP BY albums.album_id";
$result = mysqli_query($conn, $sql);

// Inisialisasi array untuk menyimpan data album dan foto
$albums = array();

// Periksa apakah ada hasil dari query
if (mysqli_num_rows($result) > 0) {
    // Ambil setiap baris hasil query dan simpan dalam array
    while ($row = mysqli_fetch_assoc($result)) {
        $row['photo_paths'] = explode(',', $row['photo_paths']); // Pisahkan setiap path foto menjadi array
        $albums[] = $row;
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
    <title>Read Album</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        body {
            background-color: #f3f4f6; /* Warna latar belakang body */
        }

        .album-container {
            background-color: #ffffff; /* Warna latar belakang album */
            padding: 20px; /* Padding untuk memisahkan antara album */
            margin-bottom: 20px; /* Margin bawah untuk memisahkan antara album */
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'?>
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-4">Album Gallery</h1>
        <?php foreach ($albums as $album): ?>
            <div class="album-container">
                <h2 class="text-2xl font-semibold mb-2"><?php echo $album['title']; ?></h2>
                <p class="text-gray-600 mb-4"><?php echo $album['description']; ?></p>
                <p class="text-gray-700">By <?php echo $album['user_name']; ?></p>
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-4">
                    <?php foreach ($album['photo_paths'] as $photo_path): ?>
                        <div class="bg-white rounded shadow-md overflow-hidden">
                            <img class="w-full h-48 object-cover" src="<?php echo $photo_path; ?>" alt="<?php echo $album['title']; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
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