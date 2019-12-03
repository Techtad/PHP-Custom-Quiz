<?php
echo $_SERVER["DOCUMENT_ROOT"];
echo "<br>";
print_r(require $_SERVER["DOCUMENT_ROOT"] . "/php/auth/userdata.php");
print_r($_GET);
print_r($_POST);