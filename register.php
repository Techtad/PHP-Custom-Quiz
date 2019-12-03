<?php
if(require("userdata.php")) {header("Location: /"); return;}

$register_success = false;
$reason = "";
if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["repeat_password"])) {
    $user = $_POST["username"];
    $pass = $_POST["password"];
    $rep_pass = $_POST["repeat_password"];

    if(strlen($user) < 6 || strlen($pass) < 6) {
        $reason = "Login i hasło muszą mieć przynajmniej 6 dowolnych znaków.";
        goto skip;
    }

    if($rep_pass != $pass) {
        $reason = "Hasła muszą się zgadzać.";
        goto skip;
    }

    require("settings.php");
    if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych");
    if(!$query = $conn->query(sprintf("SELECT `username` FROM `accounts` WHERE `username` LIKE '%s'", $_POST["username"]))) die("Nie udało się wykonać zapytania SQL");

    if($query->fetch_row()) {
        $reason = "Podana nazwa użytkownika jest już zajęta.";
        goto skip;
    }
    $query->close();

    if(!$query = $conn->prepare("INSERT INTO `accounts` VALUES(?, ?, ?)")) die("Nie udało się wykonać zapytania SQL");
    $hash = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $admin = 0;
    $query->bind_param("ssi", $user, $hash, $admin);

    $success = $query->execute();
    $query->close();
    $conn->close();
    if($success) {
        if(!isset($_POST["auto"])) header("Location: login.php");
        else $register_success = true;
    } else {
        $reason = "Wystąpił błąd podczas dokonywania rejestracji.";
    }
}

skip:
?>

<?php if(!isset($_POST["auto"])) { ?>
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
    <form action="register.php" method="post">
      <label for="username">Login:</label
      ><input type="text" name="username" id="username" maxlength="36" />
      <label for="password">Hasło:</label
      ><input type="password" name="password" id="password" maxlength="72" />
      <label for="repeat_password">Hasło:</label
      ><input type="password" name="repeat_password" id="repeat_password" maxlength="72" />
      <input type="submit" value="Zarejestruj" />
      <h1 style="color: red"><?php if(isset($reason)) echo $reason; ?></h1>
    </form>
  </body>
</html>
<?php } else echo json_encode(["success" => $register_success, "reason" => $reason]); ?>