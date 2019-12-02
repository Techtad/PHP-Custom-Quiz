<?php
if(!isset($_COOKIE["session"]) || (isset($_COOKIE["session"]) && strlen($_COOKIE["session"]) == 0) && isset($_POST["username"]) && strlen($_POST["username"]) > 0 && isset($_POST["password"]) && strlen($_POST["password"]) > 0) {
    if(!$conn = mysqli_connect("localhost", "root", "", "quizapp")) die("Nie udało się połączyć z bazą danych");
    if(!$query = $conn->query(sprintf("SELECT `password` FROM `accounts` WHERE `username` LIKE '%s'", $_POST["username"]))) die("Nie udało się wykonać zapytania SQL");

    if($record = $query->fetch_row()) {
        if($record[0] == $_POST["password"]) {
            $token = bin2hex(openssl_random_pseudo_bytes(8));
            if($file = fopen("sessions.json", "r")) {
                if(!$users = json_decode(fread($file, filesize("sessions.json")), true)) $users = [];
                $users[$token] = $_POST["username"];
                fclose($file);
                $file = fopen("sessions.json", "w");
                fwrite($file, json_encode($users));
                fclose($file);
            } else die("Nie udało się otworzyć pliku na serwerze.");
            setcookie("session", $token, time()+3600*24, "/");
        }
    }
}
?>