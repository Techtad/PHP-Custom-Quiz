<?php
if(!$userdata = require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/userdata.php")) {header("Location: /login.php"); return;}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Testy</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/lib/jquery.min.js"></script>
    <script src="js/lib/utils.js"></script>
</head>

<body>
    <h1>Zalogowano jako: <span id="username"><?php echo $userdata["username"]; ?></span></h1>
    <h2><?php echo (require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/isadmin.php")) ? "Jesteś administratorem. <a href='admin.php'>Do panelu</a>" : "Nie masz uprawnień administratora." ?></h2>
    <input type="button" id="btnLogOut" value="Wyloguj" onclick="window.location = 'php/auth/logout.php'"/>
</body>

</html>