<div class="bg-blue-500 text-white p-4 flex justify-between items-center">
    <div class="flex items-center">
        <a href="dasboard.php" class="text-xl mr-4 hover:text-red-500 ">
            <i class="fas fa-house"></i>
        </a>
        <?php if (isset($_SESSION['name']) && $_SESSION['name'] !== '') : ?>
            <span class="text-xl mr-4"><?php echo $_SESSION['name']; ?></span>
        <?php endif; ?>
        <?php if (isset($_SESSION['access_level']) && $_SESSION['access_level'] === 'admin') : ?>
            <a href="halaman_admin.php" class="text-xl mr-4 hover:underline ">Admin</a>
        <?php endif; ?>
        <a href="album_user.php" rel="noopener noreferrer" class="text-xl mr-4 hover:underline ">Lihat Album</a>
        <a href="upload.php" class="text-xl mr-4 hover:underline ">Upload</a>
        <a href="create_album.php" class="text-xl hover:underline ">Buat Album</a>
    </div>
    <div>
        <button onclick="showConfirmation()" class="bg-red-500 text-white px-4 py-2 rounded hover:text-gray-400">Logout</button>
    </div>
</div>
