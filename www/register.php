<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Rejestracja</title>
    <link rel="stylesheet" href="css/style.css" />
    <script src="js/lib/jquery.min.js"></script>
    <script src="js/lib/utils.js"></script>
  </head>

  <body>
    <form action="php/auth/sendregister.php" method="post">
      <label for="username">Login:</label
      ><input type="text" name="username" id="username" maxlength="36" />
      <label for="password">Hasło:</label
      ><input type="password" name="password" id="password" maxlength="72" />
      <label for="repeat_password">Hasło:</label
      ><input type="password" name="repeat_password" id="repeat_password" maxlength="72" />
      <input type="submit" value="Zarejestruj" />
      <h1 style="color: red"><?php if(isset($_SESSION["register-fail"])) {echo $_SESSION["register-fail"]; unset($_SESSION["register-fail"]);} ?></h1>
    </form>
  </body>
</html>