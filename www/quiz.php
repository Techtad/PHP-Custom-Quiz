<?php
    if((!$userdata = require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/userdata.php")) || !isset($_GET["id"])) {header("Location: /"); return;}
    $quiz_id = $_GET["id"];
    require_once($_SERVER["DOCUMENT_ROOT"] . "/php/settings.php");
    if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych");
    if(!$query = $conn->query(sprintf("SELECT `name`, `description` FROM `quizzes` WHERE `id` LIKE '%d'", $quiz_id))) die("Nie udało się wykonać zapytania SQL");

    if($record = $query->fetch_row()) {
        $quiz_name = $record[0];
        $quiz_desc = $record[0];
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
</head>
<body>
    <div id="container">
        <div id="title">
            <h1><?php echo $quiz_name; ?></h1>
            <h2><?php echo $quiz_desc; ?></h2>
        </title>
        <?php
            if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych");
            if(!$query = $conn->query(sprintf("SELECT * FROM `questions` WHERE `quiz_id` LIKE '%d'", $quiz_id))) die("Nie udało się wykonać zapytania SQL");
            while($record = $query->fetch_array()) {
                echo "<div class='question'>";
                echo "<form method='POST' action='test.php' id='question_" . $record["id"] . "'>";
                echo sprintf("<div>%s</div><hr>", $record["question"]);
                foreach(["a", "b", "c", "d"] as $answer) {
                    echo "<input type='radio' name='answer_" . $record["id"] . "' value='" . $answer . "'>" . strtoupper($answer) . ". " . $record["answer_" . $answer];
                }
                echo "</form></div>";
            }
            $query->close();
            $conn->close();
        ?>
        <div class="button" id="submit">Wyślij</div>
    </div>
</body>
</html>