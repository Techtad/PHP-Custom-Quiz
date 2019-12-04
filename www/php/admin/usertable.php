<?php
if(!require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/isadmin.php")) {header("Location: /"); return;}
$userdata = require $_SERVER["DOCUMENT_ROOT"] . "/php/auth/userdata.php";
?>
<table border="1">
    <tr>
        <th>Login</th>
        <th>Nowe hasło</th>
        <th>-</th>
        <th>-</th>
        <th>-</th>
    <tr>
    <?php
        require_once($_SERVER["DOCUMENT_ROOT"] . "/php/settings.php");
        if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych.");
        if(!$query = $conn->query("SELECT `username`, `admin` FROM `accounts`")) die("Nie udało się wykonać zapytania SQL.");

        while($record = $query->fetch_assoc()) {
            echo "<tr>";

            echo sprintf("<td>%s</td>", $record["username"]);
            if($record["username"] != "admin" || $userdata["username"] == "admin") {
                echo sprintf('<td><input type="password" id="%s_newpassword" value="" oninput="newPasswordChanged(event)"></td>', $record["username"]);
                echo sprintf("<td><input type='button' value='ZMIEŃ HASŁO' id='%s_change_password' onclick='changePassword(event)' disabled></td>", $record["username"], $record["username"]);
            } else echo "<td></td><td></td>";
            if($record["username"] == "admin") {
                echo "<td></td><td></td>";
            } else {
                echo sprintf("<td><input type='button' value='" . ($record["admin"] == 0 ? "NADAJ ADMINA" : "ODBIERZ ADMINA") . "' id='%s_toggle_admin' onclick='toggleAdmin(event)'></td>", $record["username"], $record["username"]);
                echo sprintf("<td><input type='button' value='USUŃ' id='%s_delete_user' onclick='deleteUser(event)'></td>", $record["username"], $record["username"]);
            }

            echo "</tr>";
        }
        $query->close();
        $conn->close();
    ?>
</table>