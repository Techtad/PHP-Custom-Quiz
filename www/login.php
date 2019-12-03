<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Logowanie</title>
    <link rel="stylesheet" href="css/style.css" />
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
    <form action="php/auth/sendlogin.php" method="post" id="login-form">
      <label for="username">Login:</label
      ><input type="text" name="username" id="username" maxlength="36"/>
      <label for="password">Hasło:</label
      ><input type="password" name="password" id="password" maxlength="72"/>
      <input type="submit" value="Zaloguj" />
      <h1 style="color: red"><?php if(isset($_SESSION["login-fail"])) {echo $_SESSION["login-fail"]; unset($_SESSION["login-fail"]);} ?></h1>
    </form>
  </body>
</html>
