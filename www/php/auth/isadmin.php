<?php
if(isset($_COOKIE["session"]) && strlen($_COOKIE["session"]) > 0) {
    if(!$userdata = require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/userdata.php")) return false;

    require_once $_SERVER["DOCUMENT_ROOT"] . "/php/settings.php";
    if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych");
    if(!$query = $conn->query(sprintf("SELECT `admin` FROM `accounts` WHERE `username` LIKE '%s'", $userdata["username"]))) die("Nie udało się wykonać zapytania SQL");

    if($record = $query->fetch_row()) {
        return $record[0];
    } else die("Użytkownika nie ma w bazie danych.");
}

return false;