<?php
if(isset($_POST["token"])) {
    if($file = fopen("../sessions.json", "r")) {
        if($users = json_decode(fread($file, filesize("../sessions.json")), true)) {
            if(isset($users[$_POST["token"]]))
                echo $users[$_POST["token"]];
            else die("Błędny token");
        } else die("Nie ma żadnych sesji.");
        fclose($file);
    } else die("Nie można otworzyć pliku");
} else die("Nie ma tokena.");