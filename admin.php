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
</head>
<body>
    <form id="questions" method="post">
        <select name="quiz_name" id="quiz_name" onchange="this.form.submit()">
            <?php
               require_once($_SERVER["DOCUMENT_ROOT"] . "/php/settings.php");
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
        <form method="POST" actin="php/admin/modifyquestion.php">
            <table border="1">
                <tr>
                    <th>Pytanie</th>
                    <th>A</th>
                    <th>B</th>
                    <th>C</th>
                    <th>D</th>
                    <th>Prawidłowa</th>
                    <th>-</th>
                </tr>
                <?php
                    if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych.");
                    if(!$query = $conn->query(sprintf("SELECT * FROM `questions` WHERE `quiz_id` LIKE (SELECT `id` FROM `quizzes` WHERE `name` LIKE '%s')", $_POST["quiz_name"]))) die("Nie udało się wykonać zapytania SQL.");

                    while($record = $query->fetch_assoc()) {
                        echo "<tr>";
                        foreach(["question", "answer_a", "answer_b", "answer_c", "answer_d"] as $col) {
                            echo sprintf('<td><input type="text" name="%1$d_%2$s" value="%2$s"></td>', $record["id"], $record["question"]);
                        }
                       
                        echo "<td><select name='%d_right'>";
                        foreach(["A", "B", "C", "D"] as $letter) {
                            echo "<option ";
                            if($letter == $record["right_answer"]) echo "selected ";
                            echo sprintf('value="%1$s">%1$s</option>', $letter);
                        }
                        echo "</select></td></tr>";
                    }
                    $query->close();
                    $conn->close();
                ?>
            </table>
        </form>
        <form method="POST" action="php/admin/addquestion.php">
            <input type="hidden" name="quiz_name" value="<?php echo $_POST["quiz_name"] ?>">
            <table border="1">
                <tr>
                        <th>Pytanie</th>
                        <th>A</th>
                        <th>B</th>
                        <th>C</th>
                        <th>D</th>
                        <th>Prawidłowa</th>
                        <th>-</th>
                </tr>
                <tr>
                    <td><input type="text" name="new_question" id="new_question"></td>
                    <td><input type="text" name="new_a" id="new_a"></td>
                    <td><input type="text" name="new_b" id="new_b"></td>
                    <td><input type="text" name="new_c" id="new_c"></td>
                    <td><input type="text" name="new_d" id="new_d"></td>
                    <td>
                        <select name="new_right" id="new_right">
                            <option value="A" selected>A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </td>
                    <td><input type="submit" value="dodaj"></td>
                </tr>
            </table>
        </form>
    <?php } ?>
</body>
</html>