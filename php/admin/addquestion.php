<?php
if(!require($_SERVER["DOCUMENT_ROOT"] . "/php/auth/isadmin.php")) {header("Location: /"); return;}
//echo print_r($_POST);
$success = false;
$reason = "Nie podano wszystkich parametrów.";
if(isset($_POST["quiz_name"], $_POST["new_question"], $_POST["new_a"], $_POST["new_b"], $_POST["new_c"], $_POST["new_d"], $_POST["new_right"])) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/php/settings.php");
    if(!$conn = db_connect()) die("Nie udało się połączyć z bazą danych.");
    if($conn->connect_error) die("Nie udało się połączyć z bazą danych.");

    
    if($query = $conn->prepare("INSERT INTO `questions` VALUES((SELECT `id` FROM `quizzes` WHERE `name` LIKE ?), ?, ?, ?, ?, ?, ?)")) {
        $query->bind_param("sssssss", $_POST["quiz_name"], $_POST["new_question"], $_POST["new_a"], $_POST["new_b"], $_POST["new_c"], $_POST["new_d"], $_POST["new_right"]);
        $success = $query->execute();
        $query->close();
        $reason = $success ? "" : $conn->error;
    }
    $conn->close();
}
header("Location: /admin.php");
header("Content-Type: application/json");
echo json_encode(["success" => $success, "reason" => $reason]);
?>