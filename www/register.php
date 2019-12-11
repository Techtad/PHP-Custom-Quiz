<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Rejestracja</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/login.css">
    <script src="js/lib/jquery.min.js"></script>
    <script src="js/lib/utils.js"></script>
  </head>

  <body>
    <div id="container">
      <div id="content">
        <form action="php/auth/sendregister.php" method="post">
          <div class="row">
            <label for="username">Login:</label>
            <input type="text" name="username" id="username" maxlength="36" />
          </div>
          <div class="row">
            <label for="password">Hasło:</label>
            <input type="password" name="password" id="password" maxlength="72" />
          </div>
          <div class="row">
            <label for="repeat_password">Powtórz hasło:</label>
            <input type="password" name="repeat_password" id="repeat_password" maxlength="72" />
          </div>
          <div class="row" style="text-align: center">
            <input type="submit" value="Zarejestruj" />
          </div>
        </form>
        <div class="row">
          <h1 style="color: red; font-size: 12px"><?php if(isset($_SESSION["register-fail"])) {echo $_SESSION["register-fail"]; unset($_SESSION["register-fail"]);} ?></h1>
        </div>
        <div class="row" style="text-align: center">
          <input type="button" value="Logowanie" onclick="window.location = '/login.php'"/>
        </div>
    </div>
  </div>
  </body>
</html>