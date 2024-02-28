<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Jika pengguna tidak sedang login, arahkan ke halaman login
    header("Location: dalam/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Memastikan input tidak kosong
    if (empty($_POST["comment_text"]) || empty($_POST["photo_id"])) {
        // Redirect atau tampilkan pesan error jika input kosong
        exit("Error: Input tidak boleh kosong.");
    }

    // Koneksi ke database
    $conn = mysqli_connect("localhost", "root", "", "album");

    // Cek koneksi
    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    // Ambil data input
    $user_id = $_SESSION['user_id'];
    $photo_id = $_POST['photo_id'];
    $comment_text = mysqli_real_escape_string($conn, $_POST['comment_text']);
    $created_at = date('Y-m-d H:i:s');

    // Query untuk menyisipkan komentar ke dalam tabel comments
    $sql = "INSERT INTO comments (user_id, photo_id, comment_text, created_at) VALUES (?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind parameter ke statement
        mysqli_stmt_bind_param($stmt, "iiss", $user_id, $photo_id, $comment_text, $created_at);

        // Eksekusi statement
        if(mysqli_stmt_execute($stmt)) {
            // Redirect ke halaman foto setelah komentar disisipkan
            header("Location: view_comments.php?photo_id=" . $photo_id);
            exit();
        } else {
            echo "Error: " . mysqli_stmt_error($stmt);
        }

        // Tutup statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Tutup koneksi
    mysqli_close($conn);
} else {
    // Redirect atau tampilkan pesan error jika tidak menggunakan metode POST
    exit("Error: Metode request tidak valid.");
}
?>
