<?php
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'quizapp');

    function db_connect() { return mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); }
?>