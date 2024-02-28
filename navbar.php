<div class="navbar">
        <a href="dasboard.php">Home</a>
        <?php if (isset($_SESSION['name']) && $_SESSION['name'] !== '') : ?>
            <a href="create_album.php"><?php echo $_SESSION['name']; ?></a>
        <?php endif; ?>

        <?php if (isset($_SESSION['access_level']) && $_SESSION['access_level'] === 'admin') : ?>
            <a href="halaman_admin.php">admin</a>
            <a href="read_album.php" target="_blank" rel="noopener noreferrer">album</a>
        <?php endif; ?>
        <a href="upload.php">upload</a>
        <div style="float: right;">
            <button onclick="showConfirmation()">Logout</button>
        </div>
    </div>
    