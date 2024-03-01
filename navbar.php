<div class="bg-blue-500 text-white p-4 flex justify-between items-center">
    <div class="flex items-center">
        <a href="dasboard.php" class="text-xl mr-4">
            <i class="fas fa-house"></i>
        </a>
        <?php if (isset($_SESSION['name']) && $_SESSION['name'] !== '') : ?>
            <span class="text-xl mr-4"><?php echo $_SESSION['name']; ?></span>
        <?php endif; ?>
        <?php if (isset($_SESSION['access_level']) && $_SESSION['access_level'] === 'admin') : ?>
            <a href="halaman_admin.php" class="text-xl mr-4">admin</a>
            <a href="album.php" rel="noopener noreferrer" class="text-xl mr-4">album</a>
            
        <?php endif; ?>
        <a href="upload.php" class="text-xl mr-4">upload</a>
        <a href="create_album.php" class="text-xl">buat album</a>
    </div>
    <div>
        <button onclick="showConfirmation()" class="bg-red-500 text-white px-4 py-2 rounded">Logout</button>
    </div>
</div>
