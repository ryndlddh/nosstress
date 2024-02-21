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
    <title>Edit Comment</title>
    <style>
        /* CSS untuk tampilan umum */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
        }

        form {
            margin-top: 20px;
        }

        textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: vertical;
        }

        button[type="submit"] {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        button[type="submit"]:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Comment</h2>
        <form action="edit_comment.php?comment_id=<?php echo $comment_id; ?>" method="POST">
            <textarea name="comment_text" rows="4" required><?php echo $comment_data['comment_text']; ?></textarea>
            <!-- Tambahkan hidden input untuk menyimpan nilai created_at -->
            <input type="hidden" name="current_created_at" value="<?php echo $comment_data['created_at']; ?>">
            <button type="submit" name="submit_edit">Update Comment</button>
        </form>
        <?php
        // Display error message if comment update fails
        if (isset($comment_error)) {
            echo "<p>$comment_error</p>";
        }
        ?>
    </div>
</body>
</html>

<?php
// Tutup koneksi ke database
mysqli_close($conn);
?>