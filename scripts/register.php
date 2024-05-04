<?php
if(!isset($_POST["login"]) || !isset($_POST["password"])) {
    header("Location: /auth");
    die;
}

include_once $GLOBALS["ROOT_DIR"]."/config.php";
include_once $GLOBALS["ROOT_DIR"]."/Database.php";

$db = new IdkChat\DatabaseClass\Database($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_password"], $GLOBALS["db_database"]);
try {
    $db->addUser($_POST["login"], $_POST["password"]);
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        die("
            <html lang=\"en\">
                <head>
                    <meta http-equiv=\"refresh\" content=\"3;url=/auth\" />
                    <title>Register</title>
                </head>
                <body>
                    <h1>User with same login already exists!</h1>
                    <a href='/auth'>Redirecting in 3 seconds...</a>
                </body>
            </html>
        ");
    }
}

session_start();

$_SESSION["login"] = $_POST["login"];

header("Location: /dialogs");
