<?php
if(!isset($_GET["take"])) die("Nie podano podejścia do wyświetlenia.");

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
</head>
<body>
    <h1>Twój wynik: <?php echo $take["correct"] . "/10 (" . ($take["correct"] * 10) . "%)" ?></h1>
    <table border="1">
        <tr>
            <th>Pytanie</th>
            <th>Twoja odpowiedź</th>
            <th>Poprawna odpowiedź</th>
        </tr>

    </table>
</body>
</html>