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
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold text-center mb-4">User List</h2>
        <?php
        // Cek apakah ada pesan dari halaman_hapus_user.php
        if (isset($_GET['pesan'])) {
            $pesan = urldecode($_GET['pesan']);
            echo "<p class='text-green-500'>" . $pesan . "</p>";
        }
        ?>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">User ID</th>
                    <th class="border border-gray-300 px-4 py-2">Nama</th>
                    <th class="border border-gray-300 px-4 py-2">Username</th>
                    <th class="border border-gray-300 px-4 py-2">Password</th>
                    <th class="border border-gray-300 px-4 py-2">Email</th>
                    <th class="border border-gray-300 px-4 py-2">Access Level</th>
                    <th class="border border-gray-300 px-4 py-2">Dibuat pada</th>
                    <th class="border border-gray-300 px-4 py-2">Edit</th>
                    <th class="border border-gray-300 px-4 py-2">Delete</th>
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
                        echo "<tr class='" . ($row["access_level"] !== "admin" ? "bg-gray-100" : "") . "'>";
                        echo "<td class='border border-gray-300 px-4 py-2'>" . $row["user_id"] . "</td>";
                        echo "<td class='border border-gray-300 px-4 py-2'>" . $row["name"] . "</td>";
                        echo "<td class='border border-gray-300 px-4 py-2'>" . $row["username"] . "</td>";
                        echo "<td class='border border-gray-300 px-4 py-2'>" . $row["password"] . "</td>";
                        echo "<td class='border border-gray-300 px-4 py-2'>" . $row["email"] . "</td>";
                        echo "<td class='border border-gray-300 px-4 py-2'>" . $row["access_level"] . "</td>";
                        echo "<td class='border border-gray-300 px-4 py-2'>" . $row["create_at"] . "</td>";

                        // Cek level akses pengguna sebelum menampilkan tombol Edit dan Delete
                        if ($row["access_level"] !== "admin") {
                            echo "<td class='border border-gray-300 px-4 py-2'><a class='bg-green-400 text-white px-4 py-2 rounded' href='halaman_edit_user.php?user_id=" . $row['user_id'] . "'>Edit</a></td>";
                            echo "<td class='border border-gray-300 px-4 py-2'>
                                  <button class='bg-red-500 text-white px-4 py-2 rounded' onclick='sshowConfirmation(\"halaman_hapus_user.php?user_id=" . $row['user_id'] . "\")'>Delete</button>
                                  </td>";

                        } else {
                            echo "<td class='border border-black-300 px-4 py-2'></td><td class='border border-gray-300 px-4 py-2'></td>"; // Tampilkan kolom kosong untuk pengguna admin
                        }

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' class='border border-gray-300 px-4 py-2'>No users found</td></tr>";
                }

                // Tutup koneksi ke database
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
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
