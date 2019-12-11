<?php session_start(); ?>
<?php
if(require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/userdata.php")) {header("Location: /"); return;}

if(isset($_POST["username"], $_POST["password"])) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/php/settings.php");
    if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych");
    if(!$query = $conn->query(sprintf("SELECT `password_hash` FROM `accounts` WHERE `username` LIKE '%s'", $_POST["username"]))) die("Nie udało się wykonać zapytania SQL");

    if($record = $query->fetch_row()) {
        if(password_verify($_POST["password"], $record[0])) {
            $token = bin2hex(openssl_random_pseudo_bytes(8));

            // Tworzę plik w przypadku gdy nie istnieje
            $file = fopen($_SERVER["DOCUMENT_ROOT"] . "/sessions.json", "w");
            fclose($file);

            if($file = fopen($_SERVER["DOCUMENT_ROOT"] . "/sessions.json", "r")) {
                if(!$users = json_decode(fread($file, filesize($_SERVER["DOCUMENT_ROOT"] . "/sessions.json")), true)) $users = [];
                $users[$token] = ["username" => $_POST["username"], "expires" => time() + 3600*24];
                fclose($file);
                $file = fopen($_SERVER["DOCUMENT_ROOT"] . "/sessions.json", "w");
                fwrite($file, json_encode($users));
                fclose($file);
            } else die("Nie udało się otworzyć pliku na serwerze.");

            setcookie("session", $token, time()+3600*24, "/");

            header("Location: /");
            return true;
        } else $_SESSION["login-fail"] = "Błędne hasło.";

        $query->close();
    } else $_SESSION["login-fail"] = "Użytkownik o podanym loginie nie istnieje.<br><input type='button' value='Zarejestruj automatycznie' onclick='autoRegister(\"" . $_POST["username"] . "\", \"" . $_POST["password"] . "\")'>";

    $conn->close();
}

header("Location: /login.php");
return false;
?>