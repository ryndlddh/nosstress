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

<div class="container mx-auto p-4" style="margin-top: 72px;">
    <div class="bg-white rounded shadow-md overflow-hidden p-4">
        <h2 class="text-2xl font-bold mb-4">Daftar Laporan</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php if (mysqli_num_rows($result) > 0) : ?>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <div class="bg-white shadow-md rounded p-4">
                    <div class="flex justify-between items-center">
                        <div><strong>Username:</strong> <?php echo $row['username']; ?></div>
                        
                    </div>
                    <div><strong>Tanggal Lapor:</strong> <?php echo $row['created_at']; ?></div>
                    <div><strong>Judul Foto:</strong> <?php echo $row['photo_title']; ?></div>
                    <div><strong>Laporan:</strong> <?php echo $row['report']; ?></div>
                    <div class="flex justify-end">
                        <a href='view_comments.php?photo_id=<?php echo $row['photo_id']; ?>' class='bg-blue-500 text-white px-4 py-2 rounded mr-2'>Lihat Foto</a>
                        <button onclick="sshowConfirmation('<?php echo $row['report_id']; ?>')" class="bg-red-500 text-white px-4 py-2 rounded"><i class='fa-solid fa-trash'></i></button>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="bg-white shadow-md rounded p-4">Tidak ada laporan.</div>
            <?php endif; ?>
        </div>
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
