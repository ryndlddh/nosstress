<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/slfa.css">
</head>
<body>
    
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="#admin">guest</a>
        <div style="float: right;">
        <a href="dalam/login.php">Login</a>
        </div>
    </div>
    
    <?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "album");

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$sql = "SELECT photos.*, users.name as username FROM photos INNER JOIN users ON photos.user_id = users.user_id"; // Menggunakan INNER JOIN untuk menggabungkan tabel photos dan users

// Eksekusi query
$result = mysqli_query($conn, $sql);

// Container div untuk menampilkan data
echo "<div class='photo-container'>";
echo "<div class='photo-wrapper'>";
// Cek apakah query berhasil dieksekusi
if ($result) {
    $counter = 0; // Counter untuk menghitung jumlah item
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Membuat div untuk setiap data foto
                 // Membuat pembungkus baru setiap 4 item

            echo "<div class='photo-item'>";
            echo "<div class='user-id'>" . $row['username'] . "</div>"; // Menampilkan username dari tabel users
            $title = strlen($row['title']) > 20 ? substr($row['title'], 0, 20) . "..." : $row['title'];
            $description = strlen($row['description']) > 20 ? substr($row['description'], 0, 20) . "..." : $row['description'];
            
            echo "<div class='title'>Title: " . $row['title'] . "</div>";
            echo "<div class='img'><img src='" . $row['image_path'] . "' alt='" . $row['title'] . "'></div>";
            echo "<div class='description'>" . $row['description'] . "</div>";
            echo "<br>";
            echo "<div class='created-at'>" . $row['create_at'] . "</div>";

            // Tambahkan logic sesuai dengan role pengguna
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']) {
                // Jika pengguna memiliki peran admin atau user_id nya sama dengan user_id pada foto, tampilkan tombol atau fungsi tambahan
                echo "<div><button>Edit</button></div>";
                echo "<div><button>Delete</button></div>";
            }
            if (isset($_SESSION['access_level']) && $_SESSION['access_level'] == 'admin') {
                // Jika pengguna memiliki peran admin, tampilkan tombol atau fungsi tambahan
                echo "<div><button>Edit</button></div>";
                echo "<div><button>Delete</button></div>";
            }
            
            echo "</div>"; // Tutup div photo-item
        }
        
    } else {
        echo "<div class='no-data'>Tidak ada data foto.</div>";
    }
} else {
    echo "<div class='error'>Error: " . mysqli_error($conn) . "</div>";
}

echo "</div>"; 
echo "</div>"; 

// Tutup koneksi ke database
mysqli_close($conn);
?>

</body>
</html>