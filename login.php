<?php
if(require("userdata.php")) {
    header("Location: /");
    return;
}

if(isset($_POST["username"]) && isset($_POST["password"])) {
    if(!$conn = mysqli_connect("localhost", "root", "", "quizapp")) die("Nie udało się połączyć z bazą danych");
    if(!$query = $conn->query(sprintf("SELECT `password` FROM `accounts` WHERE `username` LIKE '%s'", $_POST["username"]))) die("Nie udało się wykonać zapytania SQL");

    if($record = $query->fetch_row()) {
        if($record[0] == $_POST["password"]) {
            $token = bin2hex(openssl_random_pseudo_bytes(8));
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
    } else $reason = "Użytkownik o podanym loginie nie istnieje.";
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
  </head>

  <body>
    <form action="login.php" method="post">
      <label for="username">Login:</label
      ><input type="text" name="username" id="username" />
      <label for="password">Hasło:</label
      ><input type="text" name="password" id="password" />
      <input type="submit" value="Zaloguj" />
      <h1 style="color: red"><?php if(isset($reason)) echo $reason; ?></h1>
    </form>
  </body>
</html>
