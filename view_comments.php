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
$sql_photo = "SELECT photos.*, users.name as username FROM photos INNER JOIN users ON photos.user_id = users.user_id WHERE photos.photo_id = '$photo_id'";
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

// Tutup koneksi ke database
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Photo</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body class="bg-gray-200">
<?php include'navbar.php';?>

<div class="container mx-auto p-4">
    <!-- Photo Details Section -->
    <div class="bg-white rounded shadow-md overflow-hidden p-4">
        <p class="text-gray-500 mb-4"><?php echo htmlspecialchars($photo_data['username'], ENT_QUOTES, 'UTF-8'); ?></p>   
        <h2 class="text-2xl font-bold mb-2"><?php echo $photo_data['title']; ?></h2>
        <p class="text-gray-700 mb-4"><?php echo $photo_data['description']; ?></p>
        <div class="flex justify-center">
            <img class="w-1/2 h-96 object-cover" src="<?php echo $photo_data['image_path']; ?>" alt="<?php echo $photo_data['title']; ?>">
        </div>
        <p class="text-gray-500 mb-4">suka: <?php echo $like_count; ?></p>
        <form method="post" action="view_comments.php?photo_id=<?php echo $photo_id; ?>">
            <?php if ($has_liked) : ?>
                <button class="bg-blue-500 text-white px-4 py-2 rounded mb-4" type="submit" name="like_action" value="unlike"><i class="fa-solid fa-heart" style="color: red;"></i></button>
            <?php else : ?>
                <button class="bg-blue-500 text-white px-4 py-2 rounded mb-4" type="submit" name="like_action" value="like"><i class="fa-solid fa-heart"></i></button>
            <?php endif; ?>
        </form>
    </div>

    <!-- Comments Section -->
    <div class="bg-white rounded shadow-md overflow-hidden p-4 mt-4">
        <h3 class="text-xl font-bold mb-2">Comments</h3>
        <!-- Display existing comments -->
        <?php
        if ($result_comments && mysqli_num_rows($result_comments) > 0) {
            while ($row = mysqli_fetch_assoc($result_comments)) {
                echo "<div class='border-b border-gray-200 py-4'>";
                echo "<p class='text-gray-700'><strong>" . $row['commenter_name'] . "</strong>: " . $row['comment_text'] . "</p>";
                echo "<small class='text-gray-500'>" . $row['created_at'] . "</small>";
                if (isset($_SESSION['access_level']) && $_SESSION['access_level'] === 'admin') {
                    // Admin can edit and delete any comment
                    echo "<div class='mt-2'><a href='edit_comment.php?comment_id=" . $row['comment_id'] . "' class='text-blue-500 hover:text-blue-700'>Edit</a></div>";
                    echo "<div class='mt-2'>
                            <button class='text-red-500 hover:text-red-700' onclick='sshowConfirmation(this, \"delete_comment.php?comment_id=" . $row['comment_id'] . "&photo_id=" . $_GET["photo_id"] . "\")'><i class='fa-solid fa-trash'></i></button>
                            </div>";
                } elseif (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']) {
                    // User can edit and delete their own comments
                    echo "<div class='mt-2'><a href='edit_comment.php?comment_id=" . $row['comment_id'] . "' class='text-blue-500 hover:text-blue-700'>Edit</a></div>";
                    echo "<div class='mt-2'>
                            <button class='text-red-500 hover:text-red-700' onclick='sshowConfirmation(this, \"delete_comment.php?comment_id=" . $row['comment_id'] . "&photo_id=" . $_GET["photo_id"] . "\")'><i class='fa-solid fa-trash'></i></button>
                            </div>";
                }
                echo "</div>";
            }
        } else {
            echo "<p class='text-gray-500'>Belum ada komentar di foto ini.</p>";
        }
        ?>

        <!-- Form to add new comment -->
        <form action="proses_commants.php" method="POST" class="mt-4">
            <input type="hidden" name="photo_id" value="<?php echo $photo_id; ?>">
            <textarea name="comment_text" rows="3" placeholder="Add your comment here..." required class="w-full p-2 border border-gray-300 rounded mb-2"></textarea>
            <button type="submit" name="submit_comment" class="bg-blue-500 text-white px-4 py-2 rounded">Add Comment</button>
        </form>

        <?php
        // Display error message if comment insertion fails
        if (isset($comment_error)) {
            echo "<p class='text-red-500'>$comment_error</p>";
        }
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

    function sshowConfirmation(button, url) {
        if (confirm("Are you sure you want to delete this comment?")) {
            window.location.href = url;
        }
    }
</script>
</body>
</html>
