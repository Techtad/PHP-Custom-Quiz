<?php
if(!require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/isadmin.php")) {header("Location: /"); return;}

if(isset($_POST["name"], $_POST["description"])) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/php/settings.php");
    if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych.");
    if($conn->connect_error) die("Nie udało się połączyć z bazą danych.");
    
    if(!$query = $conn->query(sprintf("INSERT INTO `quizzes` (`name`, `description`) VALUES ('%s', '%s')", $_POST["name"], $_POST["description"]))) die($conn->error);
    $conn->close();
} else header("Location: /admin.php");
?>