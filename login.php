<?php
if (isset($_POST["username"]) & isset($_POST["password"])) {
    echo (json_encode(["success" => true]));
} else echo (json_encode(["success" => false]));
