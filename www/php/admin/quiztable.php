<?php
if(!require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/isadmin.php")) {header("Location: /"); return;}
?>
<table border="1">
    <tr>
        <th>Nazwa testu</th>
        <th>Opis</th>
        <th>Liczba pytań</th>
        <th>-</th>
        <th>-</th>
        <th>-</th>
    <tr>
    <?php
        require_once($_SERVER["DOCUMENT_ROOT"] . "/php/settings.php");
        if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych.");
        if(!$query = $conn->query("SELECT `quizzes`.`id`, `name`, `description`, COUNT(`questions`.`id`) as `count` FROM `quizzes` LEFT JOIN `questions` ON `quizzes`.`id` = `questions`.`quiz_id` GROUP BY `name`")) die("Nie udało się wykonać zapytania SQL.");

        while($record = $query->fetch_assoc()) {
            echo "<tr>";
            echo sprintf('<td><input type="text" id="quiz_%1$d_name" value="%2$s" oldval="%2$s" oninput="onQuizEditValChanged(event)"></td>', $record["id"], $record["name"]);
            echo sprintf('<td><input type="text" id="quiz_%1$d_desc" value="%2$s" oldval="%2$s" oninput="onQuizEditValChanged(event)"></td>', $record["id"], $record["description"]);
            echo sprintf("<td>%d</td>", $record["count"]);
            echo sprintf("<td><input type='button' value='ZAPISZ' id='quiz_%d_edit' onclick='editQuiz(%d)' disabled>", $record["id"], $record["id"]);
            echo sprintf("<input type='button' value='ANULUJ' id='quiz_%d_cancel' onclick='loadQuizTable()' disabled>", $record["id"], $record["id"]);
            echo "</td>";
            echo sprintf("<td><input type='button' value='USUŃ' id='quiz_%d_delete' onclick='deleteQuiz(%d)'", $record["id"], $record["id"]);
            if(isset($_GET["selected"]) && $_GET["selected"] == $record["id"]) echo(" disabled ");
            echo "></td>";
            echo sprintf("<td><input type='button' value='WYBIERZ' id='quiz_%d_select' onclick='selectQuiz(%d)'", $record["id"], $record["id"]);
            if(isset($_GET["selected"]) && $_GET["selected"] == $record["id"]) echo(" disabled ");
            echo "></td></tr>";
        }
        $query->close();
        $conn->close();
    ?>
    <tr>
        <td><input class="addition-row" type="text" name="new_quiz_name" id="new_quiz_name" oninput="quizAddChanged(event)"></td>
        <td><input class="addition-row" type="text" name="new_quiz_desc" id="new_quiz_desc" oninput="quizAddChanged(event)"></td>
        <td>0</td>
        <td>
            <input class="addition-row" id="add_quiz" type="button" value="WSTAW" disabled onclick="addQuiz()">
            <input class="addition-row" id="cancel_add_quiz" type="button" value="ANULUJ" disabled onclick="loadQuizTable()">
        </td>
        <td>-</td>
        <td>-</td>
    </tr>
</table>