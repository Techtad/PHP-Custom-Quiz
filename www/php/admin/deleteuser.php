<?php
if(!require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/isadmin.php")) {header("Location: /"); return;}

if(isset($_POST["username"])) {
    $user = $_POST["username"];

    require_once $_SERVER["DOCUMENT_ROOT"] . "/php/settings.php";
    if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych.");
    if($conn->connect_error) die("Nie udało się połączyć z bazą danych.");

    $conn->begin_transaction();
        $conn->query(sprintf("DELETE FROM `accounts` WHERE `username` LIKE '%s'", $user));
        $conn->query(sprintf("DELETE FROM `scores` WHERE `user` LIKE '%s'", $user));
    $conn->commit();

    $conn->close();
} else header("Location: /admin.php");