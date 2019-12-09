<?php
if(!require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/isadmin.php")) {header("Location: /"); return;}

if(isset($_GET["quiz_id"])) { ?>
<table border="1">
    <tr>
        <th>Pytanie</th>
        <th>A</th>
        <th>B</th>
        <th>C</th>
        <th>D</th>
        <th>Prawidłowa</th>
        <th>-</th>
        <th>-</th>
    </tr>
    <?php
        require_once($_SERVER["DOCUMENT_ROOT"] . "/php/settings.php");
        if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych.");
        if(!$query = $conn->query(sprintf("SELECT * FROM `questions` WHERE `quiz_id` LIKE '%d'", $_GET["quiz_id"]))) die("Nie udało się wykonać zapytania SQL.");

        while($record = $query->fetch_assoc()) {
            echo "<tr>";
            foreach(["question", "answer_a", "answer_b", "answer_c", "answer_d"] as $col) {
                echo sprintf('<td><input type="text" id="%1$d_%2$s" value="%3$s" oldval="%3$s" oninput="editValChanged(event)"></td>', $record["id"], $col, $record[$col]);
            }
            
            echo sprintf("<td><select id='%d_right_answer' oldval='%s' oninput='editValChanged(event)'>", $record["id"], $record["right_answer"]);
            foreach(["A", "B", "C", "D"] as $letter) {
                echo "<option ";
                if($letter == $record["right_answer"]) echo "selected";
                echo sprintf(' value="%1$s">%1$s</option>', $letter);
            }
            echo "</select></td>";
            echo sprintf("<td><input id='%d_submit' onclick='editQuestion(%d)' type='button' value='ZAPISZ' disabled>", $record["id"], $record["id"]);
            echo sprintf("<td><input id='%d_cancel' onclick='loadQuestionTable()' type='button' value='ANULUJ' disabled>", $record["id"], $record["id"]);
            echo "</td>";
            echo sprintf("<td><input id='%d_delete' onclick='deleteQuestion(%d)' type='button' value='USUŃ'></td></tr>", $record["id"], $record["id"]);
        }
        $query->close();
        $conn->close();
    ?>
    <tr>
        <td><input class="addition-row" type="text" name="new_question" id="new_question" oninput="addValChanged(event)"></td>
        <td><input class="addition-row" type="text" name="new_answer_a" id="new_answer_a" oninput="addValChanged(event)"></td>
        <td><input class="addition-row" type="text" name="new_answer_b" id="new_answer_b" oninput="addValChanged(event)"></td>
        <td><input class="addition-row" type="text" name="new_answer_c" id="new_answer_c" oninput="addValChanged(event)"></td>
        <td><input class="addition-row" type="text" name="newanswer__d" id="new_answer_d" oninput="addValChanged(event)"></td>
        <td>
            <select class="addition-row" name="new_right_answer" id="new_right_answer">
                <option value="A" selected>A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
            </select>
        </td>
        <td><input class="addition-row" id="add_question" type="button" value="WSTAW" disabled onclick="addQuestion()"></td>
        <td>-</td>
    </tr>
</table>
<?php } else die("Nie podano id testu."); ?>