<?php
if(!$userdata = require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/userdata.php")) {header("Location: /"); return;}

if(!isset($_GET["take"])) die("Nie podano podejścia do wyświetlenia.");

function fileContent($path) {
    $file = fopen($path, "r");
    $content = fread($file, filesize($path));
    fclose($file);
    return $content;
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/php/settings.php");
if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych");

if(!$query = $conn->query("SELECT * FROM `takes` WHERE `id` = " . $_GET["take"])) die($conn->error);
if(!$take = $query->fetch_assoc()) die("Nie ma takiego podejścia.");

if(!$query = $conn->query("SELECT * FROM `quizzes` WHERE `id` = " . $take["quiz_id"])) die($conn->error);
if(!$quiz = $query->fetch_assoc()) die("Nie ma takiego quizu:" . $take["quiz_id"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Wyniki: <?php echo $quiz["name"] ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/result.css">
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
        <h1>Twój wynik: <?php echo $take["correct"] . "/10 (" . ($take["correct"] * 10) . "%)" ?></h1>
        <table border="1">
            <tr>
                <th>Pytanie</th>
                <th>Twoja odpowiedź</th>
                <th>Poprawna odpowiedź</th>
            </tr>
            <?php
            if(!$query = $conn->query("SELECT * FROM `scores` WHERE `take_id` = " . $_GET["take"])) die($conn->error);
            while($score = $query->fetch_assoc()) {
                if(!$query2 = $conn->query("SELECT * FROM `questions` WHERE `id` = " . $score["question_id"])) die($conn->error);
                if(!$question = $query2->fetch_assoc()) die("Błędne id pytania.");

                echo sprintf('<tr class="' . (($score["correct"] == 1) ? 'correct' : 'wrong') . '"><td>%s</td><td>%s</td><td>%s</td></tr>', $question["question"], $score["answer"], $question["right_answer"]);
            }
            ?>
        </table>
    </div>
    <div>
        <h1>Ranking testu</h1>
        <table border="1">
            <tr>
                <th>#</th>
                <th>Użytkownik</th>
                <th>Poprawne</th>
                <th>Niepoprawne</th>
                <th>%</th>
            </tr>
            <?php
                if(!$query = $conn->query(sprintf(fileContent($_SERVER["DOCUMENT_ROOT"] . "/sql/quizranking.sql"), "(SELECT `quiz_id` FROM `takes` WHERE `id` = " . $_GET["take"] . ")", 0))) die($conn->error);
                $lp = 1;
                while($row = $query->fetch_assoc()) {
                    echo sprintf("<tr><td>%d</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $lp++, $row["user"], $row["right"], $row["wrong"], $row["percent"]);
                }
            ?>
        </table>
    </div>
</body>
</html>