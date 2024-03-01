<?php
session_start();

// Fungsi untuk mengarahkan pengguna ke halaman login
function redirect_to_login() {
    header("Location: dalam/login.php");
    exit();
}

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "album");

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Ambil data pengguna dari database berdasarkan user_id yang disimpan dalam sesi
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_id = '$user_id'";

// Eksekusi query
$result = mysqli_query($conn, $sql);

// Cek apakah query berhasil dieksekusi dan apakah pengguna ditemukan
if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    // Set session user_id
    $_SESSION['user_id'] = $user_id;
    $_SESSION['name'] = $user_data['name'];
} else {
    // Jika data pengguna tidak ditemukan, arahkan pengguna ke halaman login
    redirect_to_login();
}

// Tutup koneksi ke database
mysqli_close($conn);


// Proses jika form telah di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai input dari form
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Proses upload foto
    $target_dir = "uploads/"; // Direktori tempat menyimpan file

    // Pastikan direktori uploads ada
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $target_file = $target_dir . basename($_FILES["image_path"]["name"]); // Path lengkap file yang diupload
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION)); // Ekstensi file

    // Batasi tipe file yang diizinkan
    $allowed_types = array('png', 'jpg', 'jpeg', 'gif');
    if (!in_array($imageFileType, $allowed_types)) {
        echo "Hanya file dengan tipe PNG, JPG, JPEG, atau GIF yang diizinkan.";
        exit();
    }

    // Pindahkan file ke direktori upload
    if (move_uploaded_file($_FILES["image_path"]["tmp_name"], $target_file)) {
        echo "File ". basename($_FILES["image_path"]["name"]). " berhasil diunggah.";

        // Mendapatkan ukuran asli gambar
        list($width, $height) = getimagesize($target_file);

        // Mengatur ukuran gambar sesuai rasio aslinya
        $new_width = 300; // Atur lebar gambar yang diinginkan
        $new_height = round($new_width * $height / $width);

        // Membuat gambar baru dengan ukuran yang ditetapkan
        $image_resized = imagecreatetruecolor($new_width, $new_height);

        // Memuat gambar asli
        if ($imageFileType == "png") {
            $image = imagecreatefrompng($target_file);
        } elseif ($imageFileType == "jpeg" || $imageFileType == "jpg") {
            $image = imagecreatefromjpeg($target_file);
        } elseif ($imageFileType == "gif") {
            $image = imagecreatefromgif($target_file);
        }

        // Menggabungkan gambar yang diunggah ke gambar baru dengan ukuran yang ditetapkan
        imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        // Simpan gambar yang diubah ke direktori uploads
        $resized_file = $target_dir . 'resized_' . basename($_FILES["image_path"]["name"]);
        imagejpeg($image_resized, $resized_file);

        // Hapus gambar asli
        imagedestroy($image);

        // Hapus gambar yang diubah
        imagedestroy($image_resized);

        // Koneksi ke database
        $conn = mysqli_connect("localhost", "root", "", "album");

        // Cek koneksi
        if (!$conn) {
            die("Koneksi gagal: " . mysqli_connect_error());
        }

        // Query untuk insert data ke dalam tabel photos
        $sql = "INSERT INTO photos (user_id, title, description, image_path)
                VALUES ('$user_id','$title', '$description', '$resized_file')";

        if (mysqli_query($conn, $sql)) {
            echo "Data berhasil ditambahkan.";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        // Tutup koneksi ke database
        mysqli_close($conn);
    } else {
        echo "Terjadi kesalahan saat mengunggah file.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Data</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</head>
<body class="bg-gray-200">
<?php include 'navbar.php'; ?>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold text-center mb-4">Form Unggah Foto</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" class="space-y-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Judul:</label>
            <input type="text" id="title" name="title" class="mt-1 block w-full p-2 border border-gray-300 rounded">

            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi:</label>
            <textarea id="description" name="description" class="mt-1 block w-full p-2 border border-gray-300 rounded"></textarea>

            <label for="image_path" class="block text-sm font-medium text-gray-700">Pilih foto:</label>
            <input type="file" id="image_path" name="image_path" class="mt-1 block w-full p-2 border border-gray-300 rounded">

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Kirim</button>
        </form>
    </div>
    <?php include 'footer.php'?>
</body>
</html>
<script>
function showConfirmation() {
    // Tampilkan notifikasi konfirmasi
    var confirmation = confirm("Apakah Anda yakin ingin logout?");
    
    // Jika pengguna menekan tombol "OK" pada notifikasi konfirmasi
    if (confirmation) {
        // Lakukan perintah logout atau tindakan lainnya
        window.location.href = "dalam/logout.php"; // Ganti dengan URL logout atau tindakan lainnya
    } else {
        // Jika pengguna memilih "Tidak" atau menutup notifikasi, tidak ada tindakan yang diambil
    }
}
</script>