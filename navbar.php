<div class="bg-blue-500 text-white p-4 fixed w-full z-10 top-0 shadow">
    <div class="container mx-auto flex justify-between items-center">
        <div class="flex items-center">
            <a href="dasboard.php" class="text-xl mr-4 hover:text-red-500 ">
                <i class="fas fa-house"></i>
            </a>
            <?php if (isset($_SESSION['name']) && $_SESSION['name'] !== '') : ?>
                <span class="text-xl mr-4"><?php echo $_SESSION['name']; ?></span>
            <?php endif; ?>
            <?php if (isset($_SESSION['access_level']) && $_SESSION['access_level'] === 'admin') : ?>
                <a href="halaman_admin.php" class="text-xl mr-4 hover:underline md:block hidden">Admin</a>
                <a href="read_report.php" class="text-xl mr-4 hover:underline md:block hidden">Keluhan</a>
            <?php endif; ?>
            <a href="album_user.php" class="text-xl mr-4 hover:underline md:block hidden">Lihat Album</a>
            <a href="upload.php" class="text-xl mr-4 hover:underline md:block hidden">Upload</a>
            <a href="create_album.php" class="text-xl hover:underline md:block hidden">Buat Album</a>
        </div>
        <div class="md:block hidden">
            <button onclick="showConfirmation()" class="bg-red-500 text-white px-4 py-2 rounded hover:text-gray-400">Logout</button>
        </div>
        <div class="block md:hidden">
            <button id="menu-btn" class="bg-gray-500 text-white px-4 py-2 rounded">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
    <div id="menu" class="hidden md:hidden">
        <div class="px-4 py-2 bg-blue-500 border-t border-b">
            <?php if (isset($_SESSION['access_level']) && $_SESSION['access_level'] === 'admin') : ?>
                <a href="halaman_admin.php" class="block text-xl hover:bg-blue-300">Admin</a>
                <a href="read_report.php" class="block text-xl hover:bg-blue-300">Keluhan</a>
            <?php endif; ?>
            <a href="album_user.php" class="block text-xl hover:bg-blue-300">Lihat Album</a>
            <a href="upload.php" class="block text-xl hover:bg-blue-300">Upload</a>
            <a href="create_album.php" class="block text-xl hover:bg-blue-300">Buat Album</a>
        </div>
        <div class="px-4 py-2 bg-blue-500">
            <button onclick="showConfirmation()" class="bg-red-500 text-white px-4 py-2 rounded hover:text-gray-400 w-full">Logout</button>
        </div>
    </div>
</div>



<script>
document.getElementById("menu-btn").addEventListener("click", function() {
  document.getElementById("menu").classList.toggle("hidden");
});
</script>