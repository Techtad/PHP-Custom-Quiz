<?php
    if((!$userdata = require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/userdata.php")) || !isset($_GET["id"])) {header("Location: /"); return;}
    $quiz_id = $_GET["id"];
    require_once($_SERVER["DOCUMENT_ROOT"] . "/php/settings.php");
    if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych");
    if(!$query = $conn->query(sprintf("SELECT `name`, `description` FROM `quizzes` WHERE `id` LIKE '%d'", $quiz_id))) die("Nie udało się wykonać zapytania SQL");

    if($record = $query->fetch_row()) {
        $quiz_name = $record[0];
        $quiz_desc = $record[1];
    } else {echo "Nie ma takiego testu."; return;}
    $query->close();
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test: <?php echo $quiz_name ?></title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/quiz.css" />
    <script src="js/lib/jquery.min.js"></script>
    <script src="js/lib/utils.js"></script>
    <script src="js/quiz.js"></script>
</head>
<body>
    <div id="container">
        <div id="title">
            <h1><?php echo $quiz_name; ?></h1>
            <h2><?php echo $quiz_desc; ?></h2>
        </title>
        <?php
            if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych");
            if(!$query = $conn->query(sprintf("SELECT * FROM `questions` WHERE `quiz_id` LIKE '%d' ORDER BY RAND() LIMIT 10", $quiz_id))) die("Nie udało się wykonać zapytania SQL");
            echo "<form method='POST' action='/php/quiz/submitscore.php' id='quiz'>";
            echo sprintf("<input type='hidden' name='quiz_id' value='%s'>", $quiz_id);
            while($record = $query->fetch_array()) {
                echo sprintf("<div class='question' id='q_%d'>", $record["id"]);
                echo sprintf("<input type='hidden' name='answer_%d' id='a_%d' value='X'>", $record["id"], $record["id"]);
                echo sprintf("<div>%s</div><hr>", $record["question"]);
                foreach(["a", "b", "c", "d"] as $answer) {
                    echo sprintf("<div class='answer' onclick='selectAnswer(event, %d)' letter='%s'>%s. %s</div>", $record["id"], strtoupper($answer), strtoupper($answer), $record["answer_" . $answer]);
                }
                echo "</div>";
            }
            echo "</form>";
            $query->close();
            $conn->close();
        ?>
        <div class="button" id="submit" onclick="$('#quiz').submit()">Wyślij</div0>
    </div>
</body>
</html>