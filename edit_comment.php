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

// Ambil comment_id dari parameter URL
if (isset($_GET['comment_id'])) {
    $comment_id = $_GET['comment_id'];
} else {
    // Jika parameter comment_id tidak tersedia, arahkan pengguna kembali ke halaman sebelumnya
    header("Location: view_comments.php");
    exit();
}

// Ambil informasi komentar dari database berdasarkan comment_id
$sql_comment = "SELECT c.comment_id, c.user_id, c.photo_id, c.comment_text, c.created_at, u.name as commenter_name
                FROM comments c
                INNER JOIN users u ON c.user_id = u.user_id
                WHERE c.comment_id = '$comment_id'";
$result_comment = mysqli_query($conn, $sql_comment);

if ($result_comment && mysqli_num_rows($result_comment) > 0) {
    $comment_data = mysqli_fetch_assoc($result_comment);

    // Periksa apakah pengguna berhak untuk mengedit komentar ini
    if ($comment_data['user_id'] == $_SESSION['user_id'] || $_SESSION['access_level'] === 'admin') {
        // Pengguna berhak untuk mengedit komentar

        // Proses update komentar jika form dikirim
        if (isset($_POST['submit_edit'])) {
            $updated_comment_text = mysqli_real_escape_string($conn, $_POST['comment_text']);

            // Simpan nilai created_at saat ini
            $current_created_at = $comment_data['created_at'];

            // Update komentar dalam database
            $sql_update_comment = "UPDATE comments SET comment_text = '$updated_comment_text', created_at = '$current_created_at' WHERE comment_id = '$comment_id'";
            $result_update_comment = mysqli_query($conn, $sql_update_comment);

            if ($result_update_comment) {
                // Redirect user back to the previous page after comment is updated
                $photo_id = $comment_data['photo_id'];
                header("Location: view_comments.php?photo_id=$photo_id");
                exit();
            } else {
                // Tampilkan error jika pembaruan komentar gagal
                $comment_error = "Error: " . mysqli_error($conn);
            }
        }
    } else {
        // Pengguna tidak berhak untuk mengedit komentar ini
        header("Location: view_comments.php");
        exit();
    }
} else {
    // Jika komentar tidak ditemukan, arahkan pengguna kembali ke halaman sebelumnya
    header("Location: view_comments.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Komentar</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="shortcut icon" href="asset/ryegallery.png" type="image/x-icon">
</head>
<body class="bg-gray-200">
<?php include 'navbar.php'?>
    <div class="container mx-auto p-4">
        <div class="bg-white rounded shadow-md overflow-hidden p-4">
            <h2 class="text-2xl font-bold mb-2">Edit Komentar</h2>
            <form action="edit_comment.php?comment_id=<?php echo $comment_id; ?>" method="POST" class="space-y-4">
                <textarea name="comment_text" rows="4" required class="w-full p-2 border border-gray-300 rounded"><?php echo $comment_data['comment_text']; ?></textarea>
                <input type="hidden" name="current_created_at" value="<?php echo $comment_data['created_at']; ?>">
                <button type="submit" name="submit_edit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Comment</button>
            </form>
            <?php
            // Display error message if comment update fails
            if (isset($comment_error)) {
                echo "<p class='text-red-500'>$comment_error</p>";
            }
            ?>
        </div>
    </div>
    <?php include 'footer.php'?>
</body>
</html>

<?php
// Tutup koneksi ke database
mysqli_close($conn);
?>