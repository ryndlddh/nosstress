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
    $_SESSION['access_level'] = $user_data['access_level']; // Tambahkan ini untuk memastikan akses level pengguna tersimpan dalam sesi
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
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="shortcut icon" href="asset/ryegallery.png" type="image/x-icon">
</head>
<body class="bg-gray-200">
<?php include 'navbar.php'; ?>
<?php if (!empty($success_message)): ?>
  
 <div class="success-message"><?php echo $success_message; ?></div>
<?php endif; ?>
<div class="container mx-auto p-4" style="margin-top: 72px;">
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
    <?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "album");

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$conn = mysqli_connect("localhost", "root", "", "album");

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Perbarui kondisi query untuk memastikan admin dapat melihat semua foto
$sql = "SELECT photos.*, users.name AS username, users.access_level
        FROM photos
        INNER JOIN users ON photos.user_id = users.user_id
        WHERE (photos.access = 'PUBLIC' OR photos.user_id = '$user_id' OR users.access_level = 'admin')
        ORDER BY photos.create_at DESC";

        

// Eksekusi query
$result = mysqli_query($conn, $sql);

// Cek apakah query berhasil dieksekusi
if ($result) {
    // Foto yang ditambahkan ke album
    if (isset($_SESSION['added_photo'])) {
        $added_photo = $_SESSION['added_photo'];
        echo "<div class='bg-white rounded shadow-md overflow-hidden'>";
        // Kode untuk menampilkan foto yang baru ditambahkan
        echo "</div>";
        unset($_SESSION['added_photo']);
    }

    // Semua foto dari database
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Cek apakah akses foto adalah "PUBLIC" atau pengguna saat ini adalah pengupload/admin
            if ($row['access'] == 'PUBLIC' || $row['user_id'] == $user_id || $_SESSION['access_level'] == 'admin') {
                echo "<div class='bg-white rounded shadow-md overflow-hidden'>";
                echo "<div class='p-4'>";
                echo "<div class='font-bold text-xl mb-2'>" . $row['username'] . ($row['access_level'] == 'admin' ? " <span class='text-blue-500'><i class='fa-solid fa-microchip'></i></span>" : "") . "</div>";
                echo "<img class='w-full h-80 object-cover' src='" . $row['image_path'] . "' alt='" . $row['title'] . "'>";
                echo "<div class='mt-2'>";
                echo "<p class='text-gray-700'>" . $row['title'] . "</p>";
                echo "<p class='text-gray-500'>" . $row['description'] . "</p>";
                echo "<br>";
                echo "<a href='view_comments.php?photo_id=" . $row['photo_id'] . "' class='bg-gray-400 hover:bg-black text-white px-4 py-2 rounded mr-2'>Lihat selengkapnya    <i class='fa-solid fa-arrow-right'></i></a>";
                echo "</div>";
                echo "<div class='mt-4'>";
                if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']) {
                    echo "<a href='tambah_photo_ke_album.php?photo_id=" . $row['photo_id'] . "' class='bg-blue-500 text-white px-4 py-2 rounded mr-2'><i class='fa-solid fa-plus'></i></a>";
                }
                if (isset($_SESSION['access_level']) && $_SESSION['access_level'] == 'admin') {
                    echo "<a href='dalam/edit.php?photo_id=" . $row['photo_id'] . "' class='bg-yellow-500 text-white px-4 py-2 rounded mr-2'><i class='fa-solid fa-pen'></i></a>";
                    echo "<button class='bg-red-500 text-white px-4 py-2 rounded' onclick='showConfirmation(this, " . $row['photo_id'] . ")'><i class='fa-solid fa-trash'></i></button>";
                } else if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']) {
                    echo "<a href='dalam/edit.php?photo_id=" . $row['photo_id'] . "' class='bg-yellow-500 text-white px-4 py-2 rounded mr-2'><i class='fa-solid fa-pen'></i></a>";
                    echo "<button class='bg-red-500 text-white px-4 py-2 rounded' onclick='showConfirmation(this, " . $row['photo_id'] . ")'><i class='fa-solid fa-trash'></i></button>";
                }
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        }
    } else {
        echo "<div class='bg-white rounded shadow-md overflow-hidden p-4'>";
        echo "<p class='text-gray-500'>Tidak ada data foto.</p>";
        echo "</div>";
    }
} else {
    echo "<div class='bg-white rounded shadow-md overflow-hidden p-4'>";
    echo "<p class='text-red-500'>Error: " . mysqli_error($conn) . "</p>";
    echo "</div>";
}

// Tutup koneksi ke database
mysqli_close($conn);
?>
    </div>
    
</div>
<?php include 'footer.php';?>

<script>
function showConfirmation() {
    var confirmation = confirm("Apakah Anda yakin ingin logout?");
    if (confirmation) {
        window.location.href = "dalam/logout.php";
    }
}

function sshowConfirmation(button, photoId) {
    var confirmation = confirm("Apakah Anda yakin ingin menghapus foto ini?");
    if (confirmation) {
        window.location.href = "dalam/hapus_photo.php?photo_id=" + photoId;
    }
}
</script>
</body>

