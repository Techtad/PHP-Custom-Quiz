<?php
header("Location: /");
if(isset($_COOKIE["session"]) && strlen($_COOKIE["session"]) > 0) {
    setcookie("session", "", time() - 3600);

    if($file = fopen("sessions.json", "r")) {
        if($users = json_decode(fread($file, filesize("sessions.json")), true)) {
            fclose($file);
            if(array_key_exists($_COOKIE["session"], $users)) {
                unset($users[$_COOKIE["session"]]);
                if($file = fopen("sessions.json", "w")) {
                    fwrite($file, json_encode($users));
                    fclose($file);
                    return true;
                }
            }
        } else fclose($file);
    }
}

return false;