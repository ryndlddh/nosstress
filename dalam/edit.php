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
    <link rel="stylesheet" href="../style/komen.css">
    <link rel="stylesheet" href="../style/slfa.css">
</head>
<body>
    <?php include'../navbar.php';?>
    
    <div class="container">
    <h2 style="text-align: center;">Edit Foto</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="photo_id" value="<?php echo $photo_id; ?>">
        <label for="title">Judul:</label>
        <input type="text" id="title" name="title" value="<?php echo $title; ?>" required>
        <label for="description">Deskripsi:</label>
        <textarea id="description" name="description" required><?php echo $description; ?></textarea>
        <button type="submit">Simpan perubahan</button>
    </form>
    </div>
</body>
</html>
