<?php
// Mulai sesi
session_start();

// Fungsi untuk mengarahkan pengguna ke halaman login
function redirect_to_login() {
    header("Location: dalam/login.php");
    exit();
}

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "album");

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Ambil data pengguna dari database berdasarkan user_id yang disimpan dalam sesi
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_id = '$user_id'";

// Eksekusi query
$result = mysqli_query($conn, $sql);

// Cek apakah query berhasil dieksekusi dan apakah pengguna ditemukan
if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    // Set session user_id
    $_SESSION['user_id'] = $user_id;
    $_SESSION['name'] = $user_data['name'];
} else {
    // Jika data pengguna tidak ditemukan, arahkan pengguna ke halaman login
    redirect_to_login();
}

// Tutup koneksi ke database
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pra</title>
    <link rel="stylesheet" href="style/slfa.css?v=<?php echo time()?>">
</head>
<body>
<?php include 'navbar.php'; ?>
    
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
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']) {
                echo "<div class='add_album'><button><a href='tambah_photo_ke_album.php?photo_id=" . $row['photo_id'] . "'>Tambah ke Album</a></button></div>";
                
                
            }
            echo "<div class='title'>Title: " . $row['title'] . "</div>";
            echo "<div class='img'><img src='" . $row['image_path'] . "' alt='" . $row['title'] . "'></div>";
            echo "<div class='description'>" . $row['description'] . "</div>";
            echo "<br>";
            echo "<div class='created-at'>" . $row['create_at'] . "</div>";
            echo "<div><button><a href='view_comments.php?photo_id=" . $row['photo_id'] . "'>Lihat Komentar</a></button></div>";
            // Tambahkan logic sesuai dengan role pengguna
            
            if (isset($_SESSION['access_level']) && $_SESSION['access_level'] == 'admin') {
                
                echo "<div><button><a href='dalam/edit.php?photo_id=" . $row['photo_id'] . "'>Edit</a></button></div>";
                echo "<div ><button class='detele' onclick='sshowConfirmation(this, " . $row['photo_id'] . ")'>Hapus</button></div>";

                
                
            } else if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']) {
                
                echo "<div><button><a href='dalam/edit.php?photo_id=" . $row['photo_id'] . "'>Edit</a></button></div>";
                echo "<div ><button class='detele' onclick='sshowConfirmation(this, " . $row['photo_id'] . ")'>Hapus</button></div>";

                
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
<script>
    function sshowConfirmation(button, photoId) {
    // Tampilkan notifikasi konfirmasi
    var confirmation = confirm("Apakah Anda yakin ingin menghapus foto ini?");
    
    // Jika pengguna menekan tombol "OK" pada notifikasi konfirmasi
    if (confirmation) {
        // Lakukan perintah hapus foto atau tindakan lainnya
        window.location.href = "dalam/hapus_photo.php?photo_id=" + photoId; // Ganti dengan URL untuk menghapus foto
    } else {
        // Jika pengguna memilih "Tidak" atau menutup notifikasi, tidak ada tindakan yang diambil
    }
}

</script>