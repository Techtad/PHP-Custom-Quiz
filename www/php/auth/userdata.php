<?php
if(isset($_COOKIE["session"]) && strlen($_COOKIE["session"]) > 0) {
    if($file = fopen($_SERVER["DOCUMENT_ROOT"] . "/sessions.json", "r")) {
        if($users = json_decode(fread($file, filesize($_SERVER["DOCUMENT_ROOT"] . "/sessions.json")), true)) {
            fclose($file);
            return array_key_exists($_COOKIE["session"], $users) ? $users[$_COOKIE["session"]] : false;
        }
        fclose($file);
    }
}

return false;