<?php
// Mulai sesi
session_start();

// Fungsi untuk mengarahkan pengguna ke halaman login jika belum login
function redirect_to_login() {
    header("Location: dalam/login.php");
    exit();
}

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    redirect_to_login();
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

// Cek apakah pengguna sudah memberikan like pada foto ini
$user_id = $_SESSION['user_id'];
$sql_like_check = "SELECT * FROM likes WHERE user_id = '$user_id' AND photo_id = '$photo_id'";
$result_like_check = mysqli_query($conn, $sql_like_check);
$has_liked = mysqli_num_rows($result_like_check) > 0;

// Proses like/unlike
if (isset($_POST['like_action'])) {
    $action = $_POST['like_action'];
    $created_at = date('Y-m-d H:i:s');

    if ($action == 'like') {
        // Masukkan like ke dalam database
        $sql_insert_like = "INSERT INTO likes (user_id, photo_id, created_at) VALUES ('$user_id', '$photo_id', '$created_at')";
        mysqli_query($conn, $sql_insert_like);
    } elseif ($action == 'unlike') {
        // Hapus like dari database
        $sql_delete_like = "DELETE FROM likes WHERE user_id = '$user_id' AND photo_id = '$photo_id'";
        mysqli_query($conn, $sql_delete_like);
    }

    // Refresh halaman setelah like/unlike
    header("Location: view_comments.php?photo_id=$photo_id");
    exit();
}

// Ambil jumlah like untuk foto ini
$sql_like_count = "SELECT COUNT(*) AS like_count FROM likes WHERE photo_id = '$photo_id'";
$result_like_count = mysqli_query($conn, $sql_like_count);
$like_count = mysqli_fetch_assoc($result_like_count)['like_count'];

// Fungsi untuk membuat komentar baru
// ... (kode lama untuk menambahkan komentar baru)

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
        <a href="dasboard.php">Home</a>
        <?php if (isset($_SESSION['name']) && $_SESSION['name'] !== '') : ?>
            <a href="create_album.php"><?php echo $_SESSION['name']; ?></a>
        <?php endif; ?>

        <?php if (isset($_SESSION['access_level']) && $_SESSION['access_level'] === 'admin') : ?>
            <a href="halaman_admin.phpadmin">admin</a>
        <?php endif; ?>
        <a href="upload.php">upload</a>
        <div style="float: right;">
            <a href="dalam/logout.php">Logout</a>
        </div>
    </div>


    <div class="container">
        <!-- Photo Details Section -->
        <div class="photo-details">
            <h2><?php echo $photo_data['title']; ?></h2>
            <p><?php echo $photo_data['description']; ?></p>
            <img src="<?php echo $photo_data['image_path']; ?>" alt="<?php echo $photo_data['title']; ?>">
            <p>Likes: <?php echo $like_count; ?></p>
            <form method="post" action="view_comments.php?photo_id=<?php echo $photo_id; ?>">
                <?php if ($has_liked) : ?>
                    <button class="like-button unlike" type="submit" name="like_action" value="unlike">Unlike</button>
                <?php else : ?>
                    <button class="like-button" type="submit" name="like_action" value="like">Like</button>
                <?php endif; ?>
            </form>
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
                    if (isset($_SESSION['access_level']) && $_SESSION['access_level'] === 'admin') {
                        // Admin can edit and delete any comment
                        echo "<div><button><a href='edit_comment.php?comment_id=" . $row['comment_id'] . "'>Edit</a></button></div>";
                        echo "<div><button><a href='delete_comment.php?comment_id=" . $row['comment_id'] . "'>Delete</a></button></div>";
                    } elseif (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']) {
                        // User can edit and delete their own comments
                        echo "<div><button><a href='edit_comment.php?comment_id=" . $row['comment_id'] . "'>Edit</a></button></div>";
                        echo "<div><button><a href='delete_comment.php?comment_id=" . $row['comment_id'] . "'>Delete</a></button></div>";
                    }
                    echo "</div>";
                }
            } else {
                echo "<p>Belum ada komentar di foto ini.</p>";
            }
            ?>

            <!-- Form to add new comment -->
            <form action="view_comments.php?photo_id=<?php echo $photo_id; ?>" method="POST">
                <textarea name="comment_text" rows="3" placeholder="Add your comment here..." required></textarea>
                <button type="submit" name="submit_comment">Add Comment</button>
            </form>
            <?php
            // Display error message if comment insertion fails
            if (isset($comment_error)) {
                echo "<p>$comment_error</p>";
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
