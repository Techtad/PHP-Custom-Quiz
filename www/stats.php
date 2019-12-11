<?php
    if(!$userdata = require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/userdata.php")) {header("Location: /"); return;}

    function fileContent($path) {
        $file = fopen($path, "r");
        $content = fread($file, filesize($path));
        fclose($file);
        return $content;
    }

    require_once($_SERVER["DOCUMENT_ROOT"] . "/php/settings.php");
    if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Statystyki</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/stats.css">
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

    <div>
        <h1>Statystyki pytań</h1>
        <table border="1">
            <tr>
                <th>#</th>
                <th>Test</th>
                <th>Pytanie</th>
                <th>Poprawne</th>
                <th>Niepoprawne</th>
                <th>%</th>
            </tr>
            <?php
                if(!$query = $conn->query(sprintf(fileContent($_SERVER["DOCUMENT_ROOT"] . "/sql/answerstats.sql"), 0))) die($conn->error);
                $lp = 1;
                while($row = $query->fetch_assoc()) {
                    echo sprintf("<tr><td>%d</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $lp++, $row["quiz"], $row["question"], $row["right"], $row["wrong"], $row["percent"]);
                }
            ?>
        </table>
    </div>
    <div>
        <h1>Ranking użytkowników</h1>
        <table border="1">
            <tr>
                <th>#</th>
                <th>Użytkownik</th>
                <th>Poprawne</th>
                <th>Niepoprawne</th>
                <th>%</th>
            </tr>
            <?php
                if(!$query = $conn->query(sprintf(fileContent($_SERVER["DOCUMENT_ROOT"] . "/sql/userstats.sql"), 0))) die($conn->error);
                $lp = 1;
                while($row = $query->fetch_assoc()) {
                    echo sprintf("<tr><td>%d</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $lp++, $row["user"], $row["right"], $row["wrong"], $row["percent"]);
                }
            ?>
        </table>
    </div>
</body>
</html>