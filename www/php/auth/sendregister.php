<?php session_start(); ?>
<?php
if(require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/userdata.php")) {header("Location: /"); return;}

function register() {
    $user = $_POST["username"];
    $pass = $_POST["password"];
    $rep_pass = $_POST["repeat_password"];

    if(strlen($user) < 6 || strlen($pass) < 6) {
        $_SESSION["register-fail"] = "Login i hasło muszą mieć przynajmniej 6 dowolnych znaków.";
        return false;
    }

    if($rep_pass != $pass) {
        $_SESSION["register-fail"] = "Hasła muszą się zgadzać.";
        return false;
    }

    if(!ctype_alnum($user)) {
        $_SESSION["register-fail"] = "Login nie może zawierać znaków specjalnych.";
        return false;
    }

    require_once($_SERVER["DOCUMENT_ROOT"] . "/php/settings.php");
    if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych");
    if(!$query = $conn->query(sprintf("SELECT `username` FROM `accounts` WHERE `username` LIKE '%s'", $_POST["username"]))) die("Nie udało się wykonać zapytania SQL");

    if($query->fetch_row()) {
        $_SESSION["register-fail"] = "Podana nazwa użytkownika jest już zajęta.";
        return false;
    }
    $query->close();

    if(!$query = $conn->prepare("INSERT INTO `accounts` VALUES(?, ?, ?)")) die("Nie udało się wykonać zapytania SQL");
    $hash = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $admin = 0;
    $query->bind_param("ssi", $user, $hash, $admin);

    $success = $query->execute();
    $query->close();
    $conn->close();
    if($success) {
        unset($_SESSION["register-fail"]);
        return true;
    } else {
        $_SESSION["register-fail"] = "Wystąpił błąd podczas dokonywania rejestracji.";
        return false;
    }
}

if(isset($_POST["username"], $_POST["password"], $_POST["repeat_password"])) {
    $registered = register();
    if(isset($_POST["auto"])) {
        echo json_encode(["success" => $registered, "reason" => isset($_SESSION["register-fail"]) ? $_SESSION["register-fail"] : ""]);
    } else header("Location: " . (($registered) ? "/php/auth/sendlogin.php" : "/register.php"));
} else {
    if(isset($_POST["auto"])) {
        echo json_encode(["success" => false, "reason" => "Nie podano wszystkich danych do rejestracji."]);
    } else header("Location: /register.php");
}

?>