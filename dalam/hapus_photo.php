<?php
session_start();

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if user has admin access
if ($_SESSION['access_level'] === 'admin') {
    // If user is admin, allow photo deletion regardless of user_id
    $admin_access = true;
} else {
    $admin_access = false;
}

// Check if photo_id is provided in the URL
if (isset($_GET['photo_id'])) {
    $photo_id = $_GET['photo_id'];

    // Perform database connection
    $conn = mysqli_connect("localhost", "root", "", "album");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if the logged-in user has permission to delete the photo
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM photos WHERE photo_id = $photo_id AND user_id = $user_id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1 || $admin_access) {
        // Delete the photo from the database
        $delete_sql = "DELETE FROM photos WHERE photo_id = $photo_id";

        if (mysqli_query($conn, $delete_sql)) {
            // Redirect back to dashboard.php with success message
            $_SESSION['success_message'] = "Photo deleted successfully!";
            header("Location: ../dasboard.php");
            exit();
        } else {
            echo "Error deleting photo: " . mysqli_error($conn);
        }
    } else {
        echo "You don't have permission to delete this photo.";
    }

    mysqli_close($conn);
} else {
    echo "Photo ID not provided.";
}
?>
