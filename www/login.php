<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Logowanie</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/login.css">
    <script src="js/lib/jquery.min.js"></script>
    <script src="js/lib/utils.js"></script>
    <script type="text/javascript">
      function autoRegister(username, password) {
        $.post("php/auth/sendregister.php", {"auto": true, "username": username, "password": password, "repeat_password": password}, function(data) {
          console.log("autoRegister", data);
          let resp = JSON.parse(data);
          console.log(resp);
          if(resp.success) {
            //window.location = window.location;
            $("#username").val(username);
            $("#password").val(password);
            $("#login-form").submit();
          } else
            alert(resp.reason);
        })
      }
    </script>
  </head>

  <body>
    <div id="container">
      <div id="content">
        <form action="php/auth/sendlogin.php" method="post" id="login-form">
          <div class="row">
            <label for="username">Login:</label>
            <input type="text" name="username" id="username" maxlength="36"/>
          </div>
          <div class="row">
            <label for="password">Hasło:</label>
            <input type="password" name="password" id="password" maxlength="72"/>
          </div>
          <div class="row" style="text-align: center">
            <input type="submit" value="Zaloguj"/>
          </div>
        </form>
        <div class="row">
          <h1 style="color: red; font-size: 12px"><?php if(isset($_SESSION["login-fail"])) {echo $_SESSION["login-fail"]; unset($_SESSION["login-fail"]);} ?></h1>
        </div>
        <div class="row" style="text-align: center">
          <input type="button" value="Rejestracja" onclick="window.location = '/register.php'"/>
        </div>
    </div>
  </body>
</html>
