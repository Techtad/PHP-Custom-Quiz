<?php
if(require("userdata.php")) {header("Location: /"); return;}

if(isset($_POST["username"]) && isset($_POST["password"])) {
    require("settings.php");
    if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych");
    if(!$query = $conn->query(sprintf("SELECT `password_hash` FROM `accounts` WHERE `username` LIKE '%s'", $_POST["username"]))) die("Nie udało się wykonać zapytania SQL");

    if($record = $query->fetch_row()) {
        if(password_verify($_POST["password"], $record[0])) {
            $token = bin2hex(openssl_random_pseudo_bytes(8));

            // Create the file if it doesn't exist
            $file = fopen("sessions.json", "w");
            fclose($file);

            if($file = fopen("sessions.json", "r")) {
                if(!$users = json_decode(fread($file, filesize("sessions.json")), true)) $users = [];
                $users[$token] = ["username" => $_POST["username"], "expires" => time() + 3600*24];
                fclose($file);
                $file = fopen("sessions.json", "w");
                fwrite($file, json_encode($users));
                fclose($file);
            } else die("Nie udało się otworzyć pliku na serwerze.");
            setcookie("session", $token, time()+3600*24, "/");
            header("Location: /");
        } else $reason = "Błędne hasło.";

        $query->close();
    } else $reason = "Użytkownik o podanym loginie nie istnieje. <input type='button' value='Zarejestruj automatycznie' onclick='autoRegister(\"" . $_POST["username"] . "\", \"" . $_POST["password"] . "\")'>";

    $conn->close();
}
?>

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
        $.post("register.php", {"auto": true, "username": username, "password": password, "repeat_password": password}, function(data) {
          let resp = JSON.parse(data);
          console.log("autoRegister", resp);
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
    <form action="login.php" method="post" id="login-form">
      <label for="username">Login:</label
      ><input type="text" name="username" id="username" maxlength="36"/>
      <label for="password">Hasło:</label
      ><input type="password" name="password" id="password" maxlength="72"/>
      <input type="submit" value="Zaloguj" />
      <h1 style="color: red"><?php if(isset($reason)) echo $reason; ?></h1>
    </form>
  </body>
</html>
