<?php
if(!require("isadmin.php")) {header("Location: /"); return;}
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
</head>
<body>
    <form id="questions" method="post">
        <select name="quiz_name" id="quiz_name" onchange="this.form.submit()">
            <?php
               require("settings.php");
               if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych.");
               if(!$query = $conn->query("SELECT `name` FROM `quizzes`")) die("Nie udało się wykonać zapytania SQL.");

               if(!isset($_POST["quiz_name"])) echo "<option selected>--Wybierz test--</option>";

               while($record = $query->fetch_row()) {
                   echo sprintf('<option ' . ((isset($_POST["quiz_name"]) && $_POST["quiz_name"] == $record[0]) ? 'selected' : '') . ' value=\'%1$s\'>%1$s</option>', $record[0]);
               }
               $query->free();
               $conn->close();
            ?>
        </select>
        
    </form>
    <?php if(isset($_POST["quiz_name"])) { ?>
    <form method="POST" action="addquestion.php">
        <input type="hidden" name="quiz_name" value="<?php echo $_POST["quiz_name"] ?>">
        <table border="1">
            <tr>
                <th>Pytanie</th>
                <th>Odpowiedzi</th>
                <th>Prawidłowa</th>
                <th>-</th>
            </tr>
            <?php
                if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych.");
                if(!$query = $conn->query(sprintf("SELECT * FROM `questions` WHERE `quiz_id` LIKE (SELECT `id` FROM `quizzes` WHERE `name` LIKE '%s')", $_POST["quiz_name"]))) die("Nie udało się wykonać zapytania SQL.");

                while($record = $query->fetch_row()) {
                    echo sprintf("<tr><td>%s</td>", $record[1]);
                    echo "<td><table border='1'>";
                    $answers = json_decode($record[2], true);
                    foreach($answers as $letter => $answer) {
                        echo sprintf("<tr><td>%s</td><td>%s</td></tr>", $letter, $answer);
                    }
                    echo "</table></td>";
                    echo sprintf("<td>%s</td><td></td></tr>", $record[3]);
                }
                $query->free();
                $conn->close();
            ?>
            <tr>
                <td><input type="text" name="new_question" id="new_question"></td>
                <td><textarea name="new_answers" id="new_answers"></textarea></td>
                <td><input type="text" name="new_right" id="new_right"></td>
                <td><input type="submit" value="dodaj"></td>
            </tr>
        </table>
    </form>
    <?php } ?>
</body>
</html>