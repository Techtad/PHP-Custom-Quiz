<?php
if(!require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/isadmin.php")) {header("Location: /"); return;}

if(isset($_POST["question_id"])) {
    $id = $_POST["question_id"];

    require_once $_SERVER["DOCUMENT_ROOT"] . "/php/settings.php";
    if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych.");
    if($conn->connect_error) die("Nie udało się połączyć z bazą danych.");

    $cmd = "UPDATE `questions` SET ";
    foreach(["question", "answer_a", "answer_b", "answer_c", "answer_d", "right_answer"] as $col ) {
        if(isset($_POST[$col])) $cmd .= sprintf("`%s` = '%s',", $col, $_POST[$col]);
    }
    $cmd = substr($cmd, 0, -1);
    $cmd .= " WHERE `id` LIKE '" . $id . "';";

    if(!$query = $conn->query($cmd)) die("Nie udało się wykonać zapytania SQL.");
    $conn->close();
} else header("Location: /admin.php");