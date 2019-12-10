<?php
if(!require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/isadmin.php")) {header("Location: /"); return;}

if(isset($_POST["quiz_id"])) {
    $id = $_POST["quiz_id"];

    require_once $_SERVER["DOCUMENT_ROOT"] . "/php/settings.php";
    if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych.");
    if($conn->connect_error) die("Nie udało się połączyć z bazą danych.");

    $conn->begin_transaction();
        $conn->query(sprintf("DELETE FROM `quizzes` WHERE `id` LIKE %d", $id));
        $conn->query(sprintf("DELETE FROM `takes` WHERE `quiz_id` LIKE '%d'", $row[0]));
        $res = $conn->query(sprintf("SELECT `id` FROM `questions` WHERE `quiz_id` LIKE %d", $id));
        while($row = $res->fetch_row()) {
            $conn->query(sprintf("DELETE FROM `questions` WHERE `id` LIKE '%d'", $row[0]));
            $conn->query(sprintf("DELETE FROM `scores` WHERE `question_id` LIKE '%d'", $row[0]));
        }
    $conn->commit();

    $conn->close();
} else header("Location: /admin.php");