<?php
if(!isset($_POST["login"]) || !isset($_POST["password"])) {
    header("Location: /auth");
    die;
}

include "config.php";
include "Database.php";

// TODO: handle errors
$db = new Database($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_password"], $GLOBALS["db_database"]);
try {
    $db->addUser($_POST["login"], $_POST["password"]);
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062) {
        die("
            <html>
                <head>
                    <meta http-equiv=\"refresh\" content=\"3;url=/auth\" />
                </head>
                <body>
                    <h1>User with same login already exists!</h1>
                    <p>Redirecting in 3 seconds...</p>
                </body>
            </html>
        ");
    }
}

$db->close();

session_start();

$_SESSION["login"] = $_POST["login"];

header("Location: /dialogs");
