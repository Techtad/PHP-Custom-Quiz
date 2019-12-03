<?php
if($file = fopen($_SERVER["DOCUMENT_ROOT"] . "/sessions.json", "r")) {
    $removed = 0;
    if($users = json_decode(fread($file, filesize($_SERVER["DOCUMENT_ROOT"] . "/sessions.json")), true)) {
        
        foreach($users as $k => $usr) {
            if($usr["expires"] < time() || isset($_GET["purge"])) {
                unset($users[$k]);
                $removed++;
            }
        }

        fclose($file);
        if($file = fopen($_SERVER["DOCUMENT_ROOT"] . "/sessions.json", "w")) {
            fwrite($file, json_encode($users));
            fclose($file);
        } else die("Nie udało się otworzyć pliku z sesjami.");
        
    } else fclose($file);

    echo sprintf("Usunięto sesji: %d", $removed);
} else die("Nie udało się otworzyć pliku z sesjami.");
?>