<?php
if(!$userdata = require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/userdata.php")) die("Użytkownik nie jest zalogowany.");

if(isset($_POST["quiz_id"]) && sizeof($_POST) == 11) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/php/settings.php");
    if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych");

    $answers = array();
    foreach($_POST as $k => $v) {
        $parts = explode("_", $k);
        if($parts[0] == "answer") {
            $answers[$parts[1]] = $v;
        }
    }
    //echo print_r($answers) . "<br>";

    $score = array();
    $correct = 0;
    $wrong = 0;
    foreach($answers as $k => $v) {
        if(!$query = $conn->query("SELECT `right_answer` FROM `questions` WHERE `id` LIKE '" . $k . "'")) die("Nie udało się wykonać zapytania SQL");
        if(!$row = $query->fetch_assoc()) die("Nie znaleziono pytania: " . $k);

        $is_right = $v == $row["right_answer"];
        if($is_right) $correct++;
        else $wrong++;

        $score[$k] = $is_right ? 1 : 0;

        $query->close();
    }
    //echo print_r($score) . "<br>";

    if(!$query = $conn->query(sprintf("INSERT INTO `takes` (`quiz_id`, `user`, `correct`, `wrong`) VALUES('%d', '%s', '%d', '%d')", $_POST["quiz_id"], $userdata["username"], $correct, $wrong))) die("Nie udało się wykonać zapytania SQL");
    $take_id = $conn->insert_id;
    //echo $take_id . "<br>";

    foreach($score as $k => $v) {
        //echo $v . "<br>";
        if(!$query = $conn->query(sprintf("INSERT INTO `scores` (`take_id`, `question_id`, `user`, `answer`, `correct`) VALUES(%d, %d, '%s', '%s', %b)", $take_id, $k, $userdata["username"], $answers[$k], $v))) die("Nie udało się wykonać zapytania SQL");
    }

    $conn->close();

    header("Location: /result.php?take=" . $take_id);
} else die("Błędnie wysłane dane odpowiedzi do testu.");
?>