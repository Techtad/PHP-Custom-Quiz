<?php
if(!require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/isadmin.php")) {header("Location: /"); return;}

if(isset($_POST["quiz_id"], $_POST["question"], $_POST["answer_a"], $_POST["answer_b"], $_POST["answer_c"], $_POST["answer_d"], $_POST["right_answer"])) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/php/settings.php");
    if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych.");
    if($conn->connect_error) die("Nie udało się połączyć z bazą danych.");
    
    if($query = $conn->prepare("INSERT INTO `questions`(`quiz_id`, `question`, `answer_a`, `answer_b`, `answer_c`, `answer_d`, `right_answer`) VALUES(?, ?, ?, ?, ?, ?, ?)")) {
        $query->bind_param("issssss", $_POST["quiz_id"], $_POST["question"], $_POST["answer_a"], $_POST["answer_b"], $_POST["answer_c"], $_POST["answer_d"], $_POST["right_answer"]);
        if(!$query->execute()) echo($conn->error);
        $query->close();
    }
    $conn->close();
} else header("Location: /admin.php");
?>