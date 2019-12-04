<?php
if(!require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/isadmin.php")) {header("Location: /"); return;}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Panel Administracyjny</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/admin.css">
    <script src="js/lib/jquery.min.js"></script>
    <script src="js/utils.js"></script>
    <script src="js/admin.js"></script>
</head>
<body>
    <select name="select-quiz" id="select-quiz">
        <?php
            require_once($_SERVER["DOCUMENT_ROOT"] . "/php/settings.php");
            if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych.");
            if(!$query = $conn->query("SELECT `name`, `id` FROM `quizzes`")) die("Nie udało się wykonać zapytania SQL.");

            while($record = $query->fetch_row()) {
                echo sprintf('<option value=\'%2$d\'>%1$s</option>', $record[0], $record[1]);
            }
            $query->free();
            $conn->close();
        ?>
    </select>
    <div id="question-table"></div>
</body>
</html>