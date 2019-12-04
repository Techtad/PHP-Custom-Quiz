<?php
session_start();
$userdata = require $_SERVER["DOCUMENT_ROOT"] . "/php/auth/userdata.php";

if(require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/isadmin.php")) {
    if(isset($_POST["username"], $_POST["new_password"])) {
        if($_POST["username"] == "admin" && $userdata["username"] != "admin") {
            $_SESSION["password-change-fail"] = "Tylko admin możne zmienić swoje hasło.";
            echo $_SESSION["password-change-fail"];
            return false;
        }
        $oldpasswordverified = true;
    } else {header("Location: /"); return false;}
} else {
    if(isset($_POST["username"], $_POST["new_password"], $_POST["repeat_new_password"], $_POST["old_password"])) {
        if($_POST["username"] == "admin") {
            $_SESSION["password-change-fail"] = "Tylko admin możne zmienić swoje hasło.";
            echo $_SESSION["password-change-fail"];
            return false;
        }

        if($_POST["username"] != $userdata["username"]) {
            $_SESSION["password-change-fail"] = "Można zmienić wyłącznie własne hasło, chyba że jest się adminem.";
            echo $_SESSION["password-change-fail"];
            return false;
        }

        if($_POST["repeat_new_password"] != $_POST["new_password"]) {
            $_SESSION["password-change-fail"] = "Powtórzone hasło nie zgadza się z pierwszym.";
            echo $_SESSION["password-change-fail"];
            return false;
        }

        require_once($_SERVER["DOCUMENT_ROOT"] . "/php/settings.php");
        if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych");
        if(!$query = $conn->query(sprintf("SELECT `password_hash` FROM `accounts` WHERE `username` LIKE '%s'", $_POST["username"]))) die("Nie udało się wykonać zapytania SQL");

        if($record = $query->fetch_row()) {
            $oldpasswordverified = password_verify($_POST["old_password"], $record[0]);
        } else {
            $_SESSION["password-change-fail"] = "Użytkownik o takim loginie nie istnieje: " . $_POST["username"];
            echo $_SESSION["password-change-fail"];
            return false;
        }
        $query->close();
        $conn->close();
        
    } else {header("Location: /"); return false;}
}

if($oldpasswordverified) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/php/settings.php");
    if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych");
    $hash = password_hash($_POST["new_password"], PASSWORD_BCRYPT);
    $success = $conn->query("UPDATE `accounts` SET `password_hash` = '" . $hash . "' WHERE `username` LIKE '" . $_POST["username"] . "';");
    $conn->close();
    if($success) {
        unset($_SESSION["password-change-fail"]);
        return true;
    } else {
        $_SESSION["password-change-fail"] = "Błąd podczas akutalizacji danych bazie: " . $conn->error;
        echo $conn->error;
        return false;
    }
}
?>