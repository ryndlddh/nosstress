<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Daftar RyeGallery</title>
 <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
 <link rel="shortcut icon" href="../asset/ryegallery.png" type="image/x-icon">
</head>
<body class="bg-gray-200">
 <?php include 'nav.php' ?>

 <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Daftar RyeGallery
        </h2>
      </div>
      <form id="registration-form" action="create_produk.php" method="post" onsubmit="return validateForm()" class="mt-8 space-y-6">
        <input type="hidden" name="remember" value="true">
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="name" class="sr-only">Nama</label>
            <input id="name" name="name" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Nama">
            <span id="name-error" class="text-red-500 text-xs"></span>
          </div>
          <div>
            <label for="username" class="sr-only">Username</label>
            <input id="username" name="username" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Username" onblur="checkUsername()">
            <span id="username-error" class="text-red-500 text-xs"></span>
          </div>
          <div>
            <label for="password" class="sr-only">Password</label>
            <input id="password" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password">
          </div>
          <div>
            <label for="email" class="sr-only">Email</label>
            <input id="email" name="email" type="email" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Email">
          </div>
        </div>

        <div>
          <button type="submit" name="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            Simpan
          </button>
        </div>
      </form>
    </div>
 </div>

 <script>
    function validateForm() {
      var nameInput = document.getElementById("name").value;
      var regex = /^[A-Za-z\s]+$/; // Hanya huruf dan spasi diizinkan

      if (!regex.test(nameInput)) {
        document.getElementById("name-error").innerHTML = "Nama tidak boleh mengandung angka!";
        return false;
      } else {
        document.getElementById("name-error").innerHTML = "";
      }

      return true;
    }

    function checkUsername() {
      var username = document.getElementById("username").value;
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "create_produk.php", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          if (xhr.responseText === "username_taken") {
            document.getElementById("username-error").innerHTML = "Username sudah di gunakan, silakan ganti username.";
          } else {
            document.getElementById("username-error").innerHTML = "";
          }
        }
      };
      xhr.send("check_username=true&username=" + encodeURIComponent(username));
    }
 </script>

 <?php include '../footer.php'?>
</body>
</html>
