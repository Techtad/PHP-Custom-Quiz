<?php
if(!require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/isadmin.php")) {header("Location: /"); return;}

if(isset($_POST["question_id"])) {
    $id = $_POST["question_id"];

    require_once $_SERVER["DOCUMENT_ROOT"] . "/php/settings.php";
    if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych.");
    if($conn->connect_error) die("Nie udało się połączyć z bazą danych.");

    $conn->begin_transaction();
        $conn->query(sprintf("DELETE FROM `questions` WHERE `id` LIKE '%s'", $id));
        $conn->query(sprintf("DELETE FROM `scores` WHERE `question_id` LIKE '%s'", $id));
    $conn->commit();

    $conn->close();
} else header("Location: /admin.php");