<?php
if(isset($_COOKIE["session"]) && strlen($_COOKIE["session"]) > 0) {
    if($file = fopen("sessions.json", "r")) {
        if($users = json_decode(fread($file, filesize("sessions.json")), true)) {
            fclose($file);
            return array_key_exists($_COOKIE["session"], $users) ? $users[$_COOKIE["session"]] : false;
        }
        fclose($file);
    }
}

return false;