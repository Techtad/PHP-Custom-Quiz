<?php
if (!$userdata = require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/userdata.php")) {
    header("Location: /");
    return;
}

function fileContent($path)
{
    $file = fopen($path, "r");
    $content = fread($file, filesize($path));
    fclose($file);
    return $content;
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/php/settings.php");
if (!$conn = db_connect()) die("Nie udało się połączyć z bazą danych");

$questions_page = isset($_GET["qpage"]) ? $_GET["qpage"] - 1 : 0;
$users_page = isset($_GET["upage"]) ? $_GET["upage"] - 1 : 0;
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
            <!--<div class="menuBtn" id="btnChangePassword" onclick="window.location = 'changepassword.php'">Zmień hasło</div>-->
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
            if (!$query = $conn->query(sprintf(fileContent($_SERVER["DOCUMENT_ROOT"] . "/sql/answerstats.sql"), $questions_page * 10))) die($conn->error);
            $lp = 1 + $questions_page * 10;
            while ($row = $query->fetch_assoc()) {
                echo sprintf("<tr><td>%d</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $lp++, $row["quiz"], $row["question"], $row["right"], $row["wrong"], $row["percent"]);
            }
            if (!$query = $conn->query("SELECT COUNT(*) as `count` FROM `questions`")) die($conn->error);
            if (!$res = $query->fetch_assoc()) die($conn->error);
            if ($questions_page > 0) echo ("<a href='stats.php?qpage=" . ($questions_page) .  "'>Poprzednia strona</a>");
            if ($lp < $res["count"]) echo ("<a href='stats.php?qpage=" . ($questions_page + 2) .  "'>Następna strona</a>");
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
            if (!$query = $conn->query(sprintf(fileContent($_SERVER["DOCUMENT_ROOT"] . "/sql/userstats.sql"), $users_page * 10))) die($conn->error);
            $lp = 1 + $users_page * 10;
            while ($row = $query->fetch_assoc()) {
                echo sprintf("<tr><td>%d</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $lp++, $row["user"], $row["right"], $row["wrong"], $row["percent"]);
            }
            if (!$query = $conn->query("SELECT COUNT(*) as `count` FROM `accounts`")) die($conn->error);
            if (!$res = $query->fetch_assoc()) die($conn->error);
            if ($users_page > 0) echo ("<a href='stats.php?upage=" . ($users_page) .  "'>Poprzednia strona</a>");
            if ($lp < $res["count"]) echo ("<a href='stats.php?qpage=" . ($users_page + 2) .  "'>Następna strona</a>");
            ?>
        </table>
    </div>
</body>

</html>