<?php
session_start();

// Periksa apakah pengguna adalah admin
if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] !== 'admin') {
    // Jika bukan admin, arahkan ke index.php
    header("Location: index.php");
    exit();
}
?>
<?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "album");

// Query untuk mendapatkan daftar album
$sql_albums = "SELECT * FROM albums";
$result_albums = mysqli_query($conn, $sql_albums);
?>

<!DOCTYPE html>
<html>
<head>
<style>
        body {
    font-family: Arial, sans-serif;
    background-color: #e6f2ff; /* Warna latar belakang biru langit */
    color: #333;
    margin: 0;
    padding: 20px;
}

h1 {
    color: #0066cc; /* Warna judul biru */
    text-align: center;
}

.album {
    background-color: #fff;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.album h2 {
    color: #0066cc; /* Warna judul album biru */
    margin-top: 0;
}

.photo-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    margin-top: 10px;
}

.photo-item {
    margin-right: 10px;
    margin-bottom: 10px;
}

.photo-item img {
    max-width: 200px;
    height: auto;
}
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
    <h1>Daftar Album</h1>

    <?php
    // Query untuk mendapatkan daftar album
$sql_albums = "SELECT albums.*, photos.image_path, users.name
FROM albums
INNER JOIN photos ON albums.album_id = photos.album_id
INNER JOIN users ON albums.user_id = users.user_id";
$result_albums = mysqli_query($conn, $sql_albums);
    if (mysqli_num_rows($result_albums) > 0) {
        while ($row = mysqli_fetch_assoc($result_albums)) {
            echo "<div class='album'>";
            echo "<h2>" . $row['title'] . "</h2>";
            echo "<div class='user-id'>" . $row['name'] . "</div>";
            // Query untuk mendapatkan foto-foto dalam album
            $album_id = $row['album_id'];
            $sql_photos = "SELECT * FROM photos WHERE album_id = '$album_id'";
            $result_photos = mysqli_query($conn, $sql_photos);

            // Menampilkan foto-foto dalam album
            if (mysqli_num_rows($result_photos) > 0) {
                echo "<div class='photo-list'>";
                while ($photo = mysqli_fetch_assoc($result_photos)) {
                    echo "<div class='photo-item'>";
                    
                    echo "<div class='img'><img src='" . $photo['image_path'] . "' alt='Photo'></div>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<p>Tidak ada foto dalam album ini.</p>";
            }

            echo "</div>";
        }
    } else {
        echo "<p>Tidak ada album.</p>";
    }
    ?>

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