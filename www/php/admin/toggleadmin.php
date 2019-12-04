<?php
if(!require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/isadmin.php")) {header("Location: /"); return;}
if(isset($_POST["username"])) {
    if($_POST["username"] == "admin") return false;

    require_once($_SERVER["DOCUMENT_ROOT"] . "/php/settings.php");
    if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych.");
    $success = $conn->query(sprintf("UPDATE `accounts` SET `admin`=NOT(SELECT `admin` FROM `accounts` WHERE `username` LIKE '%s') WHERE `username` LIKE '%s'", $_POST["username"], $_POST["username"]));
    $conn->close();
    return $success;
} else {header("Location: /"); return;}
?>