<?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "album");

// Query untuk mendapatkan daftar album
$sql_albums = "SELECT * FROM albums";
$result_albums = mysqli_query($conn, $sql_albums);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Album</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #e6f2ff; /* Warna latar belakang biru langit */
    color: #333;
    margin: 0;
    padding: 20px;
}

h1 {
    color: #0066cc; /* Warna judul biru */
    text-align: center;
}

.album {
    background-color: #fff;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.album h2 {
    color: #0066cc; /* Warna judul album biru */
    margin-top: 0;
}

.photo-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    margin-top: 10px;
}

.photo-item {
    margin-right: 10px;
    margin-bottom: 10px;
}

.photo-item img {
    max-width: 200px;
    height: auto;
}
    </style>
</head>
<body>
    <h1>Daftar Album</h1>

    <?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "album");

// Query untuk mendapatkan daftar album
$sql_albums = "SELECT * FROM albums";
$result_albums = mysqli_query($conn, $sql_albums);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Album</title>
    <style>
        /* CSS styles */
    </style>
</head>
<body>
    <h1>Daftar Album</h1>

    <?php
    // Menampilkan daftar album
    if (mysqli_num_rows($result_albums) > 0) {
        while ($row = mysqli_fetch_assoc($result_albums)) {
            echo "<div class='album'>";
            echo "<h2>" . $row['title'] . "</h2>";
            echo "<p>Pemilik Album: " . $row['user_id'] . "</p>";

            // Query untuk mendapatkan foto-foto dalam album
            $album_id = $row['album_id'];
            $sql_photos = "SELECT * FROM photos WHERE album_id = '$album_id'";
            $result_photos = mysqli_query($conn, $sql_photos);

            // Menampilkan foto-foto dalam album
            if (mysqli_num_rows($result_photos) > 0) {
                echo "<div class='photo-list'>";
                while ($photo_row = mysqli_fetch_assoc($result_photos)) {
                    echo "<div class='photo-item'>";
                    echo "<img src='uploads/" . $photo_row['image_path'] . "' alt='Photo'>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<p>Tidak ada foto dalam album ini.</p>";
            }

            echo "</div>";
        }
    } else {
        echo "<p>Tidak ada album.</p>";
    }
    ?>

</body>
</html>

<?php
// Tutup koneksi ke database
mysqli_close($conn);
?>