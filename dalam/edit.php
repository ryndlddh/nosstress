<?php
session_start();

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    $photo_id = $_POST['photo_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Perform database update
    $conn = mysqli_connect("localhost", "root", "", "album");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "UPDATE photos SET title='$title', description='$description' WHERE photo_id=$photo_id";

    if (mysqli_query($conn, $sql)) {
        // Redirect back to edit.php with success message
        // Redirect back to ../dashboard.php with success message
        $_SESSION['success_message'] = "Photo data updated successfully!";
        header("Location: ../dasboard.php");
        exit();
    } else {
        echo "Error updating photo data: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    // If form is not submitted, display edit form
    if (isset($_GET['photo_id'])) {
        $photo_id = $_GET['photo_id'];

        // Fetch photo data from database
        $conn = mysqli_connect("localhost", "root", "", "album");

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "SELECT * FROM photos WHERE photo_id = $photo_id";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $title = $row['title'];
            $description = $row['description'];
        } else {
            echo "Photo not found";
            exit();
        }

        mysqli_close($conn);
    } else {
        echo "Photo ID not provided";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Photo</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body class="bg-gray-200">

    <?php include 'navdalam.php'?>
    
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold text-center mb-4">Edit Foto</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-4">
            <input type="hidden" name="photo_id" value="<?php echo $photo_id; ?>">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Judul:</label>
                <input type="text" id="title" name="title" value="<?php echo $title; ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi:</label>
                <textarea id="description" name="description" required class="mt-1 block w-full p-2 border border-gray-300 rounded"><?php echo $description; ?></textarea>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan perubahan</button>
        </form>
    </div>
    <?php include '../footer.php'?>
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
