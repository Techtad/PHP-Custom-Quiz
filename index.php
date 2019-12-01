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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Testy</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/lib/jquery.min.js"></script>
    <script src="js/lib/utils.js"></script>
    <script type="text/javascript">
        window.addEventListener("DOMContentLoaded", function(event) {
           if(Utils.getCookie("session") == "") window.location = "./php/login.php"
           else {
               $.ajax({
                    type: "POST",
                    url: "php/getusername.php",
                    data: {"token": Utils.getCookie("session")},
                    success: function(resp) {
                        $("#username").text(resp)
                    },
                });
           }

           $("#btnLogOut").on("click", function(event) {
                Utils.setCookie("session", "", -1000)
                window.location = window.location
           })
        })
    </script>
</head>

<body>
    <h1>Zalogowano jako: <span id="username">NIEZALOGOWANO</span></h1>
    <input type="button" id="btnLogOut" value="Wyloguj"/>
</body>

</html>