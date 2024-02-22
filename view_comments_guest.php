<?php
// Mulai sesi
session_start();

// Fungsi untuk mengarahkan pengguna ke halaman login jika belum login
function redirect_to_login() {
    header("Location: dalam/login.php");
    exit();
}

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "album");

// Periksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Ambil photo_id dari parameter URL
if (isset($_GET['photo_id'])) {
    $photo_id = $_GET['photo_id'];
} else {
    // Jika parameter photo_id tidak tersedia, arahkan pengguna kembali ke halaman sebelumnya
    header("Location: dasboard.php");
    exit();
}

// Ambil informasi foto dari database berdasarkan photo_id
$sql_photo = "SELECT * FROM photos WHERE photo_id = '$photo_id'";
$result_photo = mysqli_query($conn, $sql_photo);
if ($result_photo && mysqli_num_rows($result_photo) > 0) {
    $photo_data = mysqli_fetch_assoc($result_photo);
} else {
    // Jika foto tidak ditemukan, arahkan pengguna kembali ke halaman sebelumnya
    header("Location: dasboard.php");
    exit();
}

// Ambil semua komentar terkait dengan foto ini
$sql_comments = "SELECT c.comment_id, c.user_id, c.photo_id, c.comment_text, c.created_at, u.name as commenter_name
                 FROM comments c
                 INNER JOIN users u ON c.user_id = u.user_id
                 WHERE c.photo_id = '$photo_id'
                 ORDER BY c.created_at DESC";
$result_comments = mysqli_query($conn, $sql_comments);

// Ambil jumlah like untuk foto ini
$sql_like_count = "SELECT COUNT(*) AS like_count FROM likes WHERE photo_id = '$photo_id'";
$result_like_count = mysqli_query($conn, $sql_like_count);
$like_count = mysqli_fetch_assoc($result_like_count)['like_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Photo</title>
    <link rel="stylesheet" href="style/slfa.css">
    <link rel="stylesheet" href="style/komen.css">
</head>
<body>
<div class="navbar">
        <a href="index.php">Home</a>
        <a href="#admin">guest</a>
        <div style="float: right;">
        <a href="dalam/login.php">Login</a>
        </div>
    </div>
    <div class="container">
        <!-- Photo Details Section -->
        <div class="photo-details">
            <h2><?php echo $photo_data['title']; ?></h2>
            <p><?php echo $photo_data['description']; ?></p>
            <img src="<?php echo $photo_data['image_path']; ?>" alt="<?php echo $photo_data['title']; ?>">
            <p>Likes: <?php echo $like_count; ?></p>
        </div>

        <!-- Comments Section -->
        <div class="comments-section">
            <h3>Comments</h3>
            <!-- Display existing comments -->
            <?php
            if ($result_comments && mysqli_num_rows($result_comments) > 0) {
                while ($row = mysqli_fetch_assoc($result_comments)) {
                    echo "<div class='comment'>";
                    echo "<p><strong>" . $row['commenter_name'] . "</strong>: " . $row['comment_text'] . "</p>";
                    echo "<small>" . $row['created_at'] . "</small>";
                    echo "</div>";
                }
            } else {
                echo "<p>Belum ada komentar di foto ini.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>

<?php
// Tutup koneksi ke database
mysqli_close($conn);
?>