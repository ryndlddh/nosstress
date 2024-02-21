<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Page - User List</title>
<link rel="stylesheet" href="style/slfa.css">
<style>
    /* CSS untuk tampilan umum */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f5f5f5;
    }

    /* CSS untuk konten utama */
    .container {
        max-width: 1200px;
        margin: 20px auto;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        padding: 20px;
    }

    .user-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .user-table th,
    .user-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .user-table th {
        background-color: #3498db;
        color: white;
    }

    .user-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

/* CSS untuk tombol edit */
.edit-button {
    background-color: #4CAF50; /* Warna hijau */
    color: white;
    border: none;
    padding: 8px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin: 4px 2px;
    cursor: pointer;
    transition-duration: 0.4s;
    width: calc(100% - 10px); /* Ukuran tombol menyesuaikan lebar kolom */
}

.edit-button:hover {
    background-color: #45a049; /* Warna hijau yang sedikit lebih gelap saat dihover */
}

/* CSS untuk tombol hapus */
.delete-button {
    background-color: #f44336; /* Warna merah */
    color: white;
    border: none;
    padding: 8px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin: 4px 2px;
    cursor: pointer;
    transition-duration: 0.4s;
    width: calc(100% - 10px); /* Ukuran tombol menyesuaikan lebar kolom */
}

.delete-button:hover {
    background-color: #ff3d00; /* Warna merah yang sedikit lebih gelap saat dihover */
}


    /* CSS Responsif */
    @media only screen and (max-width: 600px) {
        .container {
            width: 100%;
            padding: 10px;
        }

        .user-table {
            font-size: 12px;
        }
    }
</style>
</head>
<body>
<div class="navbar">
        <a href="dasboard.php">Home</a>
        <?php if (isset($_SESSION['name']) && $_SESSION['name'] !== '') : ?>
            <a href="create_album.php"><?php echo $_SESSION['name']; ?></a>
        <?php endif; ?>

        <?php if (isset($_SESSION['access_level']) && $_SESSION['access_level'] === 'admin') : ?>
            <a href="halaman_admin.php">admin</a>
        <?php endif; ?>
        <a href="upload.php">upload</a>
        <div style="float: right;">
            <a href="dalam/logout.php">Logout</a>
        </div>
    </div>
    <div class="container">
        <h2>User List</h2>
        <table class="user-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Email</th>
                    <th>Access Level</th>
                    <th>Created At</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Koneksi ke database
                $conn = mysqli_connect("localhost", "root", "", "album");

                // Periksa koneksi
                if (!$conn) {
                    die("Koneksi gagal: " . mysqli_connect_error());
                }

                // Query untuk membaca data dari tabel users
                $sql = "SELECT `user_id`, `name`, `username`, `password`, `email`, `access_level`, `create_at` FROM `users`";
                $result = mysqli_query($conn, $sql);

                // Tampilkan data dari tabel users dalam bentuk baris tabel HTML
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row["user_id"] . "</td>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $row["username"] . "</td>";
                        echo "<td>" . $row["password"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $row["access_level"] . "</td>";
                        echo "<td>" . $row["create_at"] . "</td>";
                        echo "<td><a class='edit-button' href='halaman_edit_user.php?user_id=" . $row['user_id'] . "'>Edit</a></td>";
                        echo "<td><a class='delete-button' href='halaman_hapus_user.php?user_id=" . $row['user_id'] . "'>Delete</a></td>";

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No users found</td></tr>";
                }

                // Tutup koneksi ke database
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
