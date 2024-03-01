<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: dalam/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_comment'])) {
        $comment_id = $_POST['comment_id'];
        $photo_id = $_GET['photo_id'];

        // Koneksi ke database
        $conn = mysqli_connect("localhost", "root", "", "album");
        if (!$conn) {
            die("Koneksi gagal: " . mysqli_connect_error());
        }

        $sql_delete_comment = "DELETE FROM comments WHERE comment_id = '$comment_id'";

        if (mysqli_query($conn, $sql_delete_comment)) {
            // Redirect kembali ke view_comments.php setelah berhasil menghapus komentar
            header("Location: view_comments.php?photo_id=$photo_id");
            exit();
        } else {
            echo "Error: " . $sql_delete_comment . "<br>" . mysqli_error($conn);
        }

        mysqli_close($conn);
    }
}
?>
