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
    <header>
        <h1>Testy ABCD</h1>
        <nav>
            <div class="menuBtn" id="btnMainPage" onclick="window.location = '/'">Strona główna</div>

            <div class="menuBtn" id="btnLogOut" onclick="window.location = 'php/auth/logout.php'">Wyloguj</div>
            <div class="menuBtn" id="btnChangePassword" onclick="window.location = 'changepassword.php'">Zmień hasło</div>
            <div class="menuInfo" id="userInfo">Zalogowano jako: <span id="username-display"><?php echo $userdata["username"]; ?></div>
        </nav>
    </header>
    
    <h2><?php echo (require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/isadmin.php")) ? "Jesteś administratorem. <a href='admin.php'>Do panelu</a>" : "Nie masz uprawnień administratora." ?></h2>
    <?php
    require_once $_SERVER["DOCUMENT_ROOT"] . "/php/settings.php";
    if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych");
    if(!$query = $conn->query("SELECT `id`, `name` FROM `quizzes`")) die($conn->error);
    while($row = $query->fetch_assoc()) {
        echo sprintf("<a href='/quiz.php?id=%d'>%s</a>", $row["id"], $row["name"]);
    }
    $query->close();
    $conn->close();
    ?>
</body>

</html>