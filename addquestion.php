<?php
if(!require("isadmin.php")) {header("Location: /"); return;}
echo print_r($_POST);
if(isset($_POST["quiz_name"]) && isset($_POST["new_question"]) && isset($_POST["new_answers"]) && isset($_POST["new_right"])) {
    require("settings.php");
    if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych.");
    if($conn->connect_error) die("Nie udało się połączyć z bazą danych.");

    $querytext = sprintf("INSERT INTO `questions` (`quiz_id`, `question`, `answers`, `right_answer`) VALUES((SELECT `id` FROM `quizzes` WHERE `name` LIKE '%s'), '%s', '%s', '%s')", $_POST["quiz_name"], $_POST["new_question"], $_POST["new_answers"], $_POST["new_right"]);
    if(!$query = $conn->query($querytext)) echo $conn->error;
    $conn->close();
}
header("Location: admin.php");
?>