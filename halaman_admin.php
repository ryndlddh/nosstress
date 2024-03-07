<?php
session_start();

// Periksa apakah pengguna adalah admin
if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] !== 'admin') {
    // Jika bukan admin, arahkan ke index.php
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Halaman Admin</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<link rel="shortcut icon" href="asset/ryegallery.png" type="image/x-icon">
</head>
<body class="bg-gray-200">
<?php include 'navbar.php'; ?>
<div class="container mx-auto p-4" style="margin-top: 72px;">
    <h2 class="text-sm md:text-base lg:text-lg xl:text-xl">Pengguna</h2>

    <?php
    // Cek apakah ada pesan dari halaman_hapus_user.php
    if (isset($_GET['pesan'])) {
        $pesan = urldecode($_GET['pesan']);
        echo "<p class='text-green-500'>" . $pesan . "</p>";
    }
    ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php
        // Koneksi ke database
        $conn = mysqli_connect("localhost", "root", "", "album");

        // Periksa koneksi
        if (!$conn) {
            die("Koneksi gagal: " . mysqli_connect_error());
        }

        // Query untuk membaca data dari tabel users
        $sql = "SELECT `user_id`, `name`, `username`, `password`, `email`, `access_level`, `create_at` FROM `users`";
        $result = mysqli_query($conn, $sql);

        // Tampilkan data dari tabel users dalam bentuk card
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='bg-white shadow-md rounded p-4'>";
                echo "<div class='flex justify-between items-center'>";
                echo "<div><strong>User ID:</strong> " . $row["user_id"] . "</div>";
                echo "<div><strong>Access Level:</strong> " . $row["access_level"] . "</div>";
                echo "</div>";
                echo "<div><strong>Nama:</strong> " . $row["name"] . "</div>";
                echo "<div><strong>Username:</strong> " . $row["username"] . "</div>";
                echo "<div><strong>Email:</strong> " . $row["email"] . "</div>";
                echo "<div><strong>Dibuat pada:</strong> " . $row["create_at"] . "</div>";

                // Cek level akses pengguna sebelum menampilkan tombol Edit dan Delete
                if ($row["access_level"] !== "admin") {
                    echo "<div class='flex justify-end'>";
                    echo "<a class='bg-green-400 text-white px-4 py-2 rounded mr-2' href='halaman_edit_user.php?user_id=" . $row['user_id'] . "'>Edit</a>";
                    echo "<button class='bg-red-500 text-white px-4 py-2 rounded' onclick='sshowConfirmation(\"halaman_hapus_user.php?user_id=" . $row['user_id'] . "\")'>Delete</button>";
                    echo "</div>";
                }

                echo "</div>";
            }
        } else {
            echo "<div class='bg-white shadow-md rounded p-4'>No users found</div>";
        }

        // Tutup koneksi ke database
        mysqli_close($conn);
        ?>
    </div>
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

<script>
    function sshowConfirmation(url) {
        if (confirm("Are you sure you want to delete this user?")) {
            window.location.href = url;
        }
    }
</script>
