<?php
if(isset($_COOKIE["session"]) && strlen($_COOKIE["session"]) > 0) {
    if($file = fopen($_SERVER["DOCUMENT_ROOT"] . "/sessions.json", "r")) {
        if($users = json_decode(fread($file, filesize($_SERVER["DOCUMENT_ROOT"] . "/sessions.json")), true)) {
            fclose($file);
            if(array_key_exists($_COOKIE["session"], $users)) {
                unset($users[$_COOKIE["session"]]);
                if($file = fopen($_SERVER["DOCUMENT_ROOT"] . "/sessions.json", "w")) {
                    fwrite($file, json_encode($users));
                    fclose($file);

                    setcookie("session", "", time() - 3600, "/");
                    header("Location: /");
                    return true;
                }
            }
        } else fclose($file);
    }
}

header("Location: /");
return false;