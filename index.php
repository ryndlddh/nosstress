<?php
// Mulai sesi
session_start();

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "album");

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pra</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body class="bg-gray-200">
<?php include 'nav.php'; ?>

<div class="container mx-auto p-4">
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php
        // Koneksi ke database
        $conn = mysqli_connect("localhost", "root", "", "album");

        // Cek koneksi
        if (!$conn) {
            die("Koneksi gagal: " . mysqli_connect_error());
        }

        $sql = "SELECT photos.*, users.name as username, users.access_level FROM photos INNER JOIN users ON photos.user_id = users.user_id ORDER BY photos.create_at DESC";
        // Eksekusi query
        $result = mysqli_query($conn, $sql);

        // Cek apakah query berhasil dieksekusi
        if ($result) {
            // Foto yang ditambahkan ke album
            if (isset($_SESSION['added_photo'])) {
                $added_photo = $_SESSION['added_photo'];
                echo "<div class='bg-white rounded shadow-md overflow-hidden'>";
                echo "<div class='p-4'>";
                echo "<div class='font-bold text-xl mb-2'>" . $added_photo['username'] . ($added_photo['access_level'] == 'admin' ? " <span class='text-blue-500'>(Admin)</span>" : "") . "</div>";
                echo "<img class='w-full h-48 object-cover' src='" . $added_photo['image_path'] . "' alt='" . $added_photo['title'] . "'>";
                echo "<div class='mt-2'>";
                echo "<p class='text-gray-700'>" . $added_photo['title'] . "</p>";
                echo "<p class='text-gray-500'>" . $added_photo['description'] . "</p>";
                echo "<a href='view_comments_guest.php?photo_id=" . $added_photo['photo_id'] . "' class='bg-gray-400 text-white px-4 py-2 rounded mr-2'>Lihat selengkapnya    <i class='fa-solid fa-arrow-right'></i></a>";
                echo "</div>";
                echo "<div class='mt-4'>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                unset($_SESSION['added_photo']);
            }

            // Semua foto dari database
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='bg-white rounded shadow-md overflow-hidden'>";
                    echo "<div class='p-4'>";
                    echo "<div class='font-bold text-xl mb-2'>" . $row['username'] . ($row['access_level'] == 'admin' ? " <span class='text-blue-500'><i class='fa-solid fa-microchip'></i></span>" : "") . "</div>";

                    echo "<img class='w-full h-48 object-cover' src='" . $row['image_path'] . "' alt='" . $row['title'] . "'>";
                    echo "<div class='mt-2'>";
                    echo "<p class='text-gray-700'>" . $row['title'] . "</p>";
                    echo "<p class='text-gray-500'>" . $row['description'] . "</p>";
                    echo "<br>";
                    echo "<a href='view_comments_guest.php?photo_id=" . $row['photo_id'] . "' class='bg-gray-400 text-white px-4 py-2 rounded mr-2'>Lihat selengkapnya    <i class='fa-solid fa-arrow-right'></i></a>";
                    echo "</div>";
                    echo "<div class='mt-4'>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<div class='bg-white rounded shadow-md overflow-hidden p-4'>";
                echo "<p class='text-gray-500'>Tidak ada data foto.</p>";
                echo "</div>";
            }
        } else {
            echo "<div class='bg-white rounded shadow-md overflow-hidden p-4'>";
            echo "<p class='text-red-500'>Error: " . mysqli_error($conn) . "</p>";
            echo "</div>";
        }

        // Tutup koneksi ke database
        mysqli_close($conn);
        ?>
    </div>
    
</div>
<?php include 'footer.php';?>

</body>
</html>
