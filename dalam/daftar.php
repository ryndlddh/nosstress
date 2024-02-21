<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar</title>
  <link rel="stylesheet" href="../style/slfa.css">
  <style>
    form {
      width: 300px;
      margin: 0 auto;
    }

    label {
      display: block;
      margin-bottom: 5px;
    }

    input[type="text"],
    input[type="email"] {
      width: 100%;
      padding: 8px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    button[type="submit"] {
      background-color: #4CAF50;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      float: right;
    }

    button[type="submit"]:hover {
      background-color: #45a049;
    }

    button[type="submit"]:focus {
      outline: none;
    }
  </style>
</head>
<body>
  <div class="navbar">
    <a href="index.php">Home</a>
    <div style="float: right;">
      <a href="login.php">Login</a>
    </div>
  </div>
  <form id="registration-form" action="create_produk.php" method="post" onsubmit="return validateForm()">
    <label>Nama:</label>
    <input type="text" id="name" name="name"><br>
    <span id="name-error"></span>

    <label>Username:</label>
    <input type="text" id="username" name="username" onblur="checkUsername()"><br>
    <span id="username-error"></span>

    <label>Password:</label>
    <input type="text" name="password"><br>

    <label>Email:</label>
    <input type="email" name="email"><br>

    <button type="submit" name="submit">Simpan</button>
  </form>

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
</body>
</html>