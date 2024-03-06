<?php
// Mulai sesi
session_start();

// Periksa apakah pengguna adalah admin
if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] !== 'admin') {
    // Jika bukan admin, arahkan ke index.php
    header("Location: index.php");
    exit();
}

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "album");

// Periksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Query untuk mengambil laporan dengan INNER JOIN
$sql = "SELECT r.report_id, u.name AS username, p.title AS photo_title, r.report, r.created_at, r.photo_id
        FROM `report` r
        INNER JOIN users u ON r.user_id = u.user_id
        INNER JOIN photos p ON r.photo_id = p.photo_id
        ORDER BY r.created_at DESC";

$result = mysqli_query($conn, $sql);

// Tutup koneksi ke database
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Laporan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="shortcut icon" href="asset/ryegallery.png" type="image/x-icon">
</head>
<body class="bg-gray-200">
<?php include 'navbar.php'; ?>

<div class="container mx-auto p-4">
    <div class="bg-white rounded shadow-md overflow-hidden p-4">
        <h2 class="text-2xl font-bold mb-4">Daftar Laporan</h2>
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">Username</th>
                    <th class="px-4 py-2">Judul Foto</th>
                    <th class="px-4 py-2">Laporan</th>
                    <th class="px-4 py-2">Tanggal Lapor</th>
                    <th class="px-4 py-2">Lihat Komentar</th>
                    <th class="px-4 py-2">Hapus Laporan</th> <!-- Kolom baru untuk tombol -->
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0) : ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                    <td class="border px-4 py-2"><?php echo $row['username']; ?></td>
                    <td class="border px-4 py-2"><?php echo $row['photo_title']; ?></td>
                    <td class="border px-4 py-2"><?php echo $row['report']; ?></td>
                    <td class="border px-4 py-2"><?php echo $row['created_at']; ?></td>
                    <td class="border px-4 py-2">
                    <a href='view_comments.php?photo_id=<?php echo $row['photo_id']; ?>' class=''>Lihat Foto</a>
                    </td>
                    
                    <!-- Bagian yang diperbaiki -->
                    <td class="border px-4 py-2">
                        <button onclick="sshowConfirmation('<?php echo $row['report_id']; ?>')" class="bg-red-500 text-white px-4 py-2 rounded"><i class='fa-solid fa-trash'></i></button>
                    </td>

                    
                    </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="border px-4 py-2 text-center">Tidak ada laporan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
<script>
    function showConfirmation() {
    var confirmation = confirm("Apakah Anda yakin ingin logout?");
    if (confirmation) {
        window.location.href = "dalam/logout.php";
    }
}

function sshowConfirmation(reportId) {
        var confirmation = confirm("Apakah Anda yakin ingin menghapus laporan ini?");
        if (confirmation) {
            window.location.href = "hapus_report.php?report_id=" + reportId;
        }
    }


</script>
</body>
</html>
