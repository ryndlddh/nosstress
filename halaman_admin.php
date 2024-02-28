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
<title>Admin Page - User List</title>
<link rel="stylesheet" href="style/slfa.css">
<style>
    /* CSS untuk tampilan umum */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f5f5f5;
    }

    /* CSS untuk konten utama */
    .container {
        max-width: 1200px;
        margin: 20px auto;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        padding: 20px;
    }

    .user-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .user-table th,
    .user-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .user-table th {
        background-color: #3498db;
        color: white;
    }

    .user-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

/* CSS untuk tombol edit */
.edit-button {
    background-color: #4CAF50; /* Warna hijau */
    color: white;
    border: none;
    padding: 8px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin: 4px 2px;
    cursor: pointer;
    transition-duration: 0.4s;
    width: calc(100% - 10px); /* Ukuran tombol menyesuaikan lebar kolom */
}

.edit-button:hover {
    background-color: #45a049; /* Warna hijau yang sedikit lebih gelap saat dihover */
}

/* CSS untuk tombol hapus */
.delete-button {
    background-color: #f44336; /* Warna merah */
    color: white;
    border: none;
    padding: 8px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin: 4px 2px;
    cursor: pointer;
    transition-duration: 0.4s;
    width: calc(100% - 10px); /* Ukuran tombol menyesuaikan lebar kolom */
}

.delete-button:hover {
    background-color: #ff3d00; /* Warna merah yang sedikit lebih gelap saat dihover */
}


    /* CSS Responsif */
    @media only screen and (max-width: 600px) {
        .container {
            width: 100%;
            padding: 10px;
        }

        .user-table {
            font-size: 12px;
        }
    }
</style>
</head>
<body>
<?php include 'navbar.php'; ?>
    <div class="container">
        <h2>User List</h2>
        <?php
// Cek apakah ada pesan dari halaman_hapus_user.php
if (isset($_GET['pesan'])) {
    $pesan = urldecode($_GET['pesan']);
    echo "<p style='color: green;'>" . $pesan . "</p>";
}
?>
        <table class="user-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Email</th>
                    <th>Access Level</th>
                    <th>Dibuat pada</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
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

                // Tampilkan data dari tabel users dalam bentuk baris tabel HTML
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["user_id"] . "</td>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["username"] . "</td>";
        echo "<td>" . $row["password"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["access_level"] . "</td>";
        echo "<td>" . $row["create_at"] . "</td>";

        // Cek level akses pengguna sebelum menampilkan tombol Edit dan Delete
        if ($row["access_level"] !== "admin") {
            echo "<td><a class='edit-button' href='halaman_edit_user.php?user_id=" . $row['user_id'] . "'>Edit</a></td>";
            echo "<td><a class='delete-button' href='halaman_hapus_user.php?user_id=" . $row['user_id'] . "'>Delete</a></td>";
        } else {
            echo "<td></td><td></td>"; // Tampilkan kolom kosong untuk pengguna admin
        }

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='9'>No users found</td></tr>";
}

                // Tutup koneksi ke database
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>
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